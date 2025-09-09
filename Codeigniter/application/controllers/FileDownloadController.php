<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class FileDownloadController
 *
 * 파일 다운로드를 중앙에서 처리하는 공통 컨트롤러입니다.
 */
class FileDownloadController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// CodeIgniter의 'download' 헬퍼를 로드합니다.
		$this->load->helper('download');
	}

	/**
	 * 파일을 다운로드합니다.
	 *
	 * @param string $dir             - 파일이 저장된 디렉토리 (e.g., 'board')
	 * @param string $storedFileName  - 서버에 저장된 실제 파일명
	 * @param string $originalFileName- 다운로드 시 사용자에게 보여줄 원본 파일명
	 */
	public function download($dir, $storedFileName, $originalFileName)
	{
		// Base64로 인코딩된 파일명을 원래대로 디코딩합니다.
		$storedFileName = base64_decode($storedFileName);
		$originalFileName = base64_decode($originalFileName);

		// 파일의 전체 경로를 구성합니다.
		// FCPATH는 public/index.php가 있는 프로젝트 루트를 가리킵니다.
		$filePath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $storedFileName;

		// 파일이 존재하지 않거나 읽을 수 없는 경우, 404 에러를 표시합니다.
		if (!file_exists($filePath) || !is_readable($filePath)) {
			show_404();
			return;
		}

		// force_download() 함수는 파일 데이터를 읽어와
		// 브라우저가 다운로드하도록 강제하는 HTTP 헤더를 전송합니다.
		// 두 번째 인자로 파일의 내용을 직접 전달할 수도 있습니다.
		force_download($originalFileName, file_get_contents($filePath));
	}
}
