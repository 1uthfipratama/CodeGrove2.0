@extends('template')

@section('title', 'Plans')

@section('content')
    @include('navbar')
    <main class="cg-container d-flex flex-column align-items-center">
        <section class="cg-hero mb-4 w-100">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <div class="cg-hero-title">Pick your perfect plan</div>
                    <div class="cg-hero-subtitle">Unlock premium perks, faster replies, and exclusive badges.</div>
                </div>
            </div>
        </section>

        <form class="w-100" action="/plans" method="POST">
            @csrf
            <div class="cg-plan-grid mb-4">
                @foreach ($subs as $sub)
                    <label class="cg-card cg-plan-card" onclick="document.getElementById('subscription{{$sub->id}}').checked = true; this.classList.add('active')">
                        <input type="radio" id="subscription{{$sub->id}}" name="subscription_id" value="{{$sub->id}}" class="d-none">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">{{$sub->subscription_name}}</h5>
                            <span class="cg-status-badge cg-status-active">Popular</span>
                        </div>
                        <p class="text-muted">{{$sub->subscription_description}}</p>
                        <div class="cg-price">Rp {{$sub->subscription_price}},-</div>
                    </label>
                @endforeach
            </div>
            <div class="cg-card cg-form">
                <label class="form-label">Bank account number</label>
                <input type="text" class="cg-input mb-3" placeholder="Enter your bank account number..." name="account_no">
                <button type="submit" class="cg-btn-primary w-100">Start Plan</button>
            </div>
        </form>
    </main>
@endsection
