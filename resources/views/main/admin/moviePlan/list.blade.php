@extends('main.admin.layout.master')
@section('title')
    Movie Plan
@endsection
@section('style')
<style>
    #loading{
        position: fixed;
        inset: 0;
        background-color: #1419238c;
        z-index: 99999;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .plan-loading{
        background-color: #fff;
        padding: 30px 40px;
        border-radius: 18px;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,.18);
    }
</style>
@endsection
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
    <div id="loading" class="d-none">
        <div class="plan-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#moviePlanCinemaList')}}">Cinemas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List Plan</li>
                    </ol>
                </nav>
            </div>
            <div class="table-bg">
                <div class="hall-view">
                    <h5 class="text-center">
                        <i class="fa-solid fa-door-open me-1"></i>
                        {{$hall->name}}@if ($hall->du_id!=null) (Copy) @endif
                    </h5>
                </div>
                <div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 my-2">
                        <div>
                            <form action="" method="get">
                                <label class="label-small">Choose Date</label>
                                <div class="d-flex">
                                    <input type="date" style="font-size: 11pt;" name="date" value="{{request('date')}}" id="" class="form-control" required>
                                    <button type="submit" class="d-flex align-items-center btn btn-sm btn-primary rounded"><i class="fa-solid fa-filter me-2" style="font-size: 8pt;"></i>Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#add-plan"><i class="fa-solid fa-square-plus me-2"></i>Add Plan</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-plan" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Movie Name</th>
                                    <th class="text-center">Plan ID</th>
                                    <th class="text-center">Show Date</th>
                                    <th class="text-center">Start Time</th>
                                    <th class="text-center">Clean Time</th>
                                    <th class="text-center">End Time</th>
                                    <th class="text-center">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($moviePlan as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->movie_name}}</td>
                                    <td class="text-center">{{$item->id}}</td>
                                    <td class="text-center">{{$item->date}}</td>
                                    <td class="text-center">{{Carbon::parse($item->start_time)->format('h:i A')}}</td>
                                    <td class="text-center">{{$item->clean_time}} min</td>
                                    <td class="text-center">{{Carbon::parse($item->end_time)->format('h:i A')}}</td>
                                    <td class="text-center">
                                        @if ($item->active==1)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Not Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('admin#moviePlanView',$item->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-calendar-check me-2"></i>View Plan</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{$moviePlan->appends(request()->query())->links()}}
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="modal fade" id="add-plan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-planLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="add-planLabel">Add Plan</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#moviePlanCreate')}}" method="post" enctype="multipart/form-data" class="parents mt-1">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Choose Movie</label>
                                            <select name="movie_id" id="movie" class="form-select movie">
                                                @foreach ($movie as $item)
                                                    <option value="{{$item->id}}" data-run_time="{{$item->run_time}}">{{$item->name}} ({{$item->run_time}} min)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Show Date</label>
                                            @if (isset($lastPlan))
                                                <input type="date" value="{{$lastPlan->date}}" name="date" class="form-control">
                                            @else
                                                <input type="date" name="date" class="form-control">
                                            @endif
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Day Count</label>
                                            <input type="number" name="day_count" class="form-control" value="1">
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Start Time</label>
                                            @if (isset($lastPlan))
                                                <input type="time" name="start_time" value="{{$lastPlan->end_time}}" class="form-control start_time" id="start_time">
                                            @else
                                                <input type="time" name="start_time" class="form-control start_time" id="start_time" required>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Clean Time (Min)</label>
                                            <input type="number" name="clean_time" class="form-control clean_time" id="clean_time" required>
                                        </div>
                                        <div id="end_time">

                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Online Booking</label>
                                            <select name="online_book" id="" class="form-select">
                                                <option value="1">True</option>
                                                <option value="0">False</option>
                                            </select>
                                            @error('online_book')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Online Buy</label>
                                            <select name="online_buy" id="" class="form-select">
                                                <option value="1">True</option>
                                                <option value="0">False</option>
                                            </select>
                                            @error('online_buy')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Status</label>
                                            <select name="active" class="form-select">
                                                <option value="1">Active</option>
                                                <option value="0">Not Active</option>
                                            </select>
                                            @error('active')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Note</label>
                                            <textarea name="note" id="editor" cols="30" rows="10" class=" @error('note') is-invalid @enderror"></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="hidden" name="hall_id" value="{{$hall->id}}">
                                    </div>
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal fade" id="delete-plan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-planLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="delete-planLabel">Delete Movie Plan</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#deleteAllMoviePlan')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="delete-confirmation">
                                        <div class="delete-warning">
                                            <div class="delete-icon">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </div>
                                            <div class="delete-message">
                                                <h6>Are you sure to delete all plans?</h6>
                                                <small>Verify you are admin. Please enter the password.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group my-3">
                                        <label class="mb-1">Password</label>
                                        <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div>
                                        <input type="hidden" name="hall_id" value="{{$hall->id}}">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </main>
@endsection

{{-- backend need to update  --}}

@section('script')
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#movie').change(function(){
                calculateRunTime();
            });

            $('#start_time').change(function(){
                calculateRunTime();
            })

            $('#clean_time').change(function(){
                calculateRunTime();
            })

            function calculateRunTime(){
                $('#loading').removeClass('d-none');
                $runTime=parseInt($('#movie option:selected').data('run_time'))||0;
                $startTime=$('#start_time').val()|| "00:00";
                $cleanTime=parseInt($('#clean_time').val())||0;
                $startTimeMin=$startTime.split(':');
                $startHour=parseInt($startTimeMin[0],10);
                $startMin=parseInt($startTimeMin[1],10);
                let totalEndMinutes=($startHour*60)+$startMin+$runTime+$cleanTime;
                totalEndMinutes = totalEndMinutes % 1440;
                let endHours = Math.floor(totalEndMinutes / 60);
                let endMinutes = totalEndMinutes % 60;
                let formattedHours = String(endHours).padStart(2, '0');
                let formattedMinutes = String(endMinutes).padStart(2, '0');
                $endTime=`${formattedHours}:${formattedMinutes}`;
                $('#end_time').html(`
                    <div class="col-sm-12 form-group mb-4">
                        <label class="mb-1">End Time</label>
                        <input type="time" readonly name="end_time" value='${$endTime}' class="form-control">
                    </div>
                `);
                $('#loading').addClass('d-none');
            }

        })
    </script>
    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph
        } from 'ckeditor5';

        ClassicEditor
            .create( document.querySelector( '#editor' ), {
                plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                toolbar: {
                    items: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                    ]
                }
            } )
            .then( /* ... */ )
            .catch( /* ... */ );
    </script>
@endsection
