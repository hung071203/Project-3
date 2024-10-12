<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Report;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\SchoolYear;
use App\Models\grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TranscriptDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Auth::guard('student')->user(); // Lấy thông tin của sinh viên đang đăng nhập
        $studentId = $student->id;

        $reportStudentModel = new Report();

        $reports = $reportStudentModel->ReportsByStudent($studentId);
//        dd($reports);
        return view('report.index', [
            'reports' => $reports
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $reports = DB::table('reports')
            ->join('transcript_details', 'reports.transcriptdetail_id', '=', 'transcript_details.id')
            ->join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')

            ->select([
                'reports.*',
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
//                'divisions.division_name AS division_name',
                'divisions.semester AS semester',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.graded_name AS graded_name',
                'school_years.sy_name AS sy_name'
            ])
            ->get();

        return view('report.receive', ['reports' => $reports]);
    }

    public function showteacher()
    {
        $teacher = Auth::guard('teacher')->user(); // Lấy thông tin của sinh viên đang đăng nhập
        $teacherId = $teacher->id;

        $reports = DB::table('reports')
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
                'reports.*',
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
                'divisions.*',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.graded_name AS graded_name',
                'school_years.sy_name AS sy_name'
            ])
            ->get();

        return view('report.received', ['reports' => $reports]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report, Request $request)
    {
//        $objStudent = new Student();
//        $students = $objStudent->index();
//        $objClass = new Classes();
//        $classes = $objClass->index();
//        $objSubject = new Subject();
//        $subjects = $objSubject->index();
//        $objgrade = new grade();
//        $grades = $objgrade->index();
//        $objSy = new SchoolYear();
//        $school_years = $objSy->index();
//        $objReport = new Report();
//        $objReport->id = $request->id;
//        $reports = $objReport->edit();
//        return view('class.edit', [
//            'reports' => $reports,
//            'students' => $students,
//            'subjects' => $subjects,
//            'grades' => $grades,
//            'school_years' => $school_years,
//            'classes' => $classes,
//            'id' => $objReport->id
//            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {

    }

    public function updateReport(Request $request, Report $report)
    {
        // Lấy thông tin từ request
        $transcriptdetailId = $request->input('transcriptdetail_id');
        $message = $request->input('message');
        $status = $request->input('status');

        // Kiểm tra nếu $status hợp lệ (1 cho Accepted, 2 cho Rejected)
        if ($status == 1 || $status == 2) {
            // Kiểm tra xem bản ghi với transcriptdetail_id đã tồn tại hay không
            $existingReport = Report::where('transcriptdetail_id', $transcriptdetailId)->first();

            // Nếu bản ghi tồn tại, cập nhật các trường
            if ($existingReport) {
                $existingReport ->where('transcriptdetail_id',$transcriptdetailId)
                    ->update([
                    'status' => $status,
                ]);
            } else {
                // Nếu không tồn tại, tạo mới một bản ghi với các trường được cung cấp
                Report::create([
                    'transcriptdetail_id' => $transcriptdetailId,
                    'message' => $message,
                    'status' => $status,
                ]);
            }
        }

        return redirect()->route('report.receive');
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }

    public function showReportForm()
    {

        $student = Auth::guard('student')->user(); // Lấy thông tin của sinh viên đang đăng nhập
        $studentId = $student->id;

        // Tạo một đối tượng TranscriptDetail
        $transcriptDetailModel = new TranscriptDetail();

        // Lấy danh sách transcript details liên quan đến sinh viên
        $transcriptDetails = $transcriptDetailModel->transcriptDetailsByStudent($studentId);


        return view('report.create',['transcriptDetails' => $transcriptDetails]
        );
    }

    public function submitReport(Request $request)
    {
        $existingRecord = Report::where('transcriptdetail_id', $request->transcriptdetail_id)->first();

        if ($existingRecord) {
            // Nếu đã tồn tại, hiển thị thông báo lỗi
            Session::flash('error', 'This Report Has Exists.');
        } else {
            // Nếu không tồn tại, truy vấn bảng transcript_details để kiểm tra exam_type
            $transcriptDetail = TranscriptDetail::where('id', $request->transcriptdetail_id)->first();

            if ($transcriptDetail && ($transcriptDetail->exam_type === 2 || $transcriptDetail->exam_type === 3)) {
                // Nếu exam_type là 2 hoặc 3, hiển thị thông báo lỗi
                Session::flash('error', 'If you got banned or you skip the exams, you cant make a report.');
            } else {
                // Nếu không tồn tại hoặc exam_type không phải là 2 hoặc 3, thêm bản ghi mới
                $obj = new Report();
                $obj->id = $request->id;
                $obj->transcriptdetail_id = $request->transcriptdetail_id;
                $obj->message = $request->message;
                $obj->status = 0;
                $obj->save();
                Session::flash('success', 'Report Has Been Sent');
            }
        }

        return redirect()->route('report.index');
    }



//$status = $request->status;
//$reportId = $request->reportId;
//
//    // Kiểm tra xem status có hợp lệ không
//if ($status === 'accepted' || $status === 'rejected') {
//    // Cập nhật trạng thái trong cơ sở dữ liệu
//Report::where('id', $reportId)->update(['status' => ($status === 'accepted' ? 1 : 2)]);
//
//return response()->json(['message' => 'Status updated successfully']);
//} else {
//    return response()->json(['message' => 'Invalid status'], 422);
//}
}
