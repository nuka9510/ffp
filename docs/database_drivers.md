# Database 드라이버 지원 현황

FFP 프레임워크는 PDO(PHP Data Objects)를 기반으로 다양한 데이터베이스 드라이버를 지원합니다. 모든 드라이버는 공통 인터페이스(`\FFP\Interfaces\Database\Driver`)를 준수하며, 각 DBMS의 고유한 문법(특히 트랜잭션 및 잠금 메커니즘)을 처리하도록 설계되었습니다.

## 공통 지원 기능

- **PDO 기반 연결**: DSN, 사용자명, 비밀번호, 옵션을 통한 안전한 연결.
- **트랜잭션 관리**: `beginTransaction`, `commit`, `rollback` 지원.
- **쿼리 빌더**: `query`, `where`, `set` 메서드를 통한 단계별 쿼리 생성.
- **파라미터 바인딩**: 데이터 타입에 따른 자동 `PDO::PARAM_*` 바인딩.
- **오류 처리**: DBMS별 에러 코드 및 마지막 실행 쿼리 정보 제공.

## 필수 PHP 확장

데이터베이스 연결을 위해 **`pdo`** 확장이 공통적으로 필요하며, 사용하는 DBMS에 따라 아래 확장을 추가로 설치해야 합니다.

- **MySQL / MariaDB**: `pdo_mysql`
- **PostgreSQL**: `pdo_pgsql`
- **Microsoft SQL Server**: `pdo_sqlsrv` (Windows) 또는 `pdo_dblib` (Linux/macOS)
- **Oracle**: `pdo_oci`
- **SQLite**: `pdo_sqlite`

## 드라이버별 상세 지원 현황

### 1. MySQL / MariaDB (`MySqlDriver`)
- **DBMS 감지**: 서버 버전을 확인하여 MySQL과 MariaDB를 구분합니다.
- **행 수준 잠금**: 
  - 읽기 트랜잭션: `FOR SHARE` (MySQL 8.0+, MariaDB 10.6+) 또는 `LOCK IN SHARE MODE`.
  - 쓰기 트랜잭션: `FOR UPDATE`.
- **고급 옵션**: `NOWAIT`, `SKIP LOCKED` 지원.

### 2. PostgreSQL (`PostgreDriver`)
- **행 수준 잠금**: 
  - 읽기 트랜잭션: `FOR SHARE`.
  - 쓰기 트랜잭션: `FOR UPDATE`.
- **고급 옵션**: `NOWAIT`, `SKIP LOCKED` 지원.

### 3. Microsoft SQL Server (`MSSqlDriver`)
- **테이블 힌트**: `FROM` 절에 `WITH` 구문을 사용하여 잠금을 제어합니다.
- **잠금 제어**: 
  - 기본: `ROWLOCK` 적용.
  - 쓰기 트랜잭션: `UPDLOCK` 추가 적용.
- **고급 옵션**: `NOWAIT`, `READPAST` (Skip Locked 대응) 지원.

### 4. Oracle (`OracleDriver`)
- **행 수준 잠금**: 
  - 쓰기 트랜잭션: `FOR UPDATE`.
- **고급 옵션**: `NOWAIT`, `SKIP LOCKED` 지원.
- **연결 확인**: `SELECT 1 FROM DUAL`을 통한 실시간 연결 상태 확인.

### 5. SQLite (`SQLiteDriver`)
- **동시성 제어**: SQLite의 특성에 맞춰 트랜잭션 모드를 조정합니다.
  - 쓰기 트랜잭션: `BEGIN IMMEDIATE TRANSACTION`을 사용하여 데이터베이스 수준의 락 충돌을 방지합니다.
- **제한 사항**: 행 수준 잠금(Row-level locking)을 지원하지 않으므로 관련 옵션은 무시됩니다.

## 드라이버 추가 및 확장
새로운 DBMS를 지원하려면 `\FFP\Implements\Database\Driver`를 상속받아 고유 문법이 필요한 메서드(예: `select`)를 오버라이드하여 구현할 수 있습니다.
