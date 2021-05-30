<?php

use App\Http\Controllers\BotManController;
use App\Product;
use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
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
        mainMenu($bot, 'Main menu');
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
        ["Products"],
        ["Cart" . ($count == null ? "(0£)" : "(" . $count . "£)")],
        ["Make an order"],
        ["About us"],
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
        ["Contacts"],
        ["Main menu"],
    ];
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => $id,
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
$botman->hears('/start|Main menu', function ($bot) {
//    createUser($bot);
    mainMenu($bot, 'Main menu');
})->stopsConversation();
$botman->hears('/stop', function ($bot) {
    mainMenu($bot, 'Main menu');
})->stopsConversation();
$botman->hears(".*About us", function ($bot) {
    aboutMenu($bot, "*Our contacts*\n"
        . "*Phone*: _+7 (000) 000 00 00_\n"
        . "*Email*: _colorit@gmail.com_\n"
    );
});
$botman->hears(".*Contacts", function ($bot) {
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'We are in Instagram', 'url' => 'https://www.instagram.com/'],
            ],
            [
                ['text' => 'We are in Вконтакте', 'url' => 'https://vk.com/'],
            ],
            [
                ['text' => 'Our website', 'url' => 'http://colorit-it.herokuapp.com/'],
            ]
        ]
    ];
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => 'Do you have any questions? - Go to our website and get answers!',
            "disable_notification"=>true,
            'reply_markup' => json_encode($keyboard)
        ]);
});
//$botman->hears('Make an order', BotManController::class . "@serviceOrderConversation");

