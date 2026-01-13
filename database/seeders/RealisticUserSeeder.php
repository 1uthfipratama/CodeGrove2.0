<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RealisticUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $usernames = [
            'CodeMaster', 'jenny_dev', 'alex_codes', 'pythonista', 'JavaJoe', 'web_wizard', 'debugger_dan', 'css_queen',
            'data_druid', 'backend_ben', 'frontend_fae', 'cloud_casper', 'go_guru', 'rustacean_ray', 'devops_dina',
            'ui_ux_uma', 'bug_hunter', 'algorithm_amy', 'script_sam', 'pixel_pete', 'logic_lena', 'binary_beth',
            'stack_overlord', 'ctrl_alt_delia', 'commit_carlos', 'merge_mike', 'lambda_luke', 'async_ava', 'pointer_paul',
            'query_queen',
        ];

        foreach (array_slice($usernames, 0, 30) as $username) {
            User::create([
                'username' => $username,
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('Password123!'),
                'dob' => $this->randomDob(),
                'role' => 'user',
                'display_picture_path' => 'default.svg',
                'lines' => $this->weightedLines(),
            ]);
        }

        User::create([
            'username' => 'admin',
            'email' => 'admin@codegrove.com',
            'password' => Hash::make('Admin123!'),
            'dob' => Carbon::now()->subYears(30)->toDateString(),
            'role' => 'admin',
            'display_picture_path' => 'default.svg',
            'lines' => 0,
        ]);
    }

    private function randomDob(): string
    {
        $years = random_int(18, 40);
        $days = random_int(0, 364);

        return Carbon::now()->subYears($years)->subDays($days)->toDateString();
    }

    private function weightedLines(): int
    {
        // Skew toward lower numbers by taking the minimum of two random draws.
        return min(random_int(0, 200), random_int(0, 200));
    }
}
