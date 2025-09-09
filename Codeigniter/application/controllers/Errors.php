<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class Errors
	 *
	 * 커스텀 오류 페이지를 처리하는 컨트롤러입니다.
	 */
	class Errors extends CI_Controller {
		
		public function __construct() {
			parent::__construct();
		}
		
		public function show_404() {
			$this->output->set_status_header('404'); // 404 상태 코드 설정
			$this->load->view('errors/custom_404'); // 커스텀 404 뷰 파일을 로드합니다.
		}
	}