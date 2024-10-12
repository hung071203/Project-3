<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Classes;
use App\Models\Division;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\Teacher;
use App\Models\grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Transcript;
use App\Models\TranscriptDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        session()->flash('search', $request->input('search'));
        session()->flash('status', $request->input('status'));
        $admin = Auth::guard('admin')->user();
        $adminId = $admin->id;
        $searchTerm = $request->input('search');

        $divisions = Division::with(['class.school_year', 'teacher', 'subject.grade', 'admin'])->get();

        $divisions = $divisions->sortBy(function ($division) {
            switch ($division->getStatus()) {
                case 'Not Working':
                    return 1;
                case 'Working':
                    return 2;
                case 'Job Done':
                    return 3;
                default:
                    return 4;
            }
        });

        foreach ($divisions as $division) {
            $division->status = $division->getStatus();
        }

        $selectedStatus = $request->input('status');

        return view('division.index', [
            'divisions' => $divisions,
            'selectedStatus' => $selectedStatus
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objClass= new Classes();
        $classes = $objClass->index();
        $objteacher = new Teacher();
        $teacher = $objteacher->index();
        $objgrade = new grade();
        $grade = $objgrade->index();
        $objSubject= new Subject();
        $subject = $objSubject->index();

        $objAdmin = new Admin();
        $admin = $objAdmin->index();
        return view('division.create', [
            'classes' => $classes,
            'teachers' => $teacher,
            'grades' => $grade,
            'subjects' => $subject,
            'admins' => $admin
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDivisionRequest $request)
    {
        // Kiểm tra xem đã tồn tại bản ghi có teacher_id và subject_id tương ứng chưa
        $existingRecord = Division::where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->where('exam_type', $request->exam_type)
            ->first();

        // Kiểm tra số lượng sinh viên trong lớp học
        $studentCount = Student::where('class_id', $request->class_id)->count();

        // Lấy teacher_id và subject_id từ request
        $classId = $request->class_id;
        $teacherId = $request->teacher_id;
        $subjectId = $request->subject_id;

        $examTypes = $request->exam_type;

        // Tìm graded_id của teacher và subject
        $classgradedId = Classes::where('id', $classId)->value('grade_id');
//        $teachergradedId = Teacher::where('id', $teacherId)->value('grade_id');
        $subjectgradedId = Subject::where('id', $subjectId)->value('grade_id');

        // Kiểm tra xem teacher và subject có cùng graded_id không
//        if ($teachergradedId != $subjectgradedId) {
//            // Nếu không, hiển thị thông báo lỗi
//            Session::flash('error', 'Teacher and Subject must belong to the same graded.');
//        } else
//            if ($teachergradedId != $classgradedId) {
//            // Nếu không, hiển thị thông báo lỗi
//            Session::flash('error', 'Teacher and Class must belong to the same graded.');
//        } else
            if ($subjectgradedId != $classgradedId) {
            // Nếu không, hiển thị thông báo lỗi
            Session::flash('error', 'Môn học và lớp phải thuộc cùng một khối.');
        } elseif ($studentCount == 0) {
            // Nếu không có sinh viên trong lớp học, hiển thị thông báo lỗi
            Session::flash('error', 'Lớp học này không có học sinh nào, vui lòng thêm học sinh vào lớp này');
        } elseif (!$existingRecord) {
            foreach ($examTypes as $examType) {
                $obj = new Division();
                $obj->class_id = $request->class_id;
                $obj->teacher_id = $request->teacher_id;
                $obj->subject_id = $request->subject_id;
                $obj->admin_id = Auth::guard('admin')->user()->id;
                $obj->datetime = now();
                $obj->exam_type = $examType;
                $obj->save();
            }
            Session::flash('success', 'Phân công chấm điểm thành công');
        } else {
            // Nếu đã tồn tại, hiển thị thông báo lỗi
            Session::flash('error', 'Loại hình phân công này đã tồn tại hoặc đã được bàn giao cho giáo viên khác');
        }

        return redirect()->route('division.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
{
    $selectedStatus = $request->input('status');
    session()->flash('status', $selectedStatus);

    $teacher = Auth::guard('teacher')->user();
    $teacherId = $teacher->id;

    // Retrieve divisions for the logged-in teacher
    $divisions = Division::join('classes', 'divisions.class_id', '=', 'classes.id')
        ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
        ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
        ->join('grades', 'subjects.grade_id', '=', 'grades.id')
        ->join('admins', 'divisions.admin_id', '=', 'admins.id')
        ->where('divisions.teacher_id', $teacherId)
        ->select([
            'divisions.*',
            'classes.class_name AS class_name',
            'teachers.teacher_name AS teacher_name',
            'grades.grade_name AS grade_name',
            'subjects.subject_name AS subject_name',
            'admins.username AS username'
        ])
        ->get();

    foreach ($divisions as $division) {
        if (in_array($division->exam_type, [1, 2])) {
            $transcriptCount = Transcript::where('division_id', $division->id)->count();
            $division->transcriptCount = $transcriptCount;
        }
    }

    // Filter divisions based on selected status
    if (!empty($selectedStatus)) {
        $divisions = $divisions->filter(function ($division) use ($selectedStatus) {
            return $division->getStatus() == $selectedStatus;
        });
    }

    // Sort divisions based on their status
    $divisions = $divisions->sortBy(function ($division) {
        switch ($division->getStatus()) {
            case 'Not Working':
                return 1;
            case 'Working':
                return 2;
            case 'Job Done':
                return 3;
            default:
                return 4;
        }
    });

    return view('division.show', [
        'divisions' => $divisions,
        'selectedStatus' => $selectedStatus
    ]);
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division, Request $request)
    {
        $objClass = new Classes();
        $classes = $objClass->index();
        $objteacher = new Teacher();
        $teachers = $objteacher->index();
        $objSubject= new Subject();
        $subjects = $objSubject->index();
        $objAdmin = new Admin();
        $admins = $objAdmin->index();
        $objDivision = new Division();
        $objDivision->id = $request->id;
        $divisions = $objDivision->edit();
        return view('division.edit', [
            'classes' => $classes,
            'teachers' => $teachers,
            'subjects' => $subjects,
            'admins' => $admins,
            'divisions' => $divisions,
            'id' => $objDivision->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDivisionRequest $request, Division $division)
    {
        $obj = new Division();
        $obj->id = $request->id;
//        $obj->division_name = $request->division_name;
        $obj->semester = $request->semester;
        $obj->class_id = $request->class_id;
        $obj->teacher_id = $request->teacher_id;
        $obj->subject_id = $request->subject_id;
        $obj->admin_id = Auth::guard('admin')->user()->id;

            $obj->updateDivision();
            Session::flash('success', 'Updated Record Successfully');


        return redirect()->route('division.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division, Request $request)
    {
        $obj = new Division();

        $arr =  $obj->deleteDivision($request);
        if ($arr[0]){
            Session::flash('success', $arr[1]);
        }else{
            Session::flash('error', $arr[1]);
        }
        return Redirect::route('division.index');
    }

    // Thêm hàm isDivisionFinished() vào model Division
//    public function isDivisionFinished()
//    {
//        $transcriptsCount = Transcript::where('division_id', $this->id)->count();
//
//        // Kiểm tra số lượng transcripts
//        if ($transcriptsCount === 0) {
//            return false; // Không có transcript, trạng thái là Unwork
//        }
//
//        // Kiểm tra số lượng transcripts với class count và isFinished()
//        $finishedTranscriptsCount = Transcript::where('division_id', $this->id)
//            ->whereHas('class', function ($query) {
//                $query->whereColumn('class_count', '=', 'transcripts.class_count');
//            })
//            ->where(function ($query) {
//                $query->where('status', 'Finished')->orWhere('status', 'Done');
//            })
//            ->count();
//
//        // Trạng thái là Working nếu có transcripts với class count và isFinished()
//        if ($finishedTranscriptsCount > 0) {
//            return true;
//        }
//
//        return false; // Trạng thái là Unwork nếu không có transcripts đáp ứng điều kiện
//    }


    public function checkTranscript($id)
    {
        // Lấy thông tin graded dựa trên ID
        $divisions = Division::findOrFail($id);

        // Lấy danh sách lớp học thuộc graded đó
        $transcripts = Transcript::where('division_id', $id)->get();

        // Lấy danh sách sinh viên thuộc các lớp đó
//        $students = Student::whereIn('class_id', $classes->pluck('id'))->get();

        return view('division.checkTranscript', [
            'divisions' => $divisions,
            'transcripts' => $transcripts
//            'students' => $students,
        ]);
    }

    public function checkTransDetail($transcript_id)
    {
        // Get the class based on the class_id
        $transcript = Transcript::findOrFail($transcript_id);

        // Get the students for the specific class
        $transcriptDetails = TranscriptDetail::with('student')->where('transcript_id', $transcript_id)->get();

        return view('division.checkTransDetail', [
            'transcript' => $transcript,
            'transcriptDetails' => $transcriptDetails,
        ]);
    }


//    public function getSubjectsByTeacher($teacherId)
//    {
//        $teacher = Teacher::with('subjects')->find($teacherId);
//        if (!$teacher) {
//            return response()->json(['error' => 'Teacher not found'], 404);
//        }
//
//        $subject = $teacher->subjects->first(); // Lấy môn học đầu tiên của giáo viên
//        $subjectName = $subject ? $subject->subject_name : ''; // Nếu không có môn học, trả về chuỗi trống
//
//        return response()->json(['subject_name' => $subjectName]);
//    }

}
