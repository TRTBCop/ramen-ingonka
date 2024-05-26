<?php

namespace App\Exports;

use App\Enums\StudentStatusEnum;
use App\Models\Academy;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AcademyExport implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{

    public function __construct()
    {
    }

    public function headings(): array
    {
        return [
            '고유번호',
            '학원명',
            '우편번호',
            '주소',
            '상세주소',
            '전화번호',
            '담당자이메일',
            '대표자명',
            '등록일',
            '상태',
            '메모',
            '사용중학생수',
            '태그',
        ];
    }

    public function collection(): array|Collection|\Illuminate\Support\Collection
    {
        return Academy::with('tags')->withCount([
            'students' => function ($query) {
                $query->where([
                    'status' => StudentStatusEnum::IN_USE
                ]);
            },
        ])->listFilter()
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
            $row->name,
            $row->zipcode,
            $row->address,
            $row->address2,
            $row->phone,
            $row->staff_email,
            $row->president_name,
            $row->created_at,
            $row->status->text(),
            $row->memo,
            $row->students_count,
            $row->tags->pluck('name')->implode(','),
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
