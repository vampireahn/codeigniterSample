<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class MY_Form_validation extends CI_Form_validation {
		public function password_strength($str) {
			// 비밀번호 규칙: 영문(대소문자 구분 없음), 숫자, 특수문자를 각각 1개 이상 포함하고 8자 이상
			$ok = preg_match('/[a-zA-Z]/', $str)
				&& preg_match('/[0-9]/', $str)
				&& preg_match('/[\W_]/', $str)
				&& strlen($str) >= 8;
			if (!$ok)
			{
				$this->set_message('password_strength', '비밀번호는 영문, 숫자, 특수문자를 포함한 8자 이상이어야 합니다.');
				return FALSE;
			}
			return TRUE;
		}
		
		public function username_policy($str) {
			if (!preg_match('/^[a-z][a-z0-9_]{3,19}$/', $str))
			{
				$this->set_message('username_policy', '아이디는 영문 소문자로 시작하고 영문/숫자/언더바만 허용(4~20자)입니다.');
				return FALSE;
			}
			return TRUE;
		}
		
		public function id_checked($str) {
			// hidden 필드의 값이 'checked'가 아니면, 중복 체크를 하지 않은 것으로 간주합니다.
			if ($str !== 'checked')
			{
				$this->set_message('id_checked', '아이디 중복 체크를 해주세요.');
				return FALSE;
			}
			return TRUE;
		}
		
		public function phone_kr($str) {
			// 휴대전화는 선택 입력 항목이므로, 비어있으면 통과시킵니다.
			if ($str === '' || $str === NULL)
			{
				return TRUE;
			}
			
			// 사용자가 숫자만 입력한 경우, 하이픈을 포함하도록 유도합니다.
			if (ctype_digit($str))
			{
				// 숫자만으로 구성된 유효한 휴대폰 번호 형식(10~11자리)인지 추가로 확인합니다.
				if (preg_match('/^01[016789]\d{7,8}$/', $str))
				{
					$this->set_message('phone_kr', '휴대전화 번호에 하이픈(-)을 포함하여 입력해주세요. (예: 010-1234-5678)');
					return FALSE;
				}
			}
			
			// 하이픈을 포함한 최종 유효성 검사를 합니다.
			if (preg_match('/^(01[016789])-(\d{3,4})-(\d{4})$/', $str))
			{
				return TRUE;
			}
			
			// 위의 모든 조건에 맞지 않으면 올바르지 않은 형식입니다.
			$this->set_message('phone_kr', '휴대전화 형식이 올바르지 않습니다.');
			return FALSE;
		}
		
		public function file_ext($dummy, $field) {
			if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE)
			{
				return TRUE;
			}
			$name = $_FILES[$field]['name'];
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			$allowed_env = getenv('UPLOAD_ALLOWED_EXT');
			$allowed = $allowed_env ? array_map('trim', explode(',', strtolower($allowed_env))) : array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'zip');
			if (!in_array($ext, $allowed))
			{
				$this->set_message('file_ext', '허용되지 않은 파일 형식입니다.');
				return FALSE;
			}
			return TRUE;
		}
		
		public function file_max_size($dummy, $field) {
			if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE)
			{
				return TRUE;
			}
			$max = (int)(getenv('UPLOAD_MAX_BYTES') ?: 104857600);
			$size = (int)$_FILES[$field]['size'];
			if ($size > $max)
			{
				$this->set_message('file_max_size', '파일 크기가 제한을 초과했습니다.');
				return FALSE;
			}
			return TRUE;
		}
	}
