<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\grade;
//use App\Http\Requests\StoreGradeRequest;
//use App\Http\Requests\UpdateGradeRequest;
//use Illuminate\Support\Facades\Redirect;
//
//class GradeController extends Controller
//{
//    /**
//     * Display a listing of the resource.
//     */
//    public function index()
//    {
//        $grades = grade::all();
//        return view('graded.index', [
//            'grades' => $grades
//        ]);
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     */
//    public function create()
//    {
//        return view('graded.create');
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     */
//    public function store(StoreGradeRequest $request)
//    {
//        $data = $request->all();
//        grade::create($data);
//        return Redirect::route('graded.index');
//    }
//
//    /**
//     * Display the specified resource.
//     */
//    public function show(grade $grade)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     */
//    public function edit(grade $grade)
//    {
//        return view('graded.edit', [
//            'grade' => $grade
//        ]);
//    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(UpdateGradeRequest $request, grade $grade)
//    {
//        $data = $request->all();
//        $grade->update($data);
//        return Redirect::route('graded.index');
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(grade $grade)
//    {
//        $grade->delete();
//        return Redirect::route('graded.index');
//    }
//}


namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Models\Classes;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $searchTerm = $request->input('search');

        $grades = Grade::when($searchTerm, function ($query, $searchTerm) {
            return $query->search($searchTerm);
        })->get();

        // Eager load the 'students' relationship to avoid N+1 query issue
        $grades->load('students');

        return view('grade.index', [
            'grades' => $grades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('grade.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGradeRequest $request)
    {
        $existinggrade = Grade::where('grade_name', $request->grade_name)->exists();

        if ($existinggrade) {
            // Nếu grade_name đã tồn tại, bạn có thể trả về một thông báo lỗi hoặc chuyển hướng người dùng về trang tương ứng.
            // Ví dụ:
            Session::flash('error', 'Bản ghi đã tồn tại.');
            return Redirect::route('grade.index');

        }

        // Nếu graded_name không tồn tại, bạn có thể tiếp tục lưu dữ liệu vào database.
        $obj = new Grade();
        $obj->grade_name = $request->grade_name;
        $obj->store();

        Session::flash('success', 'Thêm mới thành công!');
        return Redirect::route('grade.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     */
    public function edit(Grade $grades, \Illuminate\Http\Request $request)
    {

        $obj = new Grade();
        $obj->id = $request->id;

        $grades = $obj->edit();

        return view('grade.edit', [
            'grades' => $grades
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGradeRequest $request, grade $grades)
    {
        $existinggrade = grade::where('grade_name', $request->grade_name)->exists();

        if ($existinggrade) {
            // Nếu graded_name đã tồn tại, bạn có thể trả về một thông báo lỗi hoặc chuyển hướng người dùng về trang tương ứng.
            // Ví dụ:
            Session::flash('error', 'Bản ghi đã tồn tại!  .');
            return Redirect::route('grade.index');

        }
        $obj = new Grade();
        $obj->id = $request->id;
        $obj->grade_name = $request->grade_name;
        $obj->updateGrade();
        Session::flash('success', 'Chỉnh sửa thành công!');
        return Redirect::route('grade.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grades, \Illuminate\Http\Request $request)
    {
        $obj = new Grade();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->destroyGrade();

            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Xóa thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Xóa thất bại. Vui lòng thử lại sau');
        }
        return Redirect::route('grade.index');
    }

    public function checkClass($id)
    {
        // Lấy thông tin graded dựa trên ID
        $grade = Grade::findOrFail($id);

        // Lấy danh sách lớp học thuộc graded đó
        $classes = Classes::where('grade_id', $id)->get();

        // Lấy danh sách sinh viên thuộc các lớp đó
        $students = Student::whereIn('class_id', $classes->pluck('id'))->get();

        return view('grade.checkClass', [
            'grade' => $grade,
            'classes' => $classes,
            'students' => $students,
        ]);
    }

    public function checkStudentFromClass($class_id)
    {
        // Get the class based on the class_id
        $class = Classes::findOrFail($class_id);

        // Get the students for the specific class
        $students = Student::where('class_id', $class_id)->get();

        return view('grade.checkStudent', [
            'class' => $class,
            'students' => $students,
        ]);
    }


}
