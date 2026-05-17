<?php
  namespace FPW\Enums\Route;

  enum Method: string {
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';

    public function getParams(): array {
      $params = match ($this) {
        Method::GET => $_GET,
        Method::POST => $_POST,
        default => array(),
      };

      match ($this) {
        Method::HEAD,
        Method::OPTIONS,
        Method::PUT,
        Method::PATCH,
        Method::DELETE => parse_str(file_get_contents('php://input'), $params),
        default => null,
      };

      $this->____setNull($params);

      return $params;
    }

    private function ____setNull(array &$params): void {
      foreach ($params as &$p) {
        if (is_array($p)) {
          $this->____setNull($p);
        } else { $p = ($p === '') ? null : $p; }
      }
    }
  }
?>