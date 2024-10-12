<?php

namespace App\Http\Controllers;

use App\Exports\TranscriptDetailExport;
use App\Exports\TranscriptExport;
use App\Imports\TranscriptDetailsImport;
use App\Models\Classes;
use App\Models\Division;
use App\Models\Teacher;
use App\Models\SchoolYear;
use App\Models\grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Transcript;
use App\Http\Requests\StoreTranscriptRequest;
use App\Http\Requests\UpdateTranscriptRequest;
use App\Models\TranscriptDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TranscriptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user(); // Get the currently logged-in teacher
        $teacherId = $teacher->id;
        $transcripts = Transcript::join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->where('teachers.id', $teacherId)
            ->select([
                'transcripts.*',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'subjects.semester AS semester',
                'grades.grade_name AS grade_name',
                'classes.class_name AS class_name',
                'school_years.sy_name AS sy_name'
                // Các cột khác của bảng transcripts nếu cần
            ])
            ->orderByRaw('CASE
                        WHEN divisions.exam_type = 4 THEN 1
                        WHEN divisions.exam_type = 3 THEN 2
                        WHEN divisions.exam_type = 2 THEN 3
                        WHEN divisions.exam_type = 1 THEN 4
                        ELSE 5
                    END')
            ->orderBy('subjects.semester', 'ASC') // Sắp xếp theo semester từ thấp đến cao
            ->get();


// Áp dụng isFinish() và sắp xếp lại mảng transcripts
        $sortedTranscripts = [];
        $unfinishedTranscripts = [];

        foreach ($transcripts as $transcript) {
            if ($transcript->isFinish()) {
                $unfinishedTranscripts[] = $transcript;
            } else {
                $sortedTranscripts[] = $transcript;
            }
        }

// Gộp mảng các bản ghi chưa hoàn thành xuống cuối
        $sortedTranscripts = array_merge($sortedTranscripts, $unfinishedTranscripts);

        return view('transcript.index', [
            'transcripts' => $sortedTranscripts,
        ]);

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($division_id)
    {
        $teacher = Auth::guard('teacher')->user(); // Get the currently logged-in teacher
        $teacherId = $teacher->id;

        // Lấy danh sách divisions của giáo viên hiện tại
        $division = Division::find($division_id);
        $subjects = Subject::whereIn('id', $division->pluck('subject_id'))->get();

        // Lấy danh sách classes thuộc các subject trên
        $classes = Classes::whereIn('grade_id', $subjects->pluck('grade_id'))
            ->with('school_year')
                ->get();

        return view('transcript.create', [
            'division' => $division,
            'subjects' => $subjects,
            'classes' => $classes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTranscriptRequest $request)
    {
//        dd($request);
        $divisionId = $request->division_id;
        $examTypes = $request->exam_type; // Lưu ý rằng đây là một mảng
        if (in_array($examTypes, [1, 2])) {
            // Sử dụng vòng lặp foreach để tạo nhiều bản ghi transcript
            foreach (range(1, 2) as $i) {
                $transcript = new Transcript();
                $transcript->transcript_name = $request->transcript_name;
                $transcript->division_id = $divisionId;
                $transcript->save();
            }
        }else{
            // Tạo bản ghi transcript mới và lưu vào cơ sở dữ liệu
            $transcript = new Transcript();
            $transcript->transcript_name = $request->transcript_name;
            $transcript->division_id = $divisionId;
            $transcript->save();
        }


            Session::flash('success', 'Thêm mới bảng điểm thành công');
            return redirect()->route('transcript.index');
//        }
    }




    /**
     * Display the specified resource.
     */
    public function show(Transcript $transcript)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transcript $transcript, Request $request)
    {
        $teacher = Auth::guard('teacher')->user(); // Get the currently logged-in teacher
        $teacherId = $teacher->id;

        // Lấy danh sách divisions của giáo viên hiện tại
        $divisions = Division::where('teacher_id', $teacherId)->get();

        $subjects = Subject::whereIn('id', $divisions->pluck('subject_id'))->get();

        // Lấy danh sách classes thuộc các subject trên
        $classes = Classes::whereIn('grade_id', $subjects->pluck('grade_id'))->get();

        $objTranscript = new Transcript();
        $objTranscript->id = $request->id;
        $transcripts = $objTranscript->edit();

        return view('transcript.edit', [
            'divisions' => $divisions,
            'classes' => $classes,
            'subjects' => $subjects,
            'transcripts' => $transcripts,
            'id' => $objTranscript->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTranscriptRequest $request, Transcript $transcript)
    {

        $divisionId = $request->division_id;
        $exam_type = $request->exam_type;
        $transcriptId = $request->transcript_id;

        // Lấy subject_id từ Division

//        $classCount = Division::where('id', $divisionId)->value('class_count');

        // Kiểm tra xem subject_id từ Division khớp với subject_id từ Transcript không

        // Kiểm tra xem đã tồn tại bản ghi trong TranscriptDetail tương ứng với transcript đang được cập nhật và có student thuộc transcript_id và class_id không
//        $existingTranscriptDetail = TranscriptDetail::where('transcript_id', $transcript->id)
//            ->whereHas('student', function ($query) use ($transcript) {
//                $query->where('class_id', $transcript->class_id);
//
//            })
//            ->exists();
//
//        if ($existingTranscriptDetail) {
//            // Nếu đã tồn tại bản ghi trong TranscriptDetail có student thuộc transcript_id và class_id đó, hiển thị thông báo lỗi
//            return redirect()->route('transcript.index')->with('error', 'Cannot update transcript. Related TranscriptDetail record with corresponding student exists.');
//        }

        // Kiểm tra xem đã tồn tại bản ghi có division_id và class_id tương ứng chưa
        $existingRecord = Transcript::where('exam_type', $exam_type)
            ->first();

//        // Kiểm tra số lượng lớp học đã đăng ký bởi giáo viên cho division_id này
//        $registeredClassesCount = Transcript::where('division_id', $divisionId)
//            ->count();


        // Kiểm tra xem lớp học đã được đăng ký bởi giáo viên nào đó chưa
//        $isClassAssigned =  Transcript::where('class_id', $classId)
//            ->exists();

        if (!$existingRecord ) {
            // Nếu chưa tồn tại và lớp học chưa được đăng ký bởi giáo viên, tạo một bản ghi mới và lưu trữ nó
            $obj = new Transcript();
            $obj->id = $request->id;
            $obj->transcript_name = $request->transcript_name;
            $obj->exam_type = $exam_type;
            $obj->division_id = $divisionId;

            $obj->updateTranscript(); // Lưu trữ bản ghi

            Session::flash('success', 'Cập nhật thaành công');
        } elseif ($existingRecord) {
            // Nếu lớp học đã tồn tại, hiển thị thông báo lỗi
            Session::flash('error', 'Bảng điểm đã tồn tại');
        }
//        elseif ($isClassAssigned) {
//            // Nếu lớp học đã được đăng ký bởi giáo viên khác, hiển thị thông báo lỗi
//            Session::flash('error', 'Class already assigned to another teacher.');
//        } else {
//            // Nếu số lượng lớp học đã vượt quá giới hạn, hiển thị thông báo lỗi
//            Session::flash('error', 'Something Gone Wrong.');
//        }

        return redirect()->route('transcript.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transcript $transcript, Request $request)
    {
        $obj = new Transcript();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteTranscript();

            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Xóa thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Xóa thất bại. Vui lòng thử lại sau');
        }

        return redirect()->route('transcript.index');
    }
    public function isFinished(Request $request)
    {
        $divisionId = $request->division_id;
        $classId = Division::where('id', $divisionId)->value('class_id');
        $transcriptId = $request->id;
        // Kiểm tra số lượng sinh viên trong lớp
        $totalStudentsInClass = Student::where('class_id', $classId)->count();

        // Kiểm tra số lượng sinh viên đã nhận điểm trong transcript_detail
        $studentsWithScores = TranscriptDetail::where('transcript_id', $transcriptId)->count();

        // Nếu số lượng sinh viên với điểm bằng số lượng sinh viên trong lớp, transcript đã hoàn thành
        return $totalStudentsInClass > 0 && $totalStudentsInClass === $studentsWithScores;
    }

    public function teacherView(Request $request)
    {
        $classes = Classes::all();
        $schoolYears = SchoolYear::all();

        // Lấy danh sách các môn học duy nhất (distinct subject names)
        $subjects = Subject::select('subject_name')->distinct()->get();

        $semester = $request->input('semester', 'all');
        $subjectName = $request->input('subject_name', 'all'); // Ensure it's a string
        $classId = $request->input('class_id', 'all');
        $schoolYearId = $request->input('school_year_id', 'all');

        $transcriptDetailModel = new TranscriptDetail();

        // Call the method to fetch transcript details based on filters
        $transcriptDetails = $transcriptDetailModel->transcriptDetailsByFilter($semester, $subjectName, $classId, $schoolYearId);

        if ($request->has('export')) {
            // Ensure $transcriptDetails is passed here
            return Excel::download(new TranscriptDetailExport($semester, $subjectName, $classId, $schoolYearId, $transcriptDetails), 'transcript_details.xlsx');
        }

        return view('teacher.transcriptCheck', [
            'transcriptDetails' => $transcriptDetails,
            'classes' => $classes,
            'subjects' => $subjects,
            'schoolYears' => $schoolYears,
            'semester' => $semester,
            'subjectName' => $subjectName,
            'classId' => $classId,
            'schoolYearId' => $schoolYearId,
        ]);
    }

    public function export()
    {
        return Excel::download(new TranscriptExport, 'transcripts.xlsx');
    }


}
