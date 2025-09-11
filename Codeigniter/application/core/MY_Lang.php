<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom Language Class
 *
 * CI_Lang 클래스를 확장하여 번역 키가 없을 경우 기본 언어(english)에서
 * 값을 찾아 반환하는 fallback 기능을 추가합니다.
 */
class MY_Lang extends CI_Lang {

    /**
     * CodeIgniter 인스턴스
     * @var object
     */
    /**
     * 기본 대체(fallback) 언어 디렉토리 이름
     * @var string
     */
    protected $fallback_lang = 'english';

    /**
     * 언어 배열에서 단일 텍스트 라인을 가져옵니다.
     *
     * @param   string  $line       언어 라인 키
     * @param   bool    $log_errors 라인을 찾지 못했을 때 오류를 기록할지 여부
     * @return  string  번역, 또는 현재 언어와 대체(fallback) 언어 모두에서 찾지 못한 경우 키 자체.
     */
    public function line($line = '', $log_errors = TRUE)
    {
        // 1. 현재 로드된 언어 배열에서 번역을 직접 찾아봅니다.
        // parent::line()은 값을 못 찾으면 false를 반환하는데, 이 과정에서 불필요한 로직이 있어 직접 배열에서 찾습니다.
        $translation = $this->language[$line] ?? FALSE;

        $CI =& get_instance(); // 메소드 내에서 CI 인스턴스를 가져옵니다.

        // 2. 번역에 실패했고, 현재 세션의 언어가 fallback 언어와 다른 경우
        $current_session_lang = $CI->session->userdata('site_lang') ?? 'korean';
        if ($translation === FALSE && $current_session_lang !== $this->fallback_lang)
        {
            // 3. Fallback 언어 팩이 아직 로드되지 않았다면 로드합니다.
            // $this->is_loaded는 protected이므로 직접 접근 대신 array_key_exists로 확인합니다.
            $fallback_file = 'languagePack_lang.php';
            if ( ! array_key_exists($fallback_file, $this->is_loaded))
            {
                $this->load('languagePack', $this->fallback_lang);
            }

            // 4. 다시 번역을 시도합니다.
            $translation = $this->language[$line] ?? FALSE;
        }

        // 5. 최종적으로 번역을 찾지 못했다면, 에러를 기록하고 키 자체를 반환합니다.
        // 이렇게 하면 어떤 키가 누락되었는지 개발자가 쉽게 파악할 수 있습니다.
        if ($translation === FALSE)
        {
            if ($log_errors === TRUE) {
                log_message('error', '[' . $current_session_lang . '] 언어에서 다음 라인을 찾을 수 없습니다: "' . $line . '"');
            }
            return $line; // 키 자체를 반환
        }

        return $translation;
    }
}
