<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function store()
    {
        DB::table('reports')
            ->insert([
                'transcriptdetail_id' => $this->transcriptdetail_id,
                'message' => $this->message,
            ]);
    }
//public function show(){
//  $reports = Report::join('transcript_details', 'reports.transcriptdetail_id', '=', 'transcript_details.id')
//        ->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
//        ->join('students', 'transcript_details.student_id', '=', 'students.id')
//        ->join('classes', 'students.class_id', '=', 'classes.id')
//        ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
//        ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
//        ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
//        ->join('subjects', 'divisions.subject_id', '=', 'subjects.id') // Thêm join bảng subjects
//        ->select([
//            'reports.*',
//            'transcript_details.*',
//            'transcripts.transcript_name AS transcript_name',
//            'transcripts.semester AS semester',
//            'students.student_name AS student_name',
//            'classes.class_name AS class_name',
//            'divisions.division_name AS division_name',
//            'teachers.teacher_name AS teacher_name',
//            'subjects.subject_name AS subject_name', // Chọn trường subject_name
//            'school_years.sy_name AS sy_name'
//            // Các cột khác của bảng transcripts nếu cần
//        ])
//        ->get();
//}

    public function edit()
    {
        $reports = DB::table('reports')
            ->where('id', $this->id)
            ->get();
        return $reports;
    }

    public function updateStatus()
    {
        DB::table('reports')
            ->where('id', $this->id)
            ->update([
                'status' => $this->status
            ]);
    }


    public function ReportsByStudent($studentId)
    {
        return $this->join('transcript_details', 'reports.transcriptdetail_id', '=', 'transcript_details.id')
            ->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id') // Thêm join bảng subjects
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->where('students.id', $studentId)
            ->select([
                'reports.*',
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
//                'divisions.division_name AS division_name',
                'divisions.semester AS semester',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name', // Chọn trường subject_name

                'school_years.sy_name AS sy_name'
                // Các cột khác của bảng transcripts nếu cần
            ])
            ->get();
    }
    // Trong Report Model

    public function getDetailsByTranscriptDetailId($transcriptDetailId)
    {
        return $this->join('transcript_details', 'reports.transcriptdetail_id', '=', 'transcript_details.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('grades', 'classes.grade_id', '=', 'grades.id')
            ->join('divisions', 'transcript_details.division_id', '=', 'divisions.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->where('transcript_details.id', $transcriptDetailId)
            ->select([
                'students.student_name',
                'classes.class_name',
                'grades.grade_name',
                'subjects.subject_name',
                'transcript_details.semester'
            ])
            ->first();
    }

//    public function ReportsByStudent($studentId)
//    {
//        return $this->join('transcript_details', 'reports.transcriptdetail_id', '=', 'reports.id')
//            ->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
//            ->join('students', 'transcript_details.student_id', '=', 'students.id')
//            ->join('classes', 'students.class_id', '=', 'classes.id')
//            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
//            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
//            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
//            ->join('subjects', 'transcripts.subject_id', '=', 'subjects.id')
//            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
//            ->where('students.id', $studentId)
//            ->select([
//                'reports.*',
//                'transcript_details.*',
//                'transcripts.transcript_name AS transcript_name',
//                'transcripts.semester AS semester',
//                'students.student_name AS student_name',
//                'classes.class_name AS class_name',
//                'divisions.division_name AS division_name',
//                'teachers.teacher_name AS teacher_name',
//                'subjects.subject_name AS subject_name',
//                'grades.grade_name AS grade_name',
//                'school_years.sy_name AS sy_name'
//                // Các cột khác của bảng transcripts nếu cần
//            ])
//            ->get();
//    }
    public function transcript_detail()
    {
        return $this->belongsTo(TranscriptDetail::class, 'transcriptdetail_id', 'id');
    }
}
