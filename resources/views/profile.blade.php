@extends('template')

@section('title', 'Profile')
    
@section('content')
    @include('navbar')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 d-flex flex-column align-items-center">
                <!-- Display picture -->
                <img src="{{ asset($profile_picture) }}" class="rounded-circle" alt="Profile Picture" width="200" height="200" style="margin-bottom: 20px">
                @if (isset($membership))
                    <div class="membership-type m-4">
                        {{$membership->subscription->subscription_name}}
                    </div>
                    <form action="/remove-membership" method="post" class="mb-3">
                        @csrf
                        <button class="btn btn-danger">Unsubscribe</button>
                    </form>
                @endif
                <a href="/plans" class="mb-3">
                    <button class="btn btn-primary">
                        View Available Plans
                    </button>
                </a>
            </div>
            <div class="col-md-8">
                <!-- Username -->
                <h1>{{ $user->username }}</h1>
                <div class="mb-3">
                    <span class="badge" style="background-color: yellow; font-size: 24px; color: #333;">
                        🔥 {{ $user->lines }} Lines
                    </span>
                    <div class="text-muted" style="font-size: 14px;">Reputation Points</div>
                </div>
                <!-- Count of post liked -->
                <p>Number of posts liked: {{ $total_post_like }}</p>
                <!-- Count of likes from others -->
                <p>Number of likes received: {{ $total_like_count }}</p>
                <!-- Top 3 posts -->
                <h2 class="mb-3">Top 3 Posts</h2>
                
                @foreach ($top_posts as $post)
                    <div class="card border border-secondary mb-3">
                        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/images/'.$post->user->display_picture_path) }}" class="rounded-circle" width="40" height="40" alt="User Image">
                                <span class="ms-2">{{$post->user->username}}</span>
                            </div>
                            <span class="badge bg-success">{{$post->programmingLanguage->programming_language_name}}</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{$post->post_content}}</p>
                            <hr class="my-4">
                            <a href="/post/{{$post->id}}" class="card-link">View all replies...</a>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
        
        <a href="/edit-profile" class="custom-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M3 17.46v3.04c0 .28.22.5.5.5h3.04c.13 0 .26-.05.35-.15L17.81 9.94l-3.75-3.75L3.15 17.1c-.1.1-.15.22-.15.36M20.71 7.04a.996.996 0 0 0 0-1.41l-2.34-2.34a.996.996 0 0 0-1.41 0l-1.83 1.83l3.75 3.75z"/></svg>
        </a>
    </div>
    <style>
        .membership-type {
            background-color: yellow;
            border-radius: 10px;
            padding: 5px 10px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }
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
    </style>
@endsection