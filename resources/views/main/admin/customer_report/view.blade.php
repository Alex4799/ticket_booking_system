@extends('main.admin.layout.master')
@section('title')
    Message
@endsection
@section('content')
<main class="content px-4 py-3 mb-5">
    <div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('admin#customerReportList')}}">List</a></li>
              <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div class="p-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{session('warning')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{session('danger')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-md-10 offset-md-1 card shadow border-0 p-3">
                <h4 class=" text-center py-2 mb-4">{{$message->subject}}</h4>
                <div class="">
                    <div class="py-2 px-3">
                        <h6>Name - {{$message->name}}</h6>
                    </div>
                    <div class="py-2 px-3">
                        <h6>Email - {{$message->email}}</h6>
                    </div>
                </div>
                <div class="px-3 mt-3">
                    <p class=" text-justify">{!!$message->message!!}</p>
                </div>
                <div class="py-2 d-flex justify-content-between px-3">
                    <div>
                        {{$message->created_at}}
                    </div>
                    <div>
                        Status - @if ($message->status==0)
                                    <span class=" text-warning">Deliver</span>
                                @else
                                    <span class=" text-success">Seen</span>
                                @endif
                    </div>
                </div>
                <div class="py-2 d-flex justify-content-end gap-1 mt-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reply">Reply</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="p-2 mt-5">
            <h5 class="py-2">Reply Message</h5>
            <div>
                @foreach ($replyMessage as $item)
                <div class="row">
                    <div class="col-md-10 offset-md-1 card shadow border-0 p-3">
                        <h4 class=" text-center py-2 mb-3">{{$item->subject}}</h4>
                        <div class="">
                            <div class="py-2 px-3">
                                <h5>Name - {{$item->name}}</h5>
                            </div>
                            <div class="py-2 px-3">
                                <h5>Email - {{$item->email}}</h5>
                            </div>
                        </div>
                        <div class="px-3 nt-2">
                            <p class=" text-justify">{!!$item->message!!}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        {{-- model  --}}
        <div class="modal fade" id="reply" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="replyLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title d-block w-100 text-center" id="replyLabel">Reply Message</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#customerReportReply')}}" method="post">
                            @csrf
                            <div class="">
                                <div class="form-group">
                                    <input type="hidden" name="email" value="{{Auth::user()->email}}">
                                    <input type="hidden" name="name" value="{{Auth::user()->name}}">
                                    <input type="hidden" name="reply_id" value="{{$message->id}}">
                                </div>
                                <div class="form-group mb-4">
                                    <label class="mb-1">Title</label>
                                    <input name="subject" value="Reply {{$message->subject}}" id="" type="text"class="form-control @error('title') is-invalid  @enderror" placeholder="Enter message title">
                                    @error('title')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label class="mb-1">Message</label>
                                    <textarea name="message" id="editor" cols="30" rows="10" placeholder="Enter message"></textarea>
                                    @error('message')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="py-2 d-flex justify-content-end">
                                    <button class="btn btn-primary w-100" type='submit'>Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')
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
