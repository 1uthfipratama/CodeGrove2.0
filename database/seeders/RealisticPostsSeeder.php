<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
                'Segfaults often come from invalid pointers—use valgrind to see where it crashes and check your array bounds.',
                'calloc zeroes memory while malloc does not. If you rely on zero-init, use calloc or manually memset.',
                'Pointer arithmetic must use the right type size; make sure you increment by elements, not bytes.',
                'Use tools like valgrind or AddressSanitizer to track memory leaks and invalid frees.',
                'If fgets is skipped, consume the trailing newline from prior scanf calls using getchar or similar.',
            ],
            2 => [
                'Wrap suspicious code in Optional.ofNullable and handle the empty case to avoid NPEs.',
                'ArrayList is better for random access; LinkedList for many inserts/removals in the middle.',
                'Use Files.readAllLines or BufferedReader in a try-with-resources block for safe file reading.',
                'ConcurrentModificationException occurs when modifying while iterating—use iterators or streams that copy first.',
                'Use java.time.format.DateTimeFormatter to format dates reliably.',
            ],
            3 => [
                'Center a div with display:flex; justify-content:center; align-items:center on the parent.',
                '<div> is block-level while <span> is inline. Use them according to layout needs.',
                'Use Bootstrap navbar components or CSS flexbox with media queries for responsiveness.',
                'CSS grid with repeat(auto-fit,minmax()) helps build responsive galleries.',
                'Use prefers-color-scheme media query to toggle dark mode variables.',
            ],
            4 => [
                'That error usually means the variable is undefined—log it before calling to confirm the function exists.',
                'let/const are block-scoped while var is function-scoped; prefer const for values that do not change.',
                'Wrap await in try/catch and ensure the function is declared async.',
                'Debounce by wrapping the function with setTimeout and clearing previous timers on each call.',
                'Use fetch with proper headers and confirm the server allows the origin to resolve CORS.',
            ],
            5 => [
                'IndentationError often comes from mixing tabs and spaces—configure your editor to use spaces only.',
                'Lists are mutable, tuples are immutable—use tuples for fixed data.',
                'Use pandas.read_csv for convenience or csv.reader for lightweight parsing.',
                'Asyncio.gather can speed up IO-bound API calls when used with async HTTP clients.',
                'Use venv or virtualenv and activate it before installing packages to avoid conflicts.',
            ],
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

                $this->createReplies($question, $users, $replyExamples[$languageId]);
                $createdQuestions->push($question);
            }
        }

        $this->markSolvedReplies($createdQuestions);
    }

    private function createReplies(Post $question, $users, array $replyExamples): void
    {
        $replyCount = random_int(2, 5);
        $availableUsers = $users->where('id', '!=', $question->user_id);

        for ($i = 0; $i < $replyCount; $i++) {
            $replyAuthor = $availableUsers->random();
            $replyCreatedAt = $question->created_at->copy()->addMinutes(random_int(10, 10080));
            $replyCreatedAt = $replyCreatedAt->greaterThan(Carbon::now()) ? Carbon::now() : $replyCreatedAt;

            Post::create([
                'user_id' => $replyAuthor->id,
                'programming_language_id' => $question->programming_language_id,
                'post_id' => $question->id,
                'post_content' => $replyExamples[array_rand($replyExamples)],
                'status' => 'active',
                'is_solution' => false,
                'created_at' => $replyCreatedAt,
                'updated_at' => $replyCreatedAt,
            ]);
        }
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
