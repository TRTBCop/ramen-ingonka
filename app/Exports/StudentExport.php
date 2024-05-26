<?php

namespace App\Exports;

use App\Enums\StudentStatusEnum;
use App\Models\Academy;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentExport implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{

    public function __construct()
    {
    }

    public function headings(): array
    {
        return [
            '고유번호',
            '학원명',
            '학생명',
            '아이디',
            '부모님 연락처',
            '학년',
            '서비스 상태',
            '가입일',
            '서비스 종료일',
        ];
    }

    public function collection(): array|Collection|\Illuminate\Support\Collection
    {
        return Student::listFilter()
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
            $row->academy_name,
            $row->name,
            $row->access_id,
            $row->parents_phone,
            $row->grade,
            $row->status->text(),
            $row->created_at,
            $row->service_end_date,
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
