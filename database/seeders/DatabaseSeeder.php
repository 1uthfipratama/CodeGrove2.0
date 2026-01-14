<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('programming_languages')->insert([
            ['programming_language_name' => 'C', 'programming_language_image_path' => 'asset/c.png', 'created_at' => Carbon::now()->subSeconds(2200)],
            ['programming_language_name' => 'Java', 'programming_language_image_path' => 'asset/java.png', 'created_at' => Carbon::now()->subSeconds(2200)],
            ['programming_language_name' => 'HTML', 'programming_language_image_path' => 'asset/html.png', 'created_at' => Carbon::now()->subSeconds(2200)],
            ['programming_language_name' => 'JavaScript', 'programming_language_image_path' => 'asset/js.png', 'created_at' => Carbon::now()->subSeconds(2200)],
            ['programming_language_name' => 'Python', 'programming_language_image_path' => 'asset/py.png', 'created_at' => Carbon::now()->subSeconds(2200)],
        ]);

        $this->call(RealisticUserSeeder::class);

        DB::table('subscriptions')->insert([
            ['subscription_name' => 'Basic', 'subscription_description' => '10 questions per week', 'subscription_price' => 10000],
            ['subscription_name' => 'Premium', 'subscription_description' => '50 questions per week', 'subscription_price' => 40000],
            ['subscription_name' => 'Diamond', 'subscription_description' => '100 questions per week', 'subscription_price' => 80000],
            ['subscription_name' => 'Infinite', 'subscription_description' => 'Unlimited amount of questions', 'subscription_price' => 500000]
        ]);

        $this->call(RealisticPostsSeeder::class);

        $this->call(RealisticLikesSeeder::class);
    }
}
