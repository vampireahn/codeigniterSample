# CodeIgniter 3 Docker Development Environment

이 프로젝트는 Docker를 사용하여 CodeIgniter 3 애플리케이션을 실행하는 개발 환경입니다.

## 기술 스택

*   **Framework**: CodeIgniter 3
*   **PHP**: 7.4
*   **Web Server**: Apache
*   **Database**: MySQL (Docker 외부에서 실행)
*   **Dependency Manager**: Composer

## 디렉토리 구조

```
p2p/
├── docker/              # Docker 설정 파일 (Apache, PHP)
└── src/                 # 애플리케이션 소스 코드
    ├── Codeigniter/     # CodeIgniter 프레임워크
    │   ├── application/ # CI 애플리케이션 (컨트롤러, 모델, 뷰 등)
    │   ├── system/      # CI 시스템 코어
    │   └── vendor/      # Composer 라이브러리
    ├── public/          # 웹 서버 공개 루트 (index.php, assets 등)
    └── .env             # 환경 변수 설정 파일
```

## 실행 방법

1.  **Docker 컨테이너 실행**

    ```sh
    docker-compose up -d --build
    ```

2.  **로그 디렉토리 생성 (필수)**

    CodeIgniter가 생성하는 로그 파일을 저장하기 위한 디렉토리입니다. 이 디렉토리는 `.gitignore`에 등록되어 버전 관리에서 제외되므로, **반드시 수동으로 생성해야 합니다.**

    ```sh
    # src 폴더 내에 log 디렉토리 생성
    mkdir -p src/log
    ```

2.  **Composer 의존성 설치**

    컨테이너 내부의 Xdebug가 활성화되어 있어, 터미널에서 Composer 명령어를 직접 실행하면 멈출 수 있습니다. `-e XDEBUG_MODE=off` 옵션을 사용하여 Xdebug를 일시적으로 비활성화해야 합니다.

    ```sh
    docker-compose exec -e XDEBUG_MODE=off apache sh -c "cd /var/www/html/Codeigniter && composer install"
    ```

3.  **웹사이트 접속**

    웹 브라우저에서 `http://localhost` 로 접속합니다.

## 주요 아키텍처 및 설정

### 환경 변수 (`.env`)

프로젝트의 모든 주요 설정은 `src/.env` 파일에서 관리합니다. 이 파일은 `vlucas/phpdotenv` 라이브러리를 통해 자동으로 로드됩니다.

-   `CI_ENV`: CodeIgniter의 실행 환경 (e.g., `development`, `production`)
-   `BASE_URL`: 애플리케이션의 기본 URL (e.g., `http://localhost/`)
-   `CI_DB_HOST`: 데이터베이스 호스트 주소 (e.g., `host.docker.internal`)
-   `CI_DB_NAME`: 데이터베이스 이름
-   `CI_DB_USER`: 데이터베이스 사용자 이름
-   `CI_DB_PASS`: 데이터베이스 비밀번호

### Composer 자동 로딩

CodeIgniter가 Composer로 설치된 라이브러리(`phpdotenv` 등)를 자동으로 인식하도록 `application/config/config.php` 파일에 다음과 같이 설정되어 있습니다.

```php
$config['composer_autoload'] = APPPATH . '../vendor/autoload.php';
```

### .env 파일 로딩 (Hooks)

CodeIgniter 시스템이 시작되기 전, 가장 먼저 `.env` 파일을 읽어 환경 변수를 설정하기 위해 `application/config/hooks.php` 파일에 `pre_system` 훅이 설정되어 있습니다. 이 방식을 통해 애플리케이션 전체에서 `getenv()` 함수로 환경 변수를 안전하게 사용할 수 있습니다.

### 웹 서버 설정

-   웹 서버(Apache)의 공개 루트(DocumentRoot)는 `/src/public` 디렉토리로 설정되어 있습니다.
-   `src/public/.htaccess` 파일이 URL에서 `index.php`를 숨기고, `assets` 같은 실제 파일/디렉토리로의 직접적인 접근을 허용하는 URL 재작성(Rewrite) 규칙을 처리합니다.

## 문제 해결

-   **CSS 또는 JavaScript 파일이 로드되지 않을 때:**
    1.  `assets` 폴더가 `src/public` 디렉토리 내부에 있는지 확인합니다.
    2.  `.env` 파일의 `BASE_URL`이 올바르게 설정되었는지 확인합니다. (e.g., `http://localhost/`)
    3.  **브라우저의 캐시를 비우고 강력 새로고침**을 실행합니다. (가장 흔한 원인)

-   **터미널에서 `composer` 또는 `php` 명령어가 멈출 때:**
    -   컨테이너의 Xdebug가 원인일 가능성이 높습니다. 명령어 앞에 `docker-compose exec -e XDEBUG_MODE=off` 를 붙여 실행하세요.

## 라우팅
- `application/config/routes.php` 참고 (GET/POST 배열)

## 세션
- DB에서 관리

### 로그인 시 세션 처리

