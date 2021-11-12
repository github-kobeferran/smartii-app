<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class InvoicesReportPerSheet implements FromCollection, WithMapping, WithHeadings, WithStyles, WithStrictNullComparison, WithTitle 
{

    protected $getByType = '';
    protected $title = '';

    public function __construct($getByType, $title)
    {
        $this->getByType = $getByType;        
        $this->title = $title;        
    }

     
    public function styles(Worksheet $sheet)
    {

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],           
                  
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        switch($this->getByType){
            case 'day':               
                return Invoice::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();
            break;
            case 'month':
                return Invoice::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
            break;
            case 'year':
                return Invoice::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->get();
            break;                
        }
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

    public function title(): string
    {
        return $this->title;
    }
}
