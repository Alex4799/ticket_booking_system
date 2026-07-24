@extends('main.admin.layout.master')
@section('title')
    Hall
@endsection
@section('style')

@endsection
@section('content')
            <!-- general details start  -->
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div>
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{route('admin#hallList')}}">List</a></li>
                              <li class="breadcrumb-item active" aria-current="page">View</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <h4 class="text-center py-2">View Hall</h4>
                    </div>
                    <div class="row gap-1">
                        <div class="col-md-5 offset-md-1 d-flex justify-content-center card p-3">
                            <div class=" ">
                                <h5 class="py-2">Name - {{$hall->name}}@if ($hall->du_id!=null) (Copy) @endif</h5>
                                <h5 class="py-2">Column - {{$hall->seat_column}}</h5>
                                <h5 class="">Acitve - @if ($hall->active==0)
                                                <span class="text-danger">Not Active</span>
                                            @else
                                                <span class="text-success">Active</span>
                                            @endif</h5>

                                <div class=" d-flex justify-content-end gap-1 flex-wrap">
                                    @if ($hall->du_id==null)
                                        @if (Auth::user()->position=='developer')
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#duplicate-hall">Duplicate Hall</button>
                                        @endif
                                    @endif
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit-hall">Edit Hall</button>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-hall">Delete Hall</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex justify-content-center card p-2">
                            <div class=" m-auto">
                                @if (count($summary)!=0)
                                    @foreach ($summary as $item)
                                        <h6 class="py-1"><input type="color" class="" value="{{$item['seat_color']}}"><span class="ps-1">{{$item['seat_plan_name']}} -  {{$item['seat_count']}}</span></h6>
                                    @endforeach
                                @else
                                    <h5>There is no seat</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="d-flex justify-content-between">
                            <h4 class="text-center py-3">Seat List</h4>
                            <div class="py-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-seat">Add Seat</button>
                            </div>
                            @if ($hall->du_id!=null)
                                <div class="py-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#replace-seat-plan">Replace Seat Plan</button>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="cinema-seating">
                                <div class="screen-container">
                                    <div class="screen">Screen</div>
                                </div>

                                <div id="seat-plan-container">
                                    <div id="seat-plan">
                                        <!-- Row A -->
                                        <div class="d-grid" style="grid-template-columns: repeat({{ $hall->seat_column }}, auto);">
                                            @if (count($seat)!=0)
                                                @foreach ($seat as $item)
                                                    @if ($item->seat_type==1)
                                                        <a href="{{route('admin#seatView',$item->id)}}" title="{{$item->name}},@if ($item->active==0) Not Active @else Active @endif,{{$item->seat_plan_name}}" class="mx-auto" style="color: @if($item->active==0) red @else {{$item->seat_color}} @endif">
                                                            <div class="double-seat ">
                                                                <div class="seat-content">
                                                                    <text class="svg-number">{{$item->name}}</text>
                                                                    <svg class="lucide-armchair" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.6" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/>
                                                                        <path d="M3 16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V11a2 2 0 0 0-4 0z"/>
                                                                        <path d="M5 18v2"/>
                                                                        <path d="M19 18v2"/>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="{{route('admin#seatView',$item->id)}}" title="{{$item->name}},@if ($item->active==0) Not Active @else Active @endif,{{$item->seat_plan_name}}" class="mx-auto" style="color: @if($item->active==0) red @else {{$item->seat_color}} @endif">
                                                            <div class="seat ">
                                                                <div class="seat-content">
                                                                    <text class="svg-number">{{$item->name}}</text>
                                                                    <svg class="lucide-couple-armchair" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <!-- Mirrored and shortened Left backrest -->
                                                                        <path d="M22 12V10a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v8" />

                                                                        <!-- Shortened Right backrest -->
                                                                        <path d="M26 12V10a3 3 0 0 1 3-3h11a3 3 0 0 1 3 3v8" />

                                                                        <!-- Shared seat base -->
                                                                        <path d="M2 30a3 3 0 0 0 3 3h38a3 3 0 0 0 3-3v-8a3 3 0 0 0-6 0v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2a3 3 0 0 0-6 0z" />

                                                                        <!-- Legs -->
                                                                        <path d="M6 34v6" />
                                                                        <path d="M24 34v6" />
                                                                        <path d="M42 34v6" />
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>

                                        <!-- add other row  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- general details end  -->

            {{-- model start --}}
            <div>
                <div class="modal fade" id="edit-hall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-hallLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="edit-hallLabel">Edit Hall</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#hallUpdate')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Hall Name</label>
                                        <input id="" name="name" type="text" value="{{$hall->name}}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter cinema name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-1">Hall Seat Column</label>
                                        <input id="" name="seat_column" type="number" value="{{$hall->seat_column}}" class="form-control @error('seat_column') is-invalid @enderror" placeholder="Enter hall seat column">
                                        @error('seat_column')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="name" class="form-label @error('active') is-invalid @enderror">Active</label>
                                        <select name="active" id="" class="form-control ">
                                            <option value="">Choose Active Status</option>
                                            <option value="0" @if ($hall->active==0) selected @endif>Not Active</option>
                                            <option value="1" @if ($hall->active==1) selected @endif>Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="cinema_id" value="{{$hall->cinema_id}}">
                                    <input type="hidden" name="id" value="{{$hall->id}}">

                                    <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="add-seat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-seatLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="add-seatLabel">Add Seat</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatCreate')}}" method="post" class="mt-1">
                                    @csrf
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Seat Name</label>
                                        <input id="" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter seat name">
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="py-1">Seat plan</label>
                                        <select name="seat_plan_id" class="form-control @error('seat_plan_id') is-invalid @enderror">
                                            <option value="">Choose Seat Plan</option>
                                            @foreach ($seatPlan as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('seat_plan_id')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="py-1">Seat Type</label>
                                        <select name="seat_type" class="form-control @error('seat_type') is-invalid @enderror">
                                            <option value="1">Single</option>
                                            <option value="2">Couple</option>
                                        </select>
                                        @error('seat_type')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="py-1">Seat Count</label>
                                        <input type="number" name="count" class="form-control @error('count') is-invalid @enderror" min="1" value="1" id="">
                                        @error('count')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="name" class="form-label @error('active') is-invalid @enderror">Active</label>
                                        <select name="active" id="" class="form-control ">
                                            <option value="">Choose Active Status</option>
                                            <option value="0">Not Active</option>
                                            <option value="1">Active</option>
                                        </select>
                                        @error('active')
                                            <small class=" text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="hall_id" value="{{$hall->id}}">

                                    <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete-hall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-hallLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="delete-hallLabel">Delete Hall</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#hallDelete')}}" method="post" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <div class="py-1">
                                        <p class="text-danger">Are you sure to delete this. If you delete this, the count of seat in this must be 0."</p>
                                    </div>
                                    <div class="py-1">
                                        <h6>Verify you are admin. Please enter the password.</h6>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Password</label>
                                        <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" value="{{$hall->id}}">

                                    <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal fade" id="duplicate-hall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="duplicate-hallLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="duplicate-hallLabel">Duplicate Hall</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#hallDuplicate')}}" method="post" class="mt-1">
                                    @csrf
                                    <div class="py-1">
                                        <h6>Verify you are admin. Please enter the password.</h6>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="mb-1">Password</label>
                                        <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="id" value="{{$hall->id}}">

                                    <button type="submit" class="btn btn-primary w-100 my-2">Duplicate</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="modal fade" id="replace-seat-plan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="replace-seat-planLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title d-block w-100 text-center" id="replace-seat-planLabel">Add Seat</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#seatReplace')}}" method="post" class="mt-1">
                                    @csrf

                                    <div class="form-group mb-4">
                                        <label class="py-1">From</label>
                                        <select name="from" class="form-control @error('from') is-invalid @enderror">
                                            <option value="">Choose Seat Plan</option>
                                            @foreach ($seatPlan as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('from')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="py-1">To</label>
                                        <select name="to" class="form-control @error('to') is-invalid @enderror">
                                            <option value="">Choose Seat Plan</option>
                                            @foreach ($seatPlan as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('to')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="hall_id" value="{{$hall->id}}">

                                    <button type="submit" class="btn btn-primary w-100 my-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            {{-- model end --}}
@endsection
@section('script')

@endsection
