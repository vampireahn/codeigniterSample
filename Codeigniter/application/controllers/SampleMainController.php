<?php
	
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class MainController (메인 페이지)
	 */
	class SampleMainController extends MY_Controller {
		public function __construct() {
			parent::__construct();
		}
		
		public function index() {
			// 뷰에 전달할 데이터를 설정합니다.
			$data['page_title'] = '메인 페이지';
			$data['styles'] = array('assets/css/sampleMain.css'); // 이 페이지 전용 스타일시트를 지정합니다.
			
			$this->load->view('_templates/header', $data);
			$this->load->view('sampleMain/index');
			$this->load->view('_templates/footer');
		}
	}
