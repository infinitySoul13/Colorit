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
//            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'telegram_chat_id' => '',
            'fio_from_telegram' => '',
            'phone' => '',
            'is_admin' => true
        ]);

        Category::create([
            'title' =>'Масло трансмиссионное',
            'words' => ["трансмиссионное", "трансмис", "трансмиссион", "трансм","трансмиccионное"],
            'img' => '/assets/images/cat_6.jpg'
        ]);
        Category::create([
            'title' =>'Щетки',
            'words' => ['Щетки','Щётки','Щетка','Щётка'],
            'img' => '/assets/images/cat.png'
        ]);
        Category::create([
            'title' =>'Масло моторное',
            'words' => ["моторное","мот", "мотор"],
            'img' => '/assets/images/cat_8.jpg'
        ]);
        Category::create([
            'title' =>'АКБ',
            'words' => ['АКБ'],
            'img' => '/assets/images/cat_9.jpg'
        ]);
        Category::create([
            'title' =>'Лампы',
            'words' => ['Лампа'],
            'img' => '/assets/images/cat_10.jpg'
        ]);
        Category::create([
            'title' =>'Жидкость',
            'words' => ['Жидкость'],
            'img' => '/assets/images/cat_3.jpg'
        ]);
        Category::create([
            'title' =>'Выхлопы',
            'words' => ['Выхлоп'],
            'img' => '/assets/images/cat_4.jpg'
        ]);
        Category::create([
            'title' =>'Кузовы',
            'words' => ['Кузов'],
            'img' => '/assets/images/cat_7.jpg'
        ]);
        Category::create([
            'title' =>'Дворники',
            'words' => ['Дворники'],
            'img' => '/assets/images/cat.png'
        ]);
        Category::create([
            'title' =>'Другое',
            'words' => [],
            'img' => '/assets/images/cat.png'
        ]);

    }
}
