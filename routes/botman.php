<?php

use App\Http\Controllers\BotManController;
use App\Product;
use App\User;
use BotMan\BotMan\BotMan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Conversations\UserConversation;
use App\Conversations\UniqueOrderConversation;
use App\Conversations\VinConversation;

$botman = resolve('botman');

function createUser($bot)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $username = $telegramUser->getUsername();
    $lastName = $telegramUser->getLastName();
    $firstName = $telegramUser->getFirstName();

    $user = \App\User::where("telegram_chat_id", $id)->first();
    if ($user == null)
    {
        $bot->startConversation(new UserConversation($bot));

    }
    else {
        mainMenu($bot, 'Главное меню');
        return $user;
    }

    return $user;
}
function mainMenu($bot, $message)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $basket = json_decode($bot->userStorage()->get("basket")) ?? [];

    $count=null;
    if(count($basket)) {
        $count = 0;
    }
    foreach ($basket as $product) {
        $count += floatval($product->total);
    }

    $keyboard = [
        ["Товары"],
        ["Корзина" . ($count == null ? "(0₽)" : "(" . $count . "₽)")],
        ["Сделать заказ"],
//        ["Заказ уникального товара"],
//      ["Настройки"],
        ["О Нас"],
    ];
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $message,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'one_time_keyboard' => false,
                'resize_keyboard' => true
            ])
        ]);
}
function aboutMenu($bot, $message)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $keyboard = [
        ["Контакты"],
        ["Главное меню"],
    ];
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $message,
            "parse_mode" => "Markdown",
            "disable_notification"=>true,
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'one_time_keyboard' => false,
                'resize_keyboard' => true
            ])
        ]);
}
$botman->hears('/start|Главное меню', function ($bot) {
//    createUser($bot);
    mainMenu($bot, 'Главное меню');
})->stopsConversation();
$botman->hears('/stop', function ($bot) {
    mainMenu($bot, 'Главное меню');
})->stopsConversation();
$botman->hears(".*О нас", function ($bot) {
    aboutMenu($bot, "*Наши контакты*\n"
        . "*Телефон*: _+7 (000) 000 00 00_\n"
        . "*Почта*: _colorit@gmail.com_\n"
    );
});
$botman->hears(".*Контакты", function ($bot) {

    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'Мы в Instagram', 'url' => 'https://www.instagram.com/'],
            ],
            [
                ['text' => 'Мы в Вконтакте', 'url' => 'https://vk.com/'],
            ],
            [
                ['text' => 'Наш сайт', 'url' => 'http://colorit.ru/'],
            ]
        ]
    ];
    $bot->sendRequest("sendMessage",
        [
            "text" => 'Возникли вопросы? - Переходите на наш сайт и получите ответы!',
            "disable_notification"=>true,
            'reply_markup' => json_encode($keyboard)
        ]);
});
$botman->hears('Сделать заказ', BotManController::class . "@serviceOrderConversation");

