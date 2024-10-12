<?php

namespace App\Http\Controllers;

use App\Models\grade;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::query()
            ->join('grades', 'subjects.grade_id', '=', 'grades.id')
            ->select([
                'subjects.*',
                'grades.grade_name AS grade_name'
            ]);

        // Thêm điều kiện tìm kiếm nếu có tên lớp học hoặc năm học được nhập
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('subject_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('grade_name', 'like', '%' . $searchTerm . '%');
        }

        $subjects = $query->get();

        return view('subject.index', [
            'subjects' => $subjects
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $objgrade = new grade();
        $grade = $objgrade->index();
        return view('subject.create', [
            'grades' => $grade
        ]);
//        $grades = grade::all();
//        return view('subject.create', [
//            'grades' =>$grades
//        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $subjectName = $request->subject_name;
        $selectedGrades = $request->input('grade_id', []);

        if (empty($selectedGrades)) {
            Session::flash('error', 'Bạn phải chọn ít nhất một khối.');
            return Redirect::route('subject.index');
        }

        foreach ($selectedGrades as $gradeId) {
            // Kiểm tra xem môn học đã tồn tại chưa
            $existingSubject = Subject::where('subject_name', $subjectName)
                ->where('grade_id', $gradeId)
                ->exists();

            if ($existingSubject) {
                // Nếu môn học đã tồn tại, kiểm tra xem đã có kỳ 1 hay kỳ 2 chưa
                $hasSemester1 = Subject::where('subject_name', $subjectName)
                    ->where('grade_id', $gradeId)
                    ->where('semester', 1)
                    ->exists();

                $hasSemester2 = Subject::where('subject_name', $subjectName)
                    ->where('grade_id', $gradeId)
                    ->where('semester', 2)
                    ->exists();

                if (!$hasSemester1) {
                    // Nếu chưa có kỳ 1, thêm môn học với kỳ 1
                    $subject1 = new Subject();
                    $subject1->subject_name = $subjectName;
                    $subject1->semester = 1; // Học kỳ 1
                    $subject1->grade_id = $gradeId;
                    $subject1->save();
                }

                if (!$hasSemester2) {
                    // Nếu chưa có kỳ 2, thêm môn học với kỳ 2
                    $subject2 = new Subject();
                    $subject2->subject_name = $subjectName;
                    $subject2->semester = 2; // Học kỳ 2
                    $subject2->grade_id = $gradeId;
                    $subject2->save();
                }
            } else {
                // Nếu môn học chưa tồn tại, thêm môn học với cả kỳ 1 và kỳ 2
                $subject1 = new Subject();
                $subject1->subject_name = $subjectName;
                $subject1->semester = 1; // Học kỳ 1
                $subject1->grade_id = $gradeId;
                $subject1->save();

                $subject2 = new Subject();
                $subject2->subject_name = $subjectName;
                $subject2->semester = 2; // Học kỳ 2
                $subject2->grade_id = $gradeId;
                $subject2->save();
            }
        }

        Session::flash('success', 'Thêm mới thành công cho khối đã chọn.');
        return Redirect::route('subject.index');
    }



    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject, Request $request)
    {
        $objgrade = new grade();
        $grades = $objgrade->index();
        $objSubject = new Subject();
        $objSubject->id = $request->id;
        $subjects = $objSubject->edit();
        return view('subject.edit', [
            'grades' => $grades,
            'subjects' => $subjects,
            'id' => $objSubject->id
        ]);
//        $grades = grade::all();
//        return view('subject.edit', [
//            'subject' => $subject,
//            'grades' => $grades
//        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $existingSubject = Subject::where('subject_name', $request->subject_name)
            ->where('semester', $request->semester)
            ->where('grade_id', $request->grade_id)
//            ->where('text_book', $request->text_book)
            ->where('id', '!=', $subject->id)  // Loại trừ bản ghi hiện tại
            ->exists();
        if ($existingSubject) {
            Session::flash('error', 'Môn học đã tồn tại.');
            return Redirect::route('subject.index');

        }
        $obj = new Subject();
        $obj->id = $request->id;
        $obj->subject_name = $request->subject_name;
        $obj->semester = $request->semester;
//        $obj->text_book = $request->text_book;
        $obj->grade_id = $request->grade_id;
        $obj->updateSubject();
        Session::flash('success', 'Cập nhật môn học thành công ');
        return Redirect::route('subject.index');
//        $subject->update($request->all());
//        return Redirect::route('subject.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject, Request $request)
    {
        $obj = new Subject();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteSubject();

            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Xóa thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Xóa thất bại. Vui lòng thử lại sau');
        }

        return Redirect::route('subject.index');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('delete_ids');

        if (!empty($ids)) {
            Subject::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Đã xóa các môn học đã chọn thành công.');
        } else {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một môn học để xóa.');
        }
    }


//        $subject->delete();
//        return Redirect::route('subject.index');

}
