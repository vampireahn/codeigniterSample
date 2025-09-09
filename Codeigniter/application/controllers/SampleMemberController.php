<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class MemberController (회원)
	 *
	 * @property MemberModel $memberModel
	 */
	class SampleMemberController extends MY_Controller {
		
		/**
		 * MemberController 컨트롤러 생성자.
		 *
		 * 회원 관련 모델을 로드.
		 */
		public function __construct() {
			parent::__construct();
			
			$this->load->model('sampleMember/MemberModel', 'memberModel');
		}
		
		/**
		 * 회원가입 폼 페이지를 표시.
		 * @return void
		 */
		public function register() {
			$data['page_title'] = '회원가입';
			$this->load->view('_templates/header', $data);
			$this->load->view('sampleMember/register');
			$this->load->view('_templates/footer');
		}
		
		/**
		 * 회원가입 폼 제출을 처리.
		 * 유효성 검사를 수행하고, 성공 시 회원 정보를 데이터베이스에 저장.
		 * @return void
		 */
		public function registerProc() {
			// application/config/validation/memberValidation.php 파일을 사용하기 위해,
			// 설정을 별도 섹션 없이 직접 로드하여 안정성을 높입니다.
			$this->config->load('validation/memberValidation');
			$rules = $this->config->item('member_register');
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() === FALSE)
			{
				// 유효성 검사 실패 시, redirect 대신 view를 직접 로드합니다.
				// 이렇게 해야 set_value() 함수가 이전 입력값을 기억할 수 있습니다.
				$data['page_title'] = '회원가입';
				$this->load->view('_templates/header', $data);
				$this->load->view('sampleMember/register');
				$this->load->view('_templates/footer');
			}
			else
			{
				
				$data = array(
					'userId' => $this->input->post('userId'),
					'userName' => $this->input->post('userName'),
					'email' => $this->input->post('email'),
					'phone' => $this->input->post('phone'),
					'password' => $_POST['password']
				);
				
				// 모델의 join() 메소드를 명시적으로 호출합니다.
				$result = $this->memberModel->join($data);
				
				if ($result)
				{
					$this->session->set_flashdata('notice', '회원가입이 완료되었습니다.');
					redirect('/auth/login');
				}
				else
				{
					$this->load->view('_templates/header', ['page_title' => '회원가입']);
					$this->load->view('sampleMember/register');
					$this->load->view('_templates/footer');
				}
			}
		}
		
		/**
		 * 아이디 중복 체크를 위한 AJAX 요청을 처리.
		 * POST 방식으로 userId를 받아 중복 여부를 JSON 형태로 응답.
		 * @return void
		 */
		public function ajax_id_check() {
			// AJAX 요청이 아니거나 POST 방식이 아니면 접근을 거부합니다.
			if (!$this->input->is_ajax_request() || $this->input->method() !== 'post')
			{
				show_404();
				return;
			}
			
			$userId = $this->input->post('userId');
			$response = ['status' => 'error', 'message' => '알 수 없는 오류가 발생했습니다.'];
			
			if (!empty($userId))
			{
				$user = $this->memberModel->findByUserId($userId);
				$response['status'] = 'success';
				$response['is_duplicate'] = ($user !== NULL);
			}
			
			// CSRF 토큰을 갱신하는 경우, 새로운 토큰 값을 응답에 포함시킵니다.
			$response['new_csrf_hash'] = $this->security->get_csrf_hash();
			
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response));
		}
		
		/**
		 * 비밀번호 변경 폼 페이지를 표시.
		 * 로그인 상태가 아니면 로그인 페이지로 리디렉션한다.
		 * @return void
		 */
		public function changePassword() {
			if (!is_logged_in())
			{
				$this->session->set_flashdata("error", "정상적인 방법으로 접속 해주세요.");
				redirect('/auth/login');
			}
			
			$data['page_title'] = '비밀번호 변경!';
			$this->load->view('_templates/header', $data);
			$this->load->view('sampleMember/change_password');
			$this->load->view('_templates/footer');
		}
		
		/**
		 * 비밀번호 변경 폼 제출을 처리.
		 * 유효성 검사 및 현재 비밀번호 확인 후, 새 비밀번호로 업데이트.
		 * @return void
		 */
		public function changePasswordProc() {
			$this->config->load('validation/memberValidation');
			$rules = $this->config->item('member_change_password');
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() === FALSE)
			{
				$this->session->set_flashdata('error', validation_errors());
				redirect('/member/changePassword');
				return;
			}
			
			if (is_logged_in())
			{
				$currentPassword = $_POST["currentPassword"];
				$newPassword = $_POST["newPassword"];
				
				$user = $this->memberModel->findByUserId(get_user_id());
				
				if ($user && password_verify($currentPassword, $user->user_password))
				{
					// 현재 비밀번호가 일치하면, 새 비밀번호로 업데이트합니다.
					$this->memberModel->passwordUpdate($user->user_id, $newPassword);
					$this->session->set_flashdata('notice', '비밀번호가 변경되었습니다.');
					redirect('/');
					return;
				}
				else
				{
					// 현재 비밀번호가 일치하지 않으면, 오류 메시지와 함께 이전 페이지로 돌아갑니다.
					$this->session->set_flashdata('error', '현재 비밀번호가 일치하지 않습니다.');
					redirect('/member/changePassword');
					return;
				}
			}
		}
	}
