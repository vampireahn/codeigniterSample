<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LanguageController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // URL 헬퍼를 로드합니다.
        $this->load->helper('url');
    }

    /**
     * 사용자가 선택한 언어를 세션에 저장하고 이전 페이지로 리디렉션합니다.
     */
    public function switchLanguage() {
        $lang = $this->input->post('language');

        // constants.php에 정의된 LANGUAGES 상수의 키(key)를 지원 언어 목록으로 사용합니다.
        $supported_langs = array_keys(LANGUAGES);
        $site_lang = in_array($lang, $supported_langs) ? $lang : 'korean'; // 기본값은 한국어

        $this->session->set_userdata('site_lang', $site_lang);
        redirect($this->agent->referrer() ?? '/');
    }
}
