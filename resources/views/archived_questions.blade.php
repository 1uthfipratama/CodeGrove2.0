@extends('template')

@section('title', 'Home')
    
@section('content')
    @include('navbar')
    <div class="bg-white">
        <div class="container mt-5 mb-5">
            <form action="/archived-questions" method="get" id="filterForm">
            <div class="d-flex">
                    <select class="form-select form-select-sm mb-4 me-2" aria-label="Small select example" name="sort" onchange="document.getElementById('filterForm').submit();">
                        <option value="newToOld" {{ isset($sort) && $sort === 'newToOld' ? 'selected' : '' }}>Created Date (Newest to Oldest)</option>
                        <option value="oldToNew" {{ isset($sort) && $sort === 'oldToNew' ? 'selected' : '' }}>Created Date (Oldest to Newest)</option>
                        <option value="AZ" {{ isset($sort) && $sort === 'AZ' ? 'selected' : '' }}>A - Z</option>
                        <option value="ZA" {{ isset($sort) && $sort === 'ZA' ? 'selected' : '' }}>Z - A</option>
                    </select>
                    <select class="form-select form-select-sm mb-4 ms-2" aria-label="Small select example" name="language" onchange="document.getElementById('filterForm').submit();">
                        <option selected value={{-1}}>All</option>
                        @foreach ($languages as $language)
                            <option value={{$language->id}} {{ isset($selectedLanguage) && $selectedLanguage == $language->id ? 'selected' : '' }}>{{$language->programming_language_name}}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            @foreach ($posts as $post)
                <div class="card border border-secondary mb-4">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="margin-top: 10px">
                            <img src="{{ asset('storage/images/'.$post->user->display_picture_path) }}" class="rounded-circle" width="40" height="40" alt="User Image">
                            <span class="ms-2">{{$post->user->username}}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning" style="margin-right: 10px">{{ucwords($post->status)}}</span>
                            <span class="badge bg-success">{{$post->programmingLanguage->programming_language_name}}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{$post->post_content}}</p>
                        <hr class="my-4">
                        <a href="/post/{{$post->id}}" class="card-link">View all replies...</a>
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
