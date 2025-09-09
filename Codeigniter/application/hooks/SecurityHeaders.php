<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SecurityHeaders
{
    public function setHeaders()
    {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: no-referrer-when-downgrade');
        header('X-XSS-Protection: 1; mode=block');

        // CSP 정책에 Bootstrap과 Font Awesome CDN을 허용하도록 수정합니다.
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' https://ajax.googleapis.com https://cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "font-src 'self' https://cdnjs.cloudflare.com; " .
               "img-src 'self' data:; " .
               "base-uri 'self'; form-action 'self'";
        header("Content-Security-Policy: " . $csp);

        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
}