/*Товары*/
$botman->hears("Товары", function ($bot) {
    $categories = \App\Product::all()->unique('category');

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    foreach ($categories as $key => $category) {
        $bot->sendRequest("sendPhoto",
            [
                "chat_id" => "$id",
                "caption" => $category->category,
                "photo" => 'https://ld-magento-72.template-help.com/magento_62000/pub/media/catalog/category/cat_3_lt_5.jpg',
                "parse_mode" => "Markdown",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [
                            ["text" => "\xF0\x9F\x91\x89Детальнее", "callback_data" => "/category 0 $key"]
                        ]
                    ],
                ])
            ]);
    }

});
$botman->hears('/category ([0-9]+) ([0-9]+)', function ($bot, $page, $catIndex) {

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $categories = \App\Product::all()->unique('category');

    $products = \App\Product::where("category", $categories[$catIndex]->category)
        ->where("quantity",">","0")
        ->take(10)
        ->skip($page * 10)
        ->get();

    if (count($products) == 0) {
        $bot->reply("Товары в категории не найдены");
        return;
    }

    foreach ($products as $key => $product) {
        $keyboard = [
            [
                ['text' => "\xF0\x9F\x91\x89Детальнее", 'callback_data' => "/product_info " . $product->id],
                ['text' => "\xE2\x86\xAAВ корзину(" . $product->price . "₽)", 'callback_data' => "/add_to_basket " . $product->id]
            ],

        ];

        if (count($products) - 1 == $key && $page == 0 && count($products) == 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/category  " . ($page + 1) . " " . $catIndex]
            ]);

        if (count($products) - 1 == $key && $page != 0 && count($products) == 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => "/category  " . ($page - 1) . " " . $catIndex],
                ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/category  " . ($page + 1) . " " . $catIndex]
            ]);

        if (count($products) - 1 == $key && $page != 0 && count($products) < 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => "/category  " . ($page - 1) . " " . $catIndex],
            ]);

        $bot->sendRequest("sendPhoto",
            [
                "chat_id" => "$id",
                "caption" => $product->title,
                "photo" => 'https://ld-magento-72.template-help.com/magento_62000/pub/media/catalog/category/cat_3_lt_5.jpg',
                'reply_markup' => json_encode([
                    'inline_keyboard' =>
                        $keyboard
                ])
            ]);
    }
});
//$botman->hears('/product_info ([0-9]+)', function ($bot, $productId) {
//
//    $telegramUser = $bot->getUser();
//    $id = $telegramUser->getId();
//
//    $product = \App\Product::find($productId);
//
//    $message = "*" . $product->title . "*\n"
//        . "*Описание:*" . $product->description . "\n"
////        . "*Производитель*:" . $product->brand . "\n"
//        . "*Цена*:" . $product->price . "₽\n";
//
//
//    $keyboard = [
//        [
//            ['text' => "\xE2\x86\xAAВ корзину(" . $product->price . "₽)", 'callback_data' => "/add_to_basket " . $product->id]
//        ]
//    ];
//
//    $bot->sendRequest("sendPhoto",
//        [
//            "chat_id" => "$id",
//            "photo" => 'https://ld-magento-72.template-help.com/magento_62000/pub/media/catalog/category/cat_3_lt_5.jpg',
//            "caption" => $message,
//            "parse_mode" => "Markdown",
//            'reply_markup' => json_encode([
//                'inline_keyboard' =>
//                    $keyboard
//            ])
//        ]);
//
//});
$botman->hears('/product_info ([0-9]+)', function ($bot, $productId) {
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $product = \App\Product::find($productId);
    $message = "*" . $product->title . "*\n"
        . "_" . $product->description . "_\n"
        . "*Тип*:" . $product->type . "\n"
        . "*Категория*:" . $product->category . "\n"
        . "*Цена*:" . $product->price . "₽\n";

    $keyboard = [
        [

            ['text' => "\xE2\x86\xAAВ корзину(" . $product->price . "₽)", 'callback_data' => "/add_to_basket " . $product->id],
            ['text' => "Отзывы", 'callback_data' => "/product_reviews " . $product->id],
        ]
    ];

    if (count($product->src) > 1) {
        array_push($keyboard, [['text' => "\xE2\x9C\xA8Больше фотографий", 'callback_data' => "/product_photos 0 " . $product->id]]);
    }
    $bot->sendRequest("sendPhoto",
        [
            "chat_id" => "$id",
            "photo" => env("APP_URL_IMAGES") . $product->src[0]["name"],
            "caption" => $message,
            "parse_mode" => "Markdown",
            "disable_notification"=>true,
            'reply_markup' => json_encode([
                'inline_keyboard' =>
                    $keyboard
            ])
        ]);
});
$botman->hears("/products ([0-9]+) ([а-яА-Яa-zA-Z0-9]+)", function ($bot, $page, $catId) {
    productList($bot, $page, $catId);
});
$botman->hears("/product_photos ([0-9]+) ([0-9]+)", function ($bot, $page, $producId) {

    for ($i = 0; $i < 5; $i++) {
        $attachment = new Image('http://www.vintec.co.rs/admin/content/image5.jpg', [
            'custom_payload' => true,
        ]);

// Build message object
        $message = OutgoingMessage::create("*Название товара*\n" .
            "_Краткое описание товара_")
            ->withAttachment($attachment);

        $bot->reply($message, ["parse_mode" => "Markdown"]);
    }

    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'К товару', 'callback_data' => "/product_info $producId"],
            ],
            [
                ['text' => 'Назад', 'callback_data' => "/product_photos $page"],
                ['text' => 'Дальше', 'callback_data' => "/product_photos $page"],
            ],


        ]
    ];
    $bot->sendRequest("sendMessage",
        [
            "text" => 'Ваши действия',
            "disable_notification"=>true,
            'reply_markup' => json_encode($keyboard)
        ]);
});
function reviews($bot, $page)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $reviews = \App\Review::skip($page * 10)
        ->take(10)
        ->orderBy('id', 'desc')
        ->get();

    $keyboard = [];

    foreach ($reviews as $key => $review) {

        if (count($reviews) - 1 == $key && $page == 0 && count($reviews) == 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/reviews " . ($page + 1)]
            ]);
        if (count($reviews) - 1 == $key && $page != 0 && count($reviews) == 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => "/reviews " . ($page - 1)],
                ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/reviews " . ($page + 1)]
            ]);
        if (count($reviews) - 1 == $key && $page != 0 && count($reviews) < 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => "/reviews " . ($page - 1)],
            ]);

        $bot->sendRequest("sendPhoto",
            [
                "chat_id" => "$id",
                "photo" => env("APP_URL_IMAGES") . $review->image,
                "caption" => "*" . $review->client_name . "*\n"
                    . "_" . $review->review_text . "_\n",
                "parse_mode" => "Markdown",
                "disable_notification"=>true,
                'reply_markup' => json_encode([
                    'inline_keyboard' =>
                        $keyboard
                ])

            ]);
    }

}
function productList($bot, $page, $catId)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();


    Log::info("$catId");
    Log::info("category ".$catId);

    $products = \App\Product::where("category", $catId)
        ->take(10)
        ->skip($page * 10)
        ->get();

    if (count($products) == 0) {
        $bot->reply("Товары в категории не найдены");
        return;
    }
    foreach ($products as $key => $product) {
        $keyboard = [
            [
                ['text' => "\xF0\x9F\x91\x89Детальнее", 'callback_data' => "/product_info " . $product->id],
            ],
            [
                ['text' => "\xE2\x86\xAAВ корзину(" . $product->price . "₽)", 'callback_data' => "/add_to_basket " . $product->id]
            ]
        ];


        $bot->sendRequest("sendPhoto",
            [
                "chat_id" => "$id",
                "photo" => env("APP_URL_IMAGES") . $product->src[0]["name"],
                "disable_notification"=>true,
                'reply_markup' => json_encode([
                    'inline_keyboard' =>
                        $keyboard
                ])
            ]);
    }

    $keyboard = [];
    if (count($products) - 1 == $key && $page == 0 && count($products) == 10)
        array_push($keyboard, [
            ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/products " . ($page + 1) . " " . $catId]
        ]);
    if (count($products) - 1 == $key && $page != 0 && count($products) == 10)
        array_push($keyboard, [
            ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => "/products " . ($page - 1) . " " . $catId],
            ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/products " . ($page + 1) . " " . $catId]
        ]);
    if (count($products) - 1 == $key && $page != 0 && count($products) < 10)
        array_push($keyboard, [
            ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => "/products " . ($page - 1) . " " . $catId],
        ]);

    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => "Выберите действия",
            "disable_notification"=>true,
            'reply_markup' => json_encode([
                'inline_keyboard' =>
                    $keyboard
            ])
        ]);
}
$botman->hears(".*Шапочки", function ($bot) {
    productList($bot, 0, "Шапочка");
});
$botman->hears('.*Отзывы', function ($bot) {
    reviews($bot, 0);
});
$botman->hears('/reviews ([0-9]+)', function ($bot, $page) {
    reviews($bot, $page);
});

