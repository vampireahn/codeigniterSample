<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class AuthController (인증)
	 *
	 * @property MemberModel $memberModel
	 */
	class SampleAuthController extends MY_Controller {
		
		/**
		 * AuthController 컨트롤러 생성자.
		 *
		 * 인증 관련 언어 파일과 회원 모델을 로드.
		 */
		public function __construct() {
			parent::__construct();
			// 라이브러리와 헬퍼는 application/config/autoload.php에서 자동으로 로드됩니다.
			
			$this->load->language('auth'); // 인증 관련 언어 파일을 로드합니다.
			
			$this->load->model('sampleMember/MemberModel', 'memberModel');
		}
		
		/**
		 * 로그인 폼 페이지를 표시.
		 * @return void
		 */
		public function login() {
			$data['page_title'] = '로그인';
			$this->load->view('_templates/header', $data);
			$this->load->view('sampleAuth/login');
			$this->load->view('_templates/footer');
		}
		
		/**
		 * 로그인 폼 제출을 처리.
		 *
		 * 유효성 검사를 수행하고, 성공 시 사용자 인증을 시도한다.
		 * 인증 성공 시 세션을 생성하고 메인 페이지로 리디렉션하며,
		 * 실패 시 오류 메시지와 함께 로그인 페이지를 다시 표시한다.
		 * @return void
		 */
		public function loginProc() {
			// application/config/validation/authValidation.php 파일을 사용하기 위해,
			// 해당 설정 파일을 직접 로드하고 규칙을 설정합니다.
			$this->config->load('validation/authValidation');
			$rules = $this->config->item('login');
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() === FALSE)
			{
				// 유효성 검사 실패 시, redirect 대신 view를 직접 로드합니다.
				// 이렇게 해야 set_value() 함수가 이전 입력값을 기억할 수 있습니다.
				$data['page_title'] = '로그인';
				$this->load->view('_templates/header', $data);
				$this->load->view('sampleAuth/login');
				$this->load->view('_templates/footer');
			}
			else
			{
				$userId = $this->input->post('userId');
				$password = $_POST["password"];
				
				$user = $this->memberModel->findByUserId($userId);
				
				if ($user && password_verify($password, $user->user_password))
				{
					$this->session->sess_regenerate(TRUE);
					$this->session->set_userdata([
						'login' => TRUE,
						'userId' => $user->user_id,
						'userIdx' => (int)$user->idx
					]);

					// 1. 돌아갈 URL이 세션에 저장되어 있는지 확인합니다.
					$return_url = $this->session->userdata('return_url');
					// 2. 사용 후에는 세션에서 해당 데이터를 삭제합니다.
					$this->session->unset_userdata('return_url');
					// 3. 돌아갈 URL이 있으면 그곳으로, 없으면 메인 페이지로 리디렉션합니다.
					redirect($return_url ? $return_url : '/');
					return;
				}
				
				$data['error'] = $this->lang->line('auth_invalid_credentials') ?: '아이디 또는 비밀번호가 올바르지 않습니다.';
				$data['page_title'] = '로그인';
				$this->load->view('_templates/header', $data);
				$this->load->view('sampleAuth/login', $data);
				$this->load->view('_templates/footer');
			}
		}
		
		/**
		 * 사용자를 로그아웃 처리.
		 *
		 * 세션을 파괴하고 사용자를 로그인 페이지로 리디렉션한다.
		 * @return void
		 */
		public function logout() {
			// 1. 세션에 저장된 모든 사용자 데이터를 메모리에서 제거합니다.
			$this->session->unset_userdata(array_keys($this->session->userdata));
			// 2. 서버의 세션을 파괴하고, ci_sessions 테이블에서 해당 행을 삭제합니다.
			$this->session->sess_destroy();
			// 3. 사용자를 로그인 페이지로 리디렉션합니다.
			redirect('/');
		}
	}
