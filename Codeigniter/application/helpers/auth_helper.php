<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('is_logged_in'))
{
    /**
     * 사용자가 로그인했는지 확인합니다.
     * @return bool
     */
    function is_logged_in()
    {
        $CI =& get_instance();
        return (bool) $CI->session->userdata('login');
    }
}

if ( ! function_exists('get_user_id'))
{
    /**
     * 로그인한 사용자의 아이디를 가져옵니다.
     * @return string|null
     */
    function get_user_id()
    {
        $CI =& get_instance();
        return $CI->session->userdata('userId');
    }
}