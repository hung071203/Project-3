<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Division extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $fillable = ['semester', 'class_id', 'teacher_id', 'subject_id', 'admin_id', 'class_count'];

    public function index()
    {
        $division = DB::table('divisions')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->join('admins', 'divisions.admin_id', '=', 'admins.id')
            ->select([
                'divisions.*',
                'classes.class_name',
                'school_years.sy_name',
                'teachers.teacher_name AS teacher_name',
                'grades.grade_name AS grade_name',
                'subjects.subject_name AS subject_name',
                'admins.username AS username'
            ])
            ->get();

        return $division;
    }

    public function store()
    {
        DB::table('divisions')
            ->insert([
                'semester' => $this->semester,
                'class_id' => $this->class_id,
                'teacher_id' => $this->teacher_id,
                'subject_id' => $this->subject_id,
                'admin_id' => $this->admin_id
            ]);
    }

    public function edit()
    {
        $divisions = DB::table('divisions')
            ->where('id', $this->id)
            ->get();
        return $divisions;
    }

    public function updateDivision()
    {
        DB::table('divisions')
            ->where('id', $this->id)
            ->update([
                'semester' => $this->semester,
                'class_id' => $this->class_id,
                'admin_id' => $this->admin_id
            ]);
    }

    public function updateDivision2()
    {
        DB::table('divisions')
            ->where('id', $this->id)
            ->update([
                'semester' => $this->semester,
                'class_count' => $this->class_count,
                'admin_id' => $this->admin_id
            ]);
    }

    public function deleteDivision($request)
    {
        try {
            $deletedRows = DB::table('divisions')
                ->where('id', $request->id)
                ->delete();

            $arr = [];

            if ($deletedRows > 0) {
                $arr[] = true;
                $arr[] = 'Xóa thành công!';
            } else {
                $arr[] = false;
                $arr[] = 'Không tìm thấy bản ghi!';
            }

            return $arr;

        } catch (\Exception $e) {
            $arr[] = false;
            $arr[] = 'Đã xảy ra lỗi khi xóa bản ghi';
            return $arr;
        }
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function transcript()
    {
        return $this->hasMany(Transcript::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->join('teachers', 'divisions.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->join('admins', 'divisions.admin_id', '=', 'admins.id')
            ->where('divisions.admin_id', auth('admin')->user()->id)
            ->where(function($innerQuery) use ($searchTerm) {
                $innerQuery->where('teachers.teacher_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('grades.grade_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('subjects.subject_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('classes.class_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('school_years.sy_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('admins.username', 'like', '%' . $searchTerm . '%');
            })
            ->select([
                'divisions.*',
                'teachers.teacher_name AS teacher_name',
                'grades.grade_name AS grade_name',
                'subjects.subject_name AS subject_name',
                'classes.class_name AS class_name',
                'school_years.sy_name AS sy_name',
                'admins.username AS username'
            ]);
    }

    public function getStatus()
    {
        $tt = 2;
        $p15 = 2;
        $p45 = 1;
        $ck = 1;
        $transcripts = Transcript::where('division_id', $this->id)->get();

        // If there are no transcripts for this division, return 'Not Working'
        if ($transcripts->isEmpty()) {
            return 'Not Working';
        }

        foreach ($transcripts as $transcript) {
            $studentsCount = Student::where('class_id', $transcript->class_id)->count();
            $studentsWithNotes = TranscriptDetail::where('transcript_id', $transcript->id)
                ->whereIn('note', [1, 2, 3])
                ->count();

            // If not all students have the required notes, mark as Working
            if ($studentsWithNotes < $studentsCount) {
                return 'Working';
            }

            // If the transcript is not finished, mark as Working
            if (!$transcript->isFinish()) {
                return 'Working';
            }
        }

        // If all transcripts are finished and all students have required notes
        return 'Job Done';
    }

}








