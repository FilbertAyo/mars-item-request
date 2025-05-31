<?php

namespace App\Exports;

use App\Models\Petty;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class PettyExport implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    protected $from;
    protected $to;
    protected $status;
    protected $department;

    public function __construct($from = null, $to = null, $status = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->status = $status;
        $this->department = optional(auth()->user()->department)->name ?? 'N/A';
    }

    public function collection()
    {
        $query = Petty::with('user');

        if ($this->from && $this->to) {
            $query->whereBetween('created_at', [$this->from, $this->to]);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $petties = $query->get();

        return $petties->map(function ($petty, $index) {
            return [
                '#' => $index + 1,
                'Name' => $petty->user->name ?? 'N/A',
                'Request For' => $petty->request_for,
                'Amount' => $petty->amount,
                'Date' => $petty->created_at->format('d M Y'),
                'Status' => ucfirst($petty->status),
            ];
        });
    }

    public function headings(): array
    {
        return ['#', 'Name', 'Request For', 'Amount', 'Date', 'Status'];
    }

    public function title(): string
    {
        return 'Petty Cash Report';
    }

    public function registerEvents(): array
    {
        $dateRange = '';
        if ($this->from && $this->to) {
            $dateRange = " FROM {$this->from} TO {$this->to}";
        }

        return [
            AfterSheet::class => function (AfterSheet $event) use ($dateRange) {
                $title = "PETTY CASH REPLENISHMENT FOR {$this->department}{$dateRange}";

                // Merge and write title in row 1
                $event->sheet->mergeCells('A1:F1');
                $event->sheet->setCellValue('A1', $title);

                // Style the title
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            }
        ];
    }
}
