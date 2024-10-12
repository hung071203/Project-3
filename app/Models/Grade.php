<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//
//class Grade extends Model
//{
//    use HasFactory;
//    public $timestamps = false;
//    protected $fillable = ['grade_name'];
//    public function subject(){
//        return $this->hasMany(Subject::class);
//    }
//    public function classes(){
//        return $this -> hasMany(Classes::class);
//    }
//    public function teacher(){
//        return $this -> hasMany(Teacher::class);
//    }
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Grade extends Model
{
    public $timestamps = false;
    use HasFactory;


    public function index()
    {

        $grade = DB::table('grades')->get();
        return $grade;
    }

    public function store()
    {
        DB::table('grades')
            ->insert([
                'grade_name' => $this->grade_name,
            ]);
    }

    public function edit()
    {
        $grade = DB::table('grades')
            ->where('id', $this->id)
            ->get();
        return $grade;
    }

    public function updateGrade()
    {
        DB::table('grades')
            ->where('id', $this->id)
            ->update([
                'grade_name' => $this->grade_name,

            ]);
    }

    public function destroyGrade()
    {
        DB::table('grades')
            ->where('id', $this->id)
            ->delete();

    }
    public function students()
    {
        // Define the relationship between Grade and Student through Classes
        return $this->hasManyThrough(
            Student::class,
            Classes::class,
            'grade_id', // Foreign key on Classes table
            'class_id',       // Foreign key on Student table
            'id',             // Local key on Grade table
            'id'              // Local key on Classes table
        );
    }
    public function subject(){
        return $this->hasMany(Subject::class);
    }
    public function teacher(){
        return $this -> hasMany(Teacher::class);
    }
    public function classes(){
        return $this -> hasMany(Classes::class);
    }

//    public function getNumberOfteacher()
//    {
//        return $this->hasMany(Teacher::class, 'grade_id')->count();
//    }
    public function getNumberOfSubject()
    {
        return $this->hasMany(Subject::class, 'grade_id')->count();
    }
    public function getNumberOfClass()
    {
        return $this->hasMany(Classes::class, 'grade_id')->count();
    }
    public function getNumberOfStudent()
    {
        return $this->students->count();
    }
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('grade_name', 'like', '%' . $searchTerm . '%');
    }
}
