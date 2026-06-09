# 환경 설정 가이드

FFP 프레임워크의 환경 변수, 애플리케이션 설정 및 데이터베이스 프로필 설정 방법입니다.

## 1. 환경 변수 (`.env`)
프로젝트 루트의 `.env` 파일을 통해 서버의 동작 방식을 제어합니다.

| 변수명 | 설명 | 예시 |
| :--- | :--- | :--- |
| `APP_PROFILE` | 실행할 데이터베이스 프로필 지정 | `dev`, `prod` |
| `APP_TIMEZONE` | 서버 타임존 설정 | `Asia/Seoul`, `UTC` |
| `APP_WORKER_NUM` | FrankenPHP 워커 프로세스 수 | `4` |
| `APP_WATCH` | 소스 변경 감지 활성화 (개발 시) | `watch` |
| `APP_DEBUG` | 디버그 로그 활성화 | `debug` |
| `APP_LOG_LEVEL` | 로그 레벨 설정 | `DEBUG`, `INFO`, `ERROR` |

## 2. 애플리케이션 설정 (`config/env.php`)
세션, 공통 헤더 등 PHP 레벨의 설정을 관리합니다.

```php
$env = array(
    'headers' => array(
        array('Cache-Control: no-cache, no-store, must-revalidate;')
    ),
    'session' => array(
        'name' => 'PHPSESSID',
        'save_handler' => 'file',
        'save_path' => "{$_SERVER['DOCUMENT_ROOT']}/sessions",
        // ... 기타 세션 옵션
    )
);
```

## 3. 데이터베이스 프로필 설정 (`databases/`)
`APP_PROFILE` 값에 따라 로드되는 설정 파일이 결정됩니다.

```php
\FFP\Database\Driver::set(
    'default',
    array(
        'dsn' => 'mysql:host=localhost;dbname=test;charset=utf8mb4',
        'username' => 'db_user',
        'password' => 'db_password',
        'options' => [\PDO::ATTR_EMULATE_PREPARES => false]
    )
);
```
