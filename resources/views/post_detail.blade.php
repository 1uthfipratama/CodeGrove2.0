@extends('template')

@section('title', 'Post Detail')

@section('content')
    @include('navbar')
    <div class="bg-white">
        <div class="container mt-5 d-flex flex-column align-items-center">
            <div class="card border border-secondary shadow-sm main-question-card" style="width: 100%">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/images/'.$post->user->display_picture_path) }}" class="rounded-circle" width="48" height="48" alt="User Image">
                        <div class="ms-3">
                            <div class="fw-bold d-flex align-items-center">
                                <span class="me-2">{{$post->user->username}}</span>
                                <span class="badge bg-primary-soft text-primary fw-semibold align-items-center d-inline-flex main-question-badge">
                                    ❓ Main Question
                                </span>
                            </div>
                            <div class="text-muted small">Posted {{ $post->created_at ? $post->created_at->diffForHumans() : 'recently' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-end">
                        <span class="language-pill d-inline-flex align-items-center">
                            <img src="{{ asset('storage/'.$post->programmingLanguage->programming_language_image_path ?? '') }}" alt="{{ $post->programmingLanguage->programming_language_name }}" class="language-icon me-2">
                            {{$post->programmingLanguage->programming_language_name}}
                        </span>
                        @if (Auth::user() && $post->user->id == Auth::user()->id)
                            <a href="/edit-post/{{$post->id}}" class="custom-button" aria-label="Edit post">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M3 17.46v3.04c0 .28.22.5.5.5h3.04c.13 0 .26-.05.35-.15L17.81 9.94l-3.75-3.75L3.15 17.1c-.1.1-.15.22-.15.36M20.71 7.04a.996.996 0 0 0 0-1.41l-2.34-2.34a.996.996 0 0 0-1.41 0l-1.83 1.83l3.75 3.75z"/></svg>
                            </a>
                        @endif
                        @if (Auth::user() && $post->user->id == Auth::user()->id)
                            <form id="deleteForm" action="/delete-post" method="post">
                                @csrf
                                <input type="hidden" value={{$post->id}} name="post_id">
                                <button id="deleteButton"  type="submit" class="btn btn-link" style="cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        @if (Auth::user() && Auth::user()->role == "admin")
                            <form id="archiveForm" action="/archive-post" method="post" style="margin-left: 10px">
                                @csrf
                                <input type="hidden" value={{$post->id}} name="post_id">
                                <button id="archiveButton" type="submit" class="btn btn-link" style="cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M64 164v244a56 56 0 0 0 56 56h272a56 56 0 0 0 56-56V164a4 4 0 0 0-4-4H68a4 4 0 0 0-4 4m267 151.63l-63.69 63.68a16 16 0 0 1-22.62 0L181 315.63c-6.09-6.09-6.65-16-.85-22.38a16 16 0 0 1 23.16-.56L240 329.37V224.45c0-8.61 6.62-16 15.23-16.43A16 16 0 0 1 272 224v105.37l36.69-36.68a16 16 0 0 1 23.16.56c5.8 6.37 5.24 16.29-.85 22.38"/><rect width="448" height="80" x="32" y="48" fill="currentColor" rx="32" ry="32"/></svg>
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
                <div class="card-body bg-light rounded">
                    <p class="card-text fs-6 mb-1">{{$post->post_content}}</p>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-muted small d-flex align-items-center gap-3">
                        <span class="d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3.293 12.707 8 8 12.707 3.293 8z"/></svg>
                            <span>{{ $likes }} {{ $likes === 1 ? 'user finds' : 'users find' }} this helpful</span>
                        </span>
                        @if(isset($post->views))
                            <span class="d-inline-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5a2.5 2.5 0 0 0 0-5"/></svg>
                                <span>{{ $post->views }} views</span>
                            </span>
                        @endif
                        <span class="d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 0 0 1H4v2.528a4.5 4.5 0 0 0-.679 7.8l-1.1 2.2A.5.5 0 0 0 2.67 15h2.139a.5.5 0 0 0 .447-.276l.896-1.792A4.5 4.5 0 1 0 8 4.5c-.54 0-1.06.097-1.54.273V1h.5a.5.5 0 0 0 0-1z"/></svg>
                            <span>{{ $replies->count() }} replies</span>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <form action="/like" method="post" class="me-2">
                            @csrf
                            <input type="hidden" value={{$post->id}} name="post_id">
                            <button id="likeButton" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2" type="submit">
                                @if (isset($userLike) && $userLike)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.25em" height="1.25em" viewBox="0 0 24 24"><path fill="currentColor" d="m12 21.35l-1.45-1.32C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5c0 3.77-3.4 6.86-8.55 11.53z"/></svg>
                                    Liked
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.25em" height="1.25em" viewBox="0 0 24 24"><path fill="currentColor" d="m12.1 18.55l-.1.1l-.11-.1C7.14 14.24 4 11.39 4 8.5C4 6.5 5.5 5 7.5 5c1.54 0 3.04 1 3.57 2.36h1.86C13.46 6 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5c0 2.89-3.14 5.74-7.9 10.05M16.5 3c-1.74 0-3.41.81-4.5 2.08C10.91 3.81 9.24 3 7.5 3C4.42 3 2 5.41 2 8.5c0 3.77 3.4 6.86 8.55 11.53L12 21.35l1.45-1.32C18.6 15.36 22 12.27 22 8.5C22 5.41 19.58 3 16.5 3"/></svg>
                                    Like
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="w-100 mt-5">
                <hr>
                @php $replyCount = $replies->count(); @endphp
                <div class="bg-light p-3 rounded d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        💬
                        @if($replyCount > 0)
                            Replies ({{ $replyCount }})
                        @else
                            No replies yet - Be the first to respond!
                        @endif
                    </h4>
                    <a href="#reply-form" class="btn btn-outline-primary btn-sm">Jump to reply form</a>
                </div>
            </div>

            @foreach ($replies as $index => $reply)
                @php
                    $isSolution = $reply->is_solution;
                    $backgroundClass = $isSolution ? 'solution-highlight' : ($index % 2 === 0 ? 'bg-white' : 'bg-light');
                @endphp
                <div class="card border {{ $isSolution ? 'border-warning solution-border' : 'border-secondary' }} shadow-sm reply-card {{ $backgroundClass }}" style="margin-left: 5%; margin-top: 30px; width:95%">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center" style="margin-top: 10px">
                            <span class="badge bg-secondary me-3">Reply #{{ $index + 1 }}</span>
                            <img src="{{ asset('storage/images/'.$reply->user->display_picture_path) }}" class="rounded-circle" width="40" height="40" alt="User Image">
                            <span class="ms-2 fw-semibold">{{$reply->user->username}}</span>
                            @if ($reply->is_solution)
                                <span class="badge bg-warning text-dark ms-3 d-flex align-items-center solution-pill">
                                    🏆 Accepted Solution
                                    <span class="badge bg-light text-dark ms-2">+5 Lines awarded</span>
                                </span>
                            @endif
                        </div>
                        <span class="language-pill d-inline-flex align-items-center">
                            <img src="{{ asset('storage/'.$reply->programmingLanguage->programming_language_image_path ?? '') }}" alt="{{ $reply->programmingLanguage->programming_language_name }}" class="language-icon me-2">
                            {{$reply->programmingLanguage->programming_language_name}}
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{$reply->post_content}}</p>
                        @if (Auth::check() && $post->user->id === Auth::user()->id && $reply->user->id !== Auth::user()->id)
                            <div class="mt-3">
                                @if (!$reply->is_solution)
                                    <form action="/post/{{$reply->id}}/mark-solution" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Mark as Solution</button>
                                    </form>
                                @else
                                    <form action="/post/{{$reply->id}}/unmark-solution" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Unmark Solution</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="/post/{{$reply->id}}" class="card-link">View all replies...</a>
                            <span class="text-muted small">Posted {{ $reply->created_at ? $reply->created_at->diffForHumans() : 'recently' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

            <form action="/post/{{$post->id}}" method="post" id="reply-form" class="w-100" style="position: fixed; bottom: 40px; width: 90%; align-items: center">
                @csrf
                <div class="input-group m-3" style="position: fixed; bottom: 40px; width: 90%">
                    <input type="hidden" name="programming_language_id" value={{$post->programmingLanguage->id}}>
                    <input type="hidden" name="post_id" value={{$post->id}}>
                    <textarea class="form-control" placeholder="Add a reply here..." rows="1" oninput="autoSize(this)" name="reply"></textarea>
                    <button class="btn btn-primary" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>

    <button class="btn btn-primary jump-reply-btn" id="jumpToReply" aria-label="Jump to reply form">✍️</button>

    <script>
        // Function to auto resize the textarea as the user types
        function autoSize(element) {
            element.style.height = "auto";
            element.style.height = (element.scrollHeight) + "px";
        }

        document.getElementById('jumpToReply').addEventListener('click', function (event) {
            event.preventDefault();
            const replyForm = document.getElementById('reply-form');
            if (replyForm) {
                replyForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
    <style>
        .custom-button {
            width: 32px;
            height: 32px;
            cursor: pointer;
            margin-left: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        .custom-button:hover { background-color: #e9ecef; }

        .language-pill {
            background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .language-icon {
            width: 22px;
            height: 22px;
            object-fit: contain;
        }

        .main-question-badge {
            background-color: #e7f1ff;
            border: 1px solid #cfe2ff;
            border-radius: 999px;
            padding: 4px 10px;
        }

        .main-question-card {
            border-left: 6px solid #0d6efd;
        }

        .reply-card {
            margin-left: 5%;
        }

        .solution-highlight {
            background: #fff8e1 !important;
        }

        .solution-border {
            border-width: 2px !important;
        }

        .solution-pill {
            font-size: 0.95rem;
            padding: 6px 10px;
            border-radius: 10px;
        }

        .jump-reply-btn {
            position: fixed;
            bottom: 100px;
            right: 24px;
            border-radius: 50%;
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
            z-index: 1050;
        }

        @media (max-width: 768px) {
            .reply-card {
                margin-left: 0;
                width: 100%;
            }

            .jump-reply-btn {
                bottom: 80px;
                right: 16px;
            }
        }
    </style>

@endsection
