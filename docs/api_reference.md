# API 레퍼런스

FFP 프레임워크의 주요 객체별 프로퍼티 및 메서드 요약 가이드입니다.

---

## 1. FFP\App
애플리케이션의 핵심 커널 객체입니다.

### 프로퍼티 (Read-only)
- `isCli`: 현재 실행 환경이 CLI인지 여부 (bool)
- `isWorker`: FrankenPHP 워커 모드 동작 여부 (bool)
- `profile`: 현재 활성화된 데이터베이스 프로필 명 (string)
- `charset`: 애플리케이션 기본 문자셋 (string)
- `xss`: 전역 XSS 필터링 활성화 여부 (bool)
- `env`: `config/env.php`에 정의된 설정 배열 (array)

### 메서드
- `getDBDriver(string $key = 'default')`: 등록된 데이터베이스 드라이버 객체를 반환합니다.

---

## 2. Request (FFP\Interfaces\Route\Request)
현재 요청 정보를 담고 있는 객체입니다.

### 프로퍼티 (Read-only)
- `method`: HTTP 메서드 (`GET`, `POST` 등 / `FFP\Enums\Route\Method`)
- `scheme`: 프로토콜 스킴 (`http`, `https`)
- `host`: 호스트 주소
- `path`: 요청 경로 (정규화된 형태)
- `query`: 쿼리 스트링
- `paths`: 경로를 `/`로 분리한 배열
- `referer`: 이전 페이지 URL
- `clientIp`: 클라이언트 IP 주소

---

## 3. Response (FFP\Interfaces\Route\Response)
응답을 생성하고 제어하는 객체입니다.

### 메서드
- `setHeader(string $header, bool $replace)`: HTTP 헤더 설정
- `view(string $path, array $data, bool $return)`: 뷰 템플릿 렌더링 및 출력
- `json(array $data)`: 데이터를 JSON 형식으로 출력 (Content-Type 자동 설정)
- `text(string $msg)`: 일반 텍스트 출력
- `redirect(string $path, Status $status)`: 지정된 경로로 리다이렉트
- `goBack(?string $msg, Status $status)`: 브라우저 이전 페이지로 이동 (선택적 알림창)
- `file(string $path, bool $attach, ?string $fileName)`: 파일 다운로드 또는 출력
- `error(Error $error)`: HTTP 에러 응답 처리 (404, 500 등)

---

## 4. Router 및 인터셉터 관련 객체

### FFP\Route\Router
개별 라우트 설정을 담당하는 객체입니다.

- `interceptor(Handle $handle, \Closure|array|string $callback)`: 특정 라우트에 로컬 인터셉터를 추가합니다.

### FFP\Route\Http / FFP\Route\Cli
라우팅을 관리하는 정적 관리자 객체입니다.

- `append(Method $method, string $path, $callback)` (Http): HTTP 라우트를 등록합니다.
- `append(string $path, $callback)` (Cli): CLI 라우트를 등록합니다.

### FFP\Interceptor\Http / FFP\Interceptor\Cli
전역 인터셉터를 관리하는 객체입니다.

- `append(Handle $handle, \Closure|array|string $callback)`: 전역 인터셉터를 등록합니다.

---

## 5. FFP\Core\Controller
모든 컨트롤러의 부모 클래스입니다.

### 프로퍼티 (Read-only)
- `context`: `FFP\App` 인스턴스
- `request`: 현재 요청 객체 (`Request` 인터페이스)
- `response`: 현재 응답 객체 (`Response` 인터페이스)

### 메서드
- `getParam(string $key, ?Type $type, mixed $default, ?bool $xss)`: 요청 파라미터를 안전하게 추출 및 변환합니다.
- `getFile(string $key)`: 업로드된 파일 정보(`$_FILES`)를 반환합니다.
- `getModel(string $modelClass, string $driver = 'default')`: 드라이버가 주입된 모델 인스턴스를 반환합니다.
- `xssEscape(string $arg)`: 문자열을 HTML 엔티티로 변환합니다.
- `xssUnescape(string $arg)`: HTML 엔티티를 문자열로 복원합니다.

---

## 6. FFP\Core\Model
모든 모델의 부모 클래스입니다. 데이터베이스 드라이버의 메서드들을 직접 제공합니다.

### 주요 메서드
- `query(string $sql, ?array $param)`: SQL 쿼리 정의
- `where(Operator $operator, string $sql, ?array $param)`: WHERE 조건 추가
- `set(string $sql, ?array $param)`: UPDATE 문을 위한 SET 절 추가
- `select()`, `insert()`, `update()`, `delete()`: 쿼리 실행
- `fetch()`, `fetchAll()`: 결과 데이터 행 반환
- `rowCount()`: 영향을 받은 행의 수 반환
- `lastInsertId()`: 마지막으로 삽입된 ID 반환
- `beginTransaction()`, `commit()`, `rollback()`: 트랜잭션 제어

---

## 7. Utils\Pagination
데이터 페이징 처리를 위한 유틸리티 클래스입니다.

### 프로퍼티 (Read-only)
- `offset`: SQL 쿼리에서 사용할 오프셋 값 (int)
- `rows`: 페이지당 표시할 행 수 (int)
- `pages`: 하단에 표시할 페이지 번호 개수 (int)
- `page`: 현재 페이지 번호 (int)
- `totRows`: 전체 데이터 행 수 (int)
- `startPage`: 현재 페이징 바의 시작 페이지 번호 (int)
- `endPage`: 현재 페이징 바의 마지막 페이지 번호 (int)

### 메서드
- `__construct(array $data)`: `['rows' => int, 'pages' => int, 'page' => int]` 배열로 초기화
- `setTotRows(int $totRows)`: 전체 행 수 설정 및 페이징 수치 계산
- `toArray()`: 페이징 정보를 배열로 반환 (total, offset, page, startPage, endPage, pageList, prevPage, nextPage 포함)
