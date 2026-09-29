@extends('main.admin.layout.master')
@section('title')
    Movie Plan
@endsection
@section('style')
<style>
    /* Full-screen overlay */
    #loading {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
      z-index: 100000; /* High z-index to cover other elements */
      display: flex;
      justify-content: center;
      align-items: center;
    }
</style>
@endsection
@section('content')
    <div class="d-flex justify-content-center">
        <div id="loading" class="d-none">
            <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <main class="content px-3 py-4">
        <div class="container-fluid">
            <div class="">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#moviePlanCinemaList')}}">Cinema</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List Plan</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->
            <div class="">
                <div class="card mt-4 px-4 border-0 shadow">
                    <h5 class="card-title text-center mt-3">{{$hall->name}}</h5>
                    <div class=" d-flex justify-content-between flex-wrap gap-1 mt-2">
                        <div>
                            <form action="" method="get">
                                <label for="">Choose Date</label>
                                <div class="d-flex">
                                    <input type="date" name="date" value="{{request('date')}}" id="" class="form-control">
                                    <input type="submit" class="btn btn-secondary">
                                </div>
                            </form>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-plan"><i class="fa-solid fa-plus me-2"></i>Add Plan</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-plan">Delete</button>
                        </div>
                    </div>
                    <div class="row" id="table-bg">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Plan No</th>
                                        <th>Movie Name</th>
                                        <th>Show Date</th>
                                        <th>Start Time</th>
                                        <th>Clean Time</th>
                                        <th>End Time</th>
                                        <th>Active</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($moviePlan as $item)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$item->movie_name}}</td>
                                        <td>{{$item->date}}</td>
                                        <td>{{$item->start_time}}</td>
                                        <td>{{$item->clean_time}} min</td>
                                        <td>{{$item->end_time}}</td>
                                        <td>
                                            @if ($item->active==1)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-danger">Not Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin#moviePlanView',$item->id)}}" class="btn btn-outline-primary">View Plan</a>
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
                    <div>
                        <div class="modal fade" id="add-plan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-planLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title d-block w-100 text-center" id="add-planLabel">Add Plan</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#moviePlanCreate')}}" method="post" enctype="multipart/form-data" class="parents">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="" class="mb-1">Choose Movie</label>
                                                <select name="movie_id" id="movie" class="form-control movie">
                                                    @foreach ($movie as $item)
                                                        <option value="{{$item->id}}" data-run_time="{{$item->run_time}}">{{$item->name}} ({{$item->run_time}} min)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label for="" class="mb-1">Show Date</label>
                                                @if (isset($lastPlan))
                                                    <input type="date" value="{{$lastPlan->date}}" name="date" class="form-control">
                                                @else
                                                    <input type="date" name="date" class="form-control">
                                                @endif

                                            </div>
                                            <div class="mb-4">
                                                <label for="" class="mb-1">Start Time</label>
                                                @if (isset($lastPlan))
                                                    <input type="time" name="start_time" value="{{$lastPlan->end_time}}" class="form-control start_time" id="start_time">
                                                @else
                                                    <input type="time" name="start_time" class="form-control start_time" id="start_time">
                                                @endif
                                            </div>
                                            <div class="mb-4">
                                                <label for="" class="mb-1">Clean Time (MIN)</label>
                                                <input type="number" name="clean_time" class="form-control clean_time" id="clean_time">
                                            </div>
                                            <div class="mb-4" id="end_time">

                                            </div>
                                            <div class="row mb-1">
                                                <div class="mb-4 col-md-6">
                                                    <label for="name" class="form-label mb-1">Online Booking</label>
                                                    <select name="online_book" id="" class="form-control">
                                                        <option value="1">True</option>
                                                        <option value="0">False</option>
                                                    </select>
                                                    @error('online_book')
                                                        <small class=" text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                                <div class="mb-4 col-md-6">
                                                    <label for="name" class="form-label mb-1">Online Buy</label>
                                                    <select name="online_buy" id="" class="form-control">
                                                        <option value="1">True</option>
                                                        <option value="0">False</option>
                                                    </select>
                                                    @error('online_buy')
                                                        <small class=" text-danger">{{$message}}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="name" class="form-label mb-1 @error('active') is-invalid @enderror">Active</label>
                                                <select name="active" class="form-control ">
                                                    <option value="1">Active</option>
                                                    <option value="0">Not Active</option>
                                                </select>
                                                @error('active')
                                                    <small class=" text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <label for="" class="mb-1">Day Count</label>
                                                <input type="number" name="day_count" class="form-control" value="1">
                                            </div>
                                            <div class="mb-4">
                                                <label for="" class="mb-1">Note</label>
                                                <textarea name="note" id="editor" cols="30" rows="10" class=" @error('note') is-invalid @enderror"></textarea>
                                            </div>
                                            <div>
                                                <input type="hidden" name="hall_id" value="{{$hall->id}}">
                                            </div>
                                            <div class="d-flex justify-content-end gap-1">
                                                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-upload me-2"></i>Add</button>
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
                                        <h4 class="modal-title d-block w-100 text-center" id="delete-planLabel">Delete Movie Plan</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#deleteAllMoviePlan')}}" method="post" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <div class="py-1">
                                                <p class="text-danger">Are you sure to delete this.</p>
                                            </div>
                                            <div class="py-1">
                                                <h6>Verify you are admin. Please enter the password.</h6>
                                            </div>
                                            <div>
                                                <input type="hidden" name="hall_id" value="{{$hall->id}}">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="mb-1">Password</label>
                                                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                                @error('password')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
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
                    <label for="" class="py-2">End Time</label>
                    <input type="time" readonly name="end_time" value='${$endTime}' class="form-control">
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
