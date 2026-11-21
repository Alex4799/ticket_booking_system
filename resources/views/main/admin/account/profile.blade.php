@extends('main.admin.layout.master')
@section('title')
    Profile
@endsection

@section('content')
    <main class="content p-3">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="profile-card">
                        <div class="profile-top">
                            <div class="profile-avatar">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <h5>{{ Auth::user()->name }}</h5>
                            <span class="profile-position">{{ Auth::user()->position }}</span>
                        </div>
                        <div class="profile-body">
                            <div class="profile-info mb-4">
                                <span>Email</span>
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                            <div class="profile-info">
                                <span>Location</span>
                                <p>{{ $user->cinema_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="settings-card">
                        <div class="settings-header">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-gear me-2"></i>
                                <h5 class="mb-0">Account Settings</h5>
                            </div>
                        </div>
                        <div class="settings-tabs">
                            <button class="tab-btn active" data-target="edit-profile">
                                <i class="fa-solid fa-user me-2"></i>Edit Profile
                            </button>
                            <button class="tab-btn" data-target="change-password">
                                <i class="fa-solid fa-lock me-2"></i>Change Password
                            </button>
                        </div>
                        <div id="edit-profile" class="tab-content active">
                            <form action="{{route('admin#profileUpdate')}}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="update-btn-group">
                                    <div class="row mb-3">
                                        <div class="col-sm-6 mb-4">
                                            <div>
                                                <label class="form-label">Username</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}">
                                                @error('name')
                                                    <small class="text-danger">
                                                        {{ $message }}
                                                    </small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <div>
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}">
                                                @error('email')
                                                    <small class="text-danger">
                                                        {{ $message }}
                                                    </small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn-save" disabled>
                                        <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="change-password" class="tab-content">
                            <form action="{{ route('admin#changePassword') }}" method="POST">
                                @csrf
                                <div class="update-btn-group">
                                    <div class="mb-4">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" required>
                                        @error('old_password')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>
                                        @error('new_password')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control @error('con_password') is-invalid @enderror" name="con_password" required>
                                        @error('con_password')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn-save" disabled>
                                        <i class="fa-solid fa-key me-2"></i>Update Password
                                    </button>
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
    <script>
        // updatebtn disabled check
        const forms = document.querySelectorAll('.update-btn-group');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input');
            const button = form.querySelector('.btn-save');
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

