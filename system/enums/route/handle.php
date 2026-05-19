<?php
  namespace FFP\Enums\Route;

  enum Handle {
    case METHOD;
    case INSTANCE_METHOD;
    case STATIC_METHOD;
    case FUNCTION;

    public function getClass(): string {
      return match ($this) {
        Handle::METHOD,
        Handle::INSTANCE_METHOD,
        Handle::STATIC_METHOD => \ReflectionMethod::class,
        Handle::FUNCTION => \ReflectionFunction::class,
      };
    }
  }
?>