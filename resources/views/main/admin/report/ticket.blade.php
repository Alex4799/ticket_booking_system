@extends('main.admin.layout.master')
@section('title')
    Ticket Report
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <form action="" method="get" class="bg-body-secondary rounded px-3 py-2 mb-4">
            @csrf
            <div class="d-flex gap-3 flex-wrap">
                <div>
                    <label class="label-small mb-0">Start Date</label>
                    <input type="date" style="font-size: 11pt;" name="start_date" value="{{$startDate}}" id="" class="form-control @error('start_date') is-invalid @enderror">
                    @error('start_date')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div>
                    <label class="label-small mb-0">End Date</label>
                    <input type="date" style="font-size: 11pt;" name="end_date" value="{{$endDate}}" id="" class="form-control @error('end_date') is-invalid @enderror">
                    @error('end_date')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div>
                    <label class="label-small mb-0">Cinema</label>
                    <select name="cinema" class="form-select" style="font-size: 11pt;">
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
            <div class="d-flex align-items-center justify-content-end w-100 mt-3 mb-2">
                <button class="d-flex align-items-center btn btn-primary me-2" style="font-size: 10pt;"><i class="fa-solid fa-filter me-2" style="font-size: 8pt;"></i>Filter</button>
                <a href="{{route('admin#reportTicket')}}" class="d-flex align-items-center btn btn-secondary" style="font-size: 10pt;" ><i class="fa-solid fa-rotate-left me-2" style="font-size: 10pt;"></i>Reset</a>
            </div>
        </form>
        <div class="summary-cards mb-3">
            <div class="row">
                @foreach ($report['summary'] as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <div class="card-content @if ($item->status==0) pending @elseif($item->status==1) success @elseif($item->status==3) cancel @elseif($item->status==4) booking @else reject @endif">
                            <label class="@if ($item->status==0) pending @elseif($item->status==1) success @elseif($item->status==3) cancel @elseif($item->status==4) booking @else reject @endif">Total Ticket @if ($item->status==0) Pending @elseif($item->status==1) Success @elseif($item->status==3) Cancel @elseif($item->status==4) Booking @else Reject @endif</label>
                            <div>
                                <h5>{{number_format($item->total_income)}} <label class="label-small ms-1">Ks</label></h5>
                                <div class="card-bottom">
                                    <div class="card-stat">
                                        <span>Order</span>
                                        <strong>{{$item->total_order}}</strong>
                                    </div>
                                    <div class="card-divider"></div>
                                    <div class="card-stat">
                                        <span>Seat</span>
                                        <strong>{{$item->total_seat}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row report-row">
            <div class="col-md-7 report-column">
                <div class="report-container-box">
                    <div>
                        <div class="report-title">
                            <div>
                                <h5><i class="fa-solid fa-building me-2"></i>Cinema Income</h5>
                            </div>
                        </div>
                        <div class="report-table">
                            <table id="cinemaReport">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Cinema</th>
                                        <th class="text-center">Seat Count</th>
                                        <th class="text-end">Income</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($report['cinema'] as $item)
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$item->cinema_name}}</td>
                                            <td class="text-center">{{$item->total_seat}}</td>
                                            <td class="text-end">{{number_format($item->total_price)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="report-container-box">
                    <div>
                        <div class="report-title">
                            <div>
                                <h5><i class="fa-solid fa-door-open me-2"></i>Hall Income</h5>
                            </div>
                        </div>
                        <div class="report-table">
                            <table id="hallReport">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Cinema</th>
                                        <th>Hall</th>
                                        <th class="text-center">Seat Count</th>
                                        <th class="text-end">Income</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($report['hall'] as $item)
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$item->cinema_name}}</td>
                                            <td>{{$item->hall_name}}</td>
                                            <td class="text-center">{{$item->total_seat}}</td>
                                            <td class="text-end">{{number_format($item->total_price)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 report-column">
                <div class="report-container-box">
                    <div>
                        <div class="report-title">
                            <div>
                                <h5><i class="fa-solid fa-credit-card me-2"></i>Payment Income</h5>
                            </div>
                        </div>
                        <div class="report-table">
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
                                            <td class="text-end">{{number_format($item->total_price)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="report-container-box">
                    <div>
                        <div class="report-title">
                            <div>
                                <h5><i class="fa-solid fa-masks-theater me-2"></i>Genre Income</h5>
                            </div>
                        </div>
                        <div class="report-table">
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
                                            <td class="text-end">{{number_format($item->total_price)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="report-container-box">
            <div>
                <div class="report-title">
                    <div>
                        <h5><i class="fa-solid fa-clapperboard me-2"></i>Movie Income</h5>
                    </div>
                    <div class="dropdown">
                        <button class="action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-calendar-day me-2 text-white"></i>
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
                                <th class="text-end">Total Income</th>
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
        <div class="report-container-box">
            <div>
                <div class="report-title">
                    <div>
                        <h5><i class="fa-solid fa-calendar-day me-2"></i>Date</h5>
                    </div>
                </div>
                <div class="report-table">
                    <table id="dateReport">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th class="text-end">Income</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($report['date'] as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->date}}</td>
                                    <td class="text-end">{{number_format($item->total_price)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection


@section('script')
<script>
    $(document).ready(function() {
        $('#cinemaReport,#hallReport,#genreReport,#paymentReport,#dateReport,#movieReport').DataTable({
            dom: 'Bfrtip',
            scrollX: true,
            autoWidth: false,
            language: {
                search: '',
                searchPlaceholder: 'Search',
                infoEmpty: 'No records available',
                paginate: {
                        previous: '<i class="fa-solid fa-chevron-left"></i>',
                        next: '<i class="fa-solid fa-chevron-right"></i>'
                    }
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa-solid fa-download me-2"></i> Export',
                    className: 'add-btn',
                    title: 'Ticket Report'
                }
            ]
        });
    });
</script>
@endsection