/*Корзина*/
function basketMenu($bot, $message)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $basket = json_decode($bot->userStorage()->get("basket")) ?? [];
    $count=null;
    if(count($basket)) {
        $count = 0;
    }

    foreach ($basket as $product) {
        $count += floatval($product->total);
    }


    $keyboard = [
        ["Оформить заказ " . ($count == null ? "(0₽)" : "(" . $count . "₽)")],
        ["Главное меню"],
    ];
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $message,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'one_time_keyboard' => false,
                'resize_keyboard' => true
            ])
        ]);
}
$botman->hears('.*Корзина.*', function ($bot) {

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $basket = json_decode($bot->userStorage()->get("basket")) ?? [];

    if (count($basket) == 0) {
        $bot->reply("Корзина пуста:(");
        return;
    }

    basketMenu($bot, "Cодержимое корзины");


    foreach ($basket as $key => $product) {
        $keyboard = [
            [
                ['text' => "\xF0\x9F\x91\x89Детальнее", 'callback_data' => "/product_info " . $product->id],
                ['text' => "Убрать (" . $product->price . "₽)", 'callback_data' => "/remove_from_basket " . $product->id]
            ],
        ];
        Log::info([$product]);
        if($product->number>1){
            Log::info('number > 1');
            $button = ['text' => "Убрать все (" . $product->total . "₽)", 'callback_data' => "/remove_all_from_basket " . $product->id];
            array_push($keyboard, [$button]);
        }
        $text = "".$product->title."\n"
            ."Кол-во:".$product->number." шт.";
        $bot->sendRequest("sendPhoto",
            [
                "chat_id" => "$id",
                "caption" => $text,
                "photo" => 'https://ld-magento-72.template-help.com/magento_62000/pub/media/catalog/category/cat_3_lt_5.jpg',
                'reply_markup' => json_encode([
                    'inline_keyboard' =>
                        $keyboard
                ])
            ]);


    }


});
$botman->hears('/add_to_basket ([0-9]+)', function ($bot, $prodId) {
    $basket = json_decode($bot->userStorage()->get("basket"), true) ?? [];

    $item = \App\Product::find($prodId);
    $contains = false;
    $basket_tmp = [];
    foreach ($basket as $product) {
//        if ($product->id != $prodId)
//        {
//            array_push($basket_tmp, $product);
//        }
//        else
//        {
//            $contains = true;
//            $product->number++;
//            array_push($basket_tmp, $product);
//        }
        if ($product['id'] != $prodId)
        {
            array_push($basket_tmp, $product);
        }
        if ($product['id'] == $prodId)
        {
            $contains = true;
            $product['number']+=1;
            $product['total'] = intval($product['number']) * floatval($product['price']);
            array_push($basket_tmp, $product);
//            Log::info('number = '.$product['number']);
//            Log::info($product);
        }
    }
    if($contains == false)
    {
        $item->number = 1;
        $item->total = floatval($item->price);
//        array_push($basket, $item);
        array_push($basket_tmp, $item);
    }
//    array_push($basket, $product);

    $bot->userStorage()->save([
        'basket' => json_encode($basket_tmp)
    ]);

    mainMenu($bot, "Товар добавлен в корзину");

});
$botman->hears('/remove_from_basket ([0-9]+)', function ($bot, $prodId) {
    $basket = json_decode($bot->userStorage()->get("basket")) ?? [];

    $basket_tmp = [];

    foreach ($basket as $product) {
        if ($product->id != $prodId)
        {
            array_push($basket_tmp, $product);
        }
        else{
            if($product->number>1){
                $product->number--;
                $product->total = intval($product->number) * floatval($product->price);
                array_push($basket_tmp, $product);
            }
        }
    }

    $bot->userStorage()->save([
        'basket' => json_encode($basket_tmp)
    ]);

    if (count($basket_tmp) == 0)
        mainMenu($bot,"Товар удален из корзины");
    else
        basketMenu($bot, "Товар удален из корзины");

});
$botman->hears('/remove_all_from_basket ([0-9]+)', function ($bot, $prodId) {
    $basket = json_decode($bot->userStorage()->get("basket")) ?? [];

    $basket_tmp = [];

    foreach ($basket as $product) {
        if ($product->id != $prodId)
        {
            array_push($basket_tmp, $product);
        }
    }

    $bot->userStorage()->save([
        'basket' => json_encode($basket_tmp)
    ]);

    if (count($basket_tmp) == 0)
        mainMenu($bot,"Товар удален из корзины");
    else
        basketMenu($bot, "Товар удален из корзины");

});
$botman->hears('/do_order|Оформить заказ.*', BotManController::class . "@orderConversation");
//$botman->hears('Запрос по VIN', function ($bot) {
//
//    $telegramUser = $bot->getUser();
//    $id = $telegramUser->getId();
////    $phone = $telegramUser->getPhone();
//
//
//
//    $keyboard = [
//        [
//            ['text' => "Поиск шин", 'callback_data' => "/search_1"],
//            ['text' => "Поиск дисков", 'callback_data' => "/search_2"],
//
//        ],
//    ];
//
//    $bot->sendRequest("sendMessage",
//        [
//            "chat_id" => "$id",
//            "text" => "Спасибо за ваше исскуство!",
//            'reply_markup' => json_encode([
//                'inline_keyboard' =>
//                    $keyboard
//            ])
//        ]);
//
//});

