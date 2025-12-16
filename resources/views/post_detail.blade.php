@extends('template')

@section('title', 'Post Detail')
    
@section('content')
    @include('navbar')
    <div class="bg-white">
        <div class="container mt-5 d-flex flex-column align-items-center">
            <div class="card border border-secondary" style="width: 100%">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/images/'.$post->user->display_picture_path) }}" class="rounded-circle" width="40" height="40" alt="User Image">
                        <span class="ms-2">{{$post->user->username}}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success">{{$post->programmingLanguage->programming_language_name}}</span>
                        @if (Auth::user() && $post->user->id == Auth::user()->id)
                            <a href="/edit-post/{{$post->id}}" class="custom-button">
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
                <div class="card-body">
                    <p class="card-text">{{$post->post_content}}</p>
                </div>
                <div class="card-body d-flex justify-content-end align-items-center">
                    <form action="/like" method="post">
                        @csrf
                        <input type="hidden" value={{$post->id}} name="post_id">
                        <button id="likeButton" class="btn btn-link" type="submit">
                            @if (isset($userLike) && $userLike)
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24"><path fill="currentColor" d="m12 21.35l-1.45-1.32C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5c0 3.77-3.4 6.86-8.55 11.53z"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24"><path fill="currentColor" d="m12.1 18.55l-.1.1l-.11-.1C7.14 14.24 4 11.39 4 8.5C4 6.5 5.5 5 7.5 5c1.54 0 3.04 1 3.57 2.36h1.86C13.46 6 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5c0 2.89-3.14 5.74-7.9 10.05M16.5 3c-1.74 0-3.41.81-4.5 2.08C10.91 3.81 9.24 3 7.5 3C4.42 3 2 5.41 2 8.5c0 3.77 3.4 6.86 8.55 11.53L12 21.35l1.45-1.32C18.6 15.36 22 12.27 22 8.5C22 5.41 19.58 3 16.5 3"/></svg>
                            @endif
                        </button>
                    </form>
                    <p class="card-text">{{$likes}} users find this helpful</p>
                </div>
            </div>
            @foreach ($replies as $reply)
                <div class="card border border-secondary" style="margin-left: 5%; margin-top: 40px; width:95%">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="margin-top: 10px">
                            <img src="{{ asset('storage/images/'.$reply->user->display_picture_path) }}" class="rounded-circle" width="40" height="40" alt="User Image">
                            <span class="ms-2">{{$reply->user->username}}</span>
                            @if ($reply->is_solution)
                                <span class="badge bg-success ms-3 d-flex align-items-center">
                                    Solution ✓
                                    <span class="badge bg-light text-dark ms-2">+5 Lines</span>
                                </span>
                            @endif
                        </div>
                        <span class="badge bg-success">{{$reply->programmingLanguage->programming_language_name}}</span>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{$reply->post_content}}</p>
                        @if (Auth::check() && $post->user->id === Auth::user()->id && $reply->user->id !== Auth::user()->id)
                            <div class="mt-3">
                                @if (!$reply->is_solution)
                                    <form action="/post/{{$reply->id}}/mark-solution" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm">Mark as Solution</button>
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
                        <a href="/post/{{$reply->id}}" class="card-link">View all replies...</a>
                    </div>
                </div>
            @endforeach
            <form action="/post/{{$post->id}}" method="post" style="position: fixed; bottom: 40px; width: 90%; align-items: center">
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

    <script>
        // Function to auto resize the textarea as the user types
        function autoSize(element) {
            element.style.height = "auto";
            element.style.height = (element.scrollHeight) + "px";
        }
    </script>
    <style>
        .custom-button {   
            width: 25px;
            height: 25px;
            cursor: pointer;
            margin-left: 10px
        }
    </style>

@endsection