<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\UserLike;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RealisticLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all();
        $users = User::all();

        if ($posts->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach ($posts as $post) {
            $likeCount = $this->weightedLikes();
            $likers = $users->where('id', '!=', $post->user_id)->shuffle()->take($likeCount);

            foreach ($likers as $liker) {
                UserLike::firstOrCreate([
                    'user_id' => $liker->id,
                    'post_id' => $post->id,
                ]);
            }
        }

        $this->recalculateLines();
    }

    private function weightedLikes(): int
    {
        return min(random_int(0, 15), random_int(0, 8));
    }

    private function recalculateLines(): void
    {
        $likeCounts = UserLike::select('posts.user_id', DB::raw('COUNT(*) as like_count'))
            ->join('posts', 'user_likes.post_id', '=', 'posts.id')
            ->groupBy('posts.user_id')
            ->pluck('like_count', 'posts.user_id');

        $solutionCounts = Post::where('is_solution', true)
            ->select('user_id', DB::raw('COUNT(*) as solution_count'))
            ->groupBy('user_id')
            ->pluck('solution_count', 'user_id');

        foreach (User::all() as $user) {
            $likes = $likeCounts[$user->id] ?? 0;
            $solutions = $solutionCounts[$user->id] ?? 0;
            $user->lines = $likes + ($solutions * 5);
            $user->save();
        }
    }
}
