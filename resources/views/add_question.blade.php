@extends('template')

@section('title', 'Add Question')

@section('content')
    @include('navbar')
    <main class="cg-container">
        <div class="cg-card cg-form">
            <h2 class="mb-3">Start a new discussion</h2>
            <p class="text-muted">Share your question with the community. Use clear language and pick the right programming language.</p>
            <form action="/add-question" method="post">
                @csrf
                <div class="mb-3">
                    <label for="question" class="form-label">Question</label>
                    <textarea class="cg-textarea" id="question" rows="6" name="question" placeholder="Describe your problem in detail...">{{old('question')}}</textarea>
                </div>
                <div class="mb-3">
                    <label for="programming-language" class="form-label">Programming Language</label>
                    <select class="cg-select" id="programming-language" name="language">
                        <option selected>Select Language</option>
                        @foreach ($languages as $lang)
                            <option value="{{$lang->id}}">{{$lang->programming_language_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="/" class="cg-btn-secondary">Cancel</a>
                    <button type="submit" class="cg-btn-primary">Add Question</button>
                </div>
            </form>
        </div>
    </main>
    <a href="/add-question" class="cg-fab visible" id="cgFab" aria-label="Add Question">+</a>
@endsection
