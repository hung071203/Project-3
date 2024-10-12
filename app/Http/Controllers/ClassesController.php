<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreClassesRequest;
use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\grade;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Classes::query()
            ->join('grades', 'classes.grade_id', '=', 'grades.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->select([
                'classes.*',
                'grades.grade_name AS grade_name',
                'school_years.sy_start AS sy_start',
                'school_years.sy_end AS sy_end',
                'school_years.sy_name AS sy_name'
            ]);

        // Thêm điều kiện tìm kiếm nếu có tên lớp học hoặc năm học được nhập
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('class_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('sy_name', 'like', '%' . $searchTerm . '%');
        }

        $classes = $query->get();

        return view('class.index', [
            'classes' => $classes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objgrade = new grade();
        $grade = $objgrade->index();
        $objSchoolYear = new SchoolYear();
        $schoolYear = $objSchoolYear->index();
        return view('class.create', [
            'grades' => $grade,
            'school_years' => $schoolYear
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassesRequest $request)
    {
        $existingClasses = Classes::where('class_name', $request->class_name)
            ->where('grade_id', $request->grade_id)
            ->where('school_year_id', $request->school_year_id)
            ->exists();

        if ($existingClasses) {
            // Nếu graded_name đã tồn tại, bạn có thể trả về một thông báo lỗi hoặc chuyển hướng người dùng về trang tương ứng.
            // Ví dụ:
            Session::flash('error', 'Bản ghi đã tạitồn .');
            return Redirect::route('class.index');

        }
        $obj = new Classes();
        $obj->class_name = $request->class_name;
        $obj->grade_id = $request->grade_id;
        $obj->school_year_id = $request->school_year_id;
        $obj->store();
        Session::flash('success', 'Đã thêm thành công');
        return Redirect::route('class.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Classes $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classes $classes, Request $request)
    {
        $objgrade = new grade();
        $grades = $objgrade->index();
        $objSy = new SchoolYear();
        $school_years = $objSy->index();
        $objClass = new Classes();
        $objClass->id = $request->id;
        $classes = $objClass->edit();
        return view('class.edit', [
            'grades' => $grades,
            'school_years' => $school_years,
            'classes' => $classes,
            'id' => $objClass->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Classes $classes)
    {
        $existingClasses = Classes::where('class_name', $request->class_name)
            ->where('grade_id', $request->grade_id)
            ->where('school_year_id', $request->school_year_id)
            ->where('id', '!=', $request->id)
            ->exists();

        if ($existingClasses) {
            // Nếu graded_name đã tồn tại, bạn có thể trả về một thông báo lỗi hoặc chuyển hướng người dùng về trang tương ứng.
            // Ví dụ:
            Session::flash('error', 'bản ghi đã tồn tại.');
            return Redirect::route('class.index');
        }
        $obj = new Classes();
        $obj->id = $request->id;
        $obj->class_name = $request->class_name;
        $obj->grade_id = $request->grade_id;
        $obj->school_year_id = $request->school_year_id;
        $obj->updateClass();
            Session::flash('success', 'Chỉnh sửa thành công ');
        return Redirect::route('class.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $classes, Request $request)
    {
        $obj = new Classes();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteClass();
            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Xóa thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Xóa thất bại. Vui lòng thử lại sau');
        }
        return Redirect::route('class.index');
    }

}
//
//namespace App\Http\Controllers;
//
//use App\Models\Classes;
//use App\Http\Requests\StoreClassesRequest;
//use App\Http\Requests\UpdateClassesRequest;
//
//class ClassesController extends Controller
//{
//    /**
//     * Display a listing of the resource.
//     */
//    public function index()
//    {
//        $classes = Classes::all();
//        return view('class.index', [
//            '$classes' => $classes
//        ]);
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     */
//    public function create()
//    {
//        //
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     */
//    public function store(StoreClassesRequest $request)
//    {
//        //
//    }
//
//    /**
//     * Display the specified resource.
//     */
//    public function show(Classes $classes)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     */
//    public function edit(Classes $classes)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(UpdateClassesRequest $request, Classes $classes)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(Classes $classes)
//    {
//        //
//    }
//}
