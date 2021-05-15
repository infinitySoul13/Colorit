<?php

use App\Category;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'telegram_chat_id' => '',
            'fio_from_telegram' => '',
            'phone' => '',
            'is_admin' => true
        ]);

        Category::create([
            'title' =>'Clothing',
        ]);
        Category::create([
            'title' =>'Music',
        ]);
        Category::create([
            'title' =>'Decor',
        ]);
        Category::create([
            'title' =>'Accessories',
        ]);
        Category::create([
            'title' =>'Hoodies',
        ]);
        Category::create([
            'title' =>'Tshirts',

        ]);
        Category::create([
            'title' =>'Uncategorized',
        ]);
    }
}
