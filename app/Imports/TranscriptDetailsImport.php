<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class TranscriptDetailsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            // Assuming the columns are as follows:
            // [0] => student_name, [1] => note, [2] => score

            $student = Student::where('student_name', $row[0])->first();

            if ($student) {
                // Update or create your transcript detail entry here based on your logic
                // For example:
                $student->transcriptDetails()->updateOrCreate(
                    ['transcript_id' => $transcriptId],
                    [
                        'note' => $row[1],
                        'score' => $row[2]
                    ]
                );
            }
        }
    }
}


