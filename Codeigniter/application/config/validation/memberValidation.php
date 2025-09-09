<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// validation/memberValidation.php
// 섹션 사용(TRUE)로 로드하면 'member' 키로 접근합니다.
$config = array(
    'member_register' => array(
        array(
            'field'=>'userId','label'=>'아이디','rules'=>'trim|required|min_length[4]|max_length[20]|username_policy|is_unique[member.user_id]',
            'errors' => array('is_unique' => '이미 사용 중인 아이디입니다.')
        ),
        array(
            'field'=>'id_check_status','label'=>'아이디 중복 체크',
            'rules'=>'required|in_list[checked]',
            'errors' => array('required' => '아이디 중복 체크를 완료해주세요.', 'in_list' => '아이디 중복 체크를 완료해주세요.')
        ),
        array('field'=>'userName','label'=>'이름','rules'=>'trim|required|min_length[2]|max_length[50]'),
        array(
            'field'=>'email','label'=>'이메일','rules'=>'trim|required|valid_email|max_length[100]|is_unique[member.user_email]',
            'errors' => array('is_unique' => '이미 사용 중인 이메일입니다.')
        ),
        array('field'=>'phone','label'=>'휴대전화','rules'=>'trim|phone_kr'),
        array('field'=>'password','label'=>'비밀번호','rules'=>'trim|required|password_strength'),
        array('field'=>'passwordConfirm','label'=>'비밀번호 확인','rules'=>'trim|required|matches[password]')
    ),
    'member_change_password' => array(
        array('field'=>'currentPassword','label'=>'현재 비밀번호','rules'=>'trim|required|password_strength'),
        array('field'=>'newPassword','label'=>'새 비밀번호','rules'=>'trim|required|password_strength'),
        array('field'=>'newPasswordConfirm','label'=>'새 비밀번호 확인','rules'=>'trim|required|matches[newPassword]')
    )
);
