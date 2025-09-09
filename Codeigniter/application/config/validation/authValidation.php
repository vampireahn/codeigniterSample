<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// validation/authValidation.php
$config = array(
    'login' => array(
		// 로그인 시에는 아이디와 비밀번호가 비어있는지만 확인하면 충분합니다.
	    array('field'=>'userId','label'=>'아이디','rules'=>'trim|required'),
	    array('field'=>'password','label'=>'비밀번호','rules'=>'trim|required')
    )
);
