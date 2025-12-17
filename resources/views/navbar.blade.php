<nav class="navbar navbar-expand-lg cg-navbar">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center gap-3">
            <button class="navbar-toggler border-0" type="button" id="cgMenuToggle" aria-label="Open menu">
                <span data-feather="menu"></span>
            </button>
            <a class="navbar-brand cg-logo" href="/">CodeGrove</a>
        </div>

        <div class="cg-search d-none d-lg-block">
            @if (@isset($sourceUrl))
                <form class="position-relative" role="search" action="{{$sourceUrl}}/search" method="post">
                    @csrf
                    <span data-feather="search"></span>
                    <input class="form-control" type="search" placeholder="Search discussions" aria-label="Search" name="search" value="{{ $search ?? '' }}">
                </form>
            @else
                <form class="position-relative" role="search" action="search" method="post">
                    @csrf
                    <span data-feather="search"></span>
                    <input class="form-control" type="search" placeholder="Search discussions" aria-label="Search" name="search" value="{{ $search ?? '' }}">
                </form>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch" id="cgDarkToggle" aria-label="Toggle dark mode">
            </div>
            @if (Auth::user())
                <a class="position-relative" href="/profile">
                    @if (Auth::user()->display_picture_path)
                        <img src="{{ asset('storage/images/'.Auth::user()->display_picture_path) }}" class="cg-profile-img" alt="Profile Picture">
                    @else
                        <img src="{{ asset('storage/asset/gg--profile.png') }}" class="cg-profile-img" alt="Profile Picture">
                    @endif
                </a>
            @endif
            <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#cgNav" aria-controls="cgNav" aria-expanded="false" aria-label="Toggle navigation">
                <span data-feather="chevron-down"></span>
            </button>
        </div>
    </div>

    <div class="collapse navbar-collapse" id="cgNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 px-3 py-2 py-lg-0">
            @if (Auth::user())
                @if (Auth::user()->role == "user")
                    <li class="nav-item"><a class="nav-link" href="/my-questions">My Questions</a></li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2" href="/archived-questions">
                            Archived
                            <span class="badge bg-danger">{{$archiveCount}}</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item"><a class="nav-link" href="/plans">Plans</a></li>
                <li class="nav-item"><a class="nav-link" href="/profile">Profile</a></li>
                <li class="nav-item">
                    <form action="/logout" method="post" id="logout-form2">
                        @csrf
                        <button type="submit" class="btn nav-link">Log Out</button>
                    </form>
                </li>
            @else
                <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
            @endif
        </ul>
        <div class="cg-search d-lg-none w-100 px-3 pb-3">
            @if (@isset($sourceUrl))
                <form class="position-relative" role="search" action="{{$sourceUrl}}/search" method="post">
                    @csrf
                    <span data-feather="search"></span>
                    <input class="form-control" type="search" placeholder="Search discussions" aria-label="Search" name="search" value="{{ $search ?? '' }}">
                </form>
            @else
                <form class="position-relative" role="search" action="search" method="post">
                    @csrf
                    <span data-feather="search"></span>
                    <input class="form-control" type="search" placeholder="Search discussions" aria-label="Search" name="search" value="{{ $search ?? '' }}">
                </form>
            @endif
        </div>
    </div>
</nav>

<div id="cgMobileOverlay" class="cg-mobile-overlay"></div>
<div id="cgMobileMenu" class="cg-mobile-menu">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-bold">Menu</span>
        <button class="btn btn-sm btn-outline-secondary cg-close-menu" type="button">Close</button>
    </div>
    <div class="list-group list-group-flush">
        @if (Auth::user())
            <a href="/" class="list-group-item list-group-item-action cg-close-menu">Home</a>
            <a href="/my-questions" class="list-group-item list-group-item-action cg-close-menu">My Questions</a>
            <a href="/archived-questions" class="list-group-item list-group-item-action cg-close-menu">Archived ({{$archiveCount}})</a>
            <a href="/plans" class="list-group-item list-group-item-action cg-close-menu">Plans</a>
            <a href="/profile" class="list-group-item list-group-item-action cg-close-menu">Profile</a>
            <form action="/logout" method="post" class="list-group-item">
                @csrf
                <button type="submit" class="btn w-100 cg-btn-secondary">Log Out</button>
            </form>
        @else
            <a href="/login" class="list-group-item list-group-item-action cg-close-menu">Login</a>
            <a href="/register" class="list-group-item list-group-item-action cg-close-menu">Register</a>
        @endif
    </div>
</div>
