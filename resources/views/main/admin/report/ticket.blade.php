@extends('main.admin.layout.master')
@section('title')
    Ticket Report
@endsection
@section('content')
<main class="content p-4 mb-5">
    <div class="bg-body-secondary rounded p-2 mb-4">
        <form action="" method="get">
            @csrf
            <div class="d-flex flex-wrap gap-1">
                <div class="">
                    <div>
                        <label for="" style="font-size: 11pt;">Start Date</label>
                        <input type="date" name="start_date" value="{{$startDate}}" id="" class="form-control @error('start_date') is-invalid @enderror">
                        @error('start_date')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="">
                    <div>
                        <label for="" style="font-size: 11pt;">End Date</label>
                        <input type="date" name="end_date" value="{{$endDate}}" id="" class="form-control @error('end_date') is-invalid @enderror">
                        @error('end_date')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="">
                    <div class="">
                        <label for="" style="font-size: 11pt;">Cinema</label>
                        <select name="cinema" id="" class="form-control">
                            <option value="">All</option>
                            @foreach ($cinema as $item)
                                <option value="{{$item->name}}" @if (request('cinema')==$item->name) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('cinema')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end mt-2 mb-1">
                <input type="submit" value="Filter" class="btn btn-primary me-2" style="font-size: 11pt;">
                <a href="{{route('admin#reportTicket')}}" style="font-size: 11pt;" class="btn btn-danger">Clear</a>
            </div>
        </form>
    </div>
    <div class="summary-cards mt-4 mb-3">
        <div class="row">
            @foreach ($report['summary'] as $item)
                <div class="col-md-3 mb-3">
                    <div class="rounded p-3 bg-white">
                        <label for="">Total Ticket @if ($item->status==0) Pending @elseif($item->status==1) Success @elseif($item->status==3) Cancel @elseif($item->status==4) Booking @else Reject @endif</label>
                        <div class="d-flex align-items-center justify-content-between gap-1 flex-wrap">
                            <h5>{{number_format($item->total_income)}}</h5>
                            <p class="@if ($item->status==0) rep-pending @elseif($item->status==1) rep-success @elseif($item->status==3) rep-cancel @elseif($item->status==4) rep-booking @else rep-reject @endif" >Order - {{$item->total_order}}</p>
                            <p class="@if ($item->status==0) rep-pending @elseif($item->status==1) rep-success @elseif($item->status==3) rep-cancel @elseif($item->status==4) rep-booking @else rep-reject @endif" >Seat - {{$item->total_seat}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Cinema Income</h5>
            </div>
            <div class="">
                <table id="cinemaReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Cinema</th>
                            <th>Seat Count</th>
                            <th class="text-end">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['cinema'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->cinema_name}}</td>
                                <td>{{$item->total_seat}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Hall Income</h5>
            </div>
            <div>
                <table id="hallReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Cinema</th>
                            <th>Hall</th>
                            <th>Seat Count</th>
                            <th class="text-end">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['hall'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->cinema_name}}</td>
                                <td>{{$item->hall_name}}</td>
                                <td>{{$item->total_seat}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Genre</h5>
            </div>
            <div>
                <table id="genreReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Genre</th>
                            <th class="text-end">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['genre'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->genre_name}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Payment</h5>
            </div>
            <div>
                <table id="paymentReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Payment Name</th>
                            <th>User Name</th>
                            <th class="text-end">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['payment'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->payment_name}}</td>
                                <td>{{$item->username}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Date</h5>
            </div>
            <div>
                <table id="dateReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th class="text-end">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['date'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->date}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Movie Income</h5>
                <div class="d-flex gap-1">
                    <div class="">
                        <button type="button" class="btn btn-primary dropdown-toggle text-capitalize" data-bs-toggle="dropdown" aria-expanded="false">
                            {{request('group_by')??'daily'}}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{route('admin#reportTicket',[...request()->all(),"group_by"=>'daily'])}}">Daily</a></li>
                            <li><a class="dropdown-item" href="{{route('admin#reportTicket',[...request()->all(),"group_by"=>'weekly'])}}">Weekly</a></li>
                            <li><a class="dropdown-item" href="{{route('admin#reportTicket',[...request()->all(),"group_by"=>'monthly'])}}">Monthly</a></li>
                            <li><a class="dropdown-item" href="{{route('admin#reportTicket',[...request()->all(),"group_by"=>'yearly'])}}">Yearly</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="report-table">
                <table id="movieReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Movie</th>
                            @foreach ($report['movie']['dateCollector'] as $item)
                                @if (request('group_by')=='weekly')
                                    <th>{{ $item['year'] }}-{{$item['week']}}</th>
                                @elseif(request('group_by')=='monthly')
                                    <th>{{ $item['year'] }}-{{$item['month']}}</th>
                                @elseif(request('group_by')=='yearly')
                                    <th>{{ $item['year'] }}</th>
                                @else
                                    <th>{{ $item }}</th>
                                @endif
                            @endforeach
                            <th>Total Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['movie']['movieCollector'] as $movie)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{ $movie['movie_name'] }}</td>
                                @foreach ($movie['date_price'] as $item)
                                    <td class="text-end">{{ number_format($item) }}</td>
                                @endforeach
                                <td class="text-end fw-bold">{{ number_format($movie['total_price'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#cinemaReport,#hallReport,#genreReport,#paymentReport,#dateReport,#movieReport').DataTable({
            dom: 'Bfrtip', // Enable Buttons
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export <i class="fa-solid fa-download ms-2"></i>',
                    className: 'export-btn',
                    title: 'Ticket Report'
                }
            ]
        });
    });
</script>
@endsection
