@extends('template')

@section('title', 'Archived Questions')

@section('content')
    @include('navbar')
    <main class="cg-container">
        <section class="cg-hero mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <div class="cg-hero-title">Archived questions</div>
                    <div class="cg-hero-subtitle">Review past discussions and restore any threads worth revisiting.</div>
                </div>
            </div>
        </section>

        <div class="cg-card cg-filter-card mb-4">
            <form action="/archived-questions" method="get" id="filterForm" class="cg-form">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Sort by</label>
                        <select class="cg-select" aria-label="Sort posts" name="sort" onchange="document.getElementById('filterForm').submit();">
                            <option value="newToOld" {{ isset($sort) && $sort === 'newToOld' ? 'selected' : '' }}>🕒 Newest First</option>
                            <option value="oldToNew" {{ isset($sort) && $sort === 'oldToNew' ? 'selected' : '' }}>⏰ Oldest First</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Filter by Language</label>
                        <select class="cg-select" aria-label="Filter by language" name="language" onchange="document.getElementById('filterForm').submit();">
                            <option selected value={{-1}}>All</option>
                            @foreach ($languages as $language)
                                <option value={{$language->id}} {{ isset($selectedLanguage) && $selectedLanguage == $language->id ? 'selected' : '' }}>{{$language->programming_language_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <section class="cg-post-grid">
            @foreach ($posts as $post)
                @php
                    $languageName = optional($post->programmingLanguage)->programming_language_name ?? 'Unknown';
                @endphp
                <article class="cg-card cg-post-card position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/images/'.$post->user->display_picture_path) }}" class="cg-profile-img" width="48" height="48" alt="User Image">
                            <div>
                                <div class="fw-semibold">{{$post->user->username}}</div>
                                <div class="cg-meta">{{$post->created_at ? $post->created_at->diffForHumans() : 'recently'}}</div>
                            </div>
                        </div>
                        <span class="cg-language-badge" data-lang="{{$languageName}}">{{$languageName}}</span>
                    </div>
                    <p class="mb-3 text-truncate" style="-webkit-line-clamp:3; display:-webkit-box; -webkit-box-orient: vertical; overflow:hidden;">{{$post->post_content}}</p>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="cg-status-badge cg-status-archived">Archived</div>
                        <form id="restoreForm" action="/restore-post" method="post">
                            @csrf
                            <input type="hidden" value={{$post->id}} name="post_id">
                            <button id="restoreButton" type="submit" class="cg-btn-primary btn-sm">Restore</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>

        @if ($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex flex-column align-items-center mt-4">
                <div class="text-muted small mb-2">
                    Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} results
                </div>
                <nav aria-label="Posts pagination" class="w-100 d-flex justify-content-center">
                    {{ $posts->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        @endif
    </main>
@endsection
