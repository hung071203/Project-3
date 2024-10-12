<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Admin extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use HasFactory;
    use Authenticatable;
    protected $fillable = ['username', 'password'];
    public $timestamps = false;
    public function index()
    {

        $admin = DB::table('admins')->get();
        return $admin;
    }

    public function edit()
    {
        $admins = DB::table('admins')
            ->where('id', $this->id)
            ->get();
        return $admins;
    }

    public function changePass($id, $pass){
        // Mã hóa mật khẩu mới
        $hashedPass = Hash::make($pass);

        // Cập nhật mật khẩu trong cơ sở dữ liệu
        $updated = DB::table('admins')
            ->where('id', $id)
            ->update(['password' => $hashedPass]);

        // Kiểm tra xem việc cập nhật có thành công không
        if ($updated) {
            return $hashedPass;
        } else {
            return 'error';
        }
    }

    public function deleteAdmin()
    {
        DB::table('admins')
            ->where('id', $this->id)
            ->delete();
    }
}
