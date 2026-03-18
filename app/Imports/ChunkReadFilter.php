<?php
// app/Imports/ChunkReadFilter.php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    private int $startRow;
    private int $endRow;

    public function setRows(int $startRow, int $endRow): void
    {
        $this->startRow = $startRow;
        $this->endRow   = $endRow;
    }

    public function readCell($column, $row, $worksheetName = ''): bool
    {
        if ($row === 1) return true;
        return $row >= $this->startRow && $row <= $this->endRow;
    }
}