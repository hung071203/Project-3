<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reexamine extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function edit()
    {
        $reexamines = DB::table('reexamines')
            ->where('report_id', $this->report_id)
            ->get();
        return $reexamines;
    }
    public function updateReexamineScore()
    {
        DB::table('reexamines')
            ->where('report_id', $this->report_id)
            ->update([
//                'transcript_id' => $this->transcript_id,
//                'student_id' => $this->student_id,

                'new_score' => $this->new_score
            ]);
    }



}
