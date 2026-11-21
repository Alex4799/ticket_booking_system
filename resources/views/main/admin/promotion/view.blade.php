@extends('main.admin.layout.master')
@section('title')
    Promotion
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin#promotionList')}}">List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="shadow-container-box pb-0">
            <div>
                <div class="row">
                    <div class="col-md-3 movie-view">
                        <div>
                            <div class="poster-content">
                                @if ($promotion->image==null)
                                    <img src="{{asset('image/placeholder.webp')}}" alt="default image" class="img-thumbnail">
                                @else
                                    <img src="{{Storage::url('promotion/'.$promotion->image)}}" alt="promotion image" class="img-thumbnail">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 ps-md-5">
                        <div class="seat-view">
                            <div class="seat-detail-top border-none">
                                <div class="seat-title">
                                    <div>
                                        <h4>{{$promotion->name}}</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#editPromotion"><i class="fa-solid fa-pen-to-square me-2"></i>Edit</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePromotion" style="font-size: 11pt;"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="seat-detail-footer pt-2">
                                <div>
                                    <label>Cinema</label>
                                    <h6>
                                        @if ($promotion->cinema_id==0)
                                            All Cinema
                                        @else
                                            {{$promotion->cinema_name}}
                                        @endif
                                    </h6>
                                </div>
                                <div>
                                    <label>Hall</label>
                                    <h6>
                                        @if ($promotion->hall_id==0)
                                            All Hall
                                        @else
                                            {{$promotion->hall_name}}
                                        @endif
                                    </h6>
                                </div>
                                <div>
                                    <label>Movie</label>
                                    <h6>
                                        @if($promotion->movie_id==0)
                                            All Movie
                                        @else
                                            {{$promotion->movie_name}}
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="movie-view">
                            <div class="details px-0">
                                <h6 class="section-label">Details</h6>
                                <div class="details-grid">
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-key me-2"></i>Promo Code
                                        </span>
                                        <strong>{{$promotion->promo_code}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-play me-2"></i>Start Date
                                        </span>
                                        <strong>{{$promotion->start_date}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-flag-checkered me-2"></i>End Date
                                        </span>
                                        <strong>{{$promotion->end_date}}</strong>
                                    </div>
                                    @if ($promotion->percentage!=null)
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-tag me-2"></i>Percentage
                                            </span>
                                            <strong>{{$promotion->percentage}} %</strong>
                                        </div>
                                    @endif
                                    @if ($promotion->amount!=null)
                                        <div class="detail-item">
                                            <span>
                                                <i class="fa-solid fa-money-bill-wave me-2"></i>Amount
                                            </span>
                                            <strong>{{number_format($promotion->amount)}} Ks</strong>
                                        </div>
                                    @endif
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-gift me-2"></i>Max Count
                                        </span>
                                        <strong>{{$promotion->max_count}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-hourglass-half me-2"></i>Use Count
                                        </span>
                                        <strong>{{$promotion->used_count}}</strong>
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-circle me-2"></i>Feature
                                        </span>
                                        @if ($promotion->feature==0)
                                            <strong class="text-danger">
                                                <i class="fa-solid fa-circle-xmark me-1"></i>
                                                False
                                            </strong>
                                        @else
                                            <strong class="text-success">
                                                <i class="fa-solid fa-circle-check me-1"></i>
                                                True
                                            </strong>
                                        @endif
                                    </div>
                                    <div class="detail-item">
                                        <span>
                                            <i class="fa-solid fa-circle me-2"></i>Status
                                        </span>
                                        @if ($promotion->active==0)
                                            <strong class="text-danger">
                                                <i class="fa-solid fa-circle-xmark me-1"></i>
                                                Not Active
                                            </strong>
                                        @else
                                            <strong class="text-success">
                                                <i class="fa-solid fa-circle-check me-1"></i>
                                                Active
                                            </strong>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="summary px-0">
                                <h6 class="section-label">Description</h6>
                                <div class="movie-summary">
                                    {!!$promotion->description!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- model  --}}
        <div>
            <div class="modal fade" id="editPromotion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-promotionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="edit-promotionLabel">Edit Promotion</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#promotionUpdate')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="update-btn-group">
                                    <div class="row">
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Name</label>
                                            <input name="name" value="{{$promotion->name}}" type="text" class="form-control" required>
                                            @error('name')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 form-group mb-4">
                                            <label class="mb-1">Cinema</label>
                                            <select name="cinema_id" id="" class="form-select">
                                                <option value="0" @if ($promotion->cinema_id==0) selected @endif>All Cinema</option>
                                                @foreach ($cinema as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$promotion->cinema_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('cinema_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Hall</label>
                                            <select name="hall_id" id="" class="form-select">
                                                <option value="0">All Hall</option>
                                                @foreach ($hall as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$promotion->hall_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('hall_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Movie</label>
                                            <select name="movie_id" id="" class="form-select">
                                                <option value="0">All Movie</option>
                                                @foreach ($movie as $item)
                                                    <option value="{{$item->id}}" @if ($item->id==$promotion->movie_id) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('movie_id')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Promo Code</label>
                                            <input type="text" name="promo_code" id="" value="{{$promotion->promo_code}}" class="form-control" required>
                                            @error('promo_code')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Max Count</label>
                                            <input type="number" name="max_count" id="" value="{{$promotion->max_count}}" class="form-control" required>
                                            @error('max_count')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Percentage ( % )</label>
                                            <input type="number" name="percentage" value="{{$promotion->percentage}}" class="form-control">
                                            @error('percentage')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Amount ( Ks )</label>
                                            <input type="number" name="amount" id="" value="{{$promotion->amount}}" class="form-control">
                                            @error('amount')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-5 form-group mb-5">
                                            <label class="mb-1">Image</label>
                                            <div>
                                            @if ($promotion->image==null)
                                                    <img src="{{asset('image/placeholder.webp')}}" alt="default image" class="img-thumbnail" id="image">
                                                @else
                                                    <img src="{{Storage::url('promotion/'.$promotion->image)}}" alt="promotion image" class="img-thumbnail" id="image">
                                                @endif
                                                <div>
                                                    <label for="input_image" class="file-pen-logo mt-3">
                                                        <span><i class="fa-solid fa-upload me-1"></i> Upload Image</span>
                                                    </label>
                                                    <input type="file" name="image" class="d-none @error('image') is-invalid  @enderror" id="input_image">
                                                </div>
                                                @error('image')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-7 ps-sm-4 form-group mb-5">
                                            <label class="mb-1">Description</label>
                                            <textarea name="description" id="editor" cols="30" rows="10" class="form-control">{{$promotion->description}}</textarea>
                                            @error('description')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Start Date</label>
                                            <input id="" name="start_date" value="{{$promotion->start_date}}" type="date" class="form-control" required>
                                            @error('start_date')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">End Date</label>
                                            <input id="" name="end_date" value="{{$promotion->end_date}}" type="date" class="form-control" required>
                                            @error('end_date')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Status</label>
                                            <select name="active" id="" class="form-select">
                                                <option value="0" @if ($promotion->active==0) selected @endif>Not Active</option>
                                                <option value="1" @if ($promotion->active==1) selected @endif>Active</option>
                                            </select>
                                            @error('active')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 form-group mb-4">
                                            <label class="mb-1">Feature</label>
                                            <select name="feature" id="" class="form-select">
                                                <option value="0" @if ($promotion->feature==0) selected @endif>False</option>
                                                <option value="1" @if ($promotion->feature==1) selected @endif>True</option>
                                            </select>
                                            @error('featured')
                                                <small class=" text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$promotion->id}}">
                                    <div class="d-flex align-items-center my-2">
                                        <button type="submit" class="action-btn w-100" disabled><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="deletePromotion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deletePromotionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title d-block w-100 text-center" id="deletePromotionLabel">Delete Promotion</h5>
                            <button type="button" class="btn-close border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#promotionDelete')}}" method="post" class="mt-1">
                                @csrf
                                @method('DELETE')
                                <div class="delete-confirmation">
                                    <div class="delete-warning">
                                        <div class="delete-icon">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </div>
                                        <div class="delete-message">
                                            <h6>Are you sure to delete this?</h6>
                                            <small>Verify you are admin. Please enter the password.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group my-3">
                                    <label class="mb-1">Password</label>
                                    <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <input type="hidden" name="id" value="{{$promotion->id}}">

                                <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
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
        $(document).ready(function(){
            $('#input_image').change(function(){
                document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
            })
        })
    </script>
    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph
        } from 'ckeditor5';

        const editorElement = document.querySelector('#editor');
        const form = document.querySelector('.update-btn-group');
        const button = form.querySelector('.action-btn');

        ClassicEditor
            .create(editorElement, {
                plugins: [
                    Essentials,
                    Bold,
                    Italic,
                    Font,
                    Paragraph
                ],
                toolbar: {
                    items: [
                        'undo',
                        'redo',
                        '|',
                        'bold',
                        'italic',
                        '|',
                        'fontSize',
                        'fontFamily',
                        'fontColor',
                        'fontBackgroundColor'
                    ]
                }
            })
            .then(editor => {
                const originalEditorValue = editor.getData();
                const originalValues = {};
                form.querySelectorAll('input, select, textarea').forEach(input => {
                    if (input.id !== 'editor') {
                        originalValues[input.name] = input.value;
                    }
                });
                function checkChanges() {
                    let hasChanged = false;
                    form.querySelectorAll('input, select, textarea').forEach(input => {
                        if (input.id === 'editor') {
                            return;
                        }
                        if (input.value !== originalValues[input.name]) {
                            hasChanged = true;
                        }
                    });
                    if (editor.getData() !== originalEditorValue) {
                        hasChanged = true;
                    }
                    button.disabled = !hasChanged;
                }
                form.querySelectorAll('input, select, textarea').forEach(input => {
                    if (input.id !== 'editor') {
                        input.addEventListener('input', checkChanges);
                        input.addEventListener('change', checkChanges);
                    }
                });
                editor.model.document.on('change:data', checkChanges);
                checkChanges();
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
