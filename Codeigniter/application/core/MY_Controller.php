<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class MY_Controller
	 *
	 * 모든 컨트롤러가 상속받는 공통 부모 컨트롤러입니다.
	 * 공통 기능 및 인가(Authorization) 처리를 중앙에서 담당합니다.
	 *
	 * @property CI_Loader $load       // view, model, library 등을 로드하는 기능
	 * @property CI_Input $input      // GET, POST 등 사용자 입력을 받는 기능
	 * @property CI_Session $session    // 세션 관리 기능
	 * @property CI_Config $config     // config.php 파일의 설정 값을 다루는 기능
	 * @property CI_Router $router     // 현재 실행 중인 컨트롤러/메소드 정보를 다루는 기능
	 */
	class MY_Controller extends CI_Controller {
		/**
		 * 로그인이 필요한 리소스 목록입니다.
		 * '컨트롤러 클래스 이름(PascalCase)' => ['메소드1', '메소드2', '*']
		 * @var array
		 */
		private $protected_resources = [
			'SampleBoardController'  => ['*'], // SampleBoardController의 모든 메소드
			'SampleMemberController' => ['changePassword', 'changePasswordProc'], // MemberController의 특정 메소드
		];
		
		public function __construct() {
			parent::__construct();
			$this->loadLanguage(); // 0. 언어 파일 로드
			$this->validateSession(); // 1. 세션 유효성 검증
			$this->checkAuthorization();  // 2. 인가(Authorization) 확인
		}

		/**
		 * 세션을 기반으로 다국어 파일을 로드합니다.
		 */
		private function loadLanguage() {
			$site_lang = $this->session->userdata('site_lang') ?? 'korean'; // 세션에 언어 설정이 없으면 'korean'을 기본값으로 사용
			$this->lang->load('languagePack', $site_lang);
		}
		
		/**
		 * 세션 데이터의 유효성을 검증합니다.
		 *
		 * 사용자가 로그인 상태(is_logged_in)이지만, 필수 세션 데이터(userId)가 없는 경우
		 * (예: DB의 세션 테이블이 비워진 경우)를 감지하여 강제로 로그아웃 처리합니다.
		 */
		private function validateSession() {
			if (is_logged_in() && !get_user_id())
			{
				// 세션 데이터가 불일치하므로, 세션을 완전히 파괴합니다.
				$this->session->sess_destroy();
				
				// 사용자에게 상황을 안내하고, 다시 로그인하도록 유도합니다.
				$this->session->set_flashdata('notice', $this->lang->line('세션이 만료되었습니다. 다시 로그인해주세요.'));
				redirect('auth/login');
				exit; // 리디렉션 후 추가 실행을 방지하기 위해 스크립트를 종료합니다.
			}
		}
		
		/**
		 * 현재 요청된 메소드에 대한 인가(Authorization)를 확인합니다.
		 */
		private function checkAuthorization() {
			$class = $this->router->fetch_class(); // strtolower()를 제거하여 클래스 이름을 그대로 사용합니다.
			$method = $this->router->fetch_method();
			
			// 현재 요청된 컨트롤러가 보호 목록에 있는지 확인합니다.
			if (isset($this->protected_resources[$class]))
			{
				$required_methods = $this->protected_resources[$class];
				
				// 현재 메소드가 로그인이 필요한 메소드 목록에 포함되어 있고, 로그인이 되어있지 않은 경우
				if ((in_array('*', $required_methods) || in_array($method, $required_methods)) && !is_logged_in())
				{
					// 사용자가 원래 가려던 페이지를 'return_url'로 저장해두면,
					// 로그인 성공 후 해당 페이지로 다시 보내주기 위해 일반 세션에 저장합니다.
					$this->session->set_userdata('return_url', current_url());
					redirect('auth/login');
				}
			}
		}
	}