/*Запрос по VIN*/
function vinMenu($bot, $message)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $keyboard = [
        ["Главное меню"],
    ];
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $message,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'one_time_keyboard' => false,
                'resize_keyboard' => true
            ])
        ]);
}
$botman->hears('Запрос по VIN|/vin', function ($bot) {
    vinMenu($bot,'Наш интернет-магазин предоставляет вам возможность осуществить подбор запчастей по VIN в онлайн режиме. Это позволит с максимальной оперативностью найти требуемую деталь, получая гарантии того, что она идеально подойдет для вашего авто. После заполнения всех данных, запрос сразу будет отправлен нам.');
    $bot->startConversation(new VinConversation($bot));
});
function vinMark($bot, $page){
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $marks_request = json_decode($bot->userStorage()->get("marks")) ?? [];
    $marks= array_chunk($marks_request, 10);

    foreach ($marks[$page] as $key => $mark) {
        $keyboard = [
            [
                ['text' => "Выбрать", 'callback_data' => "/model" . $mark->value],
            ],
        ];

        if (count($marks[$page]) - 1 == $key && $page == 0 && count($marks[$page]) == 10)
        {
            $new_page = $page + 1;
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/mark " . ($page + 1)]
            ]);
        }

        if (count($marks[$page]) - 1 == $key && $page != 0 && count($marks[$page]) == 10)
        {
            $new_page = $page + 1;
            $old_page = $page - 1;
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAAНазад", 'callback_data' =>"/mark " . ($page - 1)],
                ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/mark " . ($page + 1)]
            ]);
        }


        if (count($marks[$page]) - 1 == $key && $page != 0 && count($marks[$page]) < 10)
        {
            $old_page = $page - 1;
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => "/mark " . ($page - 1)],
            ]);
        }

        $bot->sendRequest("sendMessage",
            [
                "chat_id" => "$id",
                "text" => $mark->name,
                'reply_markup' => json_encode([
                    'inline_keyboard' =>
                        $keyboard
                ])
            ]);
    }
};
$botman->hears('/mark ([0-9]+)', function ($bot, $page)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $marks_request = json_decode($bot->userStorage()->get("marks")) ?? [];
    $marks= array_chunk($marks_request, 10);

    Log::info('page = '.$page);
    foreach ($marks[$page] as $key => $mark) {
        $keyboard = [
            [
                ['text' => "Выбрать", 'callback_data' => "/model" . $mark->value],
            ],
        ];

        if (count($marks[$page]) - 1 == $key && $page == 0 && count($marks[$page]) == 10)
        {
            $new_page = $page + 1;
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/mark " . ($page + 1)]
            ]);
        }

        if (count($marks[$page]) - 1 == $key && $page != 0 && count($marks[$page]) == 10)
        {
            $new_page = $page + 1;
            $old_page = $page - 1;
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAAНазад", 'callback_data' =>"/mark " . ($page - 1)],
                ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => "/mark " . ($page + 1)]
            ]);
        }


        if (count($marks[$page]) - 1 == $key && $page != 0 && count($marks[$page]) < 10)
        {
            $old_page = $page - 1;
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => "/mark " . ($page - 1)],
            ]);
        }


        $bot->sendRequest("sendMessage",
            [
                "chat_id" => "$id",
                "text" => $mark->name,
                'reply_markup' => json_encode([
                    'inline_keyboard' =>
                        $keyboard
                ])
            ]);
    }Log::info('page = '.$page);
});
$botman->hears('/model ([0-9]+)', function ($bot, $id){
    $bot->userStorage()->save([
        'mark_id' => $id
    ]);
//    $bot->askModel();
});

