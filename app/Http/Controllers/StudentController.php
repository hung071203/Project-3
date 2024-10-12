<?php


namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Transcript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $classes = Classes::all();
        $obj = new Student();
        $query = $obj->index();

        // Thêm điều kiện tìm kiếm nếu có tên sinh viên được nhập
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('student_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('email','like','%'. $searchTerm . '%')
                ->orWhere('class_name','like','%'. $searchTerm . '%');
        }
        if ($request->has('class_id')) {
            $classId = $request->input('class_id');
            $query->where('class_id', $classId);
        }
        // Lấy số lượng sinh viên trên mỗi trang từ request hoặc sử dụng giá trị mặc định (ví dụ: 10)
        $perPage = $request->input('per_page', 10);

        // Thực hiện phân trang và lấy danh sách sinh viên
        $students = $query->paginate($perPage);

        // Truyền danh sách sinh viên và các thông tin phân trang vào view
        return view('student.index', [
            'students' => $students,
            'perPage' => $perPage,
            'classes' => $classes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objClass = new Classes();
        $class = $objClass->index();
        return view('student.create', [
            'classes' => $class
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $password = Hash::make($request->input('password'));
        $email = $request->email;

        $emailexists = Student::where('email', $email)->exists();
        $sdtexists = Student::where('phone', $request->input('phone'))->exists();

        if($emailexists || $sdtexists){

            Session::flash('error', 'Email hoặc sdt đã tồn tại.');
        }else{

        // Lưu mật khẩu đã được mã hóa vào database
        DB::table('students')->insert([
            'student_name' => $request->input('student_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => $password,
            'class_id' => $request->input('class_id'),
        ]);
        Session::flash('success', 'Thêm mới học sinh thành công');
        }
        return Redirect::route('student.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student, Request $request)
    {
        $objClass = new Classes();
        $classes = $objClass->index();
        $objStudent = new Student();
        $objStudent->id = $request->id;
        $students = $objStudent->edit();
        return view('student.edit', [
            'classes' => $classes,
            'students' => $students,
            'id' => $objStudent->id
        ]);
    }

    public function showForgotPasswordForm()
    {
        return view('student.forgot-password');
    }



    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::exists('students')->where(function ($query) use ($request) {
                    return $query->where('phone', $request->phone);
                }),
            ],
            'phone' => 'required|exists:students,phone',
        ]);

        // Generate a random password
        $newPassword = Str::random(8);

        // Update user's password in the database
        $user = Student::where('email', $request->email)->first();
        $user->password = bcrypt($newPassword);
        $user->save();

        // Pass success message to the view
        return redirect()->route('student.forgotPassword')->with('success', 'Password reset successful. New password: ' . $newPassword);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        // Mã hóa mật khẩu mới
        $password = Hash::make($request->input('password'));
        $email = $request->email;

        // Tìm bản ghi sinh viên ngoại trừ bản ghi đang được cập nhật
        $emailexists = Student::where('email', $email)
            ->where('id', '!=', $id)
            ->exists();

        $sdtexists = Student::where('phone', $request->input('phone'))->where('id', '!=', $id)->exists();

        if($emailexists || $sdtexists){
            Session::flash('error', 'Email hoặc sdt đã tồn tại.');
        } else {
            if($request->input('password') == ''){
                DB::table('students')->where('id', $id)->update([
                    'student_name' => $request->input('student_name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'class_id' => $request->input('class_id'),
                ]);
            }else{
                // Cập nhật thông tin sinh viên
                DB::table('students')->where('id', $id)->update([
                    'student_name' => $request->input('student_name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'password' => $password, // Lưu mật khẩu đã được mã hóa
                    'class_id' => $request->input('class_id'),
                ]);
            }

            Session::flash('success', 'Cập nhật thành công');
        }
        return redirect()->route('student.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student, Request $request)
    {
        $obj = new Student();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteStudent();

            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Xóa thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'ta là tà đế chap 482');
        }

        return Redirect::route('student.index');
    }

 public function login(){
    return view('student.login');
}

    public function loginProcess(Request $request){
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
            Session::forget('admin');
        }
        if (Auth::guard('teacher')->check()){
            Auth::guard('teacher')->logout();
            Session::forget('teacher');
        }
        $accountStudent = $request->only('email', 'password');
        if (Auth::guard('student')->attempt($accountStudent)){

            $student = Auth::guard('student')->user();
            Auth::login($student);
            session(['student' =>  $student]);
            return Redirect::route('transdetail.show');
        } else {
            Session::flash('error', 'Mật khẩu hoặc tài khoản không khớp!');
            return Redirect::back()->withInput();
        }
    }

    public function logout(){
        Auth::guard('student')->logout();
        Session::forget('student');

        return Redirect::route('student.login');
    }

}

////
//
//
//namespace App\Http\Controllers;
//
//use App\Models\Classes;
//use App\Models\Student;
//use App\Http\Requests\StoreStudentRequest;
//use App\Http\Requests\UpdateStudentRequest;
//use Illuminate\Support\Facades\Redirect;
//
//class StudentController extends Controller
//{
//    /**
//     * Display a listing of the resource.
//     */
//    public function index()
//    {
//        $students = Student::with('class')->get();
//        return view('student.index', [
//            'students' => $students
//        ]);
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     */
//    public function create()
//    {
//        $classes = Classes::all();
//        return view('student.create', [
//            'classes' => $classes
//        ]);
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     */
//    public function store(StoreStudentRequest $request)
//    {
//        Student::create($request->all());
//        return Redirect::route('student.index');
//    }
//
//    /**
//     * Display the specified resource.
//     */
//    public function show(Student $student)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     */
//    public function edit(Student $student)
//    {
//        $classes = Classes::all();
//        return view('student.edit', [
//            'student' => $student,
//            'classes' => $classes
//        ]);
//    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(UpdateStudentRequest $request, Student $student)
//    {
//        $student->update($request->all());
//        return Redirect::route('student.index');
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(Student $student)
//    {
//        $student->delete();
//        return Redirect::route('student.index');
//    }
//}

//namespace App\Http\Controllers;
//
//use App\Models\Classes;
//use App\Models\Student;
//use App\Http\Requests\StoreStudentRequest;
//use App\Http\Requests\UpdateStudentRequest;
//use Illuminate\Support\Facades\Redirect;
//
//class StudentController extends Controller
//{
//    /**
//     * Display a listing of the resource.
//     */
//    public function index()
//    {
//        $students = Student::with('class')->get();
//        return view('student.index', [
//            'students' => $students
//        ]);
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     */
//    public function create()
//    {
//        $classes = Classes::all();
//        return view('student.create', [
//            'classes' =>$classes
//        ]);
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     */
//    public function store(StoreStudentRequest $request)
//    {
//        Student::create($request->all());
//        return Redirect::route('student.index');
//    }
//
//    /**
//     * Display the specified resource.
//     */
//    public function show(Student $student)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     */
//    public function edit(Student $student)
//    {
//        $classes = Classes::all();
//        return view('student.edit', [
//            'student' => $student,
//            'classes' => $classes
//        ]);
//    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(UpdateStudentRequest $request, Student $student)
//    {
//        $student->update($request->all());
//        return Redirect::route('student.index');
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(Student $student)
//    {
//        $student->delete();
//        return Redirect::route('student.index');
//    }
//}
