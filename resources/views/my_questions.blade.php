@extends('template')

@section('title', 'Home')
    
@section('content')
    @include('navbar')
    <div class="bg-white">
        <div class="container mt-5 mb-5">
            <form action="/my-questions" method="get" id="filterForm">
                <div class="filter-panel bg-light rounded p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <h5 class="mb-2 mb-md-0">Filters</h5>
                    </div>
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-6 col-lg-5">
                            <label class="form-label fw-semibold">Sort by:</label>
                            <select class="form-select enhanced-select" aria-label="Sort posts" name="sort" onchange="document.getElementById('filterForm').submit();">
                                <option value="newToOld" {{ isset($sort) && $sort === 'newToOld' ? 'selected' : '' }}>🕒 Newest First</option>
                                <option value="oldToNew" {{ isset($sort) && $sort === 'oldToNew' ? 'selected' : '' }}>⏰ Oldest First</option>
                                <option value="AZ" {{ isset($sort) && $sort === 'AZ' ? 'selected' : '' }}>🔤 A to Z</option>
                                <option value="ZA" {{ isset($sort) && $sort === 'ZA' ? 'selected' : '' }}>🔤 Z to A</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-5">
                            <label class="form-label fw-semibold">Filter by Language:</label>
                            <select class="form-select enhanced-select" aria-label="Filter by language" name="language" onchange="document.getElementById('filterForm').submit();">
                                <option selected value={{-1}}>All</option>
                                @foreach ($languages as $language)
                                    <option value={{$language->id}} {{ isset($selectedLanguage) && $selectedLanguage == $language->id ? 'selected' : '' }}>{{$language->programming_language_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>

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

            <div class="alert alert-info d-flex flex-column flex-lg-row align-items-center justify-content-center text-center gap-2 position-relative">
                <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center gap-3">
                    <div>📂 Viewing: {{ $languageLabel === 'All Languages' ? 'All Languages' : $languageLabel . ' Posts' }}</div>
                    <div>🔄 Sorted by: {{ $sortLabel }}</div>
                    @if(!empty($searchTerm))
                        <div>🔍 Search results for: {{ $searchTerm }}</div>
                    @endif
                    <div>📊 {{ $posts->total() }} posts</div>
                </div>
                @if($isFiltered)
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm ms-lg-3 mt-2 mt-lg-0">Clear Filters</a>
                @endif
            </div>
            @foreach ($posts as $post)
                <div class="card border border-secondary mb-4">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="margin-top: 10px">
                            <img src="{{ asset('storage/images/'.$post->user->display_picture_path) }}" class="rounded-circle" width="40" height="40" alt="User Image">
                            <span class="ms-2">{{$post->user->username}}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning text-dark me-2">{{ucwords($post->status)}}</span>
                            @php
                                $languageName = $post->programmingLanguage->programming_language_name;
                                $languageBadgeClass = 'bg-secondary';
                                if ($languageName === 'C') {
                                    $languageBadgeClass = 'bg-danger';
                                } elseif ($languageName === 'Java') {
                                    $languageBadgeClass = 'bg-warning text-dark';
                                } elseif ($languageName === 'HTML') {
                                    $languageBadgeClass = 'bg-info text-dark';
                                } elseif ($languageName === 'JavaScript') {
                                    $languageBadgeClass = 'bg-primary';
                                } elseif ($languageName === 'Python') {
                                    $languageBadgeClass = 'bg-success';
                                }
                            @endphp
                            <span class="badge language-badge {{$languageBadgeClass}}">
                                <img src="{{ asset('storage/'.$post->programmingLanguage->programming_language_image_path) }}" alt="{{$languageName}} icon" class="language-icon">
                                {{$languageName}}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{$post->post_content}}</p>
                        <hr class="my-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <a href="/post/{{$post->id}}" class="card-link">View all replies...</a>
                            <span class="badge bg-secondary text-muted ms-2 mt-2 mt-sm-0">
                                @if($post->replies_count === 0)
                                    💬 No replies yet
                                @elseif($post->replies_count === 1)
                                    💬 1 reply
                                @else
                                    💬 {{ $post->replies_count }} replies
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="pagination-wrapper">
                    <div class="text-muted small mb-2">
                        Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} results
                    </div>
                    <nav aria-label="Posts pagination" class="w-100 d-flex justify-content-center">
                        {{ $posts->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            @endif
        </div>
        
        

        <a href="/add-question" class="custom-button">
            <div class="plus-symbol">+</div>
        </a>

    </div>

    <style>
        .custom-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: white;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            text-align: center;
            line-height: 50px;
            cursor: pointer;
            transition: background-color 0.3s;
            position: fixed;
            bottom: 30px;
            right: 30px;
        }
    
        .custom-button:hover {
            background-color: rgb(214, 214, 214);
        }
    
        .plus-symbol {
            color: black;
            font-size: 24px;
        }

        .pagination-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pagination-wrapper .pagination {
            margin-bottom: 0;
        }
    </style>
@endsection