/*Товары*/
$botman->hears("Products", function ($bot) {
//    $categories = \App\Product::all()->unique('category');
    $categories = \App\Category::all();
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    foreach ($categories as $key=>$category) {
//        $bot->sendRequest("sendPhoto",
//            [
//                "chat_id" => "$id",
//                "caption" => $category->title,
//                "photo" => 'https://ld-magento-72.template-help.com/magento_62000/pub/media/catalog/category/cat_3_lt_5.jpg',
//                "parse_mode" => "Markdown",
//                'reply_markup' => json_encode([
//                    'inline_keyboard' => [
//                        [
//                            ["text" => "\xF0\x9F\x91\x89More", "callback_data" => "/category 0 $key"]
//                        ]
//                    ],
//                ])
//            ]);
        $bot->sendRequest("sendMessage",
            [
                "chat_id" => "$id",
                "text" => $category->title,
                "disable_notification"=>true,
                "parse_mode" => "Markdown",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [
                            ["text" => "\xF0\x9F\x91\x89More", "callback_data" => "/category 0 ".$category->title]
                        ]
                    ],
                ])
            ]);
    }

});
$botman->hears('/category {page} {cat}', function ($bot, $page, $cat) {

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $page = intval($page);

//    $categories = \App\Product::all()->unique('category');
//    $categories = \App\Category::all();

    $products = \App\Product::where("category", $cat)
        ->where("quantity",">","0")
        ->take(10)
        ->skip($page * 10)
        ->get();

    if (count($products) == 0) {
        $bot->reply("No products found in this category $cat");
        return;
    }

    foreach ($products as $key => $product) {
        $keyboard = [
            [
                ['text' => "\xF0\x9F\x91\x89More", 'callback_data' => "/product_info " . $product->id],
                ['text' => "\xE2\x86\xAATo cart(" . $product->price . "£)", 'callback_data' => "/add_to_basket " . $product->id]
            ],
        ];

        if (count($products) - 1 == $key && $page == 0 && count($products) == 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xA9Next", 'callback_data' => "/category " . ($page + 1) . " " . $cat]
            ]);

        if (count($products) - 1 == $key && $page != 0 && count($products) == 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAABack", 'callback_data' => "/category " . ($page - 1) . " " . $cat],
                ['text' => "\xE2\x8F\xA9Next", 'callback_data' => "/category " . ($page + 1) . " " . $cat]
            ]);

        if (count($products) - 1 == $key && $page != 0 && count($products) < 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAABack", 'callback_data' => "/category " . ($page - 1) . " " . $cat],
            ]);
        $photo ='https://colorit-it.herokuapp.com/img/2colorit.png';
        if($product->src[0]['path'])
        {
            $photo ='https://colorit-it.herokuapp.com'.$product->src[0]['path'];
        }
        $bot->sendRequest("sendPhoto",
            [
                "chat_id" => "$id",
                "caption" => $product->title,
                "photo" => $photo,
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
//        . "*Цена*:" . $product->price . "£\n";
//
//
//    $keyboard = [
//        [
//            ['text' => "\xE2\x86\xAATo cart(" . $product->price . "£)", 'callback_data' => "/add_to_basket " . $product->id]
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
        . "*Type*:" . $product->type . "\n"
        . "*Category*:" . $product->category . "\n";
//    if($product->type == 'sale')
//    {
//        $message .= "*Price*:" . $product->discount_price . "£\n";
//    }
//    else {
        $message .= "*Price*:" . $product->price . "£\n";
//    }


    $keyboard = [
        [
            ['text' => "\xE2\x86\xAATo cart(" . $product->price . "£)", 'callback_data' => "/add_to_basket " . $product->id],
            ['text' => "Reviews", 'callback_data' => "/reviews " . $product->id],
        ]
    ];

    if (count($product->src) > 1) {
        array_push($keyboard, [['text' => "\xE2\x9C\xA8More photos", 'callback_data' => "/product_photos 0 " . $product->id]]);
    }
//    $bot->sendRequest("sendPhoto",
//        [
//            "chat_id" => "$id",
//            "photo" => 'https://colorit-it.herokuapp.com'.$product->src[0]['path'],
//            "caption" => $message,
//            "parse_mode" => "Markdown",
//            "disable_notification"=>true,
//            'reply_markup' => json_encode([
//                'inline_keyboard' =>
//                    $keyboard
//            ])
//        ]);
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $message,
            "disable_notification"=>true,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' =>
                    $keyboard
            ])
        ]);
});
$botman->hears("/products ([0-9]+) ([а-яА-Яa-zA-Z0-9]+)", function ($bot, $page, $catId) {
    productList($bot, $page, $catId);
});
$botman->hears("/product_photos ([0-9]+) ([0-9]+)", function ($bot, $page, $productId) {
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $product = Product::find($productId);
    foreach ($product->src as $image) {
        $attachment = new Image('https://colorit-it.herokuapp.com'.$image['path'], [
            'custom_payload' => true,
        ]);
        $message = OutgoingMessage::create("*".$product->title."*\n" .
            "_".$product->description."_")
            ->withAttachment($attachment);

        $bot->reply($message, ["parse_mode" => "Markdown"]);
    }
//    for ($i = 0; $i < 5; $i++) {
//        $attachment = new Image('https://www.vintec.co.rs/admin/content/image5.jpg', [
//            'custom_payload' => true,
//        ]);
//
//// Build message object
//        $message = OutgoingMessage::create("*Название товара*\n" .
//            "_Краткое описание товара_")
//            ->withAttachment($attachment);
//
//        $bot->reply($message, ["parse_mode" => "Markdown"]);
//    }

    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'To product', 'callback_data' => "/product_info $productId"],
            ],
            [
                ['text' => 'Back', 'callback_data' => "/product_photos $page"],
                ['text' => 'Next', 'callback_data' => "/product_photos $page"],
            ],


        ]
    ];
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => 'Your actions',
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
                ['text' => "\xE2\x8F\xA9Next", 'callback_data' => "/reviews " . ($page + 1)]
            ]);
        if (count($reviews) - 1 == $key && $page != 0 && count($reviews) == 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAABack", 'callback_data' => "/reviews " . ($page - 1)],
                ['text' => "\xE2\x8F\xA9Next", 'callback_data' => "/reviews " . ($page + 1)]
            ]);
        if (count($reviews) - 1 == $key && $page != 0 && count($reviews) < 10)
            array_push($keyboard, [
                ['text' => "\xE2\x8F\xAABack", 'callback_data' => "/reviews " . ($page - 1)],
            ]);

        $bot->sendRequest("sendMessage",
            [
                "chat_id" => "$id",
                "text" => "*" . $review->name . "*\n"
                    . "_" . $review->message . "_\n",
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
        $bot->reply("No products found in this category");
        return;
    }
    foreach ($products as $key => $product) {
        $keyboard = [
            [
                ['text' => "\xF0\x9F\x91\x89More", 'callback_data' => "/product_info " . $product->id],
            ],
            [
                ['text' => "\xE2\x86\xAATo cart(" . $product->price . "£)", 'callback_data' => "/add_to_basket " . $product->id]
            ]
        ];


        $bot->sendRequest("sendPhoto",
            [
                "chat_id" => "$id",
                "photo" => 'https://colorit-it.herokuapp.com'.$product->src[0]["path"],
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
            ['text' => "\xE2\x8F\xA9Next", 'callback_data' => "/products " . ($page + 1) . " " . $catId]
        ]);
    if (count($products) - 1 == $key && $page != 0 && count($products) == 10)
        array_push($keyboard, [
            ['text' => "\xE2\x8F\xAABack", 'callback_data' => "/products " . ($page - 1) . " " . $catId],
            ['text' => "\xE2\x8F\xA9Next", 'callback_data' => "/products " . ($page + 1) . " " . $catId]
        ]);
    if (count($products) - 1 == $key && $page != 0 && count($products) < 10)
        array_push($keyboard, [
            ['text' => "\xE2\x8F\xAABack", 'callback_data' => "/products " . ($page - 1) . " " . $catId],
        ]);

    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => "Select actions",
            "disable_notification"=>true,
            'reply_markup' => json_encode([
                'inline_keyboard' =>
                    $keyboard
            ])
        ]);
}
$botman->hears('.*Reviews', function ($bot) {
    reviews($bot, 0);
});
$botman->hears('/reviews ([0-9]+)', function ($bot, $page) {
    reviews($bot, $page);
});

