@extends('main.admin.layout.master')
@section('title')
    Movie Plan
@endsection
@section('content')
<main class="content pt-4 px-4 mb-5">
    <div class="d-flex align-items-center justify-content-between">
        <div class="bg-white text-dark px-3 border rounded pt-2 h-100 text-center">
            <h5>Total - {{count($cinema)}}</h5>
        </div>
    </div>
    <div class="">
        <div class="my-3">
            <div>
                <h6><i class="fa-solid fa-circle-info me-2"></i>Choose a cinema to create a movie plan.</h6>
            </div>
            <div class="accordion" id="accordionExample">
                @foreach ($cinema as $item)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$item['id']}}" aria-expanded="@if ($loop->index==0) true @else false @endif" aria-controls="collapse{{$item['id']}}">
                            {{$item['name']}}
                        </button>
                        </h2>
                        <div id="collapse{{$item['id']}}" class="accordion-collapse collapse @if ($loop->index==0) show @endif" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="d-flex gap-1">
                                @foreach ($item['hall'] as $hall)
                                    <a href="{{route('admin#moviePlanList',$hall['id'])}}" class="btn btn-outline-success">{{$hall['name']}} @if ($hall['du_id']) (Copy) @endif</a>
                                @endforeach
                            </div>
                        </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection
