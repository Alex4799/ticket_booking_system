@extends('main.admin.layout.master')
@section('title')
    Movies Plan
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div class="choose-info-text mb-4">
            <h6><i class="fa-solid fa-circle-info me-2"></i>Choose a cinema to create a movie plan.</h6>
        </div>
        <div class="accordion cinema-accordion" id="cinemaAccordion">
            @foreach ($cinema as $item)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $item['id'] }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $item['id'] }}">

                            <span class="cinema-icon">
                                <i class="fa-solid fa-building"></i>
                            </span>
                            <span class="cinema-info">
                                <span class="cinema-name">{{ $item['name'] }}</span>
                                <small>{{ count($item['hall']) }} Halls Available</small>
                            </span>
                        </button>
                    </h2>

                    <div id="collapse{{ $item['id'] }}"
                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                        data-bs-parent="#cinemaAccordion">

                        <div class="accordion-body">
                            <div class="hall-grid">
                                @forelse ($item['hall'] as $hall)
                                    <a href="{{ route('admin#moviePlanList', $hall['id']) }}" class="hall-item">
                                        <span class="hall-icon">
                                            <i class="fa-solid fa-door-open"></i>
                                        </span>
                                        <span class="hall-name">
                                            {{ $hall['name'] }}  @if ($hall['du_id']!=null) (Copy) @endif
                                        </span>
                                        <i class="fa-solid fa-arrow-right hall-arrow"></i>
                                    </a>
                                @empty
                                    <h6 class="text-secondary" style="font-size: 9pt;">No halls available.</h6>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>
@endsection