/*Заказ уникального товара*/
function uniqueOrderMenu($bot, $message)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $orders = json_decode($bot->userStorage()->get("orders")) ?? [];
//    $orders =[];
//    $bot->userStorage()->save([
//        'orders' => json_encode($orders)
//    ]);
    $keyboard = [
        ["Добавить запчасть"],
    ];

    if(count($orders))
    {
        array_push($keyboard, ["Отправить заказ"]);
    }
    array_push($keyboard, ["Главное меню"]);
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $message,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'one_time_keyboard' => false,
                'resize_keyboard' => true
            ])
        ]);
}
$botman->hears('Заказ уникального товара|/uniqueOrder', function ($bot) {
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $orders = json_decode($bot->userStorage()->get("orders")) ?? [];
    if (count($orders)){
        uniqueOrderMenu($bot,"У Вас есть сохраненные заказы.");
        foreach ($orders as $key => $order) {

            $keyboard = [
                [
                    ['text' => "Убрать", 'callback_data' => "/remove_from_orders " . $order->id]
                ],
            ];
            $text = "*".($key + 1) . ") Название:* ". $order->title ."\n"
                ."*Артикул:* " . $order->code . "\n"
                ."*Кол-во:* " . $order->quantity . "\n"
                ."*Примечание:* ".$order->note."\n";
            $bot->sendRequest("sendMessage",
                [
                    "chat_id" => "$id",
                    'parse_mode' => 'Markdown',
                    "text" => $text,
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $keyboard
                    ])
                ]);
        }
    }
    else{
        uniqueOrderMenu($bot,'Для того, чтобы сделать уникальный заказ, необходимо указать наименование, артикул запчасти, а также ее количество и по желанию примечание');
        $bot->startConversation(new UniqueOrderConversation($bot));
    }
});
$botman->hears('Добавить запчасть|/add_order', BotManController::class . "@uniqueOrderConversation");
$botman->hears('/remove_from_orders ([0-9]+)', function ($bot, $orderId) {
    $orders = json_decode($bot->userStorage()->get("orders")) ?? [];

    $orders_tmp = [];

    foreach ($orders as $order) {
        if ($order->id != $orderId)
        {
            array_push($orders_tmp, $order);
        }
    }

    $bot->userStorage()->save([
        'orders' => json_encode($orders_tmp)
    ]);

    if (count($orders_tmp) == 0)
        mainMenu($bot,"Все заказы удалены");
    else
        uniqueOrderMenu($bot, "Заказ удален");

});
$botman->hears('Отправить заказ.*',function ($bot) {
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $user = \App\User::where("telegram_chat_id", $id)->first();
    $orders = json_decode($bot->userStorage()->get("orders")) ?? [];
    $order_tmp = "*Бот* Заказ уникального товара:\n"
            . "*Имя*:" . ($user->fio_from_telegram ?? $user->name ). "\n"
            . "*Телефон*:" . $user->phone . "\n"
        . "*Дата заказа*:" . (Carbon::now('+3:00')) . "\n*Заказ*:\n";
    foreach ($orders as $key => $order) {
        $order_tmp .= "\n*".($key + 1) . ") Название:* ". $order->title ."\n"
            ."*Артикул:* " . $order->code . "\n"
            ."*Кол-во:* " . $order->quantity . "\n"
            ."*Примечание:* ".$order->note."\n";
    }

    try {
        Telegram::sendMessage([
            'chat_id' => env("CHANNEL_ID"),
            'parse_mode' => 'Markdown',
            'text' => $order_tmp,
            'disable_notification' => 'false'
        ]);
    } catch (\Exception $e) {
        Log::info("Ошибка отправки заказа в канал!");
    }

    $bot->userStorage()->save([
        'orders' => json_encode([])
    ]);
    mainMenu($bot,"Ваши заказы успешно отправлены");
});

