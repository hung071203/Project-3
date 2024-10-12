<?php

namespace App\Http\Controllers;
use App\Exports\TranscriptDetailExport;
use App\Exports\TranscriptExport;
use App\Models\Teacher;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Division;
use App\Models\grade;
use App\Models\Student;
use App\Models\Transcript;
use App\Models\TranscriptDetail;
use App\Http\Requests\StoreTranscriptDetailRequest;
use App\Http\Requests\UpdateTranscriptDetailRequest;
use Illuminate\Http\Request;
use  Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Redirect;
use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TranscriptDetailsImport;
class TranscriptDetailController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index($transcriptId)
    {
        $teacher = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;

        $transcript_details = TranscriptDetail::join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->where('transcript_id', $transcriptId)
            ->select([
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'divisions.exam_type AS exam_type',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
                'teachers.teacher_name AS teacher_name',
                'subjects.subject_name AS subject_name',
                'grades.grade_name AS grade_name',
                'school_years.sy_name AS sy_name'
            ])
            ->get();

        return view('transdetail.index', ['transcript_details' => $transcript_details]);
    }

    public function import(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls|max:2048',
            'transcript_id' => 'required|exists:transcripts,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Process the file
        $file = $request->file('file');
        $path = $file->getRealPath();
        $data = Excel::toArray([], $path);

        // Assuming the data is in the first sheet
        $rows = $data[0];
        array_shift($rows); // Remove the header row

        // Validate and prepare data
        $errors = [];
        foreach ($rows as $row) {
            $studentName = $row[0]; // Assuming student name is in the 1st column (index 0)
            $note = $row[1]; // Assuming note is in the 2nd column (index 1)
            $score = $row[2]; // Assuming score is in the 3rd column (index 2)

            // Validate note
            if (!in_array($note, [1, 2, 3])) {
                $errors[] = "Ghi Chú của học sinh '$studentName' không hợp lệ.";
            }

            // Validate score
            if (!$this->isValidScore($score)) {
                $errors[] = "Điểm của học sinh '$studentName' không hợp lệ.";
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // Process valid data further (save to database, etc.)

        return redirect()->route('transdetail.create')->with('success', 'Dữ liệu đã được nhập thành công.');
    }

    private function isValidScore($score)
    {
        $regex = '/^(10|(\d{1}(\.25|\.5|\.75)?))$/';
        return preg_match($regex, $score);
    }


    public function export($transcriptId)
    {
        return Excel::download(new TranscriptDetailExport($transcriptId), 'transcript.xlsx');
    }

    public function export2($transcriptId)
    {
        // Retrieve students based on the transcript ID
        $students = Student::whereIn('class_id', function ($query) use ($transcriptId) {
            $query->select('class_id')
                ->from('divisions')
                ->where('id', $transcriptId); // Adjust this based on your logic
        })->get();

        // Filter and map only the student names
        $studentNames = $students->pluck('student_name');

        // Export using Maatwebsite Excel
        return Excel::download(new class($studentNames) implements FromCollection, WithHeadings {
            public function __construct($data) {
                $this->data = $data;
            }
            public function collection() {
                return collect([$this->data]);
            }
            public function headings(): array {
                return [
                    'Tên Học Sinh',
                    'Ghi Chú',
                    'Điểm'
                ];
            }
        }, 'students.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($transcriptId)
    {
        // Get the currently logged-in teacher
        $teacher = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;

        // Get divisions associated with the teacher
        $divisions = Division::where('teacher_id', $teacherId)->pluck('id');

        // Lấy division_id từ transcript_id chỉ nếu nó thuộc danh sách division của giáo viên
        $divisionId = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->pluck('division_id')
            ->first();

        // Lấy danh sách sinh viên từ lớp học của transcript hiện tại và chưa có điểm trong lần thi này
        $students = Student::whereIn('class_id', function ($query) use ($divisionId, $transcriptId) {
            $query->select('class_id')
                ->from('divisions')
                ->where('id', $divisionId)
                ->whereNotExists(function ($subquery) use ($transcriptId) {
                    $subquery->select(DB::raw(1))
                        ->from('transcript_details')
                        ->whereRaw('transcript_details.student_id = students.id')
                        ->where('transcript_details.transcript_id', $transcriptId);
                });
        })->get();

        // Lấy chỉ một semester từ division_id
//        $semester = Division::where('id', $divisionId)->value('semester');

        // Lấy exam_type từ transcript_id
//        $examTypes = Transcript::whereIn('division_id', $divisions)
//            ->where('id', $transcriptId)
//            ->pluck('exam_type')
//            ->first();

        // Lấy các bản ghi transcript tương ứng với division_id và exam_type
        $transcripts = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->get();


        // Lấy thông tin lớp học cho sinh viên
        $classes = Classes::whereIn('id', $students->pluck('class_id'))
            ->with('school_year')
            ->get();

        return view('transdetail.create', [
            'transcripts' => $transcripts,
            'students' => $students,
            'classes' => $classes,
//            'semester' => $semester
        ]);
    }




    public function created($transcriptId)
    {
        // Get the currently logged-in teacher
        $teacher = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;

        // Get divisions associated with the teacher
        $divisions = Division::where('teacher_id', $teacherId)->pluck('id');

        // Lấy giá trị transcript_id từ request
//        $transcriptId = $request->input('transcript_id');

        // Lấy division_id từ transcript_id chỉ nếu nó thuộc danh sách division của giáo viên
        $divisionId = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->pluck('division_id')
            ->first();

        // Lấy chỉ một semester từ division_id
//        $semester = Division::where('id', $divisionId)->value('semester');

        // Lấy exam_type từ transcript_id


        // Lấy các bản ghi transcript tương ứng với division_id và exam_type
        $transcripts = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->get();

        // Lấy danh sách sinh viên từ lớp học của transcript hiện tại
        $students = Student::whereIn('class_id', function ($query) use ($divisionId) {
            $query->select('class_id')
                ->from('divisions')
                ->where('id', $divisionId);
        })
            ->whereIn('id', function ($query) use ($divisionId) {
                $query->select('student_id')
                    ->from('transcript_details')
                    ->whereIn('transcript_id', function ($subquery) use ($divisionId) {
                        $subquery->select('id')
                            ->from('transcripts')
                            ->whereIn('division_id', [$divisionId])
                            ->where('exam_type', 0);
                    })
                    ->where(function ($subquery) {
                        $subquery->where('score','<', 5)
                            ->orWhereNull('score');
                    });
            })
            ->get();


        // Lấy thông tin lớp học cho sinh viên
        $classes = Classes::whereIn('id', $students->pluck('class_id'))
            ->with('school_year')
            ->get();



        return view('transdetail.created', [
            'transcripts' => $transcripts,
            'students' => $students,
            'classes' => $classes,
//            'semester' => $semester,

        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Nhận dữ liệu từ request
        $transcriptId = $request->input('transcript_id');
        $transcriptDetails = [];
        $teacher = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;

        // Kiểm tra xem teacher có quyền truy cập vào transcript này không
        $transcript = Transcript::where('id', $transcriptId)
            ->whereHas('division', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->first();

        if (!$transcript) {
            return redirect()->route('transcript.index')->with('error', 'You do not have access to this transcript.');
        }

        // Lấy danh sách lớp học của giáo viên
        $divisions = Division::where('teacher_id', $teacherId)
            ->where('id', $transcript->division_id)
            ->get();

        // Lấy danh sách sinh viên trong các lớp của giáo viên
        $students = Student::whereIn('class_id', $divisions->pluck('class_id'))
            ->get();

        // Lặp qua mảng sinh viên và kiểm tra và tạo dữ liệu cho mỗi sinh viên
        foreach ($students as $student) {
            // Kiểm tra xem sinh viên đã có điểm trong transcript_details của lần thi này chưa
            $existingTranscriptDetail = TranscriptDetail::where('transcript_id', $transcriptId)
                ->where('student_id', $student->id)
                ->first();

            if (!$existingTranscriptDetail) {
                // Kiểm tra điểm số có hợp lệ không
                $score = $request->input('score_' . $student->id);
                if ($score < 0 || $score > 10) {
                    return redirect()->route('transcript.index')->with('error', 'Điểm nhỏ nhất là 0 và lớn nhất là 10');
                }

                $note = [];
                if ($request->has('note_' . $student->id)) {
                    $note[] = $request->input('note_' . $student->id);
                }

                // **Thuộc tính khác (thay đổi theo yêu cầu)**
                $otherAttributes = []; // Mảng để lưu trữ các thuộc tính khác

                // **Lấy dữ liệu cho các thuộc tính khác từ request**
                // Ví dụ: lấy thuộc tính "attendance" từ request
                if ($request->has('attendance_' . $student->id)) {
                    $otherAttributes['attendance'] = $request->input('attendance_' . $student->id);
                }

                // **Thêm dữ liệu cho các thuộc tính khác vào mảng transcriptDetailData**
                $transcriptDetailData = [
                    'transcript_id' => $transcriptId,
                    'student_id' => $student->id,
                    'note' => implode(',', $note), // Chuyển đổi mảng thành chuỗi
                    'score' => $score,
                ];

                // **Lưu score theo điều kiện note**
                if (in_array(2, $note) || in_array(3, $note)) {
                    $transcriptDetailData['score'] = 0; // Lưu score bằng 0 nếu note bằng 2 hoặc 3
                }

                // **Thêm các thuộc tính khác vào transcriptDetailData**
                $transcriptDetailData = array_merge($transcriptDetailData, $otherAttributes);

                // Thêm dữ liệu của sinh viên vào mảng transcriptDetails
                $transcriptDetails[] = $transcriptDetailData;
            }
        }

        // Kiểm tra xem có bản ghi transcript_details bị bỏ trống không
        if (empty($transcriptDetails)) {
            return redirect()->route('transcript.index')->with('error', 'Một số học sinh chưa có điểm hãy thêm điểm cho họ.');
        }

        // Thêm nhiều bản ghi vào cơ sở dữ liệu
        TranscriptDetail::insert($transcriptDetails);

        // Chuyển hướng hoặc thông báo sau khi thêm thành công
        return redirect()->route('transcript.index')->with('success', 'Thêm điểm cho các học sinh thành công.');
    }



    public function stored(Request $request)
    {

        // Nhận dữ liệu từ request
        // Get divisions associated with the teacher
        $teacher = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;
        $divisions = Division::where('teacher_id', $teacherId)->pluck('id');

        // Lấy giá trị transcript_id từ request
        $transcriptId = $request->input('transcript_id');
        // Lấy các bản ghi transcript tương ứng với division_id và exam_type
        $transcripts = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->get();

        // Kiểm tra exam_type của transcript dựa trên transcript_id
        $examTypes = Transcript::where('id', $transcriptId)->value('exam_type');

        // Chỉ thêm bản ghi transcript_detail cho sinh viên có điểm dưới 5 và exam_type của transcript_id bằng 0
        if ($examTypes == 1) {
            // Lấy division_id từ transcript_id chỉ nếu nó thuộc danh sách division của giáo viên
            $divisionId = Transcript::whereIn('division_id', $divisions)
                ->where('id', $transcriptId)
                ->pluck('division_id')
                ->first();

            // Lấy chỉ một semester từ division_id
            $semester = Division::where('id', $divisionId)->value('semester');

            // Lấy exam_type từ transcript_id

            // Lấy danh sách sinh viên từ lớp học của transcript hiện tại
            $students = Student::whereIn('class_id', function ($query) use ($divisionId) {
                $query->select('class_id')
                    ->from('divisions')
                    ->where('id', $divisionId);
            })
                ->whereIn('id', function ($query) use ($divisionId) {
                    $query->select('student_id')
                        ->from('transcript_details')
                        ->whereIn('transcript_id', function ($subquery) use ($divisionId) {
                            $subquery->select('id')
                                ->from('transcripts')
                                ->whereIn('division_id', [$divisionId])
                                ->where('exam_type', 0);
                        })
                        ->where(function ($subquery) {
                            $subquery->where('score','<', 5)
                                ->orWhereNull('score');
                        });
                })
                ->get();

            // Tạo một mảng để chứa dữ liệu cần lưu vào cơ sở dữ liệu
            $transcriptDetails = [];

            // Lặp qua danh sách sinh viên và tạo dữ liệu cho mỗi sinh viên
            foreach ($students as $student) {

                $note = [];

                // Kiểm tra điểm số có hợp lệ không
                $score = $request->input('score_' . $student->id);
                if ($score < 0 || $score > 10) {
                    return redirect()->route('transcript.index')->with('error', 'Điểm nhỏ nhất là 0 và lớn nhất là 10');
                }

                // Kiểm tra checkbox đã được chọn hay không
                if ($request->has('note_' . $student->id)) {
                    // Lấy giá trị của checkbox
                    $note[] = $request->input('note_' . $student->id);
                }

                // Thêm dữ liệu của sinh viên vào mảng transcriptDetails
                $transcriptDetails[] = [
                    'transcript_id' => $transcriptId,
                    'student_id' => $student->id,
                    'note' => implode(',', $note), // Chuyển đổi mảng thành chuỗi
                    'score' => $score,
                ];
            }

            // Kiểm tra xem có bản ghi transcript_details bị bỏ trống không
            if (empty($note)) {
                return redirect()->route('transcript.index')->with('error', 'Một số học sinh chưa có điểm hãy thêm điểm cho họ.');
            }

            // Thêm nhiều bản ghi vào cơ sở dữ liệu
            TranscriptDetail::insert($transcriptDetails);
        }

        // Chuyển hướng hoặc thông báo sau khi thêm thành công
        return redirect()->route('transcript.index')->with('success', 'Thêm điểm cho các học sinh thành công.');
    }




    /**
     * Display the specified resource.
     */
    public function show()
    {
        $student = Auth::guard('student')->user(); // Lấy thông tin của sinh viên đang đăng nhập
        $studentId = $student->id;

        // Tạo một đối tượng TranscriptDetail
        $transcriptDetailModel = new TranscriptDetail();

        // Lấy danh sách transcript details liên quan đến sinh viên
        $transcriptDetails = $transcriptDetailModel->transcriptDetailsByStudent($studentId);

        return view('transdetail.show', [
            'transcriptDetails' => $transcriptDetails
        ]);
    }


    public function search(Request $request)
    {

        $keyword = $request->input('keyword');

        // Sử dụng Query Builder để tạo truy vấn tìm kiếm
        $results = TranscriptDetail::where(function ($query) use ($keyword) {
            $query->where('student_id', 'like', '%' . $keyword . '%')
                ->orWhere('grade_id', 'like', '%' . $keyword . '%')
                ->orWhere('class_id', 'like', '%' . $keyword . '%')
                ->orWhere('subject_id', 'like', '%' . $keyword . '%');
        })->get();

        return view('transdetail.show', compact('results'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TranscriptDetail $transcriptDetail, Request $request)
    {
        // Get the currently logged-in teacher
        $teacher = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;
        // Get divisions associated with the teacher
        $divisions = Division::where('teacher_id', $teacherId)->get();

        // Get transcripts associated with the divisions
        $transcripts = Transcript::whereIn('division_id', $divisions->pluck('id'))->get();
        // Get students with scores
        $students = Student::whereIn('class_id', $divisions->pluck('class_id'))->get();
        $classes = Classes::whereIn('id', $students->pluck('class_id'))
            ->with('school_year')
            ->get();

        $objTransDetail = new TranscriptDetail();
        $objTransDetail-> id = $request-> id;
        $transcript_details = $objTransDetail->edit();
//        dd($transcriptDetail);

        return view('transdetail.edit', [
            'transcripts' => $transcripts,
            'students' => $students,
            'classes' => $classes,
            'transcript_details' => $transcript_details,
            'id' => $objTransDetail->id
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTranscriptDetailRequest $request, TranscriptDetail $transcriptDetail)
    {
        $note = $request->note;
        $score = $request->score;

    if (($note == 2 || $note == 3) && !empty($score)) {
        // Nếu exam_type là 2 hoặc 3 và score không rỗng, hiển thị thông báo lỗi
    Session::flash('error', 'Score should be empty if this student get banned or skipped the exams.');
    } elseif (( $note == 1) && empty($score)) {
        // Nếu exam_type là 1 hoặc 0 và score rỗng, hiển thị thông báo lỗi
        Session::flash('error', 'This Student passed the exams but why he/she doesnt have point??.');
    } elseif ($score > 10 ){
        Session::flash('error', 'Maximum Score is 10.');
    }elseif ($score < 0 ){
        Session::flash('error', 'Minimum Score is 0.');
    } else {
        $obj = new TranscriptDetail();
        $obj->id = $request->id;
        $obj->transcript_id = $request->transcript_id;
        $obj->student_id = $request->student_id;
        $obj->note = $request->note;
        $obj->score = $request->score;
        $obj->updateTransDetail();
        Session::flash('success', 'Cập nhật điểm thành công');
    }

        return redirect()->route('transcript.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TranscriptDetail $transcriptDetail, Request $request)
    {
        $obj = new TranscriptDetail();
        $obj->id = $request-> id;
        $obj->deleteTransDetail();
        Session::flash('success', 'Xóa bản ghi');

        return redirect()->route('transcript.index');

    }
    public function query(Request $request)
    {
        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');
//            dump($classId, $subjectId);

        // Thực hiện truy vấn dựa trên $classId và $subjectId
        // Ví dụ:
        $result = DB::table('transcripts')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('transcript_details', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->where('divisions.class_id', $classId)
            ->where('divisions.subject_id', $subjectId)
//            ->select('transcripts.*', 'exam_type') // Chọn tất cả các cột từ bảng transcripts và thêm cột exam_type
            ->get();


        $studentCount = Student::count();
//        $reportCount = Report::where('status','=', 0)->count();
        $teacherCount = Teacher::count();
        $divisionCount = Division::count();


        $transcriptBelow5Count = TranscriptDetail::where('score', '<', 5)
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_type', 0)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        $transcriptAbove5Count = TranscriptDetail::where('score', '>=', 5)
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_type', 0)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();


        // Đếm số lượng transcript_details có điểm null và note = 2 trong class và subject đã chọn
        $transcriptNoScoreCount1 = TranscriptDetail::whereNull('score')
            ->where(function ($query) {
                $query->where('note', 2);
            })
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_type', 0)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 3 trong class và subject đã chọn
        $transcriptNoScoreCount2 = TranscriptDetail::whereNull('score')
            ->where(function ($query) {
                $query->where('note', 3);
            })
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_type', 0)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        $transcriptBelow5Count2nd = TranscriptDetail::where('score', '<', 5)
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_type', 1)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        $transcriptAbove5Count2nd = TranscriptDetail::where('score', '>=', 5)
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_type', 1)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 2 trong class và subject đã chọn
        $transcriptNoScoreCount12nd = TranscriptDetail::whereNull('score')
            ->where(function ($query) {
                $query->where('note', 2);
            })
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_type', 1)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 3 trong class và subject đã chọn
        $transcriptNoScoreCount22nd = TranscriptDetail::whereNull('score')
            ->where(function ($query) {
                $query->where('note', 3);
            })
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_type', 1)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        // Số lớp có sinh viên
        $classWithStudentsCount = Classes::has('student')->count();

        // Số lớp không có sinh viên
        $classWithoutStudentsCount = Classes::doesntHave('student')->count();


        return view('dashboard', [
            'studentCount' => $studentCount,
//            'reportCount' => $reportCount,
            'teacherCount' => $teacherCount,
            'divisionCount' => $divisionCount,
            'classWithStudentsCount' => $classWithStudentsCount,
            'classWithoutStudentsCount' => $classWithoutStudentsCount,
            'transcriptBelow5Count' => $transcriptBelow5Count,
            'transcriptAbove5Count' => $transcriptAbove5Count,
            'transcriptNoScoreCount1' => $transcriptNoScoreCount1,
            'transcriptNoScoreCount2' => $transcriptNoScoreCount2,
            'transcriptBelow5Count2nd' => $transcriptBelow5Count2nd,
            'transcriptAbove5Count2nd' => $transcriptAbove5Count2nd,
            'transcriptNoScoreCount12nd' => $transcriptNoScoreCount12nd,
            'transcriptNoScoreCount22nd' => $transcriptNoScoreCount22nd,
            'result' => $result,
            'abd' => $classId,
            'abc' => $subjectId
        ]);
    }

//    public function showReportForm()
//    {
//
//        $student = Auth::guard('student')->user(); // Lấy thông tin của sinh viên đang đăng nhập
//        $studentId = $student->id;
//
//        // Tạo một đối tượng TranscriptDetail
//        $transcriptDetailModel = new TranscriptDetail();
//
//        // Lấy danh sách transcript details liên quan đến sinh viên
//        $transcriptDetails = $transcriptDetailModel->transcriptDetailsByStudent($studentId);
//
//
//        return view('transdetail.report',['transcriptDetails' => $transcriptDetails]
//        );
//    }
//
//
//    public function submitReport(Request $request)
//    {
//        // Xử lý lưu khiếu nại vào cơ sở dữ liệu
//        // Hiển thị thông báo khi khiếu nại được gửi thành công
//        Session::flash('success', 'Report Has Been Sent');
//        return redirect()->route('transdetail.report');
//
//    }
}
