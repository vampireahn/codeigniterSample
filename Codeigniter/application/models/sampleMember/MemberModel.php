<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class MemberModel extends CI_Model {
		private $tableName = "member";
		
		public function join($data) {
			$db_data = array(
				'user_id' => $data['userId'],
				'user_name' => $data['userName'],
				'user_email' => $data['email'],
				'user_mobile' => $data['phone'],
				// 평문 비밀번호를 해싱하여 'user_password' 키로 저장합니다.
				'user_password' => password_hash($data['password'], PASSWORD_BCRYPT)
			);
			
			// 'password' 키가 없는 깨끗한 $db_data 배열을 insert 함수에 전달합니다.
			$this->db->insert($this->tableName, $db_data);
			
			return $this->db->insert_id();
		}
		
		public function findByUserId($userId) {
			// 실제 데이터베이스에서 user_id 필드로 사용자를 찾습니다.
			$this->db->where('user_id', $userId);
			$query = $this->db->get($this->tableName);
			// 사용자 정보를 객체로 반환합니다. (사용자가 없으면 null 반환)
			return $query->row();
		}
		
		public function passwordUpdate($userId, $password) {
			$sql = "
			UPDATE {$this->tableName} SET user_password = ? WHERE user_id = ?
		";
			
			$this->db->query($sql, array(
				password_hash($password, PASSWORD_BCRYPT),
				$userId
			));
		}
	}
