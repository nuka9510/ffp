<?php
  namespace FFP\Enums\Database\Mssql;

  enum Option: string implements \FFP\Interfaces\Database\SelectOption {
    case NOWAIT = 'NOWAIT';
    case READPAST = 'READPAST';
  }
?>