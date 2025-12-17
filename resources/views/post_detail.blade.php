@extends('template')

@section('title', 'Post Detail')

@section('content')
    @include('navbar')
    <main class="cg-container">
        <div class="cg-card cg-post-card mb-4" style="border-left: 6px solid var(--cg-primary);">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset('storage/images/'.$post->user->display_picture_path) }}" class="cg-profile-img" width="64" height="64" alt="User Image">
                    <div>
                        <div class="fw-bold d-flex align-items-center gap-2">
                            <span>{{$post->user->username}}</span>
                            <span class="cg-status-badge cg-status-active d-inline-flex align-items-center">❓ Main Question</span>
                        </div>
                        <div class="cg-meta">Posted {{ $post->created_at ? $post->created_at->diffForHumans() : 'recently' }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-end">
                    <span class="cg-language-badge" data-lang="{{$post->programmingLanguage->programming_language_name}}">
                        <img src="{{ asset('storage/'.$post->programmingLanguage->programming_language_image_path ?? '') }}" alt="{{ $post->programmingLanguage->programming_language_name }}" class="language-icon">
                        {{$post->programmingLanguage->programming_language_name}}
                    </span>
                    @if (Auth::user() && $post->user->id == Auth::user()->id)
                        <a href="/edit-post/{{$post->id}}" class="cg-btn-secondary btn-sm d-flex align-items-center gap-1">Edit</a>
                    @endif
                    @if (Auth::user() && $post->user->id == Auth::user()->id)
                        <form id="deleteForm" action="/delete-post" method="post">
                            @csrf
                            <input type="hidden" value={{$post->id}} name="post_id">
                            <button id="deleteButton" type="submit" class="cg-btn-secondary btn-sm">Delete</button>
                        </form>
                    @endif
                    @if (Auth::user() && Auth::user()->role == "admin")
                        <form id="archiveForm" action="/archive-post" method="post">
                            @csrf
                            <input type="hidden" value={{$post->id}} name="post_id">
                            <button id="archiveButton" type="submit" class="cg-btn-secondary btn-sm">Archive</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="p-3" style="background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(139,92,246,0.06)); border-radius: 14px;">
                <p class="fs-5 mb-0">{{$post->post_content}}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">
                <div class="text-muted small d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center gap-1">❤️ {{ $likes }} likes</span>
                    @if(isset($post->views))
                        <span class="d-inline-flex align-items-center gap-1">👁️ {{ $post->views }} views</span>
                    @endif
                    <span class="d-inline-flex align-items-center gap-1">💬 {{ $replies->count() }} replies</span>
                </div>
                <form action="/like" method="post" class="d-flex align-items-center gap-2">
                    @csrf
                    <input type="hidden" value={{$post->id}} name="post_id">
                    <button id="likeButton" class="cg-btn-secondary btn-sm d-flex align-items-center gap-2" type="submit" data-like-button>
                        @if (isset($userLike) && $userLike)
                            <span>❤️</span> Liked
                        @else
                            <span>🤍</span> Like
                        @endif
                    </button>
                </form>
            </div>
        </div>

        @php $replyCount = $replies->count(); @endphp
        <div class="cg-card mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="mb-0 fw-bold d-flex align-items-center gap-2 text-gradient">💬 {{ $replyCount > 0 ? 'Replies (' . $replyCount . ')' : 'No replies yet - Be the first!' }}</h4>
            <a href="#reply-form" class="cg-btn-secondary btn-sm">Jump to reply form</a>
        </div>

        <div class="d-flex flex-column gap-3">
            @foreach ($replies as $index => $reply)
                @php
                    $isSolution = $reply->is_solution;
                @endphp
                <div class="cg-card cg-reply-card {{ $isSolution ? 'solution' : '' }}">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-secondary">Reply #{{ $index + 1 }}</span>
                            <img src="{{ asset('storage/images/'.$reply->user->display_picture_path) }}" class="cg-profile-img" width="48" height="48" alt="User Image">
                            <div>
                                <div class="fw-semibold d-flex align-items-center gap-2">{{$reply->user->username}}
                                    @if ($reply->is_solution)
                                        <span class="cg-status-badge cg-status-solution">✓ Solution</span>
                                    @endif
                                </div>
                                <div class="cg-meta">{{ $reply->created_at ? $reply->created_at->diffForHumans() : 'recently' }}</div>
                            </div>
                        </div>
                        <span class="cg-language-badge" data-lang="{{$reply->programmingLanguage->programming_language_name}}">
                            <img src="{{ asset('storage/'.$reply->programmingLanguage->programming_language_image_path ?? '') }}" alt="{{ $reply->programmingLanguage->programming_language_name }}" class="language-icon">
                            {{$reply->programmingLanguage->programming_language_name}}
                        </span>
                    </div>

                    <p class="mt-3 mb-2">{{$reply->post_content}}</p>
                    @if (Auth::check() && $post->user->id === Auth::user()->id && $reply->user->id !== Auth::user()->id)
                        <div class="d-flex gap-2 mt-2">
                            @if (!$reply->is_solution)
                                <form action="/post/{{$reply->id}}/mark-solution" method="post">
                                    @csrf
                                    <button type="submit" class="cg-btn-primary btn-sm">Mark as Solution</button>
                                </form>
                            @else
                                <form action="/post/{{$reply->id}}/unmark-solution" method="post">
                                    @csrf
                                    <button type="submit" class="cg-btn-secondary btn-sm">Unmark Solution</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <form action="/post/{{$post->id}}" method="post" id="reply-form" class="cg-card cg-form mt-4 position-sticky" style="bottom: 20px;">
            @csrf
            <input type="hidden" name="programming_language_id" value={{$post->programmingLanguage->id}}>
            <input type="hidden" name="post_id" value={{$post->id}}>
            <label for="reply" class="form-label">Add a reply</label>
            <textarea class="cg-textarea" placeholder="Add a reply here..." rows="3" name="reply"></textarea>
            <div class="d-flex justify-content-end mt-3">
                <button class="cg-btn-primary" type="submit">Send 💌</button>
            </div>
        </form>
    </main>

    <button class="cg-back-to-top" id="cgBackToTop" aria-label="Back to top">↑</button>
@endsection
