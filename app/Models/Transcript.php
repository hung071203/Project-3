<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Transcript extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function index()
    {
        $transcript = DB::table('transcripts')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')

            ->select(['transcripts.*',
                'divisions.*',
                'classes.class_name AS class_name',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.grade_name AS grade_name'

            ])


            ->get();

        return $transcript;
    }

    public function store()
    {

        DB::table('transcripts')

            ->insert([
                'transcript_name' => $this->transcript_name,
                'exam_type' => $this->exam_type,
                'division_id' => $this->division_id,


            ]);
    }

    public function edit()
    {
        $transcripts = DB::table('transcripts')
            ->where('id', $this->id)
            ->get();
        return $transcripts;
    }

    public function updateTranscript()
    {
        DB::table('transcripts')
            ->where('id', $this->id)
            ->update([
                'transcript_name' => $this->transcript_name,
                'exam_type' => $this->exam_type,
                'division_id' => $this->division_id,
            ]);
    }
    public function deleteTranscript()
    {
        DB::table('transcripts')
            ->where('id', $this->id)
            ->delete();
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function transcriptsByteacher($teacherId)
    {

        return $this->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->where('teachers.id', $teacherId)
            ->select([
                'transcripts.*',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.grade_name AS grade_name',
                'classes.class_name AS class_name',
                // Các cột khác của bảng transcripts nếu cần
            ])
            ->get();

    }
    // Định nghĩa mối quan hệ với bảng Subject
//    public function subject()
//    {
//        return $this->belongsTo(Subject::class, 'subject_id', 'id');
//    }

    // Định nghĩa mối quan hệ với bảng Classes
//    public function class()
//    {
//        return $this->belongsTo(Classes::class, 'class_id', 'id');
//    }

    // Định nghĩa mối quan hệ với bảng grade (nếu có)
//    public function grade()
//    {
//        return $this->belongsTo(Grade::class, 'grade_id', 'id');
//    }

    public function transcriptDetails()
    {
        return $this->hasMany(TranscriptDetail::class, 'transcript_id', 'id');
    }

    public function isFinish()
    {
        $divisionId = $this->division_id;
        $classId = Division::where('id', $divisionId)->value('class_id');
        $transcriptId = $this->id;

        // Kiểm tra số lượng sinh viên trong lớp
        $totalStudentsInClass = Student::where('class_id', $classId)->count();

        // Kiểm tra số lượng sinh viên đã nhận điểm trong transcript_detail
        $studentsWithScores = TranscriptDetail::where('transcript_id', $transcriptId)->count();

        // Nếu số lượng sinh viên với điểm bằng số lượng sinh viên trong lớp, transcript đã hoàn thành
        return $totalStudentsInClass > 0 && $totalStudentsInClass === $studentsWithScores;
    }
}
