@extends('main.admin.layout.master')
@section('title')
    Customer Report
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin#customerReportList')}}">List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="row mt-2">
            <div class="col-sm-10 mx-sm-auto mb-5">
                <div class="conversation">
                    <div class="chat-message received">
                        <div class="chat-user">
                            <div class="chat-avatar">
                                {{ strtoupper(substr($message->email, 0, 1)) }}
                            </div>
                            <div class="chat-info">
                                <strong>{{ $message->email  }}</strong>
                                <span>{{ $message->created_at->format('M d, Y · h:i A') }}</span>
                            </div>
                        </div>
                        <div class="chat-bubble">
                            <div class="chat-title">{{$message->subject}}</div>
                            <div class="chat-content">
                                <div class="mb-3">
                                    Name : {{$message->name}}
                                </div>
                                {!! $message->message !!}
                            </div>
                            <div class="chat-status">
                                @if ($message->status == 0)
                                    <span class="text-warning">
                                        <i class="fa-regular fa-envelope me-1"></i>
                                        Delivered
                                    </span>
                                @else
                                    <span class="text-success">
                                        <i class="fa-solid fa-check-double me-1"></i>
                                        Seen
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @foreach ($replyMessage as $item)
                        @php
                            $isMe = Auth::user()->email == $item->email;
                        @endphp
                        <div class="chat-message sent">
                            <div class="chat-user">
                                <div class="chat-avatar">
                                    {{ strtoupper(substr($item->email, 0, 1)) }}
                                </div>
                                <div class="chat-info">
                                    <strong>{{ $isMe ? 'You' : $item->email }}</strong>
                                    <span>{{ $item->created_at->format('M d, Y · h:i A') }}</span>
                                </div>
                            </div>
                            <div class="chat-bubble">
                                <div class="chat-title">{{ $item->subject }}</div>
                                <div class="chat-content">{!! $item->message !!}</div>
                                <div class="chat-status">
                                    @if ($item->status == 0)
                                        <span class="text-warning">
                                            <i class="fa-regular fa-envelope me-1"></i>
                                            Delivered
                                        </span>
                                    @else
                                        <span class="text-success">
                                            <i class="fa-solid fa-check-double me-1"></i>
                                            Seen
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="message-bottom-action">
                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#reply">
                        <i class="fa-solid fa-reply me-2"></i>Reply
                    </button>
                </div>
            </div>
        </div>

        <div>
            <div class="modal fade" id="reply" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="replyLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="replyLabel">Reply Message</h4>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#customerReportReply')}}" method="post" class="mt-1">
                                @csrf
                                <div class="">
                                    <div class="form-group">
                                        <input type="hidden" name="email" value="{{Auth::user()->email}}">
                                        <input type="hidden" name="name" value="{{Auth::user()->name}}">
                                        <input type="hidden" name="reply_id" value="{{$message->id}}">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Title</label>
                                        <input name="subject" value="Reply {{$message->subject}}" id="" type="text" class="form-control @error('title') is-invalid  @enderror">
                                        @error('title')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="mb-1">Message</label>
                                        <textarea name="message" id="editor" cols="30" rows="10"></textarea>
                                        @error('message')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100"><i class="fa-solid fa-square-plus me-2"></i>Send</button>
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
