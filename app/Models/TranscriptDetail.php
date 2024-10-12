<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TranscriptDetail extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function index()
    {
        $transcript_detail = DB::table('transcript_details')
            ->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id') // Corrected
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id') // Corrected
            ->select([
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
//                'divisions.division_name AS division_name',

                'students.student_name AS student_name',
                'grades.grade_name AS grade_name',
                'classes.class_name AS class_name',
                'subjects.subject_name AS subject_name',
                'school_years.sy_name AS sy_name'
            ])
            ->get();

        return $transcript_detail;


    }

    public function store()
    {
        DB::table('transcript_details')
            ->insert([
                'transcript_id' => $this->transcript_id,
                'student_id' => $this->student_id,
                'note' => $this->note,
                'score' => $this->score
            ]);
    }

    public function edit()
    {
        $transcript_details = DB::table('transcript_details')
            ->where('id', $this->id)
            ->get();
        return $transcript_details;
    }

    public function updateTransDetail()
    {
        DB::table('transcript_details')
            ->where('id', $this->id)
            ->update([
//                'transcript_id' => $this->transcript_id,
//                'student_id' => $this->student_id,
                'note' => $this->note,
                'score' => $this->score
            ]);
    }

    public function deleteTransDetail()
    {
        DB::table('transcript_details')
            ->where('id', $this->id)
            ->delete();
    }

    public function transcript()
    {
        return $this->belongsTo(Transcript::class);
    }
    public function transcriptDetailsByTranscript($teacherId)
    {
        return $this->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->where('teachers.id', $teacherId)
            ->select([
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'transcripts.exam_type AS exam_type',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
//                'divisions.division_name AS division_name',
                'divisions.semester AS semester',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.grade_name AS grade_name',
                'school_years.sy_name AS sy_name'
                // Các cột khác của bảng transcripts nếu cần

            ])
//            ->orderBy('transcript.exam_type', 'asc')
            ->get();
    }

    public function transcriptDetailsByStudent($studentId)
    {
        return $this->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->where('students.id', $studentId)
            ->select([
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'divisions.exam_type AS exam_type',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
                'subjects.semester AS semester',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.grade_name AS grade_name',
                'school_years.sy_name AS sy_name'
            ])
            ->get();
    }

    public function transcriptDetailsByClass($class_id){
        $transcriptDetails = $this->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->where('classes.id', $class_id) // Thay 'students.id' bằng 'classes.id' để lấy bảng điểm của toàn lớp
            ->select([
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'divisions.exam_type AS exam_type',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
                'subjects.semester AS semester',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.grade_name AS grade_name',
                'school_years.sy_name AS sy_name'
            ])
            ->get();

        return $transcriptDetails;

    }



    public function transcriptDetailsByFilter($semester, $subjectName, $classId, $schoolYearId)
    {
        $query = TranscriptDetail::join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->select([
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'divisions.exam_type AS exam_type',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
                'subjects.semester AS semester',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.grade_name AS grade_name',
                'school_years.sy_name AS sy_name'
            ]);

        if ($semester !== 'all') {
            $query->where('subjects.semester', $semester);
        }

        if ($subjectName !== 'all') {
            $query->where('subjects.subject_name', $subjectName);
        }

        if ($classId !== 'all') {
            $query->where('classes.id', $classId);
        }

        if ($schoolYearId !== 'all') {
            $query->where('school_years.id', $schoolYearId);
        }

        return $query->get();
    }


//    public function transcriptDetailsByStudentAndFilters($studentId, $subjectId)
//    {
//        $query = $this->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
//            ->join('students', 'transcript_details.student_id', '=', 'students.id')
//            ->join('classes', 'students.class_id', '=', 'classes.id')
//            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
//            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
//            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
//            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
//            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
//            ->where('students.id', $studentId);
//
//        if ($subjectId) {
//            $query->where('subjects.id', $subjectId);
//        }
//
//
//        return $query->select([
//            'transcript_details.*',
//            'transcripts.transcript_name AS transcript_name',
//            'students.student_name AS student_name',
//            'classes.class_name AS class_name',
////            'divisions.division_name AS division_name',
//            'divisions.semester AS semester',
//            'teachers.teacher_name AS teacher_name',
//            'subjects.subject_name AS subject_name',
//            'grades.grade_name AS grade_name',
//            'school_years.sy_name AS sy_name'
//            // Các cột khác của bảng transcripts nếu cần
//        ])->get();
//    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

//    public function class()
//    {
//        return $this->belongsTo(Classes::class, 'class_id', 'id');
//    }

    public function transcripts()
    {
        return $this->belongsTo(Transcript::class, 'transcript_id', 'id');
    }

//    public function report()
//    {
//        return $this->hasOne(Report::class);
//    }
}
