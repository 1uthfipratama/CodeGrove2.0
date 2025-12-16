<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealisticPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $questionsByLanguage = [
            1 => [
                'How do I fix segmentation fault in my C program?',
                "What's the difference between malloc and calloc?",
                'Why is my pointer arithmetic giving wrong results?',
                'How do I debug memory leaks in C?',
                'How can I safely cast between different pointer types?',
                'Why is fgets skipping input after scanf?',
                'How do I prevent buffer overflows when using gets/scanf?',
                'What is undefined behavior in C and how to avoid it?',
                'How do I properly free a linked list in C?',
                'Why does my struct not pack as expected?',
                'How do I write a makefile for multiple C source files?',
                'How can I unit test C functions easily?',
            ],
            2 => [
                'How to handle NullPointerException in Java?',
                "What's the difference between ArrayList and LinkedList?",
                'How do I read a file in Java?',
                'Why is my Spring Boot application not starting?',
                'How do I use streams to filter a list?',
                'What is the best way to handle Optional in Java 17?',
                'How do I fix classpath issues in Maven?',
                'How to avoid ConcurrentModificationException?',
                'How do I format dates with java.time?',
                'How can I reduce memory usage of my Java app?',
                'How do I write unit tests with JUnit 5?',
                'How do I handle JSON serialization with Jackson?',
            ],
            3 => [
                'How to center a div in HTML/CSS?',
                "What's the difference between <div> and <span>?",
                'How to make a responsive navbar?',
                'Why is my flex layout overflowing?',
                'How do I improve Lighthouse accessibility scores?',
                'How can I optimize images for faster page loads?',
                'How to create a sticky header without JavaScript?',
                'How do I create a CSS grid gallery?',
                'How to implement dark mode with CSS variables?',
                'Why is my SVG not scaling correctly?',
                'How to embed custom fonts responsibly?',
                'How do I prevent layout shift when images load?',
            ],
            4 => [
                "How to fix 'undefined is not a function' error?",
                "What's the difference between let, var, and const?",
                'How do I use async/await in JavaScript?',
                'Why is my fetch request returning CORS errors?',
                'How do I debounce a function in JavaScript?',
                'How to manage state without a framework?',
                'How can I detect memory leaks in a SPA?',
                'How to optimize bundle size with tree shaking?',
                'How do I use promises to chain API calls?',
                'How can I handle errors globally in a React app?',
                'How do I polyfill features for older browsers?',
                'How can I throttle scroll events efficiently?',
            ],
            5 => [
                'How to fix IndentationError in Python?',
                "What's the difference between list and tuple?",
                'How do I read a CSV file in Python?',
                'How can I speed up a slow pandas dataframe operation?',
                'Why is my virtual environment not activating?',
                'How do I debug circular imports in Python?',
                'How can I deploy a Flask app to production?',
                'How do I manage dependencies with pip-tools?',
                'How can I use asyncio to speed up API calls?',
                'Why is my Django query so slow?',
                'How do I write type hints for complex data?',
                'How can I mock HTTP requests in unit tests?',
            ],
        ];

        $replyExamples = [
            1 => [
                'First check array bounds and null pointers; valgrind usually points to the exact crash site. If you share a minimal repro of ":question", we can sanity-check the pointer math.',
                'calloc clears memory while malloc leaves it dirty. If you depend on zero-init, calloc is safer; otherwise, memset after malloc to avoid surprises in code like ":question".',
                'Pointer arithmetic is done in element sizes, not bytes. Make sure your increments match the target type; otherwise you will walk off the buffer like you described in ":question".',
                'Run AddressSanitizer or valgrind to find double frees or leaks. They will tell you which line of ":question" is mismanaging memory.',
                'If fgets skips input after scanf, consume the trailing newline with getchar. It is a classic gotcha that shows up in snippets like ":question".',
            ],
            2 => [
                'Guard everything with Optional.ofNullable and fail fast. For ":question" it helps to log the offending field before dereferencing it.',
                'ArrayList wins for random access while LinkedList is good for frequent mid-list inserts. Pick based on how ":question" manipulates the collection.',
                'Read files with Files.readAllLines or BufferedReader inside try-with-resources. It keeps the code in ":question" tidy and closes handles automatically.',
                'ConcurrentModificationException usually means you mutated during iteration. Use iterators or collect() before modifying the list mentioned in ":question".',
                'Format dates with DateTimeFormatter and the java.time API. It is safer and avoids timezone surprises the old Date API causes in ":question".',
            ],
            3 => [
                'Center the container with display:flex on the parent and use justify-content/align-items center. That usually fixes layouts like ":question".',
                '<div> is block-level while <span> is inline. Use spans for inline tweaks and divs for structure—mixing them is what breaks layouts like ":question".',
                'For a responsive navbar, lean on flexbox with media queries or Bootstrap utilities. The issue in ":question" sounds like missing breakpoints.',
                'CSS grid with repeat(auto-fit, minmax()) builds fluid galleries. It will stop the overflow you described in ":question".',
                'Prefer CSS variables and prefers-color-scheme to toggle dark mode. It keeps theming simple for pages like ":question".',
            ],
            4 => [
                '"undefined is not a function" means the reference is missing. Console.log the variable and confirm the export/import path you used in ":question".',
                'Use const for values that never change and let for mutable ones; var is function-scoped and causes hoisting bugs like the one in ":question".',
                'Wrap awaits in try/catch and mark the function async. Otherwise promises will bubble errors silently like they do in ":question".',
                'Debounce with setTimeout and clearTimeout inside the wrapper. It keeps the handler in ":question" from firing excessively.',
                'CORS errors mean the server is blocking the origin. Ensure the API used in ":question" sends proper headers and preflight responses.',
            ],
            5 => [
                'IndentationError usually means tabs and spaces got mixed. Configure your editor for spaces-only and re-indent the block you showed in ":question".',
                'Lists are mutable while tuples are immutable; choose tuples when you need hashable keys or fixed data like the config in ":question".',
                'Use pandas.read_csv for convenience or csv.reader for small files. Wrap it in with open(...) as fh to avoid file handle leaks like in ":question".',
                'asyncio.gather speeds up IO-bound work. In ":question" you can await multiple API calls instead of sequential requests.',
                'Activate a venv before installing packages so dependencies for code like ":question" stay isolated.',
            ],
        ];

        $conversationClosers = [
            'Let me know if that works or share a small snippet and we can dig deeper.',
            'Hope this helps—drop a stack trace or screenshot if the bug persists.',
            'That pattern usually clears things up; if it does not, post your minimal repro.',
            'I fixed a similar issue last week using this approach. Curious if it works for you too.',
            'Shout if you need a more detailed example; always happy to pair on tricky bugs.',
        ];

        $createdQuestions = collect();

        foreach ($questionsByLanguage as $languageId => $questions) {
            for ($i = 0; $i < 12; $i++) {
                $questionContent = $questions[$i % count($questions)];
                $author = $users->random();
                $createdAt = Carbon::now()->subDays(random_int(0, 90))->subMinutes(random_int(0, 1440));

                $question = Post::create([
                    'user_id' => $author->id,
                    'programming_language_id' => $languageId,
                    'post_id' => null,
                    'post_content' => $questionContent,
                    'status' => 'active',
                    'is_solution' => false,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $this->createReplies($question, $users, $replyExamples[$languageId], $conversationClosers);
                $createdQuestions->push($question);
            }
        }

        $this->markSolvedReplies($createdQuestions);
    }

    private function createReplies(Post $question, $users, array $replyExamples, array $conversationClosers): void
    {
        $replyCount = random_int(2, 5);
        $availableUsers = $users->where('id', '!=', $question->user_id);

        for ($i = 0; $i < $replyCount; $i++) {
            $replyAuthor = $availableUsers->random();
            $replyCreatedAt = $question->created_at->copy()->addMinutes(random_int(10, 10080));
            $replyCreatedAt = $replyCreatedAt->greaterThan(Carbon::now()) ? Carbon::now() : $replyCreatedAt;

            $replyContent = $this->formatReply($replyExamples[array_rand($replyExamples)], $question->post_content);

            if (random_int(0, 1) === 1) {
                $replyContent .= ' ' . $conversationClosers[array_rand($conversationClosers)];
            }

            Post::create([
                'user_id' => $replyAuthor->id,
                'programming_language_id' => $question->programming_language_id,
                'post_id' => $question->id,
                'post_content' => $replyContent,
                'status' => 'active',
                'is_solution' => false,
                'created_at' => $replyCreatedAt,
                'updated_at' => $replyCreatedAt,
            ]);
        }
    }

    private function formatReply(string $template, string $questionContent): string
    {
        $shortQuestion = Str::limit($questionContent, 120, '...');
        return str_replace(':question', $shortQuestion, $template);
    }

    private function markSolvedReplies($questions): void
    {
        if ($questions->isEmpty()) {
            return;
        }

        $questionsToSolve = $questions->shuffle()->take((int) round($questions->count() * 0.2));

        foreach ($questionsToSolve as $question) {
            $replies = Post::where('post_id', $question->id)->get();
            if ($replies->isEmpty()) {
                continue;
            }

            $solutionReply = $replies->random();
            $solutionReply->is_solution = true;
            $solutionReply->save();
        }
    }
}