/*Cart*/
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
        ["Checkout " . ($count == null ? "(0£)" : "(" . $count . "£)")],
        ["Main menu"],
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
$botman->hears('.*Cart.*', function ($bot) {

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $basket = json_decode($bot->userStorage()->get("basket")) ?? [];

    if (count($basket) == 0) {
        $bot->reply("Cart is empty:(");
        return;
    }

    basketMenu($bot, "Cart contents");


    foreach ($basket as $key => $product) {
        $keyboard = [
            [
                ['text' => "\xF0\x9F\x91\x89More", 'callback_data' => "/product_info " . $product->id],
                ['text' => "Remove (" . $product->price . "£)", 'callback_data' => "/remove_from_basket " . $product->id]
            ],
        ];
        Log::info(["$product"]);
        if($product->number>1){
            Log::info('number > 1');
            $button = ['text' => "Remove all (" . $product->total . "£)", 'callback_data' => "/remove_all_from_basket " . $product->id];
            array_push($keyboard, [$button]);
        }
        $text = "".$product->title."\n"
            ."Quantity:".$product->number." ";
        $photo ='https://colorit-it.herokuapp.com/img/2colorit.png';
        $src = $product->src[0];
        if($src->path)
        {
            $photo ='https://colorit-it.herokuapp.com'.$src->path;
        }
        $bot->sendRequest("sendPhoto",
            [
                "chat_id" => "$id",
                "caption" => $text,
                "photo" =>  $photo ,
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

    mainMenu($bot, "Product is added to cart");

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
        mainMenu($bot,"The item has been removed from the cart");
    else
        basketMenu($bot, "The item has been removed from the cart");

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
        mainMenu($bot,"The item has been removed from the cart");
    else
        basketMenu($bot, "The item has been removed from the cart");

});
$botman->hears('/do_order|Checkout.*', BotManController::class . "@orderConversation");

/*Service*/
$botman->hears('.*Make an order.*', function ($bot) {
    $text = 'Welcome! Here you can make an order. Choose the service that our typography provide.';
    $inline_keyboard=[
        [
            ["text" => "Business cards", "callback_data" => "/size Business_cards 200"],
            ["text" => "Leaflets", "callback_data" => "/size Leaflets 150"],
            ["text" => "Forms", "callback_data" => "/size Forms 120 "]
        ],
        [
            ["text" => "Booklets", "callback_data" => "/size Booklets 250 "],
            ["text" => "Stickers on paper", "callback_data" => "/size Stickers_on_paper 120"]
        ],
        [
            ["text" => "Plastic cards", "callback_data" => "/size Plastic_cards 300"],
            ["text" => "Drawings", "callback_data" => "/size Drawings 270"]
        ],
        [
            ["text" => "Pavement signs", "callback_data" => "/size Pavement_signs 130"],
            ["text" => "Banners", "callback_data" => "/size Banners 400"]
        ],
        [
            ["text" => "Acrylic plates", "callback_data" => "/size Acrylic_plates 500"],
            ["text" => "Stickers on tape", "callback_data" => "/size Stickers_on_tape 180"]
        ],
        [
            ["text" => "PVC plates", "callback_data" => "/size PVC_plates 260"],
            ["text" => "Stencils", "callback_data" => "/size Stencils 210"]
        ]
    ];
    $bot->userStorage()->save([
        'new_order_total' => 0
    ]);
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
$botman->hears('/size {service} ([0-9]+)', function ($bot, $service, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
        $bot->userStorage()->save([
        'service' => $service,
        'new_order_total' => $new_order_total
    ]);
    $text = 'Select the size you want:';
    $inline_keyboard=[
        [
            ["text" => "90х50", "callback_data" => "/material 90х50 100"],
            ["text" => "85х55", "callback_data" => "/material 85х55 110"],
            ["text" => "100х70", "callback_data" => "/material 100х70 120"],
        ],
        [
            ["text" => "100х90", "callback_data" => "/material 100х90 130"],
            ["text" => "105х74", "callback_data" => "/material 105х74 140"],
            ["text" => "148х70", "callback_data" => "/material 148х70 150"],
        ],
        [
            ["text" => "148х105", "callback_data" => "/material 148х105 160"],
            ["text" => "180х105", "callback_data" => "/material 180х105 170"],
            ["text" => "200х70", "callback_data" => "/material 200х70 180"],
        ],
        [
            ["text" => "204х101", "callback_data" => "/material 204х101 190"],
            ["text" => "210х90", "callback_data" => "/material 210х90 200"],
            ["text" => "210х99", "callback_data" => "/material 210х99 205"],
        ],
        [
            ["text" => "210х148", "callback_data" => "/material 210х148 220"],
            ["text" => "210х200", "callback_data" => "/material 210х200 250"],
            ["text" => "297х210", "callback_data" => "/material 297х210 350"],
        ],
    ];

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
$botman->hears('/material {size} ([0-9]+)', function ($bot, $size, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
    $bot->userStorage()->save([
        'size' => $size,
        'new_order_total' => $new_order_total
    ]);
    $text = 'Select the material:';
    $inline_keyboard = [
        [
            ["text" => "Raflatak with notches", "callback_data" => "/cover_ofset Raflatak_with_notches 50"],
            ["text" => "Craft 70", "callback_data" => "/cover_ofset Craft_70 70"],
        ],
        [
            ["text" => "Offset 80", "callback_data" => "/cover_ofset Offset_80 80"],
            ["text" => "Mel MAT 115", "callback_data" => "/cover_ofset Mel_MAT_115 115"],
            ["text" => "Mel GL 90", "callback_data" => "/cover_ofset Mel_GL_90 90"],
        ],
        [
            ["text" => "Mel GL 115", "callback_data" => "/cover_ofset Mel_GL_115 115"],
            ["text" => "Mel GL 130", "callback_data" => "/cover_ofset Mel_GL_130 130"],
            ["text" => "Mel GL 150", "callback_data" => "/cover_ofset Mel_GL_150 150"],
        ],
        [
            ["text" => "Mel GL 170", "callback_data" => "/cover_ofset Mel_GL_170 170"],
            ["text" => "Mel GL 200", "callback_data" => "/cover_ofset Mel_GL_200 200"],
            ["text" => "Mel GL 250", "callback_data" => "/cover_ofset Mel_GL_250 250"],
        ],
        [
            ["text" => "Mel GL 300", "callback_data" => "/cover_ofset Mel_GL_300 300"],
            ["text" => "Mel GL 350", "callback_data" => "/cover_ofset Mel_GL_350 350"],
        ],
    ];
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
$botman->hears('/cover_ofset {material} ([0-9]+)', function ($bot, $material, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
    $bot->userStorage()->save([
        'material' => $material,
        'new_order_total' => $new_order_total
    ]);
    $text = 'Select OFFSET coverage:';
    $inline_keyboard = [
        [
            ["text" => "No", "callback_data" => "/cover No 0"],
            ["text" => "GL lam 1 + 0", "callback_data" => "/cover GL_lam_1+0 50"],
            ["text" => "GL lam 1 + 1", "callback_data" => "/cover GL_lam_1+1 100"],
        ],
        [
            ["text" => "MAT lam 1 + 0", "callback_data" => "/cover MAT_lam_1+0 120"],
            ["text" => "MAT lam 1 + 1", "callback_data" => "/cover MAT_lam_1+1 160"],
            ["text" => "UV varnish 1 + 0", "callback_data" => "/cover UV_varnish_1+0 175"],
        ],
        [
            ["text" => "Hybrid 1 + 0", "callback_data" => "/cover Hybrid_1+0 165"],
        ],
    ];

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
$botman->hears('/cover {cover_ofset} ([0-9]+)', function ($bot, $cover_ofset, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
    $bot->userStorage()->save([
        'cover_ofset' => $cover_ofset,
        'new_order_total' => $new_order_total
    ]);
    $text = 'Select coverage:';
    $inline_keyboard = [
        [
            ["text" => "No", "callback_data" => "/processing No 0"],
            ["text" => "Roll lamination", "callback_data" => "/processing Roll_lamination 40"],
        ],
        [
            ["text" => "Envelope lamination", "callback_data" => "/processing Envelope_lamination 45"],
            ["text" => "Glossy UV varnish", "callback_data" => "/processing Glossy_UV_varnish 50"],
        ],
    ];

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
$botman->hears('/processing {cover} ([0-9]+)', function ($bot, $cover, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
    $bot->userStorage()->save([
        'cover' => $cover,
        'new_order_total' => $new_order_total
    ]);
    $text = 'Select post-printing:';
    $inline_keyboard = [
        [
            ["text" => "Personalization", "callback_data" => "/printing Personalization 30"],
            ["text" => "Rounding corners", "callback_data" => "/printing Rounding_corners 43"],
        ],
        [
            ["text" => "Glue to block", "callback_data" => "/printing Glue_to_block 56"],
            ["text" => "Drilling", "callback_data" => "/printing Drilling 75"],
        ],
        [
            ["text" => "Fold", "callback_data" => "/printing Fold 25"],
            ["text" => "Creasing", "callback_data" => "/printing Creasing 89"],
        ],
        [
            ["text" => "Perforation", "callback_data" => "/printing Perforation 74"],
            ["text" => "Plotter slicing", "callback_data" => "/printing Plotter_slicing 63"],
        ],
        [
            ["text" => "Notching", "callback_data" => "/printing Notching 28"],
            ["text" => "Envelope lamination", "callback_data" => "/printing Envelope_lamination 47"],
        ],
    ];

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
$botman->hears('/printing {processing} ([0-9]+)', function ($bot, $processing, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
    $bot->userStorage()->save([
        'processing' => $processing,
        'new_order_total' => $new_order_total
    ]);
    $text = 'Choose a print option:';
    $inline_keyboard = [
        [
            ["text" => "Full color 4 + 0", "callback_data" => "/quantity Full_color_4+0 200"],
            ["text" => "Full color 4 + 4", "callback_data" => "/quantity Full_color_4+4 300"],
        ],
        [
            ["text" => "Black and white 1 + 0", "callback_data" => "/quantity Black_and_white_1+0 100"],
            ["text" => "Black and white 1 + 1", "callback_data" => "/quantity Black_and_white_1+1 130"],
        ],
        [
            ["text" => "White 1 + 0", "callback_data" => "/quantity White_1+0 50"],
            ["text" => "White 2 + 0", "callback_data" => "/quantity White_2+0 70"],
        ],
        [
            ["text" => "White 1 + 1", "callback_data" => "/quantity White_1+1 80"],
            ["text" => "White 2 + 1", "callback_data" => "/quantity White_2+1 90"],
        ],
        [
            ["text" => "Digital varnish 1 + 0", "callback_data" => "/quantity Digital_varnish_1+0 150"],
            ["text" => "Digital varnish 1 + 1", "callback_data" => "/quantity Digital_varnish_1+1 180"],
        ],
    ];
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
$botman->hears('/quantity {printing} ([0-9]+)', function ($bot, $printing, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
    $bot->userStorage()->save([
        'printing' => $printing,
        'new_order_total' => $new_order_total
    ]);
    $text = 'Select the required number of copies:';
    $inline_keyboard = [
        [
            ["text" => "1", "callback_data" => "/period 1 30"],
            ["text" => "5", "callback_data" => "/period 5 50"],
            ["text" => "25", "callback_data" => "/period 25 80"],
        ],
        [
            ["text" => "50", "callback_data" => "/period 50 100"],
            ["text" => "100", "callback_data" => "/period 100 130"],
            ["text" => "200", "callback_data" => "/period 200 250"],
        ],
        [
            ["text" => "500", "callback_data" => "/period 500 700"],
        ],
    ];

    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
$botman->hears('/period {quantity} ([0-9]+)', function ($bot, $quantity, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
    $bot->userStorage()->save([
        'quantity' => $quantity,
        'new_order_total' => $new_order_total
    ]);
    $text = 'Select a print period:';
    $inline_keyboard = [
        [
            ["text" => "Express 3 hours", "callback_data" => "/make_an_order Express 400"],
            ["text" => "Standard 1 day", "callback_data" => "/make_an_order Standard 250"],
        ],
        [
            ["text" => "Economy 2 days", "callback_data" => "/make_an_order Economy 150"],
            ["text" => "Super economy 3 days", "callback_data" => "/make_an_order Super_economy 50"]
        ]
    ];
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "Markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
});
//$botman->hears('/image ([а-яА-Яa-zA-Z0-9]+)', function ($bot, $period) {
//    $bot->userStorage()->save([
//        'period' => $period
//    ]);
//    $text = 'Please upload an image:';
//    $bot->receivesImages(function($bot, $images) {
//
//        foreach ($images as $image) {
//            $url = $image->getUrl(); // The direct url
//            $title = $image->getTitle(); // The title, if available
//            $payload = $image->getPayload(); // The original payload
//        }
//    });
//
//    $telegramUser = $bot->getUser();
//    $id = $telegramUser->getId();
//    $bot->sendRequest("sendMessage",
//        [
//            "chat_id" => "$id",
//            "text" => $text,
//            "parse_mode" => "Markdown",
//        ]);
//});
$botman->hears('/send_an_order|Send an order*', function ($bot) {
    $text = "* New order *: \n"
        . "* Service *:". $bot->userStorage()->get("service"). "\n"
        . "*The size*:" . $bot->userStorage()->get("size"). "\n"
        . "* Material *:". $bot->userStorage()->get("material"). "\n"
        . "* Offset *:". $bot->userStorage()->get("cover_ofset"). "\n"
        . "* Coverage *:". $bot->userStorage()->get("cover"). "\n"
        . "* Post-processing *:". $bot->userStorage()->get("processing"). "\n"
        . "* Print *:". $bot->userStorage()->get("printing"). "\n"
        . "* Number of copies *:". $bot->userStorage()->get("quantity"). "\n"
        . "* Printing time *:".$bot->userStorage()->get("period"). "\n"
        . "* Amount to be paid *:". $bot->userStorage()->get("new_order_total")." £ \n"
        . "*Order date*:" . (Carbon :: now ('+ 3:00'));

    try {
        Telegram::sendMessage([
            'chat_id' => env("CHANNEL_ID"),
            'parse_mode' => 'HTML',
            'text' => $text,
            'disable_notification' => 'false'
        ]);
    } catch (\Exception $e) {
        Log::info("Error sending order to channel!");
    }
    mainMenu($bot,"Your order have been sent successfully");
});
$botman->hears('/make_an_order {period} ([0-9]+)', function ($bot, $period, $price) {
    $new_order_total = intval($bot->userStorage()->get("new_order_total"));
    $new_order_total =  $new_order_total + intval($price);
    $bot->userStorage()->save([
        'period' => $period,
        'new_order_total' => $new_order_total
    ]);
    $text = "* Your order *: \n"
         . "* Service *:". $bot->userStorage()->get("service"). "\n"
         . "*The size*:" . $bot->userStorage()->get("size"). "\n"
         . "* Material *:". $bot->userStorage()->get("material"). "\n"
         . "* Offset *:". $bot->userStorage()->get("cover_ofset"). "\n"
         . "* Coverage *:". $bot->userStorage()->get("cover"). "\n"
         . "* Post-processing *:". $bot->userStorage()->get("processing"). "\n"
         . "* Print *:". $bot->userStorage()->get("printing"). "\n"
         . "* Number of copies *:". $bot->userStorage()->get("quantity"). "\n"
         . "* Printing time *:".$bot->userStorage()->get("period"). "\n"
         . "* Amount to be paid *:". $new_order_total ." £ \n"
         . "*Order date*:" . (Carbon :: now ('+ 3:00'));
    serviceOrderMenu($bot,'You complete your order. What do you want to do now? You can send this order to us or remove it and make another one. Just press button in inline menu');
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();
    $bot->sendRequest("sendMessage",
        [
            "chat_id" => "$id",
            "text" => $text,
            "parse_mode" => "HTML",
        ]);
});


function serviceOrderMenu($bot,$message)
{
    $telegramUser = $bot->getUser();
    $id = $telegramUser->getId();

    $keyboard = [
        ["Main menu"],
        ["Send an order"],
        ["Remove order"],
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

