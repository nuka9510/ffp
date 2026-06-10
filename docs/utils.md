# 유틸리티 (Utils)

FFP 프레임워크에서 제공하는 공통 유틸리티 클래스들에 대한 가이드입니다.

---

## 1. Utils\Pagination
데이터 페이징 처리를 위한 클래스입니다. 상세한 프로퍼티 및 메서드 명세는 [API 레퍼런스](api_reference.md#7-utilspagination)를 참조하세요.

### 사용 예시

```php
// 컨트롤러에서 사용
$page = $this->getParam('page', Type::INT, 1);
$pagination = new \Utils\Pagination([
    'rows' => 10,  // 페이지당 10개
    'pages' => 5,   // 하단 페이징 바에 5개씩 노출
    'page' => $page
]);

$model = $this->getModel(\Models\Index::class);
// 전체 개수 조회 및 설정
$pagination->setTotRows($model->getTotalCount());

// 페이징된 데이터 조회
$list = $model->getList($pagination->offset, $pagination->rows);

$this->response->view('index', [
    'list' => $list,
    'pagination' => $pagination->toArray()
]);
```
