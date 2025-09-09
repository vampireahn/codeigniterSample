<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class FileuploadService
	 *
	 * 파일 업로드를 처리하는 공통 라이브러리입니다.
	 *
	 * @property CI_Upload $upload
	 * @property CI_Session $session
	 */
	class FileuploadService {
		
		private $CI;
		
		public function __construct() {
			$this->CI =& get_instance();
			$this->CI->load->library('upload');
		}
		
		/**
		 * 파일을 업로드하고 결과를 반환합니다.
		 *
		 * @param string $field_name 폼의 파일 입력 필드 이름 (예: 'uploadFile')
		 * @param string $subdirectory 업로드할 하위 폴더명 (예: 'board')
		 * @param array $options 업로드 설정을 오버라이드할 배열
		 * @return array|null 업로드 성공 시 파일 데이터 배열, 실패 시 null
		 */
		public function upload($field_name, $subdirectory = '', $options = []) {
			// 1. 파일이 전송되지 않았는지 확인합니다.
			if (!isset($_FILES[$field_name]))
			{
				return null;
			}
			
			// 2. 사용자가 파일을 첨부하지 않은 경우(UPLOAD_ERR_NO_FILE)는 오류가 아니므로, 정상적으로 null을 반환합니다.
			if ($_FILES[$field_name]['error'] === UPLOAD_ERR_NO_FILE)
			{
				return null;
			}
			
			// 업로드 기본 경로에 하위 폴더 경로를 추가합니다.
			$upload_base_path = './uploads/';
			$upload_path = $subdirectory ? rtrim($upload_base_path, '/') . '/' . trim($subdirectory, '/') . '/' : $upload_base_path;
			
			// 기본 업로드 설정
			$default_config = [
				'upload_path' => $upload_path,
				'allowed_types' => 'gif|jpg|jpeg|png|zip|pdf|hwp|doc|docx|xls|xlsx',
				'max_size' => 20480, // 20MB (20 * 1024)
				'encrypt_name' => TRUE,
			];
			
			// 사용자 정의 옵션과 기본 설정을 병합
			$config = array_merge($default_config, $options);
			
			// 업로드 경로가 없으면 생성 (쓰기 권한 필요)
			if (!is_dir($config['upload_path']))
			{
				mkdir($config['upload_path'], 0777, TRUE);
			}
			
			// CodeIgniter의 Upload 라이브러리 초기화
			$this->CI->upload->initialize($config);
			
			// 파일 업로드 실행
			if (!$this->CI->upload->do_upload($field_name) || $_FILES[$field_name]['error'] !== UPLOAD_ERR_OK)
			{
				// 업로드 실패 시, 오류 메시지를 세션에 저장하고 null 반환
				$this->CI->session->set_flashdata('error', $this->CI->upload->display_errors());
				return null;
			}
			
			// 업로드 성공 시, 파일 데이터 반환
			return $this->CI->upload->data();
		}
		
		/**
		 * 지정된 파일을 서버에서 삭제합니다. (공통 모듈)
		 *
		 * @param string $storedFileName - 서버에 저장된 실제 파일명
		 * @param string $dir - 파일이 저장된 디렉토리 (e.g., 'board')
		 * @return bool - 삭제 성공 여부
		 */
		public function deleteFile($storedFileName, $dir) {
			$filePath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $storedFileName;
			
			// 1. 실제 파일 삭제
			if (file_exists($filePath))
			{
				if (!unlink($filePath))
				{
					return false; // 파일 삭제 실패
				}
			}
			
			// 파일이 이미 없거나, 삭제에 성공한 경우 모두 true를 반환합니다.
			return true;
		}
	}
