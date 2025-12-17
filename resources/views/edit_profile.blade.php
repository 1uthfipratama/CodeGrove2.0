@extends('template')

@section('title', 'Edit Profile')

@section('content')
    @include('navbar')
    <main class="cg-container">
        <div class="cg-card cg-form">
            <h2 class="mb-3">Edit Profile</h2>
            <p class="text-muted">Refresh your avatar, change your password, or update your birthday.</p>
            <form method="POST" action="/edit-profile" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 text-center">
                    <input type="file" class="form-control visually-hidden" id="profile_picture" name="profile_picture" onchange="displayImage(this)">
                    <label for="profile_picture" class="d-inline-block">
                        <img src="{{ asset($profile_picture) }}" class="cg-profile-avatar" alt="Profile Picture" width="150" height="150">
                        <div class="small text-muted mt-2">Tap to update photo</div>
                    </label>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="cg-input" id="username" name="username" value="{{ Auth::user()->username }}" disabled>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="cg-input" id="email" name="email" value="{{ Auth::user()->email }}" disabled>
                </div>
                <div class="mb-3">
                    <label for="old_password" class="form-label">Old Password</label>
                    <input type="password" class="cg-input" id="old_password" name="old_password">
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="cg-input" id="new_password" name="new_password">
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="cg-input" id="dob" name="dob" value="{{ Auth::user()->dob }}">
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="/profile" class="cg-btn-secondary">Cancel</a>
                    <button type="submit" class="cg-btn-primary">Save</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function displayImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(input).next().find('img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
