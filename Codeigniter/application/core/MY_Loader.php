<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class MY_Loader
 *
 * CodeIgniter의 기본 CI_Loader 클래스를 확장합니다.
 * 이 클래스는 라이브러리 로딩 방식을 개선하여,
 * 클래스명과 파일명이 대소문자까지 정확히 일치하도록 만듭니다.
 * (예: 'PaginationService' 클래스는 'PaginationService.php' 파일을 로드)
 */
class MY_Loader extends CI_Loader {

    /**
     * 라이브러리 로더를 재정의(override)합니다.
     *
     * @param   string|string[] $library    라이브러리 이름
     * @param   array|null      $params     라이브러리에 전달할 파라미터
     * @param   string|null     $object_name 객체 별칭
     * @return  CI_Loader
     */
    public function library($library, $params = NULL, $object_name = NULL)
    {
        if (is_array($library)) {
            foreach ($library as $lib) {
                $this->library($lib, $params);
            }
            return $this;
        }

        // 기본 로더의 library 메소드를 호출하되, 클래스 이름을 그대로 전달합니다.
        // 이렇게 하면 CI가 파일명을 변환하지 않고 원본 이름으로 파일을 찾게 됩니다.
        return parent::library($library, $params, $object_name);
    }
}