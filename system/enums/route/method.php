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
  }
?>