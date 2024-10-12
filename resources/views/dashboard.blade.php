@extends('layout.master')



{{--    <link rel="stylesheet" href="{{asset('assets/plugins/plugin.css')}}">--}}
    <link rel="stylesheet" href="{{asset('assets/plugins/plugin.css')}}">

@section('content')
<div class="row">
{{--  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">--}}
{{--    <div class="card card-statistics">--}}
{{--      <div class="card-body">--}}
{{--        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">--}}
{{--          <div class="float-left">--}}
{{--            <i class="mdi mdi-cube text-danger icon-lg"></i>--}}
{{--          </div>--}}
{{--          <div class="float-right">--}}
{{--            <p class="mb-0 text-right">Pending Reports</p>--}}
{{--            <div class="fluid-container">--}}
{{--              <h3 class="font-weight-medium text-right mb-0">{{ $reportCount }}</h3>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">--}}
{{--          <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> 65% lower growth </p>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--  </div>--}}





    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-receipt text-warning icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Phân công đã bàn giao </p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $divisionCount }}</h3>
            </div>
          </div>
        </div>
{{--        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">--}}
{{--          <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Product-wise sales </p>--}}
      </div>
    </div>
  </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-account text-success icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Số lượng giáo viên</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $teacherCount }}</h3>
            </div>
          </div>
        </div>
{{--        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">--}}
{{--          <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Weekly Sales </p>--}}
      </div>
    </div>
  </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
      <div class="card-body">
        <div class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
          <div class="float-left">
            <i class="mdi mdi-account-box-multiple text-info icon-lg"></i>
          </div>
          <div class="float-right">
            <p class="mb-0 text-right">Số lượng học sinh</p>
            <div class="fluid-container">
              <h3 class="font-weight-medium text-right mb-0">{{ $studentCount }}
                 </h3>
            </div>
          </div>
        </div>
{{--        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">--}}
{{--          <i class="mdi mdi-reload mr-1" aria-hidden="true"></i> Product-wise sales </p>--}}
      </div>
    </div>
  </div>
</div>
@php
    $classData = \App\Models\Classes::withCount([
        'transcriptDetails as total_students',
        'transcriptDetails as students_below_5' => function ($query) {
            $query->where('score', '<', 5);
        },
        'transcriptDetails as students_above_5' => function ($query) {
            $query->where('score', '>=', 5);
        },
        'transcriptDetails as students_no_score' => function ($query) {
            $query->whereNull('score');
        }
    ])->get();

    $data = [];
    foreach ($classData as $class) {
        $data[] = [
            'class_name' => $class->class_name,
            'total_students' => $class->total_students,
            'students_below_5' => $class->students_below_5,
            'students_above_5' => $class->students_above_5,
            'students_no_score' => $class->students_no_score,
        ];
    }
@endphp

{{--<div class="form-group">--}}
{{--    <label for="schoolyearSelect">School Year Checker:</label>--}}
{{--    <select class="input-group" id="schoolyearSelect">--}}
{{--        <option value="">Check School Year</option>--}}
{{--        @foreach(\App\Models\SchoolYear::all() as $school_year)--}}
{{--            <option value="{{ $school_year->id }}" @if( $school_year->id) selected @endif>{{ $school_year->sy_start }}_{{ $school_year->sy_end }}</option>--}}
{{--        @endforeach--}}
{{--    </select>--}}
{{--</div>--}}
{{--<div class="form-group">--}}
{{--    <label for="gradeSelect">grade Checker:</label>--}}
{{--    <select class="input-group" id="gradeSelect">--}}
{{--        <option value="">Check grade</option>--}}
{{--        @foreach(\App\Models\Grade::all() as $grade)--}}
{{--            <option value="{{ $grade->id }}" @if( $grade->id) selected @endif>{{ $grade->grade_name }}</option>--}}
{{--            <option value="{{ $grade->id }}" @if( $grade->id) selected @endif>{{ $grade->grade_name }}</option>--}}
{{--        @endforeach--}}
{{--    </select>--}}
{{--</div>--}}
    <div class="form-group">
        <label for="classSelect">Danh sách lớp:</label>
        <select class="form-select" id="classSelect" aria-label="Default select ">
            <option selected value="">Chọn lớp</option>
            @foreach(\App\Models\Classes::all() as $class)
                <option value="{{ $class->id }}" @if($abd == $class->id) selected @endif>{{ $class->class_name }}_{{ $class->school_year->sy_name }}</option>
            @endforeach
        </select>

    </div>
{{--    <div class="form-group">--}}
{{--        <label for="subjectSelect">Danh sách môn học:</label>--}}
{{--        <select class="form-select" id="subjectSelect" aria-label="Default select ">--}}
{{--            <option selected value = "">Chọn môn</option>--}}
{{--            @foreach(\App\Models\Subject::all() as $subject)--}}
{{--                <option value="{{ $subject->id }}" @if($abc == $subject->id) selected @endif>{{ $subject->subject_name }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}

{{--    </div>--}}
    {{--@dump($result)--}}
@if($result == null)
    <div class="row mt-3" >
    <div class="col-md-6">

        <div class="wrapper mt-4">
            <div class="d-flex justify-content-between mb-2">
                <div class="d-flex align-items-center">
                    <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Students Passed The Exams In 1st Test</h4>
                </div>
                <p class="mb-0 font-weight-medium">0%</p>
            </div>
            <div class="progress">
            </div>
        </div>
        <div class="wrapper mt-4">
            <div class="d-flex justify-content-between mb-2">
                <div class="d-flex align-items-center">
                    <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Students Failed The Exams In 1st Test</h4>
                </div>
                <p class="mb-0 font-weight-medium">0%</p>
            </div>
            <div class="progress">
                <div class="progress-bar bg-warning" role="progressbar" style=""></div>
            </div>
        </div>

    </div>
        <div class="col-md-6">


            <div class="wrapper mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Student Got Banned </h4>
                    </div>
                    <p class="mb-0 font-weight-medium">0%</p>
                </div>
                <div class="progress">
                </div>
            </div>
            <div class="wrapper mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Student Skipped The Exam </h4>
                    </div>
                    <p class="mb-0 font-weight-medium">0%</p>
                </div>
                <div class="progress">
                </div>
            </div>
        </div>
    </div>
@else
    @php
    $totalscore = 0;
    $score5up = 0;
    $score5down = 0;
    $scoreban = 0;
    $scoreskip = 0;
        $hasSecondTest = false; // Thêm biến này để kiểm tra xem có bản ghi transcript nào có exam_type bằng 1 không

 @endphp
    @foreach($result as $re)
        @php
            $totalscore ++ ;
            if($re -> score >= 5){
                $score5up ++;

            }elseif ($re -> score < 5){

                $score5down ++;
            }elseif ($re -> note == 2){
                $scoreban ++;
            }elseif ($re -> note == 3){
               $scoreskip ++;
            }

             if ($re->exam_type == 1) {
                $hasSecondTest = true;
            }
 @endphp
    @endforeach
    @if($totalscore > 0)
        <div class="row mt-3" >

        <div class="col-md-6">
            <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Students Passed The Exams In 1st Test</h4>
            <div class="wrapper mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                                                <p class="mb-0 font-weight-medium">{{ $transcriptAbove5Count }}</p>
                    </div>
                    <p class="mb-0 font-weight-medium">{{ round(($transcriptAbove5Count / ($transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2)) * 100) }}%</p>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(($transcriptAbove5Count / ($transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2)) * 100) }}%" aria-valuenow="{{ $transcriptAbove5Count }}" aria-valuemin="0" aria-valuemax="{{ $transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2 }}"></div>
                </div>
            </div>
            <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Students Failed The Exams In 1st Test</h4>
            <div class="wrapper mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        <p class="mb-0 font-weight-medium">{{ $transcriptBelow5Count }}</p>
                    </div>

                    <p class="mb-0 font-weight-medium">{{ round(($transcriptBelow5Count / ($transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2)) * 100) }}%</p>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ round(($transcriptBelow5Count / ($transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2)) * 100) }}%" aria-valuenow="{{ $transcriptBelow5Count }}" aria-valuemin="0" aria-valuemax="{{ $transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2 }}"></div>
                </div>
            </div>
            <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Student Got Banned </h4>
            <div class="wrapper mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        <p class="mb-0 font-weight-medium">{{ $transcriptNoScoreCount1 }}</p>
                    </div>
                    <p class="mb-0 font-weight-medium">{{ round(($transcriptNoScoreCount1 / ($transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2)) * 100) }}%</p>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ round(($transcriptNoScoreCount1 / ($transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2)) * 100) }}%" aria-valuenow="{{ $transcriptNoScoreCount1 }}" aria-valuemin="0" aria-valuemax="{{ $transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2 }}"></div>
                </div>
            </div>
            <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Student Skipped The Exam </h4>
            <div class="wrapper mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        <p class="mb-0 font-weight-medium">{{ $transcriptNoScoreCount2 }}</p>
                    </div>
                    <p class="mb-0 font-weight-medium">{{ round(($transcriptNoScoreCount2 / ($transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2)) * 100) }}%</p>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-dark" role="progressbar" style="width: {{ round(($transcriptNoScoreCount2 / ($transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2)) * 100) }}%" aria-valuenow="{{ $transcriptNoScoreCount2 }}" aria-valuemin="0" aria-valuemax="{{ $transcriptBelow5Count + $transcriptAbove5Count + $transcriptNoScoreCount1 + $transcriptNoScoreCount2 }}"></div>
                </div>
            </div>
        </div>


        @if($hasSecondTest)
            <div class="col-md-6">
                <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Students Passed The Exams In 2nd Test</h4>
                <div class="wrapper mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <p class="mb-0 font-weight-medium">{{ $transcriptAbove5Count2nd }}</p>
                        </div>
                        <p class="mb-0 font-weight-medium">{{ round(($transcriptAbove5Count2nd / ($transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd)) * 100) }}%</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(($transcriptAbove5Count2nd / ($transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd)) * 100) }}%" aria-valuenow="{{ $transcriptAbove5Count2nd }}" aria-valuemin="0" aria-valuemax="{{ $transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd }}"></div>
                    </div>
                </div>
                <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Students Failed The Exams In 2nd Test</h4>
                <div class="wrapper mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <p class="mb-0 font-weight-medium">{{ $transcriptBelow5Count2nd }}</p>
                        </div>
                        <p class="mb-0 font-weight-medium">{{ round(($transcriptBelow5Count2nd / ($transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd)) * 100) }}%</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ round(($transcriptBelow5Count2nd / ($transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd)) * 100) }}%" aria-valuenow="{{ $transcriptBelow5Count2nd }}" aria-valuemin="0" aria-valuemax="{{ $transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd }}"></div>
                    </div>
                </div>
                <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Student Got Banned </h4>
                <div class="wrapper mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <p class="mb-0 font-weight-medium">{{ $transcriptNoScoreCount12nd }}</p>
                        </div>
                        <p class="mb-0 font-weight-medium">{{ round(($transcriptNoScoreCount12nd / ($transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd)) * 100) }}%</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ round(($transcriptNoScoreCount12nd / ($transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd)) * 100) }}%" aria-valuenow="{{ $transcriptNoScoreCount12nd }}" aria-valuemin="0" aria-valuemax="{{ $transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd }}"></div>
                    </div>
                </div>
                <h4 class="card-title font-weight-medium mb-0 d-none d-md-block">Student Skipped The Exam </h4>
                <div class="wrapper mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <p class="mb-0 font-weight-medium">{{ $transcriptNoScoreCount22nd }}</p>
                        </div>
                        <p class="mb-0 font-weight-medium">{{ round(($transcriptNoScoreCount22nd / ($transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd)) * 100) }}%</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-dark" role="progressbar" style="width: {{ round(($transcriptNoScoreCount22nd / ($transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd)) * 100) }}%" aria-valuenow="{{ $transcriptNoScoreCount22nd }}" aria-valuemin="0" aria-valuemax="{{ $transcriptBelow5Count2nd + $transcriptAbove5Count2nd + $transcriptNoScoreCount12nd + $transcriptNoScoreCount22nd }}"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @else
        <!-- Hiển thị thông báo khi không có dữ liệu thống kê cho lớp và môn học đã chọn -->
        <div class="alert alert-info" >
            Không tìm thấy kết quả phù hợp
        </div>
    @endif
@endif






@endsection
{{--  <script src="{{asset('/assets/js/dashboard.js')}}"></script>--}}

    <script src="{{asset('/assets/plugins/chartjs/chart.min.js')}}"></script>
    <script src="{{asset('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>


    <script src="{{asset('/assets/js/dashboard.js')}}"></script>

