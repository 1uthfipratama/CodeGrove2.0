@extends('template')

@section('title', 'Edit Profile')

@section('content')
    @include('navbar')
    <main class="cg-container">
        <div class="cg-card cg-form">
            <h2 class="mb-3">Edit Profile</h2>
            <p class="text-muted">Refresh your avatar, change your password, or update your birthday.</p>
            <form id="editProfileForm" method="POST" action="/edit-profile" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reset_photo" id="reset_photo" value="0">
                <div class="mb-3 text-center">
                    <input type="file" class="form-control visually-hidden" id="profile_picture" name="profile_picture" onchange="displayImage(this)">
                    <label for="profile_picture" class="d-inline-block">
                        <img src="{{ asset($profile_picture) }}" class="cg-profile-avatar" alt="Profile Picture" width="150" height="150">
                        <div class="small text-muted mt-2">Tap to update photo</div>
                    </label>

                    @php
                        $hasCustomPhoto = false;
                        if (Auth::user() && Auth::user()->display_picture_path) {
                            $dp = Auth::user()->display_picture_path;
                            // treat these filenames as default placeholders
                            $defaults = ['default.svg', 'defaultcopy.svg', 'gg--profile.svg', 'gg--profile.png'];
                            if (!in_array($dp, $defaults)) {
                                $hasCustomPhoto = true;
                            }
                        }
                    @endphp
                    @if($hasCustomPhoto)
                        <div class="mt-2">
                            <button type="button" id="resetPhotoBtn" class="btn btn-link text-danger small">Reset to default</button>
                        </div>
                    @endif
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
                <div class="mb-3">
                    <label class="form-label">Preferred languages</label>
                    <input type="hidden" name="languages_submitted" value="1">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($languages as $language)
                            @php
                                $isChecked = in_array($language->id, $selectedLanguageIds ?? []);
                            @endphp
                            <label class="cg-card d-flex align-items-center gap-2" style="padding: 10px 12px;">
                                <input type="checkbox" name="selected_languages[]" value="{{ $language->id }}" {{ $isChecked ? 'checked' : '' }}>
                                <img src="{{ asset('storage/' . $language->programming_language_image_path) }}" alt="{{ $language->programming_language_name }} icon" width="28" height="28">
                                <span>{{ $language->programming_language_name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="/profile" id="cancelBtn" class="cg-btn-secondary">Cancel</a>
                    <button type="submit" class="cg-btn-primary">Save</button>
                </div>
            </form>
            <form action="/delete-account" method="post" class="mt-3" onsubmit="return confirm('Delete your account? This cannot be undone.');">
                @csrf
                <button type="submit" class="cg-btn-secondary text-danger">Delete account</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('resetPhotoBtn');
            if (!btn) return;

            btn.addEventListener('click', function () {
                if (!confirm('Reset your profile photo to the default image? This will be applied when you click Save.')) return;

                var form = document.getElementById('editProfileForm');
                var tokenInput = form ? form.querySelector('input[name="_token"]') : null;
                var token = tokenInput ? tokenInput.value : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // guard: if preview already shows default or reset flag is set, do nothing
                var mainAvatar = document.querySelector('.cg-profile-avatar');
                var resetInputExisting = document.getElementById('reset_photo');
                var srcIsDefault = false;
                if (mainAvatar && mainAvatar.src) {
                    srcIsDefault = mainAvatar.src.indexOf('/storage/asset/default.svg') !== -1
                        || mainAvatar.src.indexOf('/storage/images/defaultcopy.svg') !== -1
                        || mainAvatar.src.indexOf('/storage/asset/gg--profile.svg') !== -1
                        || mainAvatar.src.indexOf('/storage/asset/gg--profile.png') !== -1;
                }
                if (srcIsDefault || (resetInputExisting && resetInputExisting.value && resetInputExisting.value !== '0')) {
                    // already on default preview or already prepared; hide the button
                    btn.style.display = 'none';
                    return;
                }

                fetch('/edit-profile/copy-default', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }).then(function(res){
                    if (!res.ok) throw new Error('network');
                    return res.json();
                }).then(function(data){
                    if (data && data.filename) {
                        var resetInput = document.getElementById('reset_photo');
                        if (resetInput) resetInput.value = data.filename;

                        // store temp filename for potential deletion on cancel
                        var tempInput = document.getElementById('temp_photo');
                        if (!tempInput) {
                            tempInput = document.createElement('input');
                            tempInput.type = 'hidden';
                            tempInput.id = 'temp_photo';
                            tempInput.name = 'temp_photo';
                            form.appendChild(tempInput);
                        }
                        tempInput.value = data.filename;

                        // update only the local preview image
                        var defaultUrl = "{{ asset('storage/asset/default.svg') }}";
                        var mainAvatar = document.querySelector('.cg-profile-avatar');
                        if (mainAvatar) mainAvatar.src = defaultUrl + '?v=' + Date.now();
                        // hide reset button after preparing default
                        btn.style.display = 'none';
                    }
                }).catch(function(){
                    alert('Unable to prepare default image.');
                });
            });

            // handle cancel: if a temp photo was created, delete it before navigating away
            var cancelBtn = document.getElementById('cancelBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function (e) {
                    var tempInput = document.getElementById('temp_photo');
                    if (!tempInput || !tempInput.value) return; // no temp file
                    e.preventDefault();

                    var form = document.getElementById('editProfileForm');
                    var tokenInput = form ? form.querySelector('input[name="_token"]') : null;
                    var token = tokenInput ? tokenInput.value : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    fetch('/edit-profile/delete-temp', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token || '',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ filename: tempInput.value })
                    }).finally(function () {
                        // navigate away regardless
                        window.location.href = '/profile';
                    });
                });
            }
        });
    </script>
@endsection
