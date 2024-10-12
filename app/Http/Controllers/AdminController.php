<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Classes;
use App\Models\Division;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\TranscriptDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obj = new Admin();
        $admin = $obj->index();
        return view('admin.index', [
            'admins' => $admin
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $password = Hash::make($request->input('password'));
        $username = $request->username;

        $usernameexists = Admin::where('username', $username)->first();

        if($usernameexists){

            Session::flash('error', 'This user already exists.');
        }else{

            // Lưu mật khẩu đã được mã hóa vào database
            DB::table('admins')->insert([
                'username' => $request->input('username'),
                'password' => $password,
            ]);
            Session::flash('success', 'Added New Admin');
        }
        return Redirect::route('admin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin, Request $request)
    {
        $objAdmin = new Admin();
        $objAdmin->id = $request->id;
        $admins = $objAdmin->edit();
        return view('admin.edit', [
            'admins' => $admins,
            'id' => $objAdmin->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, $id)
    {

        // Mã hóa mật khẩu mới
        $password = Hash::make($request->input('password'));

            // Cập nhật thông tin sinh viên
            DB::table('admins')->where('id', $id)->update([
                'password' => $password, // Lưu mật khẩu đã được mã hóa
            ]);
            Session::flash('success', 'Updated Record');

        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin, Request $request)
    {
        $obj = new Admin();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteAdmin();

            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Deleted Record');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Failed to delete the record. Please try again later.');
        }

        return Redirect::route('admin.index');
    }

    public function login(){
        return view('admin.login');
    }

    public function loginProcess(Request $request){

        if (Auth::guard('student')->check()){
            Auth::guard('student')->logout();
            Session::forget('student');
        }
        if (Auth::guard('teacher')->check()){
            Auth::guard('teacher')->logout();
            Session::forget('teacher');
        }
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


//        $transcriptBelow5Count = TranscriptDetail::where('score', '<', 5)
//            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
//                $query->where('exam_type', 0)
//                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
//                        $query->where('class_id', $classId)
//                            ->whereHas('subject', function ($query) use ($subjectId) {
//                                $query->where('id', $subjectId);
//                            });
//                    });
//            })
//            ->count();

//        $transcriptAbove5Count = TranscriptDetail::where('score', '>=', 5)
//            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
//                $query->where('exam_type', 0)
//                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
//                        $query->where('class_id', $classId)
//                            ->whereHas('subject', function ($query) use ($subjectId) {
//                                $query->where('id', $subjectId);
//                            });
//                    });
//            })
//            ->count();


//        // Đếm số lượng transcript_details có điểm null và note = 2 trong class và subject đã chọn
//        $transcriptNoScoreCount1 = TranscriptDetail::whereNull('score')
//            ->where(function ($query) {
//                $query->where('note', 2);
//            })
//            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
//                $query->where('exam_type', 0)
//                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
//                        $query->where('class_id', $classId)
//                            ->whereHas('subject', function ($query) use ($subjectId) {
//                                $query->where('id', $subjectId);
//                            });
//                    });
//            })
//            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 3 trong class và subject đã chọn
//        $transcriptNoScoreCount2 = TranscriptDetail::whereNull('score')
//            ->where(function ($query) {
//                $query->where('note', 3);
//            })
//            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
//                $query->where('exam_type', 0)
//                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
//                        $query->where('class_id', $classId)
//                            ->whereHas('subject', function ($query) use ($subjectId) {
//                                $query->where('id', $subjectId);
//                            });
//                    });
//            })
//            ->count();

//        $transcriptBelow5Count2nd = TranscriptDetail::where('score', '<', 5)
//            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
//                $query->where('exam_type', 1)
//                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
//                        $query->where('class_id', $classId)
//                            ->whereHas('subject', function ($query) use ($subjectId) {
//                                $query->where('id', $subjectId);
//                            });
//                    });
//            })
//            ->count();

//        $transcriptAbove5Count2nd = TranscriptDetail::where('score', '>=', 5)
//            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
//                $query->where('exam_type', 1)
//                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
//                        $query->where('class_id', $classId)
//                            ->whereHas('subject', function ($query) use ($subjectId) {
//                                $query->where('id', $subjectId);
//                            });
//                    });
//            })
//            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 2 trong class và subject đã chọn
//        $transcriptNoScoreCount12nd = TranscriptDetail::whereNull('score')
//            ->where(function ($query) {
//                $query->where('note', 2);
//            })
//            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
//                $query->where('exam_type', 1)
//                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
//                        $query->where('class_id', $classId)
//                            ->whereHas('subject', function ($query) use ($subjectId) {
//                                $query->where('id', $subjectId);
//                            });
//                    });
//            })
//            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 3 trong class và subject đã chọn
//        $transcriptNoScoreCount22nd = TranscriptDetail::whereNull('score')
//            ->where(function ($query) {
//                $query->where('note', 3);
//            })
//            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
//                $query->where('exam_type', 1)
//                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
//                        $query->where('class_id', $classId)
//                            ->whereHas('subject', function ($query) use ($subjectId) {
//                                $query->where('id', $subjectId);
//                            });
//                    });
//            })
//            ->count();

        // Số lớp có sinh viên
        $classWithStudentsCount = Classes::has('student')->count();

        // Số lớp không có sinh viên
        $classWithoutStudentsCount = Classes::doesntHave('student')->count();
        $accountAdmin = $request->only('username', 'password');
        if (Auth::guard('admin')->attempt($accountAdmin)){
            $admin = Auth::guard('admin')->user();
            Auth::login($admin);
            session(['admin' =>  $admin]);
            return view('dashboard', [
                'studentCount' => $studentCount,
//            'reportCount' => $reportCount,
                'teacherCount' => $teacherCount,
                'divisionCount' => $divisionCount,
                'classWithStudentsCount' => $classWithStudentsCount,
                'classWithoutStudentsCount' => $classWithoutStudentsCount,
//                'transcriptBelow5Count' => $transcriptBelow5Count,
//                'transcriptAbove5Count' => $transcriptAbove5Count,
//                'transcriptNoScoreCount1' => $transcriptNoScoreCount1,
//                'transcriptNoScoreCount2' => $transcriptNoScoreCount2,
//                'transcriptBelow5Count2nd' => $transcriptBelow5Count2nd,
//                'transcriptAbove5Count2nd' => $transcriptAbove5Count2nd,
//                'transcriptNoScoreCount12nd' => $transcriptNoScoreCount12nd,
//                'transcriptNoScoreCount22nd' => $transcriptNoScoreCount22nd,
                'result' => $result,
                'abd' => $classId,
                'abc' => $subjectId
            ]);
        } else {
            Session::flash('error', 'Mật khẩu hoặc tài khoản không khớp!');
            return Redirect::back()->withInput();
        }
    }

    public function logout(){
        Auth::guard('admin')->logout();
        Session::forget('admin');

        return Redirect::route('admin.login');
    }




    public function showFormChangePass(){
        if(Auth::guard('admin')->check() || Auth::guard('student')->check() || Auth::guard('teacher')->check())
            return view('password');
        else{
            return view('admin.login');
        }


    }

    public function changePassProcess(Request $request){

        $oldpass = $request->oldPass;
        $newpass = $request ->newPass;
        $renew = $request->reNew;
        if(Auth::guard('admin')->check()){
            $admin = Auth::guard('admin')->user();
            if(Hash::check($oldpass, $admin->password)){
                if($newpass != $renew){
                    Session::flash('error', 'Mật khẩu mới không trùng khớp');
                    return view('password');
                }
                if($oldpass == $newpass){
                    Session::flash('error', 'Mật khẩu mới đã sử được sử dụng!');
                    return view('password');
                }

                $checkAD = new Admin();
                $change = $checkAD->changePass($admin->id, $newpass);
                if($change == 'error'){
                    Session::flash('error', 'Đổi mật khẩu thất bại!');
                }else{
                    $admin->password = $change;
                    Session::flash('success', 'Mật khẩu đã thay đổi!');
                }
                return view('password');
            }else{
                Session::flash('error', 'Mật khẩu hiện tại không khớp!');
                return view('password');
            }

        }elseif (Auth::guard('teacher')->check()){
            $admin = Auth::guard('teacher')->user();
            if(Hash::check($oldpass, $admin->password)){
                if($newpass != $renew){
                    Session::flash('error', 'Mật khẩu mới không trùng khớp');
                    return view('password');
                }
                if($oldpass == $newpass){
                    Session::flash('error', 'Mật khẩu mới đã sử được sử dụng!');
                    return view('password');
                }

                $checkAD = new Teacher();
                $change = $checkAD->changePass($admin->id, $newpass);
                if($change == 'error'){
                    Session::flash('error', 'Đổi mật khẩu thất bại!');
                }else{
                    $admin->password = $change;
                    Session::flash('success', 'Mật khẩu đã thay đổi!');
                }
                return view('password');
            }else{
                Session::flash('error', 'Mật khẩu hiện tại không khớp!');
                return view('password');
            }
        }elseif (Auth::guard('student')->check()){
            $admin = Auth::guard('student')->user();
            if(Hash::check($oldpass, $admin->password)){
                if($newpass != $renew){
                    Session::flash('error', 'Mật khẩu mới không trùng khớp');
                    return view('password');
                }
                if($oldpass == $newpass){
                    Session::flash('error', 'Mật khẩu mới đã sử được sử dụng!');
                    return view('password');
                }

                $checkAD = new Student();
                $change = $checkAD->changePass($admin->id, $newpass);
                if($change == 'error'){
                    Session::flash('error', 'Đổi mật khẩu thất bại!');
                }else{
                    $admin->password = $change;
                    Session::flash('success', 'Mật khẩu đã thay đổi!');
                }
                return view('password');
            }else{
                Session::flash('error', 'Mật khẩu hiện tại không khớp!');
                return view('password');
            }
        }

    }

    public function profile(){
        if(Auth::guard('admin')->check() || Auth::guard('student')->check() || Auth::guard('teacher')->check())
            return view('user');
        else{
            return view('admin.login');
        }
    }

    public function updateProfile(Request $request){

    }

}
