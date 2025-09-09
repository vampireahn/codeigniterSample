<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * Model Class
	 *
	 * CodeIgniter의 기본 모델을 확장하여 공통 기능을 제공합니다.
	 * 암호화/복호화, 타임스탬프, CRUD 작업 등의 기본 기능을 포함합니다.
	 *
	 * @category    Core
	 * @author      Dev Team
	 */
	class MY_Model extends CI_Model {
		/**
		 * 생성자
		 */
		public function __construct() {
			parent::__construct();
			
			//  정수와 실수를 네이티브 형태로 반환하도록 설정, 설정하지 않으면 문자열로 반환됨
			mysqli_options($this->db->conn_id, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
		}
	}
