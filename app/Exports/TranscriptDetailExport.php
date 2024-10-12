<?php

namespace App\Exports;

use App\Models\TranscriptDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TranscriptDetailExport implements FromCollection, WithHeadings, WithMapping
{
    private $transcriptId;

    public function __construct($transcriptId)
    {
        $this->transcriptId = $transcriptId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TranscriptDetail::where('transcript_id', $this->transcriptId)->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tên Học Sinh',
            'Ghi Chú',
            'Điểm'
        ];
    }

    /**
     * @param mixed $transcriptDetail
     *
     * @return array
     */
    public function map($transcriptDetail): array
    {
        return [
            $transcriptDetail->student->student_name,
            $this->mapNote($transcriptDetail->note),
            $transcriptDetail->score ?? 'None'
        ];
    }

    private function mapNote($note)
    {
        switch ($note) {
            case 1:
                return 'Làm Bài';
            case 2:
                return 'Bị Cấm';
            case 3:
                return 'Vắng Mặt';
            default:
                return '';
        }
    }
}
