<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentExport implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{

    public function __construct()
    {
    }

    public function headings(): array
    {
        return [
            '고유 번호',
        ];
    }

    public function collection(): array|Collection|\Illuminate\Support\Collection
    {
        return Payment::listFilter()
            ->where(function ($query) {
                // 선택 다운로드
                $selectedIds = request()->selected_ids ?? [];
                if ($selectedIds && is_array($selectedIds)) {
                    $query->whereIn('id', $selectedIds);
                }
            })
            ->orderBy('id', 'desc')->get();
    }

    /**
     *
     */
    public function map($row): array
    {
        return [
            $row->id,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [];
    }
}
