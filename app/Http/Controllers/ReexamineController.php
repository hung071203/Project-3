<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Division;
use App\Models\Reexamine;
use App\Http\Requests\StoreReexamineRequest;
use App\Http\Requests\UpdateReexamineRequest;
use App\Models\Report;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Transcript;
use App\Models\TranscriptDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ReexamineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user(); // Lấy thông tin của sinh viên đang đăng nhập
        $teacherId = $teacher->id;

        $reexamines = DB::table('reexamines')
            ->join('reports', 'reexamines.report_id', '=', 'reports.id')
            ->join('transcript_details', 'reports.transcriptdetail_id', '=', 'transcript_details.id')
            ->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->where('teachers.id', $teacherId)
            ->select([
                'reexamines.*',
                'reports.*',
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',

                'students.student_name AS student_name',
                'classes.class_name AS class_name',
                'divisions.division_name AS division_name',
                'divisions.semester AS semester',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.graded_name AS graded_name',
                'school_years.sy_name AS sy_name'
            ])
            ->get();

    //        dd($reexamines);


        return view('reexamine.index', ['reexamines' => $reexamines]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {// Get the currently logged-in teacher
        $teacher = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;

// Get divisions associated with the teacher
        $divisions = Division::where('teacher_id', $teacherId)->get();

        $transcripts = Transcript::whereIn('division_id', $divisions->pluck('id'))->get();

// Get transcript details associated with the transcripts
        $transcript_details = TranscriptDetail::whereIn('transcript_id', $transcripts->pluck('id'))->get();
        $reports = Report::whereIn('transcriptdetail_id', $transcript_details->pluck('id'))
            ->where('status', 1)
            ->get();
        $students = Student::whereIn('id', $transcript_details->pluck('student_id'))->get();
// Get subjects associated with the divisions
        $subjects = Subject::whereIn('id', $divisions->pluck('subject_id'))->get();

// Get classes associated with the transcript details
        $classes = Classes::whereIn('id', $transcript_details->pluck('class_id'))
            ->with('school_year')
            ->get();



// Get reports associated with the transcript details

        return view('reexamine.create', [
            'divisions' => $divisions,
            'reports' => $reports,
            'transcripts' => $transcripts,
            'transcript_details' => $transcript_details,
            'students' => $students,
            'subjects' => $subjects,
            'classes' => $classes,
        ]);

    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReexamineRequest $request)
    {
        $existingRecord = Reexamine::where('report_id', $request->report_id)
            ->first();
        $new_score = $request->new_score;

        if ($existingRecord) {
            // Nếu đã tồn tại, hiển thị thông báo lỗi
            Session::flash('error', 'You Already Add Score For This Student.');
        } elseif ($new_score > 10 ){
Session::flash('error', 'Maximum Score is 10.');
} elseif ($new_score < 0 ){
Session::flash('error', 'Minimum Score is 0.');
}else{
            $obj = new Reexamine();
            $obj->report_id = $request->report_id;
            $obj->new_score = $request->new_score;
            $obj->save();
            $reportId = $request->report_id;
            $newScore = $request->new_score;

            $this->updateReportStatus($reportId, $newScore);
            Session::flash('success', 'Added New Record');

        }

        return redirect()->route('reexamine.index');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $student = Auth::guard('student')->user(); // Lấy thông tin của sinh viên đang đăng nhập
        $studentId = $student->id;

        $reexamines = DB::table('reexamines')
            ->join('reports', 'reexamines.report_id', '=', 'reports.id')
            ->join('transcript_details', 'reports.transcriptdetail_id', '=', 'transcript_details.id')
            ->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->where('students.id', $studentId)
            ->select([
                'reexamines.*',
                'reports.*',
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
                'divisions.division_name AS division_name',
                'divisions.semester AS semester',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.graded_name AS graded_name',
                'school_years.sy_name AS sy_name'
            ])
            ->get();




        return view('reexamine.show', ['reexamines' => $reexamines]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reexamine $reexamine, Request $request)
    {
        // Get the currently logged-in teacher
            $teacher = Auth::guard('teacher')->user();
            $teacherId = $teacher->id;

// Get divisions associated with the teacher
            $divisions = Division::where('teacher_id', $teacherId)->get();

            $transcripts = Transcript::whereIn('division_id', $divisions->pluck('id'))->get();

// Get transcript details associated with the transcripts
            $transcript_details = TranscriptDetail::whereIn('transcript_id', $transcripts->pluck('id'))->get();
            $reports = Report::whereIn('transcriptdetail_id', $transcript_details->pluck('id'))
                ->get();
            $students = Student::whereIn('id', $transcript_details->pluck('student_id'))->get();
// Get subjects associated with the divisions
            $subjects = Subject::whereIn('id', $divisions->pluck('subject_id'))->get();

// Get classes associated with the transcript details
            $classes = Classes::whereIn('id', $transcript_details->pluck('class_id'))
                ->with('school_year')
                ->get();

        $objReexamine = new Reexamine();
        $objReexamine-> report_id = $request-> report_id;
        $reexamines = $objReexamine->edit();

// Get reports associated with the transcript details

            return view('reexamine.edit', [
                'divisions' => $divisions,
                'reports' => $reports,
                'transcripts' => $transcripts,
                'transcript_details' => $transcript_details,
                'students' => $students,
                'subjects' => $subjects,
                'classes' => $classes,
                'reexamines'=> $reexamines,
                'report_id' => $objReexamine->report_id
                ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReexamineRequest $request, Reexamine $reexamine)
    {
        $new_score = $request->new_score;
        if ($new_score > 10) {
            Session::flash('error', 'Maximum Score is 10.');
        } elseif ($new_score < 0) {
            Session::flash('error', 'Minimum Score is 0.');
        } else {
            $obj = new Reexamine();
            $obj->report_id = $request->report_id;
            $obj->new_score = $request->new_score;
            $obj->updateReexamineScore();
            $reportId = $request->report_id;
            $newScore = $request->new_score;

            $this->updateReportStatus($reportId, $newScore);
            Session::flash('success', 'Updated New Score Successfully');
        }
        return redirect()->route('reexamine.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reexamine $reexamine)
    {
        //
    }
    public function updateReportStatus($reportId, $newScore)
    {
        // Kiểm tra xem có bản ghi reexamines tương ứng với report_id không
        $hasReexamines = DB::table('reexamines')
                ->where('report_id', $reportId)
                ->count() > 0;

        // Nếu có bản ghi reexamines, cập nhật điểm trong bảng reexamines
        if ($hasReexamines) {
            DB::table('reexamines')
                ->where('report_id', $reportId)
                ->update(['new_score' => $newScore]);

            // Cập nhật trường status trong bảng reports thành 3 (hoặc giá trị khác tùy vào nhu cầu của bạn)
            DB::table('reports')
                ->where('id', $reportId)
                ->update(['status' => 3]);
        }

        // Trả về kết quả hoặc chuyển hướng đến trang khác tùy vào logic của bạn
    }
}
