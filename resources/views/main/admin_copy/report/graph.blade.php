@extends('main.admin.layout.master')
@section('title')
  Monthly Graph
@endsection
@section('content')
<main class="content p-4 mb-5">
    <div>
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
                                    <option value="{{$item->name}}" @if (request('cinema')==$item->id) selected @endif>{{$item->name}}</option>
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
                    <a href="{{route('admin#reportGraph')}}" style="font-size: 11pt;" class="btn btn-danger">Clear</a>
                </div>
            </form>
        </div>

        <div class="report-graph">
            <div class="row ">
                <div class="col-lg-3 mb-4">
                    <div>
                        <div class="card shadow-sm border-none mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-2">Cinema</h5>
                                <div class="wrapper pie-wrapper">
                                    <canvas id="cinema"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm border-none">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Genre</h5>
                                <div class="wrapper pie-wrapper">
                                    <canvas id="genre"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 mb-4">
                    <div>
                        <div class="card shadow-sm border-none mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3 text-center">Hall</h5>
                                <div class="wrapper chart-wrapper">
                                    <canvas id="hall"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm border-none">
                            <div class="card-body">
                                <h5 class="card-title mb-3 text-center">Show Time</h5>
                                <div class="wrapper line-wrapper">
                                    <canvas id="time"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <div class="card shadow-sm border-none">
                    <div class="card-body">
                        <h5 class="card-title mb-3 text-center">Movie</h5>
                        <div class="wrapper wk-chart-wrapper">
                            <canvas id="movie"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <div class="card shadow-sm border-none">
                    <div class="card-body">
                        <h5 class="card-title mb-3 text-center">Date</h5>
                        <div class="wrapper wk-line-wrapper">
                            <canvas id="date"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')
<script>

    // => cinema
    const cinemaLabels = @json($cinemaData['cinema_name']);
    const cinemaPrice  = @json($cinemaData['total_price']);
    const colorPalette = ['#1C4D8D','#6f42c1','#198754','#e0a800','#0d6efd']; // navy, purple, green, dark yellow, blue
    const cinemaColors =  cinemaPrice.map((_, i) => colorPalette[i % colorPalette.length]);
    const cinema = document.getElementById('cinema');
    new Chart(cinema, {
        type: 'doughnut',
        data: {
            labels: cinemaLabels,
            datasets: [{
                data: cinemaPrice,
                backgroundColor: cinemaColors,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '50%',
            plugins: {
                legend: {
                    position: 'bottom',
                    align: 'start',
                    labels: {
                        boxWidth: 13,
                        padding: 15
                    }
                },
            }
        }
    });


    // => hall
    const hall = document.getElementById('hall');
    new Chart(hall, {
        type: 'line',
        data: {
            labels:  @json($hallData['hall_name']),
            datasets: [{
                label: 'Cinema & Hall',
                data:  @json($hallData['total_price']),
                backgroundColor: '#7D8ABC',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            pointBackgroundColor: '#2B7CDE',
            radius: 5,
            scales: { x: {grid: { display: false } }}
        }
    });


    // => movie
    const movie = document.getElementById('movie');
    new Chart(movie, {
        type: 'bar',
        data: {
            labels: @json($movieData['movie_name']),
            datasets: [{
                label: 'Movie',
                data: @json($movieData['total_price']),
                backgroundColor: '#4988C4',
                borderRadius: 5,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }},
            scales: { x: { grid: { display: false } }}
        }
    });


    // => genre
    const genre = document.getElementById('genre');
    new Chart(genre, {
        type: 'pie',
        data: {
            labels: @json($genreData['genre_name']),
            datasets: [{
                label: 'Genre',
                data: @json($genreData['total_price']),
            }]
        },
        options: {
            responsive: true,
             maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    align: 'start',
                    labels: {
                        boxWidth: 16,
                        padding: 15
                    }
                }
            }
        },
    });


    // => date
    const date = document.getElementById('date');
    new Chart(date, {
        type: 'line',
        data: {
            labels: @json($dateData['date']),
            datasets: [{
                label: 'Date',
                data: @json($dateData['total_price']),
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {legend: { display: false }},
            scales: { x: { grid: { display: false } }}
        }
    });


    // => showtime
    const time = document.getElementById('time');
    new Chart(time, {
        type: 'line',
        data: {
            labels: @json($showTimeData['show_time']),
            datasets: [{
                label: 'Seat Count',
                data: @json($showTimeData['total_price']),
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {legend: { display: false }},
            scales: { x: { grid: { display: false } }}
        }
    });


</script>

@endsection
