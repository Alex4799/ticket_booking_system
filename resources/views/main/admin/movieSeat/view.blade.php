@extends('main.admin.layout.master')
@section('title')
    Seat
@endsection
@section('content')
    <main class="content p-2 mb-5">
        <div class="container-fluid">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin#moviePlanView',$movieSeat->movie_plan_id)}}">Movie Plan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Seat</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="shadow-container-box">
                        <div class="seat-view">
                            <div class="seat-detail-top">
                                <div class="seat-title">
                                    <div>
                                        <i class="fa-solid fa-couch"></i>
                                    </div>
                                    <div>
                                        <label>Seat</label>
                                        <h3>{{ $movieSeat->seat_name }}</h3>
                                    </div>
                                </div>
                                <div>
                                    <h6>
                                        @if ($movieSeat->status==0)
                                            <span class="status success">Available</span>
                                        @elseif ($movieSeat->status==1)
                                            <span class="status pending">Selected</span>
                                        @elseif ($movieSeat->status==2)
                                            <span class="status reject">Sold</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            <div class="seat-detail-footer">
                                <div>
                                    <label>Hall</label>
                                    <h6>{{ $movieSeat->hall_name }}</h6>
                                </div>
                                <div>
                                    <label>Seat Plan</label>
                                    <h6>{{ $movieSeat->seat_plan_name }}</h6>
                                </div>
                                <div>
                                    <label>Price</label>
                                    <h6> {{number_format($movieSeat->price)}} Ks</h6>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h6 class="ps-sm-3">{{$movieSeat->date}} | {{$movieSeat->start_time}}</h6>
                                <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#edit"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Seat</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-block w-100 text-center" id="editLabel">Edit Seat</h5>
                                <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin#updateMovieSeat')}}" method="post" enctype="multipart/form-data" class="mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <div class="update-btn-group">
                                        <div class="form-group mb-4">
                                            <label class="mb-1">Seat Price</label>
                                            <input type="number" value="{{$movieSeat->price}}" name="price" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="mb-1">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="0" @if ($movieSeat->status==0) selected @endif>Avaliable</option>
                                                <option value="1" @if ($movieSeat->status==1) selected @endif>Selected</option>
                                                <option value="2" @if ($movieSeat->status==2) selected @endif>Sold</option>
                                            </select>
                                            @error('status')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div>
                                            <input type="hidden" name="id" value="{{$movieSeat->id}}" id="id">
                                        </div>

                                        <div class="d-flex align-items-center my-2">
                                            <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                        </div>
                                    </div>
                                </form>
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
        // updatebtn disabled check
        const forms = document.querySelectorAll('.update-btn-group');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input,select');
            const button = form.querySelector('.action-btn');
            const initialValues = {};
            inputs.forEach(input => {
                initialValues[input.name] = input.value;
            });
            function checkUpdateValueForBtn() {
                let hasChanged = false;
                inputs.forEach(input => {
                    if (input.value != initialValues[input.name]) {
                        hasChanged = true;
                    }
                });
                button.disabled = !hasChanged;
            }
            inputs.forEach(input => {
                input.addEventListener('input', checkUpdateValueForBtn);
                input.addEventListener('change', checkUpdateValueForBtn);
            });
            checkUpdateValueForBtn();
        });
    </script>
@endsection
