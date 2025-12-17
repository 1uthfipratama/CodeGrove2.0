@extends('template')

@section('title', 'Admin Dashboard')

@section('content')
    @include('navbar')
    <main class="cg-container">
        <section class="cg-hero mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <div class="cg-hero-title">Admin moderation</div>
                    <div class="cg-hero-subtitle">Review, archive, and curate the best of CodeGrove.</div>
                </div>
                <a href="/add-question" class="cg-btn-primary">Create Announcement</a>
            </div>
        </section>

        <div class="cg-card cg-filter-card mb-4">
            <form action="/admin" method="get" id="filterForm" class="cg-form">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Sort by</label>
                        <select class="cg-select" aria-label="Sort posts" name="sort" onchange="document.getElementById('filterForm').submit();">
                            <option value="newToOld" {{ isset($sort) && $sort === 'newToOld' ? 'selected' : '' }}>🕒 Newest First</option>
                            <option value="oldToNew" {{ isset($sort) && $sort === 'oldToNew' ? 'selected' : '' }}>⏰ Oldest First</option>
                            <option value="AZ" {{ isset($sort) && $sort === 'AZ' ? 'selected' : '' }}>🔤 A to Z</option>
                            <option value="ZA" {{ isset($sort) && $sort === 'ZA' ? 'selected' : '' }}>🔤 Z to A</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Filter by Language</label>
                        <select class="cg-select" aria-label="Filter by language" name="language" onchange="document.getElementById('filterForm').submit();">
                            <option selected value={{-1}}>All</option>
                            @foreach ($languages as $language)
                                <option value={{$language->id}} {{ isset($selectedLanguage) && $selectedLanguage == $language->id ? 'selected' : '' }}>{{$language->programming_language_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" value="{{ $search ?? '' }}" class="cg-input" placeholder="Search threads...">
                    </div>
                </div>
            </form>
        </div>

        @php
            $sortLabel = 'Newest First';
            if (isset($sort)) {
                $sortLabel = match($sort) {
                    'oldToNew' => 'Oldest First',
                    'AZ' => 'A to Z',
                    'ZA' => 'Z to A',
                    default => 'Newest First',
                };
            }

            $languageLabel = 'All Languages';
            if (isset($selectedLanguage) && $selectedLanguage != -1) {
                $language = $languages->firstWhere('id', $selectedLanguage);
                $languageLabel = $language ? $language->programming_language_name : 'Selected Language';
            }

            $searchTerm = $search ?? null;
            $isFiltered = ((isset($selectedLanguage) && $selectedLanguage != -1) || (isset($sort) && $sort !== 'newToOld') || (!empty($searchTerm)));
        @endphp

        <div class="cg-card d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3 mb-4">
            <div class="d-flex flex-column flex-lg-row align-items-center gap-3 text-center text-lg-start w-100">
                <div>📂 Viewing: {{ $languageLabel === 'All Languages' ? 'All Languages' : $languageLabel . ' Posts' }}</div>
                <div>🔄 Sorted by: {{ $sortLabel }}</div>
                @if(!empty($searchTerm))
                    <div>🔍 Search: {{ $searchTerm }}</div>
                @endif
                <div>📊 {{ $posts->total() }} posts</div>
            </div>
            @if($isFiltered)
                <a href="{{ url()->current() }}" class="cg-btn-secondary btn-sm">Clear Filters</a>
            @endif
        </div>

        <section class="cg-post-grid">
            @foreach ($posts as $post)
                @php
                    $languageName = $post->programmingLanguage->programming_language_name;
                    $likesCount = $post->likes_count ?? ($post->likes->count() ?? 0);
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
                        <div class="d-flex flex-column align-items-end gap-2">
                            <span class="cg-status-badge {{ $post->status === 'archived' ? 'cg-status-archived' : 'cg-status-active' }}">{{ucwords($post->status)}}</span>
                            <span class="cg-language-badge" data-lang="{{$languageName}}">{{$languageName}}</span>
                            @if($likesCount >= 10)
                                <span class="cg-badge-trending">🔥 Trending</span>
                            @endif
                        </div>
                    </div>

                    <p class="mb-3 text-truncate" style="-webkit-line-clamp:3; display:-webkit-box; -webkit-box-orient: vertical; overflow:hidden;">{{$post->post_content}}</p>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3 text-muted">
                            <span>❤️ {{$likesCount}}</span>
                            <span>💬 {{ $post->replies_count }} replies</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/post/{{$post->id}}" class="cg-btn-secondary btn-sm">Open</a>
                            <form action="/archive-post" method="post">
                                @csrf
                                <input type="hidden" value={{$post->id}} name="post_id">
                                <button type="submit" class="cg-btn-primary btn-sm">Archive</button>
                            </form>
                        </div>
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
