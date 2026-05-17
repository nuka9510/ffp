<?php
  namespace FPW\Enums\Route;

  enum Callback {
    case METHOD;
    case INSTANCE_METHOD;
    case STATIC_METHOD;
    case FUNCTION;

    public function getClass(): string {
      return match ($this) {
        Callback::METHOD,
        Callback::INSTANCE_METHOD,
        Callback::STATIC_METHOD => \ReflectionMethod::class,
        Callback::FUNCTION => \ReflectionFunction::class,
      };
    }
  }
?>