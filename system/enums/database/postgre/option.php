<?php
  namespace FFP\Enums\Database\Postgre;

  enum Option: string implements \FFP\Interfaces\Database\SelectOption {
    case NOWAIT = 'NOWAIT';
    case SKIP_LOCKED = 'SKIP LOCKED';
  }
?>