<?php
  namespace Utils;

  /**
   * @property-read int $offset
   * @property-read int $rows
   * @property-read int $pages
   * @property-read int $page
   * @property-read int $totRows
   * @property-read int $startPage
   * @property-read int $endPage
   */
  class Pagination {
    private int $_offset;

    private int $_rows;

    private int $_pages;

    private ?int $_halfPage;

    private ?int $_halfMod;

    private int $_page;

    private ?int $_totRows;

    private ?int $_calcRows;

    private ?int $_calcPages;

    private ?int $_startPage;

    private ?int $_endPage;

    public function __get(string $name) {
      return match ($name) {
        'offset' => $this->_offset,
        'rows' => $this->_rows,
        'pages' => $this->_pages,
        'page' => $this->_page,
        'totRows' => $this->_totRows,
        'stargPage' => $this->_stargPage,
        'endPage' => $this->_endPage,
        default => null,
      };
    }

    /**
     * @param  array{
     *   rows: int,
     *   pages: int,
     *   page: int
     * } $data
     */
    public function __construct(array $data) { $this->____init($data); }

    public function setTotRows(int $totRows): void {
      $this->_totRows = $totRows;
      $this->_calcRows = $totRows + (($this->_rows - ($totRows % $this->_rows)) % $this->_rows);
      $this->_calcPages = $this->_calcRows / $this->_rows;
      $this->_halfPage = (int) ($this->_pages / 2);
      $this->_halfMod = $this->_pages % 2;

      $this->____setStartPage();
      $this->____setEndPage();
      $this->____calcRange();
    }

    /**
     * @return array{
     *   total: int,
     *   offset: int,
     *   page: int,
     *   startPage: int,
     *   endPage: int,
     *   pageList: int[],
     *   prevPage?: int,
     *   nextPage?: int,
     * }
     */
    public function toArray(): array {
      $pagination = array();

      if (!isset($this->_totRows)) { return $pagination; }

      $pagination['total'] = $this->_totRows;
      $pagination['offset'] = $this->_offset;
      $pagination['page'] = $this->_page;
      $pagination['startPage'] = 1;
      $pagination['endPage'] = $this->_calcPages;
      $pagination['pageList'] = array();

      for ($i = 0; $i < ($this->_endPage - $this->_startPage) + 1; $i++) { array_push($pagination['pageList'], $this->_startPage + $i); }

      if ($this->_startPage > 1) { $pagination['prevPage'] = $this->_startPage - 1; }
      if ($this->_endPage < $this->_calcPages) { $pagination['nextPage'] = $this->_endPage + 1; }

      return $pagination;
    }

    /**
     * @param  array{
     *   rows: int,
     *   pages: int,
     *   page: int
     * } $data
     */
    private function ____init(array $data): void {
      $this->_rows = $data['rows'];
      $this->_pages = $data['pages'];
      $this->_page = $data['page'];
      $this->_offset = ($data['page'] - 1) * $data['rows'];
    }

    private function ____setStartPage(): void {
      if ((1 + $this->_halfPage) <= $this->_page) {
        $this->_startPage = $this->_page - ($this->_halfPage - (($this->_halfMod + 1) % 2));
      } else { $this->_startPage = 1; }
    }

    private function ____setEndPage(): void {
      if (($this->_calcPages - $this->_halfPage) >= $this->_page) {
        $this->_endPage = $this->_page + ($this->_halfPage - (($this->_halfMod + 1) % 2));
      } else { $this->_endPage = $this->_calcPages; }
    }

    private function ____calcRange(): void {
      $range = ($this->_endPage - $this->_startPage) + 1;
      $buffer = $this->_pages - $range;

      if ($range === $this->_pages) { return; }

      if ($this->_startPage === 1) {
        $this->_endPage += $buffer;
      } else { $this->_startPage -= $buffer; }

      if ($this->_startPage < 1) { $this->_startPage = 1; }
      if ($this->_endPage > $this->_calcPages) { $this->_endPage = $this->_calcPages; }
    }
  }
?>