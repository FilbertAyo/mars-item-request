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
use Maatwebsite\Excel\Concerns\FromView;

class PettyExport implements FromView, WithTitle
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

    public function view(): View
    {
        $query = Petty::with(['user', 'attachments', 'trips.stops', 'lists', 'trips.startPoint']);

        if ($this->from && $this->to) {
            $query->whereBetween('created_at', [$this->from, $this->to]);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return view('reports.petty.excel', [
            'petties' => $query->get(),
            'department' => $this->department,
            'from' => $this->from,
            'to' => $this->to,
            'status' => $this->status,
        ]);
    }

    public function title(): string
    {
        return 'Petty Cash Report';
    }
}
