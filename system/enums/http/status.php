<?php
  namespace FPW\Enums\Http;

  enum Status: int {
    case OK = 200;
    case FOUND = 302;
    case SEE_OTHER = 303;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
  }
?>