로그인 성공 시, 보안을 강화하고 사용자 정보를 기록하기 위해 다음과 같은 세션 처리를 수행합니다.

```php
// 1. 세션 ID 갱신 (세션 고정 공격 방지)
$this->session->sess_regenerate(TRUE);

// 2. 세션에 사용자 정보 저장
$this->session->set_userdata([
    'login' => TRUE,
    'userId' => $user->user_id,
    'userIdx' => (int)$user->idx
]);
```

1.  **`$this->session->sess_regenerate(TRUE);`**
    -   **세션 고정(Session Fixation) 공격을 방지**하기 위한 가장 중요한 보안 조치입니다.
    -   사용자가 로그인을 성공하는 민감한 권한 상승 시점에, 기존의 세션 ID를 파기하고 완전히 새로운 ID를 발급합니다.
    -   만약 공격자가 로그인 전의 세션 ID를 탈취했더라도, 로그인 후에는 그 ID가 무효화되므로 공격자는 해당 세션을 사용할 수 없게 됩니다.
    -   `TRUE` 파라미터는 이전 세션과 관련된 데이터를 즉시 파기하도록 지시하여 보안을 더욱 강화합니다.

2.  **`$this->session->set_userdata([...]);`**
    -   로그인한 사용자의 정보를 서버 세션에 기록하여, 앞으로의 모든 요청에서 이 사용자가 누구인지, 그리고 로그인 상태인지를 식별하는 데 사용합니다.
    -   `login => TRUE`: 사용자가 현재 로그인 상태임을 나타내는 플래그입니다. 페이지 접근 제어 시 이 값의 존재 여부로 로그인 여부를 판단할 수 있습니다.
    -   `userId => $user->user_id`: 사용자의 고유 아이디를 저장합니다. 화면에 사용자 아이디를 표시하는 등, 식별 가능한 정보를 보여줄 때 사용됩니다.
    -   `userIdx => (int)$user->idx`: 데이터베이스의 사용자 테이블 기본 키(Primary Key) 값입니다. 다른 데이터를 조회하거나 수정/삭제할 때, `WHERE user_idx = ?` 와 같이 사용자를 고유하게 식별하는 가장 중요한 값입니다.

### 세션 데이터베이스 저장 시점 (INSERT/UPDATE)

CodeIgniter의 데이터베이스 세션 드라이버는 매 페이지 로드마다 DB에 접근하지 않고, 세션 데이터가 변경되었을 때만 효율적으로 작동합니다. 실제 DB 작업은 스크립트 실행이 거의 끝나는 시점에 한번에 처리됩니다.

-   **`INSERT` (최초 생성)**
    -   사이트에 처음 방문한 사용자의 세션 데이터가 처음으로 설정될 때 (예: `$this->session->set_userdata()` 최초 호출 시) `ci_sessions` 테이블에 새로운 레코드가 `INSERT` 됩니다.

-   **`UPDATE` (정보 변경)**
    -   이미 세션을 가진 사용자의 세션 데이터가 변경될 때 `UPDATE`가 발생합니다. 주요 발생 시점은 다음과 같습니다.
        1.  `$this->session->set_userdata()`를 통해 새로운 값을 저장하거나 기존 값을 덮어쓸 때
        2.  `$this->session->unset_userdata()`로 특정 데이터를 삭제할 때
        3.  `$this->session->sess_regenerate()`를 통해 세션 ID가 갱신될 때 (로그인 시)

따라서, 로그인 예제 코드에서 `sess_regenerate()`와 `set_userdata()`가 호출되면, CodeIgniter는 이 변경사항을 감지하고 스크립트 마지막에 `ci_sessions` 테이블의 해당 세션 정보를 `UPDATE`하여 새로운 세션 ID와 사용자 데이터를 기록합니다.

## 파일명 및 메소드 기법
- Class 파일 : 파스칼 기법
    - Controller `MemberController`
    - Model `MemberModel`
    - Method : 카멜 기법 (e.g., `getUserInfo()`)

## 인가 (Authorization)

페이지 접근 권한은 `application/core/P2P_Controller.php`에 구현된 `authorization()` 메소드를 통해 중앙에서 관리됩니다. 각 컨트롤러는 `P2P_Controller`를 상속받아 이 기능을 활용합니다.

- **로그인 필수 페이지**: 컨트롤러의 생성자(`__construct()`)에서 `parent::authorization(true);`를 호출합니다.
- **비로그인 필수 페이지**: `parent::authorization(false);`를 호출합니다.

이 메소드는 현재 사용자의 로그인 상태를 확인하고, 조건에 맞지 않으면 각각 로그인 페이지 또는 메인 페이지로 리디렉션합니다.

## 공통 모듈

### 페이지네이션 (Pagination)
- **라이브러리**: CodeIgniter의 내장 `Pagination` 라이브러리를 사용합니다.
- **설정**: `application/config/pagination.php` 파일에 전체적인 디자인과 기본 설정이 정의되어 있습니다.
- **사용법**: 컨트롤러에서 `$this->pagination->initialize($config);`로 개별 설정을 적용하고, `$this->pagination->create_links();`를 통해 뷰에 전달할 페이지 링크 HTML을 생성합니다.

