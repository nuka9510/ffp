<?php
  namespace FFP\Enums\Val;

  enum Type: string {
    case STRING = 'string';
    case INT = 'int';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case DOUBLE = 'double';

    public function chkType(string $var): bool {
      return match ($this) {
        Type::STRING => true,
        Type::INT,
        Type::INTEGER => is_numeric($var) &&
                        $var == intval($var),
        Type::FLOAT,
        Type::DOUBLE => is_numeric($var) &&
                        $var == floatval($var),
      };
    }

    public function setType(string $var): mixed {
      if ($this->chkType($var)) {
        return match ($this) {
          Type::STRING => $var,
          Type::INT,
          Type::INTEGER => intval($var),
          Type::FLOAT,
          Type::DOUBLE => floatval($var),
        };
      } else { return null; }
    }
  }
?>