<?php
namespace App\Exports\Person\ImportFormat;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportFormatExcel implements WithMultipleSheets {
    public function sheets(): array {
        return ['DATA' => new SheetMain([])];
    }
}
