<?php
  namespace FPW\DTO;

  /**
   * @property-read null|string $path
   * @property-read null|string $name
   * @property-read null|string $regex
   * @property-read null|'string'|'bool'|'boolean'|'int'|'integer'|'float'|'double' $type
   */
  class Route {
    private ?string $_path;

    private ?string $_name;

    private ?string $_regex;

    /**
     * @var null|'string'|'bool'|'boolean'|'int'|'integer'|'float'|'double'
     */
    private ?string $_type;

    public function __get(string $name) {
      switch ($name) {
        case 'path': return $this->_path;
        case 'name': return $this->_name;
        case 'regex': return $this->_regex;
        case 'type': return $this->_type;
        default: return;
      }
    }

    public function __construct(string $path) {
      $regex = null;
      $name = null;
      $type = null;

      if (preg_match('/^{(<(?P<regex>.+)>)?(?P<name>\w+)(:(?P<type>string|bool(ean)?|int(eger)?|float|double))?}$/', $path, $matches)) {
        $regex = $matches['regex'] ?? '';
        $name = $matches['name'];
        $type = $matches['type'] ?? '';

        if ($regex === '') { $regex = null; }
        if ($type === '') { $type = null; }
      } else { $this->_path = $path; }

      $this->_regex = $regex;
      $this->_name = $name;
      $this->_type = $type;
    }

    public function match(string $path): bool {
      if (isset($this->_path)) {
        return $this->_path === $path;
      } else if (isset($this->_name)) {
        $match = false;

        if (isset($this->_regex)) {
          $match = boolval(preg_match('/^'.$this->_regex.'$/', $path));
        } else { $match = true; }

        if (isset($this->_type)) { $match = settype($path, $this->_type); }

        return $match;
      } else { return false; }
    }

    public function isArg(): bool { return isset($this->_name); }
  }
?>