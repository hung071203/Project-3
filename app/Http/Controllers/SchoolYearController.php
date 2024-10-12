<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\SchoolYear;
use App\Http\Requests\StoreSchoolYearRequest;
use App\Http\Requests\UpdateSchoolYearRequest;
use http\Env\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $searchTerm = $request->input('search');

        $school_year = SchoolYear::when($searchTerm, function ($query, $searchTerm) {
            return $query->search($searchTerm);
        })->get();
        return view('sy.index', [
            'school_years' => $school_year
        ]);
    }
//        $school_years = SchoolYear::all();
//        return view('sy.index', [
//            'school_years' => $school_years
//        ]);
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sy.create');
    }
//
//    /**
//     * Store a newly created resource in storage.
//     */
    public function store(StoreSchoolYearRequest $request)
    {
        $syStart = $request->sy_start;
        $syEnd = $request->sy_end;
        $syName = $request->sy_name;
        $diffYears = $syEnd - $syStart;

        $errors = [];

        // Kiểm tra điều kiện sy_end và sy_start
        if ($syEnd < $syStart) {
            $errors[] = "Năm kết thúc không thể nhỏ hơn năm bắt đầu";
        }

        if ($diffYears > 5) {
            $errors[] = "Khoảng cách giữa các năm vượt quá giới hạn cho phép";
        }

        // Kiểm tra trùng lặp sy_name
        $existingSyName = SchoolYear::where('sy_name', $syName)->exists();
        if ($existingSyName) {
            $errors[] = "Tên niên khóa đã tồn tại.";
        }

        // Kiểm tra trùng lặp sy_start và sy_end
        $existingSyYears = SchoolYear::where('sy_start', $syStart)
            ->where('sy_end', $syEnd)
            ->exists();
        if ($existingSyYears) {
            $errors[] = "Năm bắt đầu và năm kết thúc đã tồn tại.";
        }

        // Xử lý lỗi
        if (count($errors) > 0) {
            Session::flash('error', implode(', ', $errors));
            return Redirect::route('sy.index');
        }

        // Lưu trữ dữ liệu
        $obj = new SchoolYear();
        $obj->sy_start = $syStart;
        $obj->sy_end = $syEnd;
        $obj->sy_name = $syName;

        $obj->save();

        Session::flash('success', 'Thêm bản ghi mới thành công');
        return Redirect::route('sy.index');
    }


//
//    /**
//     * Display the specified resource.
//     */
    public function show(SchoolYear $schoolYear)
    {
        //
    }
//
//    /**
//     * Show the form for editing the specified resource.
//     */
    public function edit(SchoolYear $schoolYear, \Illuminate\Http\Request $request )
    {

        $obj = new SchoolYear();
        $obj->id = $request->id;

        $school_year = $obj->edit();

        return view('sy.edit', [
            'school_years' => $school_year
        ]);
    }
//
//    /**
//     * Update the specified resource in storage.
//     */
    public function update(UpdateSchoolYearRequest $request, SchoolYear $schoolYear)
    {

        $syStart = $request->sy_start;
        $syEnd = $request->sy_end;
        $syName = $request->sy_name;
        $diffYears = $syEnd - $syStart;
        $id = $request -> id;
        $errors = [];

        // Kiểm tra điều kiện sy_end và sy_start
        if ($syEnd < $syStart) {
            $errors[] = "Năm kết thúc không thể nhỏ hơn năm bắt đầu";
        }

        if ($diffYears > 5) {
            $errors[] = "Khoảng cách giữa các năm vượt quá giới hạn cho phép";
        }

        // Kiểm tra trùng lặp sy_name
        $existingSyName = SchoolYear::where('sy_name', $syName)->where('id', '!=', $id)->exists();
        if ($existingSyName) {
            $errors[] = "Tên niên khóa đã tồn tại.";
        }

        // Kiểm tra trùng lặp sy_start và sy_end
        $existingSyYears = SchoolYear::where('sy_start', $syStart)
            ->where('sy_end', $syEnd)
            ->where('id', '!=', $id)
            ->exists();
        if ($existingSyYears) {
            $errors[] = "Năm bắt đầu và năm kết thúc đã tồn tại.";
        }

        // Xử lý lỗi
        if (count($errors) > 0) {
            Session::flash('error', implode(', ', $errors));
            return Redirect::route('sy.index');
        }

        $obj = new SchoolYear();
        $obj->id = $request->id;
        $obj->sy_start= $request->sy_start;
        $obj->sy_end = $request->sy_end;
        $obj->sy_name = $request->sy_name;
        $obj->updateSchoolYear();
        return Redirect::route('sy.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolYear $schoolYear, \Illuminate\Http\Request $request)
    {
        $obj = new SchoolYear();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->destroySchoolYear();
            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Xóa thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Xóa thất bại. Vui lòng thử lại sau');
        }
        return Redirect::route('sy.index');
    }

    public function classes(){
        return $this -> hasMany(Classes::class);
    }
}
