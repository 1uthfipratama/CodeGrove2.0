@extends('template')

@section('title', 'Edit Question')

@section('content')
    @include('navbar')
    <main class="cg-container">
        <div class="cg-card cg-form">
            <h2 class="mb-3">Update your question</h2>
            <p class="text-muted">Improve clarity, add missing details, or adjust your language selection.</p>
            <form action="/edit-post/{{ $post->id }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="question" class="form-label">Question</label>
                    <textarea class="cg-textarea" id="question" rows="6" name="question">{{$post->post_content}}</textarea>
                </div>
                <div class="mb-3">
                    <label for="programming-language" class="form-label">Programming Language</label>
                    <select class="cg-select" id="programming-language" name="language">
                        @foreach ($languages as $lang)
                            <option value="{{$lang->id}}" {{ optional($post->programmingLanguage)->id == $lang->id ? 'selected' : '' }}>{{$lang->programming_language_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="/post/{{$post->id}}" class="cg-btn-secondary">Cancel</a>
                    <button type="submit" class="cg-btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </main>
@endsection
