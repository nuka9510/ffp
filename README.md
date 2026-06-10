# FFP (FrankenPHP Framework Project)

FFP는 **FrankenPHP** 커스텀 PHP 프레임워크입니다.

## 주요 특징
- **FrankenPHP 워커 모드(Worker Mode) 최적화**: 상주형 프로세스 실행 지원.
- **커스텀 MVC 아키텍처**: 모델-뷰-컨트롤러 구조.
- **라우팅 및 인터셉터 지원**: 라우팅 시스템과 미들웨어(인터셉터) 기능 제공.
- **통합 핸들링**: HTTP 요청과 CLI 명령을 동일한 진입점에서 처리.

---

## 문서 가이드
FFP 프레임워크 사용을 위한 상세 문서를 제공합니다.

- **[종합 개발 가이드](docs/development.md)**: 프레임워크 아키텍처 및 핵심 개발 방법 (MVC, 라우터, 인터셉터 등)
- **[환경 설정 가이드](docs/environment.md)**: 환경 변수, 애플리케이션 및 데이터베이스 프로필 설정
- **[Database CRUD 가이드](docs/database_crud.md)**: 데이터베이스 드라이버를 활용한 쿼리 작성법
- **[API 레퍼런스](docs/api_reference.md)**: 주요 클래스 및 인터페이스별 메서드/프로퍼티 요약
- **[유틸리티 가이드](docs/utils.md)**: 페이징 등 공통 유틸리티 클래스 사용법

---

## 설치 방법

### FrankenPHP 설치
FFP를 실행하기 위해서는 **FrankenPHP** 바이너리가 필요합니다.

#### Linux 및 macOS
```bash
curl https://frankenphp.dev/install.sh | sh
```

#### Windows (PowerShell)
```powershell
irm https://frankenphp.dev/install.ps1 | iex
```

### PHP-ZTS 및 의존성 설치
워커 모드 및 최적의 성능을 위해 **PHP-ZTS** 환경이 권장됩니다. 로컬 환경에서 Docker 없이 실행할 경우, 해당 환경에서 `composer`를 통해 의존성을 설치해야 합니다.

```bash
php-zts composer.phar install
```

---

## 실행 방법

### HTTP 서버 실행
FrankenPHP를 사용하여 서버를 구동합니다.

```bash
# 개발 환경
./run-server.sh --env=.env.dev

# 운영 환경 (Docker)
docker build -t ffp-app .
docker run -p 8081:8081 ffp-app
```

### CLI 명령 실행
`system/index.php`를 통해 CLI 라우트에 정의된 명령을 실행합니다.

```bash
# 실행 형식: frankenphp php-cli system/index.php [PATH] --env=[ENV_FILE]
# 예시: 개발 환경에서 /cli-test 경로 실행
frankenphp php-cli system/index.php /cli-test --env=.env.dev
```

---

## 라이선스
이 프로젝트는 MIT 라이선스를 따릅니다. 자세한 내용은 [LICENSE](LICENSE) 파일을 참조하세요.
