<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class InvoicesExport implements WithMultipleSheets
{
    use Exportable;
 /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
                    new InvoicesReportPerSheet('day', Carbon::now()->isoFormat('DD') . ' of ' .  Carbon::now()->isoFormat('MMMM')),
                    new InvoicesReportPerSheet('month', 'All of ' .  Carbon::now()->isoFormat('MMMM')),
                    new InvoicesReportPerSheet('year', 'All of ' .  Carbon::now()->isoFormat('OY')),
                ];                        
                
        return $sheets;
    }
}
