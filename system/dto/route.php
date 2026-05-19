<?php
  namespace FFP\DTO;

  /**
   * @property-read null|string $path
   * @property-read null|string $name
   * @property-read null|string $regex
   * @property-read null|\FFP\Enums\Val\Type $type
   */
  class Route {
    private ?string $_path;

    private ?string $_name;

    private ?string $_regex;

    private ?\FFP\Enums\Val\Type $_type;

    public function __get(string $name) {
      return match ($name) {
        'path' => $this->_path,
        'name' => $this->_name,
        'regex' => $this->_regex,
        'type' => $this->_type,
        default => null,
      };
    }

    public function __construct(string $path) {
      $regex = null;
      $name = null;
      $type = null;

      if (preg_match('/^{(<(?P<regex>.+)>)?(?P<name>\w+)(:(?P<type>string|int(eger)?|float|double))?}$/', $path, $matches)) {
        $regex = $matches['regex'] ?? '';
        $name = $matches['name'];
        $type = \FFP\Enums\Val\Type::tryFrom($matches['type'] ?? '');

        if ($regex === '') { $regex = null; }
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

        if (isset($this->_type)) { $match = $this->_type->chkType($path); }

        return $match;
      } else { return false; }
    }

    public function isArg(): bool { return isset($this->_name); }
  }
?>