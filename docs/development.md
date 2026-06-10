# FFP 종합 개발 가이드

이 문서는 FFP (FrankenPHP Framework Project)의 상세 개발 방법까지의 모든 내용을 담고 있습니다. 환경 설정은 별도 문서를 참조하세요.

---

## 1. Router 및 Interceptor

### 1.1 라우팅 및 로컬 인터셉터

#### HTTP 라우팅 및 로컬 인터셉터 (`routes/http.php`)
```php
use FFP\Route\Http;
use FFP\Enums\Route\Method;
use \FFP\Enums\Interceptor\Handle;

// 기본 라우팅
Http::append(Method::GET, '/test/{page:int}', [\Controllers\Index::class, 'getTest']);

// 라우트와 함께 로컬 인터셉터 정의
Http::append(Method::GET, '/test/{page:int}', [\Controllers\Index::class, 'getTest'])
    ->interceptor(Handle::PRE, function ($context, $request, $response) {
        \FFP\Logger::debug('local http interceptor');
    });
```

#### CLI 라우팅 및 로컬 인터셉터 (`routes/cli.php`)
```php
use FFP\Route\Cli;
use \FFP\Enums\Interceptor\Handle;

// 기본 라우팅
Cli::append('/cli-test', function ($context, $request, $response) { \FFP\Logger::debug('CLI Command Executed'); })

// 라우트와 함께 로컬 인터셉터 정의
Cli::append('/cli-test', function ($context, $request, $response) { \FFP\Logger::debug('CLI Command Executed'); })
    ->interceptor(Handle::PRE, function ($context, $request, $response) {
        \FFP\Logger::debug('local cli interceptor');
    });
```

### 1.2 전역 인터셉터 (Middleware)

#### HTTP 전역 인터셉터 (`interceptors/http.php`)
```php
use \FFP\Interceptor\Http;
use \FFP\Enums\Interceptor\Handle;

Http::append(Handle::PRE, function ($context, $request, $response) {
    \FFP\Logger::debug('global http interceptor');
});
```

#### CLI 전역 인터셉터 (`interceptors/cli.php`)
```php
use \FFP\Interceptor\Cli;
use \FFP\Enums\Interceptor\Handle;

Cli::append(Handle::PRE, function ($context, $request, $response) {
    \FFP\Logger::debug('global cli interceptor');
});
```

---

## 2. MVC 패턴

### 2.1 Controller
`controllers/` 디렉토리에 작성하며, `\FFP\Core\Controller`를 상속받습니다.

```php
namespace Controllers;
use FFP\Enums\Val\Type;

class Index extends \FFP\Core\Controller {
    public function getTest(int $page = 1): void {
        $search = $this->getParam('search', Type::STRING, '');
        $model = $this->getModel(\Models\Index::class);
        $data = $model->getList($page, $search);
        
        $this->response->view('index', ['data' => $data, 'page' => $page]);
    }
}
```

### 2.2 Model
`models/` 디렉토리에 작성하며, `\FFP\Core\Model`을 상속받습니다. 부모 클래스의 DB 메서드를 직접 호출합니다.

```php
namespace Models;
use FFP\Enums\Database\Operator;

class Index extends \FFP\Core\Model {
    public function getList(int $page, string $search = '') {
        $this->query("SELECT * FROM users");
        $this->where(Operator::AND, "status = ?", [1]);
        if ($search !== '') $this->where(Operator::AND, "name LIKE ?", ["%{$search}%"]);
        $this->select();
        return $this->fetchAll();
    }
}
```

### 2.3 View
`views/` 디렉토리에 `.php` 파일을 생성합니다.

```html
<!-- views/index.php -->
<h1>사용자 목록 (페이지: <?= $page ?>)</h1>
<ul>
    <?php foreach ($data as $user): ?>
        <li><?= $user['name'] ?></li>
    <?php endforeach; ?>
</ul>
```

---

## 3. 상세 가이드 모음
- **[환경 설정 가이드](environment.md)**
- **[Database CRUD 가이드](database_crud.md)**
- **[API 레퍼런스](api_reference.md)**
- **[유틸리티 가이드](utils.md)**
