# Database CRUD 가이드

FFP 프레임워크의 데이터베이스 드라이버를 이용한 CRUD 작업 방법입니다.

```php
// INSERT
$this->query("INSERT INTO table (col) VALUES (?)", ['val']);
$this->insert();

// UPDATE
$this->query("UPDATE table");
$this->set("col = ?", ['new']);
$this->where(Operator::AND, "id = ?", [1]);
$this->update();

// DELETE
$this->query("DELETE FROM table");
$this->where(Operator::AND, "id = ?", [1]);
$this->delete();
```

---

- **[Database 드라이버 지원 현황](./database_drivers.md)**

