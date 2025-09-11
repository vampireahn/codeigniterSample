<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class SampleBoardController (게시판)
	 *
	 * @property BoardModel $boardModel
	 * @property FileuploadService $fileUploadService
	 * @property PaginationService $paginationService
	 * @property ExcelDownloadService $excelDownloadService
	 * @property ExcelUploadService $excelUploadService
	 */
	class SampleBoardController extends MY_Controller {
		public function __construct() {
			parent::__construct();
			
			// 라이브러리를 로드할 때, 두 번째 인자로 원하는 별칭(alias)을 지정합니다.
			$this->load->library('PaginationService', null, 'paginationService');
			$this->load->library('FileuploadService', null, 'fileUploadService');
			$this->load->library('ExcelDownloadService', null, 'excelDownloadService');
			$this->load->library('ExcelUploadService', null, 'excelUploadService');
			$this->load->model("sampleBoard/BoardModel", "boardModel");
		}
		
		/**
		 * 게시판 목록 페이지를 표시합니다.
		 * 검색 및 페이지네이션 기능을 포함합니다.
		 * @return void
		 */
		public function index() {
			
			// 검색 파라미터 가져오기
			$searchType = $this->input->get('searchType', TRUE);
			$searchKeyword = $this->input->get('searchKeyword', TRUE);
			
			// 1. 페이지네이션 설정
			$totalRows = $this->boardModel->getTotalCount($searchType, $searchKeyword); // 전체 게시물 수
			$perPage = 10; // 페이지 당 보여줄 게시물 수
			
			// 2. 페이지네이션 서비스를 사용하여 링크 생성
			$data['paginationLinks'] = $this->paginationService->createLinks('/board', $totalRows, $perPage);
			
			// 3. 현재 페이지에 맞는 데이터 가져오기
			$pageNum = (int)$this->input->get('page');
			
			// 페이지 번호가 URL에 명시적으로 있고, 그 값이 1보다 작으면 1페이지로 리디렉션합니다.
			// 이렇게 하면 사용자가 잘못된 페이지 번호(0, -1 등)로 접근하는 것을 방지하고,
			// URL을 올바르게 교정해주는 효과가 있습니다.
			if ($this->input->get('page') !== null && $pageNum < 1)
			{
				// 기존 쿼리 스트링을 유지하면서 page만 1로 변경하여 리디렉션합니다.
				$queryParams = $this->input->get(null, TRUE);
				$queryParams['page'] = 1;
				redirect('/board?' . http_build_query($queryParams));
			}
			
			$page = ($pageNum > 0) ? $pageNum : 1;
			$offset = ($page - 1) * $perPage;
			$data['list'] = $this->boardModel->findWithSearchAndPaging($perPage, $offset, $searchType, $searchKeyword);
			
			$data['pageTitle'] = '게시판';
			$data['totalCount'] = $totalRows;
			$data['offset'] = $offset;
			$data['searchType'] = $searchType;
			$data['searchKeyword'] = $searchKeyword;
			// 목록으로 돌아가기, 상세 보기 링크 등에 사용할 쿼리 스트링을 미리 생성합니다.
			// http_build_query는 ['page'=>1, 'searchType'=>'title'] 같은 배열을
			// 'page=1&searchType=title'과 같은 문자열로 만들어줍니다.
			// CodeIgniter의 input 라이브러리를 사용하면 $_GET에 직접 접근하지 않아도 됩니다.
			$data['queryString'] = http_build_query($this->input->get(null, TRUE));
			
			$this->load->view('_templates/header', $data); // 뷰로 데이터 전달
			$this->load->view('sampleBoard/list', $data);
			$this->load->view('_templates/footer');
		}
		
		/**
		 * 특정 게시물의 상세 보기 페이지를 표시합니다.
		 * @param int $idx 게시물 고유 번호
		 * @return void
		 */
		public function view($idx) {
			$data['row'] = $this->boardModel->getFindByIdx($idx);
			
			if (empty($data['row']))
			{
				show_404();
				return;
			}
			
			$data['currentUserId'] = get_user_id();
			
			$data['queryString'] = http_build_query($this->input->get(null, TRUE));
			$data['pageTitle'] = $data['row']->title;
			
			$this->load->view('_templates/header', $data);
			$this->load->view('sampleBoard/view', $data);
			$this->load->view('_templates/footer');
		}
		
		/**
		 * 새 게시물 작성 페이지를 표시합니다.
		 * 수정 페이지와 뷰 파일을 공유합니다.
		 * @return void
		 */
		public function write() {
			// '글쓰기' 모드에서는 빈 객체를 전달하여 폼을 초기화합니다.
			$data['row'] = (object)[
				'idx' => null,
				'title' => '',
				'content' => '',
				'file_idx' => null,
				'origin_file_name' => null,
				'stored_file_name' => null,
			];
			
			$data['pageTitle'] = '게시판 작성';
			$data['formAction'] = '/board/writeProc'; // 폼 전송(submit) 경로
			// auth_helper의 get_user_id() 함수를 호출하여 사용자 ID를 가져옵니다.
			$data['userId'] = get_user_id();
			
			$this->load->view('_templates/header', $data);
			$this->load->view('sampleBoard/write', $data); // 쓰기/수정 공통 뷰 사용
			$this->load->view('_templates/footer');
		}
		
		/**
		 * 새 게시물 작성을 처리합니다.
		 * 유효성 검사, 파일 업로드, DB 저장을 수행합니다.
		 * @return void
		 */
		public function writeProc() {
			// 1. validation/boardValidation.php 설정 파일을 로드합니다.
			$this->config->load('validation/boardValidation', TRUE);
			$rules = $this->config->item('board_write', 'validation/boardValidation');
			$this->form_validation->set_rules($rules);
			
			if ($this->form_validation->run() === FALSE)
			{
				// 유효성 검사 실패 시, 에러 메시지와 함께 쓰기 페이지로 돌아갑니다.
				$this->session->set_flashdata('error', validation_errors());
				redirect('/board/write');
				return;
			}
			
			// 2. FileUploadService 라이브러리를 사용하여 파일 업로드
			$uploadData = $this->fileUploadService->upload('uploadFile', 'board');
			
			// 업로드 실패 조건을 명확하게 개선합니다.
			// 사용자가 파일을 첨부하려고 시도했지만(name이 비어있지 않음),
			// 결과적으로 업로드 데이터가 없는 경우(실패)에만 리디렉션합니다.
			$fileSubmitted = !empty($_FILES['uploadFile']['name']);
			if ($fileSubmitted && $uploadData === null)
			{
				redirect('/board/write');
				return;
			}
			
			$fileAttach = $uploadData ? "Y" : "N";
			
			// 데이터베이스 트랜잭션 시작
			$this->db->trans_start();

			// 3. 데이터베이스에 저장할 데이터 준비
			$rowData = array(
				'title' => $this->input->post('title', TRUE),
				'content' => $this->input->post('content', TRUE),
				'writer' => get_user_id(),
				'file_attach' => $fileAttach
			);
			
			$idx = $this->boardModel->insertBoard($rowData);
			
			// 4. 파일이 업로드된 경우에만 파일 정보를 별도 테이블에 저장합니다.
			if ($idx && $uploadData)
			{
				$fileData = array(
					'board_idx' => $idx,
					'origin_file_name' => $uploadData ? $uploadData['orig_name'] : null, // 원본 파일명
					'stored_file_name' => $uploadData ? $uploadData['file_name'] : null, // 저장된 파일명
				);
				$this->boardModel->insertBoardFile($fileData);
			}
			
			// 트랜잭션 완료 (모든 DB 작업이 성공하면 commit, 하나라도 실패하면 rollback)
			$this->db->trans_complete();

			$this->session->set_flashdata('notice', '게시글이 성공적으로 등록되었습니다.');
			redirect('/board');
		}
		
		/**
		 * (AJAX) 첨부파일을 삭제합니다.
		 *
		 * 상세 보기 페이지에서 '파일 삭제' 버튼 클릭 시 호출됩니다.
		 * 권한 확인 후, 물리적 파일과 DB 데이터를 모두 삭제합니다.
		 * @return CI_Output
		 */
		public function ajaxDeleteFile() {
			$response = ['success' => false, 'message' => '알 수 없는 오류가 발생했습니다.'];
			
			$fileIdx = $this->input->post('fileIdx', TRUE);
			$boardIdx = $this->input->post('boardIdx', TRUE);
			
			// 1. 필수 파라미터 확인
			if (!$fileIdx || !$boardIdx)
			{
				$response['message'] = '잘못된 요청입니다.';
				return $this->output->set_content_type('application/json')->set_output(json_encode($response));
			}
			
			// 2. 권한 확인 (게시물 작성자인지 확인)
			$row = $this->boardModel->getFindByIdx($boardIdx);
			if (!$row || $row->writer !== get_user_id())
			{
				$response['message'] = '삭제 권한이 없습니다.';
				return $this->output->set_content_type('application/json')->set_output(json_encode($response));
			}
			
			// 3. DB에서 삭제할 파일 정보를 가져옵니다.
			$fileInfo = $this->boardModel->getFileInfoByIdx($fileIdx);
			if (!$fileInfo)
			{
				$response['message'] = '삭제할 파일 정보가 없습니다.';
				return $this->output->set_content_type('application/json')->set_output(json_encode($response));
			}
			
			// 4. FileUploadService 공통 모듈을 사용하여 '물리적 파일'만 삭제합니다.
			$fileDeleted = $this->fileUploadService->deleteFile($fileInfo->stored_file_name, 'board');
			
			if ($fileDeleted)
			{
				// 데이터베이스 트랜잭션 시작
				$this->db->trans_start();

				// 5. 물리적 파일 삭제 성공 시, DB 정보를 업데이트합니다.
				$this->boardModel->deleteBoardFile($fileIdx);
				$this->boardModel->updateBoard($boardIdx, array('file_attach' => 'N'));

				$this->db->trans_complete();
				$response['success'] = true;
				$response['message'] = '파일이 성공적으로 삭제되었습니다.';
			}
			else
			{
				$response['message'] = '서버에서 파일 삭제에 실패했습니다.';
			}
			
			// 6. 새로운 CSRF 토큰을 응답에 포함하여 반환
			$response['new_csrf_hash'] = $this->security->get_csrf_hash();
			
			return $this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
		
		/**
		 * 게시물 수정 페이지를 표시합니다.
		 * 작성 페이지와 뷰 파일을 공유합니다.
		 * @param int $idx 수정할 게시물의 고유 번호
		 * @return void
		 */
		public function edit($idx) {
			// 1. DB에서 수정할 게시물 정보를 가져옵니다.
			$row = $this->boardModel->getFindByIdx($idx);
			
			// 2. 게시물이 없거나 수정 권한이 없는 경우, 접근을 거부합니다.
			if (!$row || $row->writer !== get_user_id())
			{
				$this->session->set_flashdata('error', '수정 권한이 없습니다.');
				redirect('/board/view/' . $idx);
				return;
			}
			
			// 3. 뷰로 데이터 전달
			$data['row'] = $row; // 조회된 게시물 데이터를 전달
			$data['pageTitle'] = '게시물 수정';
			$data['formAction'] = '/board/editProc/' . $idx; // 폼 전송(submit) 경로
			$data['queryString'] = http_build_query($this->input->get(null, TRUE));
			
			$this->load->view('_templates/header', $data);
			$this->load->view('sampleBoard/write', $data); // 쓰기/수정 공통 뷰 사용
			$this->load->view('_templates/footer');
		}
		
		/**
		 * 게시물 수정을 처리합니다.
		 * 유효성 검사, 파일 처리(삭제/대체), DB 업데이트를 수행합니다.
		 * @param int $idx 수정할 게시물의 고유 번호
		 * @return void
		 */
		public function editProc($idx) {
			// 1. 유효성 검사 설정 및 실행
			$this->config->load('validation/boardValidation', TRUE);
			$rules = $this->config->item('board_write', 'validation/boardValidation');
			$this->form_validation->set_rules($rules);
			
			if ($this->form_validation->run() === FALSE)
			{
				// 유효성 검사 실패 시, 에러 메시지와 함께 수정 페이지로 다시 돌아갑니다.
				$this->session->set_flashdata('error', validation_errors());
				redirect('/board/edit/' . $idx . '?' . http_build_query($this->input->get()));
				return;
			}
			
			// 2. 수정 권한 확인
			$row = $this->boardModel->getFindByIdx($idx);
			if (!$row || $row->writer !== get_user_id())
			{
				$this->session->set_flashdata('error', '수정 권한이 없습니다.');
				redirect('/board/view/' . $idx);
				return;
			}
			
			// 데이터베이스 트랜잭션 시작
			$this->db->trans_start();

			// 3. 파일 처리 (삭제 또는 신규 업로드)
			$uploadData = $this->fileUploadService->upload('uploadFile', 'board');
			
			// 새 파일이 업로드된 경우, 기존 파일이 있다면 삭제하고 새 파일 정보를 DB에 저장합니다.
			if ($uploadData)
			{
				// 기존 파일이 있었다면 먼저 삭제
				if ($row->file_idx)
				{
					$this->fileUploadService->deleteFile($row->stored_file_name, 'board');
					$this->boardModel->deleteBoardFile($row->file_idx);
				}
				// 새 파일 정보 DB에 저장
				$fileData = array(
					'board_idx' => $idx,
					'origin_file_name' => $uploadData['orig_name'],
					'stored_file_name' => $uploadData['file_name'],
				);
				$this->boardModel->insertBoardFile($fileData);
			}
			
			// 4. 게시물 내용 업데이트
			$updateData = array(
				'title' => $this->input->post('title', TRUE),
				'content' => $this->input->post('content', TRUE),
				'file_attach' => ($uploadData || $row->file_idx) ? 'Y' : 'N'
			);
			$this->boardModel->updateBoard($idx, $updateData);
			
			// 트랜잭션 완료 (모든 DB 작업이 성공하면 commit, 하나라도 실패하면 rollback)
			$this->db->trans_complete();

			$this->session->set_flashdata('notice', '게시물이 성공적으로 수정되었습니다.');
			redirect('/board/view/' . $idx . '?' . http_build_query($this->input->get()));
		}
		
		/**
		 * 게시물을 삭제합니다.
		 * 첨부파일이 있는 경우, 물리적 파일과 DB 데이터를 함께 삭제합니다.
		 * @param int $idx 삭제할 게시물의 고유 번호
		 * @return void
		 */
		public function delete($idx) {
			// 1. 게시물 정보 조회
			$row = $this->boardModel->getFindByIdx($idx);

			// 2. 게시물이 존재하지 않는 경우 404 에러 처리
			if (!$row)
			{
				show_404();
				return;
			}

			// 3. 삭제 권한 확인
			if ($row->writer !== get_user_id())
			{
				$this->session->set_flashdata('error', '삭제 권한이 없습니다.');
				redirect('/board/view/' . $idx);
				return;
			}

			// 4. 데이터베이스 트랜잭션 시작
			$this->db->trans_start();

			// 5. 첨부파일이 있는 경우, 물리적 파일과 DB 데이터를 함께 삭제
			if ($row->file_idx && $row->stored_file_name)
			{
				$this->fileUploadService->deleteFile($row->stored_file_name, 'board');
				$this->boardModel->deleteBoardFile($row->file_idx);
			}

			// 6. 게시물 본문 삭제
			$this->boardModel->deleteBoard($idx);

			// 7. 트랜잭션 완료 (모든 DB 작업이 성공하면 commit, 하나라도 실패하면 rollback)
			$this->db->trans_complete();

			$this->session->set_flashdata('notice', '게시물이 성공적으로 삭제되었습니다.');
			redirect('/board?' . http_build_query($this->input->get()));
		}

		/**
		 * 검색 조건에 맞는 게시물 목록을 엑셀 파일로 다운로드합니다.
		 * @return void
		 */
		public function excelDownload()
		{
			// 1. 현재 검색 조건을 가져옵니다.
			$searchType = $this->input->get('searchType', TRUE);
			$searchKeyword = $this->input->get('searchKeyword', TRUE);

			// 2. 검색 조건에 맞는 '모든' 데이터를 DB에서 조회합니다. (페이지네이션 없음)
			$list = $this->boardModel->findAllWithSearch($searchType, $searchKeyword);

			// 3. 엑셀 파일로 변환할 데이터를 준비합니다.
			$headers = ['번호', '제목', '작성자', '작성일'];
			$dataRows = [];
			$totalCount = count($list); // 전체 데이터 개수를 미리 계산합니다.
			foreach ($list as $key => $item) {
				$number = $totalCount - $key; // 전체 개수에서 현재 인덱스를 빼서 내림차순 번호를 만듭니다.
				$dataRows[] = [
					$number,
					$item->title,
					$item->writer,
					date('Y-m-d H:i', strtotime($item->reg_date)),
				];
			}

			// 4. ExcelDownloadService를 호출하여 다운로드를 실행합니다.
			$filename = '게시판_목록_' . date('Ymd_His') . '.xlsx';
			$this->excelDownloadService->download($filename, $headers, $dataRows);
		}

		/**
		 * 데이터 등록용 엑셀 양식을 다운로드합니다.
		 * @return void
		 */
		public function excelUploadFormDownload()
		{
			$headers = ['제목', '내용', '작성자ID']; // 사용자가 입력해야 할 컬럼
			$dataRows = [
				['샘플 제목 1', '샘플 내용 1 입니다.', 'testuser1']
			];
			$this->excelDownloadService->download('게시판_등록_양식.xlsx', $headers, $dataRows);
		}

		/**
		 * 업로드된 엑셀 파일을 읽어 게시물을 일괄 등록합니다.
		 * @return void
		 */
		public function excelUploadProc()
		{
			// 1. FileUploadService를 사용하여 엑셀 파일을 'temp' 폴더에 임시 업로드합니다.
			$uploadData = $this->fileUploadService->upload('excelFile', 'temp');

			if (!$uploadData) {
				$this->session->set_flashdata('error', '엑셀 파일 업로드에 실패했습니다.');
				redirect('/board');
				return;
			}

			// 2. ExcelUploadService를 사용하여 업로드된 파일의 데이터를 읽습니다.
			$excelData = $this->excelUploadService->readData($uploadData['full_path']);
			unlink($uploadData['full_path']); // 데이터 읽은 후 임시 파일 삭제

			if (!$excelData) {
				$this->session->set_flashdata('error', '엑셀 파일의 데이터를 읽는 데 실패했습니다.');
				redirect('/board');
				return;
			}

			// 배열의 첫 번째 요소(헤더 행)를 제거합니다.
			array_shift($excelData);

			// 3. 읽어온 데이터를 DB에 저장할 형식으로 가공합니다.
			$insertData = [];
			foreach ($excelData as $row) {
				// 엑셀 행의 필수 데이터(A열: 제목)가 비어있는 경우, 해당 행을 건너뜁니다.
				// 이렇게 하면 비어있는 행으로 인해 발생하는 오류를 방지할 수 있습니다.
				if (empty(trim($row[0] ?? '')))
				{
					continue;
				}

				$insertData[] = [
					'title'   => $row[0], // A열 -> 인덱스 0
					'content' => $row[1], // B열 -> 인덱스 1
					'writer'  => $row[2], // C열 -> 인덱스 2
				];
			}

			// 4. BoardModel을 사용하여 데이터를 일괄 등록(Batch Insert)합니다.
			$insertedCount = $this->boardModel->insertBoardBatch($insertData);

			$this->session->set_flashdata('notice', $insertedCount . '개의 게시물이 성공적으로 등록되었습니다.');
			redirect('/board');
		}
	}
