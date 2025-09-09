<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// validation/api.php
$config = array(
    'api_register' => array(
        array('field'=>'email','label'=>'이메일','rules'=>'trim|required|valid_email|max_length[100]'),
        array('field'=>'name','label'=>'이름','rules'=>'trim|required|min_length[2]|max_length[50]'),
        array('field'=>'age','label'=>'나이','rules'=>'trim|required|integer|greater_than_equal_to[19]|less_than_equal_to[120]')
    )
);
