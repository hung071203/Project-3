<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $grades = Grade::all();
        $query = Teacher::query()
            ->select([
                'teachers.*',

            ]);

        // Thêm điều kiện tìm kiếm nếu có tên giáo viên được nhập
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('teacher_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%');

        }

        // Lấy số lượng giáo viên trên mỗi trang từ request hoặc sử dụng giá trị mặc định (ví dụ: 10)
        $perPage = $request->input('per_page', 10);

        // Thực hiện phân trang và lấy danh sách giáo viên
        $teachers = $query->paginate($perPage);

        // Truyền danh sách giáo viên và các thông tin phân trang vào view
        return view('teacher.index', [
            'teachers' => $teachers,
            'perPage' => $perPage,
            'grades' => $grades
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objgrade = new grade();
        $grade = $objgrade->index();
        return view('teacher.create', [
            'grades' => $grade
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        // Kiểm tra nếu email đã tồn tại
        $emailExists = Teacher::where('email', $request->input('email'))->exists();

        // Kiểm tra nếu số điện thoại đã tồn tại
        $phoneExists = Teacher::where('phone', $request->input('phone'))->exists();

        if ($emailExists || $phoneExists) {
            // Nếu email hoặc số điện thoại đã tồn tại, trả về thông báo lỗi
            Session::flash('error', 'Sdt hoặc Email đã tồn tại!');
            return Redirect::route('teacher.index');
        }

        $password = Hash::make($request->input('password'));

        // Lưu mật khẩu đã được mã hóa vào database
        DB::table('teachers')->insert([
            'teacher_name' => $request->input('teacher_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => $password,
        ]);

        Session::flash('success', 'Thêm mới thành công');
        return Redirect::route('teacher.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher, Request $request)
    {
        $objgrade = new grade();
        $grades = $objgrade->index();
        $objteacher = new Teacher();
        $objteacher->id = $request->id;
        $teachers = $objteacher->edit();
        return view('teacher.edit', [
            'grades' => $grades,
            'teachers' => $teachers,
            'id' => $objteacher->id
        ]);
    }

    public function showForgotPasswordForm()
    {
        return view('teacher.forgot-password');
    }



    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::exists('teachers')->where(function ($query) use ($request) {
                    return $query->where('phone', $request->phone);
                }),
            ],
            'phone' => 'required|exists:teachers,phone',
        ]);

        // Generate a random password
        $newPassword = Str::random(8);

        // Update user's password in the database
        $user = Teacher::where('email', $request->email)->first();
        $user->password = bcrypt($newPassword);
        $user->save();

        // Pass success message to the view
        return redirect()->route('teacher.forgotPasswords')->with('success', 'Password reset successful. New password: ' . $newPassword);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, $id)
    {
        // Mã hóa mật khẩu mới

        $password = Hash::make($request->input('password'));
        $email = $request->email;

        $emailexists = Teacher::where('email', $email)
            ->where('id', '!=', $id)
            ->first();

        $phoneExists = Teacher::where('phone', $request->input('phone'))
            ->where('id', '!=', $id)->exists();

        if($emailexists || $phoneExists){

            Session::flash('error', 'Email, sdt đã tồn tại hoặc thông tin giáo viên không có gì thay đổi.');
        }else {
            if($request->input('password') == ''){
                // Cập nhật thông tin sinh viên
                DB::table('teachers')->where('id', $id)->update([
                    'teacher_name' => $request->input('teacher_name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone')

                ]);
            }else{
                // Cập nhật thông tin sinh viên
                DB::table('teachers')->where('id', $id)->update([
                    'teacher_name' => $request->input('teacher_name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'password' => $password, // Lưu mật khẩu đã được mã hóa

                ]);
            }

//            dd($request->all());
            Session::flash('success', 'Cập nhật thành công');
        }
        return redirect()->route('teacher.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher, Request $request)
    {
        $obj = new Teacher();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteteacher();
            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Xóa thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Xóa thất bại. Vui lòng thử lại sau');
        }
        return Redirect::route('teacher.index');
    }

    public function login(){
        return view('teacher.login');
    }

    public function loginProcess(Request $request){
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
            Session::forget('admin');
        }
        if (Auth::guard('student')->check()){
            Auth::guard('student')->logout();
            Session::forget('student');
        }
        $accountteacher = $request->only('email', 'password');
        if (Auth::guard('teacher')->attempt($accountteacher)){

            $teacher = Auth::guard('teacher')->user();
            Auth::login($teacher);
            session(['teacher' =>  $teacher]);
            return Redirect::route('division.show');
        } else {
            Session::flash('error', 'Mật khẩu hoặc tài khoản không khớp!');
            return Redirect::back()->withInput();
        }
    }

    public function logout(){
        Auth::guard('teacher')->logout();
        Session::forget('teacher');

        return Redirect::route('teacher.login');
    }


//    public function profile()
//    {
//        $teacher = Auth::guard('teacher')->user();
//        return view('teacher.profile', compact('teacher'));
//    }

//    public function updateProfile(Request $request)
//    {
//        $teacher = Auth::guard('teacher')->user();
//
//        // Validation logic for the update form here
//        $request->validate([
//            'teacher_name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:teachers,email,' . $teacher->id,
//            'phone' => 'required|string|max:20',
//            'password' => 'nullable|string|min:6|confirmed',
//        ]);
//
//        // Update the teacher's information
//        $teacher->teacher_name = $request->input('teacher_name');
//        $teacher->email = $request->input('email');
//        $teacher->phone = $request->input('phone');
//
//        // Update password if it's provided
//        if ($request->input('password')) {
//            $teacher->password = Hash::make($request->input('password'));
//        }
//
//        $teacher->save();
//
//        return redirect()->route('teacher.profile')->with('success', 'Profile updated successfully!');
//    }
}
