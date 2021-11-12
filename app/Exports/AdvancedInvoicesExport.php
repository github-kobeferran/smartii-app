<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class AdvancedInvoicesExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;


    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        if(!empty($this->month))
            $invoices = Invoice::query()->whereMonth('created_at', $this->month)->whereYear('created_at', $this->year);
        else
            $invoices = Invoice::query()->whereYear('created_at', $this->year);

        return $invoices;
    }

    public function headings(): array
    {
        return [
            'DATE',
            'INVOICE ID',
            'BALANCE',
            'PAYMENT',
            'PAYMENT RECEIVED',
            'REMAINING BALANCE',
            'STUDENT',
            'MADE BY',
        ];
    }

    
    public function map($invoices): array
    {

        return [     
            \Carbon\Carbon::parse($invoices->created_at)->isoFormat('MMM DD, OY h:mm A'),
            '#' . strtoupper($invoices->invoice_id),
            'Php ' . number_format($invoices->balance, 2),
            'Php ' . number_format($invoices->payment, 2),
            'Php ' . number_format($invoices->payment_received, 2),
            'Php ' . number_format($invoices->remaining_bal, 2),
            $invoices->student->student_id . '-' .  $invoices->student->first_name . ' ' . $invoices->student->last_name,
            $invoices->admin->admin_id . '-' . $invoices->admin->name,
        ];
    }

   
}
