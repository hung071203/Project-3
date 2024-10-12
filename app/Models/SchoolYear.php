<?php

//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//
//class SchoolYear extends Model
//{
//    use HasFactory;
//    public $timestamps = false;
//    protected $fillable = ['sy_number'];
//    public function classes(){
//        return $this -> hasMany(Classes::class);
//    }
//}
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SchoolYear extends Model
{
    public $timestamps = false;
    use HasFactory;



    public function index()
    {
        $schoolyears = DB::table('school_years')->get();
        return $schoolyears;
    }

    public function store()
    {
        DB::table('school_years')
            ->insert([
                'sy_start' => $this->sy_start,
                'sy_end' => $this->sy_end,
                'sy_name' => $this->sy_name
            ]);
    }

    public function edit()
    {
        $school_year = DB::table('school_years')
            ->where('id', $this->id)
            ->get();
        return $school_year;
    }

    public function updateSchoolYear()
    {
        DB::table('school_years')
            ->where('id', $this->id)
            ->update([
                'sy_start' => $this->sy_start,
                'sy_end' => $this->sy_end,
                'sy_name' => $this->sy_name

            ]);
    }

    public function destroySchoolYear()
    {
        DB::table('school_years')
            ->where('id', $this->id)
            ->delete();

    }
        public function classes(){
        return $this -> hasMany(Classes::class);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('sy_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('sy_number', 'like', '%' . $searchTerm . '%');
    }
}

