<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Class PaginationService
	 *
	 * 페이지네이션 생성을 처리하는 공통 라이브러리입니다.
	 *
	 * @property CI_Pagination $pagination
	 */
	class PaginationService {
		
		private $CI;
		
		public function __construct() {
			$this->CI =& get_instance();
			$this->CI->load->library('pagination');
		}
		
		/**
		 * 페이지네이션 링크 HTML을 생성하고 반환합니다.
		 * Bootstrap 5 스타일을 적용합니다.
		 *
		 * @param string $baseUrl 페이지네이션의 기본 URL
		 * @param int $totalRows 전체 아이템 수
		 * @param int $perPage 페이지 당 아이템 수
		 * @return string 생성된 페이지네이션 링크 HTML
		 */
		public function createLinks($baseUrl, $totalRows, $perPage = 10) {
			$config['base_url'] = $baseUrl;
			$config['total_rows'] = $totalRows;
			$config['per_page'] = $perPage;
			
			// URL에 오프셋 대신 실제 페이지 번호를 사용하도록 설정합니다.
			$config['use_page_numbers'] = TRUE;
			
			// 쿼리스트링을 사용하여 페이지 번호를 전달하도록 설정합니다. (예: /board?page=2)
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'page';
			$config['reuse_query_string'] = TRUE;
			
			// Bootstrap 5 스타일 적용
			$config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
			$config['full_tag_close'] = '</ul></nav>';
			
			$config['first_link'] = '처음';
			$config['first_tag_open'] = '<li class="page-item">';
			$config['first_tag_close'] = '</li>';
			
			$config['last_link'] = '마지막';
			$config['last_tag_open'] = '<li class="page-item">';
			$config['last_tag_close'] = '</li>';
			
			$config['next_link'] = '&raquo;';
			$config['next_tag_open'] = '<li class="page-item">';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_link'] = '&laquo;';
			$config['prev_tag_open'] = '<li class="page-item">';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li class="page-item">';
			$config['num_tag_close'] = '</li>';
			
			$config['attributes'] = array('class' => 'page-link');
			
			$this->CI->pagination->initialize($config);
			
			return $this->CI->pagination->create_links();
		}
	}
