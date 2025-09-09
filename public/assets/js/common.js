/**
 * Common JavaScript Module
 *
 * 이 파일은 모든 페이지에 공통으로 적용되는 JavaScript 코드를 포함합니다.
 */

// 모든 AJAX 요청이 성공했을 때, 서버가 새로운 CSRF 토큰을 보내줬다면 자동으로 폼을 업데이트합니다.
$(document).ajaxSuccess(function(event, xhr, settings, data) {
    // 응답 데이터(data)가 객체이고 new_csrf_hash 속성을 가지고 있는지 확인합니다.
    if (data && typeof data === 'object' && data.hasOwnProperty('new_csrf_hash')) {
        // 폼 안에 있는 CSRF 토큰의 값을 새로운 해시로 업데이트합니다.
        // csrfTokenName은 템플릿의 footer에서 전역 변수로 정의됩니다.
        if (typeof csrfTokenName !== 'undefined') {
            $('input[name="' + csrfTokenName + '"]').val(data.new_csrf_hash);
        }
    }
});