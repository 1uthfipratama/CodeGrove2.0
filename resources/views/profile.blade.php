@extends('template')

@section('title', 'Profile')

@section('content')
    @include('navbar')
    <main class="cg-container">
        <section class="cg-profile-hero mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-auto text-center">
                    <img src="{{ asset($profile_picture) }}" class="cg-profile-avatar mb-2 mb-md-0" alt="Profile Picture">
                </div>
                <div class="col">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <h1 class="mb-1">{{ $user->username }}</h1>
                            <div class="mt-2"><span class="cg-lines-badge">🔥 {{ $user->lines }} Lines</span></div>
                            @if (isset($membership))
                                <div class="mt-3">
                                    <span class="cg-status-badge cg-status-active">{{ $membership->subscription->subscription_name }}</span>
                                </div>
                                <form action="/remove-membership" method="post" class="mt-2 d-inline-block">
                                    @csrf
                                    <button class="cg-btn-secondary btn-sm">Unsubscribe</button>
                                </form>
                            @endif
                        </div>

                        <div class="d-flex flex-column align-items-end gap-2">
                            <a href="/edit-profile" class="cg-btn-secondary">Edit Profile</a>
                            <a href="/plans" class="cg-btn-primary">View Plans</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cg-stats-grid">
            <div class="cg-card cg-stat-card">
                <div class="cg-stat-number">{{$total_post_like}}</div>
                <div class="cg-text-muted">Posts liked</div>
            </div>
            <div class="cg-card cg-stat-card">
                <div class="cg-stat-number">{{$total_like_count}}</div>
                <div class="cg-text-muted">Likes received</div>
            </div>
            <div class="cg-card cg-stat-card">
                <div class="cg-stat-number">{{$top_posts->count()}}</div>
                <div class="cg-text-muted">Top posts</div>
            </div>
        </section>

        <section class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Languages</h2>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @forelse ($userLanguages as $language)
                    <span class="cg-language-badge" data-lang="{{ $language->programming_language_name }}">
                        <img src="{{ asset('storage/' . $language->programming_language_image_path) }}" alt="{{ $language->programming_language_name }} icon" class="language-icon">
                        {{ $language->programming_language_name }}
                    </span>
                @empty
                    <div class="text-muted">No languages selected yet.</div>
                @endforelse
            </div>
        </section>

        <section class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Top Posts</h2>
            </div>
            <div class="d-flex flex-column gap-3">
                @foreach ($top_posts as $post)
                    <article class="cg-card cg-post-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/images/'.$post->user->display_picture_path) }}" class="cg-profile-img" width="48" height="48" alt="User Image">
                                <div>
                                    <div class="fw-semibold">{{$post->user->username}}</div>
                                    <div class="cg-meta">{{$post->created_at ? $post->created_at->diffForHumans() : 'recently'}}</div>
                                </div>
                            </div>
                            @if(optional($post->programmingLanguage)->programming_language_name)
                                <span class="cg-language-badge" data-lang="{{ optional($post->programmingLanguage)->programming_language_name }}">{{ optional($post->programmingLanguage)->programming_language_name }}</span>
                            @endif
                        </div>
                        <p class="mb-3">{{$post->post_content}}</p>
                        <a href="/post/{{$post->id}}" class="cg-link">View all replies</a>
                    </article>
                @endforeach
            </div>
        </section>
    </main>
@endsection
