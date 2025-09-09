<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class MY_Exceptions
	 *
	 * CodeIgniter의 기본 예외 처리 클래스를 확장하여
	 * 커스텀 500 오류 페이지를 처리합니다.
	 */
	class MY_Exceptions extends CI_Exceptions {
		
		public function __construct() {
			parent::__construct();
		}
		
		/**
		 * 일반적인 오류(500 Internal Server Error)를 표시합니다.
		 *
		 * @param string $heading 페이지 제목
		 * @param string|string[] $message 오류 메시지
		 * @param string $template 사용할 템플릿 (error_general)
		 * @param int $status_code HTTP 응답 코드 (기본값: 500)
		 * @return string 렌더링된 오류 페이지
		 */
		public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
			// 500 오류가 발생했을 때, 커스텀 뷰 템플릿을 지정합니다.
			// 'production' 환경에서는 상세 오류가 표시되지 않도록 뷰 파일에서 분기 처리합니다.
			if ($status_code == 500) {
				// iframe 내부에서 콘텐츠만 요청하는 경우
				if (isset($_GET['content_only'])) {
					$template = 'custom_500';
				}
				// 일반적인 500 에러 요청인 경우
				else {
					// 개발 환경에서는 iframe 셸을, 운영 환경에서는 바로 커스텀 페이지를 보여줍니다.
					// CI_Exceptions에서 'html/' 접두사를 자동으로 붙여주므로, 여기서는 제거합니다.
					$template = (ENVIRONMENT !== 'production') ? 'error_500_shell' : 'custom_500';
				}
			}

			// 부모 클래스의 show_error를 호출하여 변수 전달 및 뷰 렌더링을 위임합니다.
			return parent::show_error($heading, $message, $template, $status_code);
		}
	}
