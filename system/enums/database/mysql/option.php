<?php
  namespace FPW\Enums\Database\Mysql;

  enum Option: string implements \FPW\Interfaces\Database\SelectOption {
    case NOWAIT = 'NOWAIT';
    case SKIP_LOCKED = 'SKIP LOCKED';
  }
?>