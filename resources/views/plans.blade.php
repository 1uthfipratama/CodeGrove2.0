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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @php
                $badgeMap = [
                    'Basic' => 'Starter',
                    'Premium' => 'Popular',
                    'Diamond' => 'Best value',
                    'Infinite' => 'Ultimate',
                ];
                $perkMap = [
                    'Basic' => ['Ask up to 10 questions each week', 'Community-powered replies', 'Access to language-specific tips'],
                    'Premium' => ['50 questions each week', 'Priority placement on feeds', 'Save favorite solutions'],
                    'Diamond' => ['100 questions each week', 'Faster responses from experts', 'Advanced search filters'],
                    'Infinite' => ['Unlimited questions', 'Direct access to pro supporters', 'Early access to new features'],
                ];
            @endphp
            <div class="cg-plan-grid mb-4">
                @foreach ($subs as $index => $sub)
                    @php
                        $badge = $badgeMap[$sub->subscription_name] ?? 'Plan';
                        $perks = $perkMap[$sub->subscription_name] ?? ['Access to the CodeGrove community', 'Post questions and receive replies', 'Track your learning journey'];
                        $inputId = "subscription{$sub->id}";
                        $isChecked = old('subscription_id') ? (old('subscription_id') == $sub->id) : $index === 0;
                    @endphp
                    <label class="cg-card cg-plan-card {{ $isChecked ? 'active' : '' }}" data-plan-card for="{{ $inputId }}">
                        <input type="radio" id="{{ $inputId }}" name="subscription_id" value="{{ $sub->id }}" class="d-none" {{ $isChecked ? 'checked' : '' }} required>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">{{$sub->subscription_name}}</h5>
                            <span class="cg-status-badge cg-status-active">{{$badge}}</span>
                        </div>
                        <p class="text-muted mb-3">{{$sub->subscription_description}}</p>
                        <div class="cg-price mb-3">Rp {{ number_format($sub->subscription_price, 0, ',', '.') }},-</div>
                        <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                            @foreach ($perks as $perk)
                                <li class="d-flex align-items-center gap-2">
                                    <span data-feather="check-circle" class="text-success"></span>
                                    <span>{{ $perk }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </label>
                @endforeach
            </div>
            <div class="cg-card cg-form">
                <label class="form-label">Bank account number</label>
                <input type="text" class="cg-input mb-3" placeholder="Enter your bank account number..." name="account_no" required>
                <small class="text-muted d-block mb-3">Your payment details are encrypted and secure.</small>
                <button type="submit" class="cg-btn-primary w-100">Start Plan</button>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const planCards = document.querySelectorAll('[data-plan-card]');
            const resetActive = () => planCards.forEach(card => card.classList.remove('active'));

            planCards.forEach(card => {
                const input = card.querySelector('input[type=\"radio\"]');
                card.addEventListener('click', () => {
                    resetActive();
                    card.classList.add('active');
                    input.checked = true;
                });
            });

            const checkedPlan = document.querySelector('[data-plan-card] input[type=\"radio\"]:checked');
            if (checkedPlan) {
                checkedPlan.closest('[data-plan-card]').classList.add('active');
            }
        });
    </script>
@endsection