### 파일 업로드 (File Upload)
- **라이브러리**: CodeIgniter의 내장 `Upload` 라이브러리를 사용합니다.
- **사용법**: 컨트롤러에서 업로드 경로, 허용 파일 타입 등의 설정을 `$this->upload->initialize($config);`로 초기화한 후, `$this->upload->do_upload('userfile');`을 실행하여 파일을 업로드합니다.
- **결과**: 업로드 성공 시 `$this->upload->data()`를 통해 파일 정보를 가져올 수 있고, 실패 시 `$this->upload->display_errors()`로 오류 메시지를 확인할 수 있습니다.

## AJAX 요청 시 CSRF 토큰 처리

CodeIgniter의 CSRF 보호 기능(`$config['csrf_protection'] = TRUE;`)을 활성화하면, 첫 번째 POST 요청 후 CSRF 토큰이 자동으로 갱신됩니다. 이로 인해 페이지에 남아있는 기존 CSRF 토큰은 더 이상 유효하지 않게 되어, 두 번째 AJAX 요청부터는 실패하게 됩니다.

이 프로젝트는 다음 3단계 연동을 통해 이 문제를 해결합니다.

### API 통신 시 CSRF 예외 처리

서버 간 API 통신이나 모바일 앱과의 통신 등, 세션 기반의 CSRF 토큰을 사용하기 어려운 경우에는 특정 URI를 CSRF 보호에서 제외할 수 있습니다. `application/config/config.php` 파일의 `csrf_exclude_uris` 설정에 해당 컨트롤러/메소드 경로를 추가하면 됩니다.


### 1. AJAX 요청 시 CSRF 토큰 전송 (View)

AJAX 요청을 보내는 스크립트(예: `register.php`의 `idCheck()` 함수)는, 요청 데이터(`postData`)에 현재 페이지의 CSRF 토큰 이름과 해시 값을 직접 포함시켜야 합니다.

**`application/views/member/register.php` 예시:**
```javascript
function idCheck() {
    // ... (기타 로직)

    // 보낼 데이터 객체 생성
    const postData = {
        'userId': userId
    };

    // **핵심: 현재 폼의 CSRF 토큰 이름과 값을 postData에 추가합니다.**
    // (csrfTokenName은 footer.php 등에서 전역 변수로 미리 정의되어 있어야 합니다.)
    postData[csrfTokenName] = $('input[name="' + csrfTokenName + '"]').val();

    $.ajax({
        url: "<?php echo site_url('member/ajax_id_check'); ?>",
        type: 'POST',
        data: postData,
        dataType: 'json',
        // ... (success, error 콜백 함수)
    });
}
```

### 2. 새로운 CSRF 토큰 반환 (Controller)

AJAX 요청을 처리하는 컨트롤러 메소드(예: `Member::ajax_id_check`)는, 로직 처리 후 JSON으로 응답할 때 **항상 새로 갱신된 CSRF 토큰 해시를 포함**시켜야 합니다.

**`application/controllers/member/Member.php` 예시:**
```php
public function ajax_id_check()
{
    // ... (아이디 중복 체크 로직)

    // 기존 응답 데이터에 새로운 CSRF 해시 값을 추가합니다.
    // (CSRF 토큰 이름은 클라이언트가 이미 알고 있으므로, 해시 값만 보내도 됩니다.)
    $response['new_csrf_hash'] = $this->security->get_csrf_hash();

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}
```

### 3. CSRF 토큰 자동 갱신 (`common.js`)

`public/assets/js/common.js` 파일은 jQuery의 `ajaxSuccess` 글로벌 이벤트 핸들러를 사용하여, 모든 성공적인 AJAX 요청에 대해 응답을 감지하고 페이지의 CSRF 토큰을 자동으로 갱신합니다.

**`public/assets/js/common.js` 구현:**
```javascript
// 모든 AJAX 요청이 성공했을 때, 서버가 새로운 CSRF 토큰을 보내줬다면 자동으로 폼을 업데이트합니다.
$(document).ajaxSuccess(function(event, xhr, settings, data) {
    // 응답 데이터(data)가 객체이고 new_csrf_hash 속성을 가지고 있는지 확인합니다.
    if (data && typeof data === 'object' && data.hasOwnProperty('new_csrf_hash')) {
        // csrfTokenName은 footer.php 같은 템플릿에서 전역 변수로 정의되어 있어야 합니다.
        if (typeof csrfTokenName !== 'undefined') {
            // 페이지에 있는 모든 CSRF 토큰 input의 값을 새로운 해시로 업데이트합니다.
            $('input[name="' + csrfTokenName + '"]').val(data.new_csrf_hash);
        }
    }
});
```

위와 같은 3단계 연동을 통해, 어떤 AJAX 요청이든 성공적으로 완료되면 페이지의 CSRF 토큰이 자동으로 갱신되어 연속적인 비동기 통신이 가능해집니다.
