@extends('main.customer.layout.master')
@section('content')
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp

<section class="container">
    <div class="p-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="font-size:10pt;" role="alert">
                {{session('success')}}
            <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                {{session('warning')}}
            <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" style="font-size: 10pt;" role="alert">
                {{session('danger')}}
            <button type="button" class="btn-close border-0 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <div class="row py-3">
        <div class="col-md-6 offset-md-3">
             <div id="user-contact-info">
                <h5>Ticket Check</h5>
                <form action="{{route('customer#checkOrder')}}" method="post">
                    @csrf
                    <div class="form-group my-3">
                        <label for="" class="mb-2">Enter your booking code</label>
                        <div>
                            <div class="d-flex align-items-center">
                                <span class="bg-secondary py-2 px-3 rounded me-2 text-white" style="font-size: 11pt;">OID-</span>
                                <input type="text" name="id" value="{{old('id')}}" class="form-control @error('id') is-invalid @enderror" id="">
                            </div>
                            @error('id')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group my-4">
                        <label for="" class="mb-2">Enter your order email</label>
                        <div>
                            <input type="email" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" id="ticket-btn">Check</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')

@endsection
