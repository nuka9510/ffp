# FFP (FrankenPHP Framework Project)

[![LICENSE][license]][license-url]
[![GITHUB-VERSION][github-version]][github-version-url]
[![PACKAGIST-VERSION][packagist-version]][packagist-version-url]
![GITHUB-LAST-COMMIT][github-last-commit]
![GITHUB-REPO-SIZE][github-repo-size]
![PACKAGIST-DOWNLOADS][packagist-downloads]
![TOP-LANGUAGE][top-language]

[license]: https://img.shields.io/badge/license-MIT-green
[license-url]: LICENSE

[github-version]: https://img.shields.io/github/v/tag/nuka9510/ffp?logo=github
[github-version-url]: https://github.com/nuka9510/ffp

[packagist-version]: https://img.shields.io/packagist/v/nuka9510/ffp?logo=packagist
[packagist-version-url]: https://packagist.org/packages/nuka9510/ffp

[github-last-commit]: https://img.shields.io/github/last-commit/nuka9510/ffp?logo=github

[github-repo-size]: https://img.shields.io/github/repo-size/nuka9510/ffp?logo=github

[packagist-downloads]: https://img.shields.io/packagist/dt/nuka9510/ffp?logo=packagist

[top-language]: https://img.shields.io/github/languages/top/nuka9510/ffp

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
- **[Database 드라이버 지원 현황](docs/database_drivers.md)**: 사용가능한 데이터베이스 드라이버 목록
- **[Database CRUD 가이드](docs/database_crud.md)**: 데이터베이스 드라이버를 활용한 쿼리 작성법
- **[API 레퍼런스](docs/api_reference.md)**: 주요 클래스 및 인터페이스별 메서드/프로퍼티 요약
- **[유틸리티 가이드](docs/utils.md)**: 페이징 등 공통 유틸리티 클래스 사용법

---

## 설치 방법

### FrankenPHP 설치
FFP를 실행하기 위해서는 **FrankenPHP**가 설치되어 있어야 합니다. 설치와 관련된 자세한 내용은 공식 문서를 참고하세요.

- **[FrankenPHP 공식 설치 가이드](https://frankenphp.dev/docs/#getting-started)**

### 프로젝트 생성
Composer를 사용하여 새 프로젝트를 생성할 수 있습니다.

```bash
composer create-project nuka9510/ffp <project-name>
```

---

## 실행 방법

### HTTP 서버 실행
FrankenPHP를 사용하여 서버를 구동합니다.

#### Linux / macOS
```bash
# 개발 환경
./run-server.sh --env=.env.dev
```

#### Windows
```batch
# 개발 환경
run-server.bat --env=.env.dev
```

#### Docker (운영 환경)
```bash
docker build -t ffp-app .
docker run -p 8081:8081 ffp-app
```

### CLI 명령 실행
생성된 실행 스크립트를 통해 CLI 라우트에 정의된 명령을 실행합니다.

#### Linux / macOS
```bash
# 실행 형식: ./run-cli.sh [PATH] --env=[ENV_FILE]
# 예시: 개발 환경에서 / 경로 실행
./run-cli.sh / --env=.env.dev
```

#### Windows
```batch
# 실행 형식: run-cli.bat [PATH] --env=[ENV_FILE]
# 예시: 개발 환경에서 / 경로 실행
run-cli.bat / --env=.env.dev
```