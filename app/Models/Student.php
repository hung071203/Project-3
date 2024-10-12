<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//
//class Student extends Model
//{
//    use HasFactory;
//    public $timestamps = false;
//    protected $fillable = ['student_name', 'email', 'phone', 'password', 'class_id'];
//    public function class(){
//        return $this->belongsTo(Classes::class);
//    }
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class Student extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use Authenticatable;
    use HasFactory;
    public $timestamps = false;

    public function index()
    {
        $query = DB::table('students')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->select([
                'students.*',
                'classes.class_name AS class_name',
                'school_years.sy_start AS sy_start',
                'school_years.sy_end AS sy_end',
                'school_years.sy_name AS sy_name'
            ]);

        return $query;
    }


    public function store()
    {
        DB::table('students')
            ->insert([
                'student_name' => $this->student_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => $this->password,
                'class_id' => $this->class_id

            ]);
    }

    public function edit()
    {
        $students = DB::table('students')
            ->where('id', $this->id)
            ->get();
        return $students;
    }

    public function updateStudent()
    {
        DB::table('students')
            ->where('id', $this->id)
            ->update([
                'student_name' => $this->student_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => $this->password,
                'class_id' => $this->class_id
            ]);
    }

    public function deleteStudent()
    {
        DB::table('students')
            ->where('id', $this->id)
            ->delete();
    }

    public function class()
    {
        return $this->belongsTo(Classes::class,'id', 'id');
    }
    public function transcriptDetails()
    {
        return $this->hasMany(TranscriptDetail::class, 'student_id', 'id');
    }

    public function changePass($id, $pass){
        // Mã hóa mật khẩu mới
        $hashedPass = Hash::make($pass);

        // Cập nhật mật khẩu trong cơ sở dữ liệu
        $updated = DB::table('students')
            ->where('id', $id)
            ->update(['password' => $hashedPass]);

        // Kiểm tra xem việc cập nhật có thành công không
        if ($updated) {
            return $hashedPass;
        } else {
            return 'error';
        }
    }
}



