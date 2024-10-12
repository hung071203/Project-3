<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    use HasFactory;

    public $timestamps = false;
    public function index()
    {
        $query = DB::table('subjects')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->select(['subjects.*',
                'grades.grade_name AS grade_name'
            ])
            ->get();

        return $query;
    }
    public function store()
    {
        DB::table('subjects')
            ->insert([
                'subject_name' => $this->subject_name,
                'semester' => $this->semester,
//                'text_book' => $this->text_book,
                'grade_id' => $this->grade_id
            ]);
    }

    public function edit()
    {
        $subjects = DB::table('subjects')
            ->where('id', $this->id)
            ->get();
        return $subjects;
    }

    public function updateSubject()
    {
        DB::table('subjects')
            ->where('id', $this->id)
            ->update([
                'subject_name' => $this->subject_name,
                'semester' => $this->semester,
//                'text_book' => $this->text_book,
                'grade_id' => $this->grade_id
            ]);
    }

    public function deleteSubject()
    {
        DB::table('subjects')
            ->where('id', $this->id)
            ->delete();
    }
//    protected $fillable = ['subject_name', 'grade_id'];
    public function grade(){
        return $this->belongsTo(Grade::class);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('subject_name', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('grades', function ($query) use ($searchTerm) {
                    $query->where('grade_name', 'like', '%' . $searchTerm . '%');
                });
        });
    }


}
