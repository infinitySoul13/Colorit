<?php

namespace App\Conversations;

use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Validator;

class ServiceOrderConversation extends Conversation
{
    protected $bot;
    protected $user;
    protected $chat_id;
    protected $service;
    protected $size;
    protected $material;
    protected $cover_ofset;
    protected $cover;
    protected $processing;
    protected $printing;
    protected $period;
    protected $sum;

    protected $quantity;
    protected $phone;
    protected $user_name;

    public function __construct($bot)
    {
        $telegramUser = $bot->getUser()->getId();
        $this->bot = $bot;
        $this->user = User::where("telegram_chat_id", $telegramUser)->first();
        $this->chat_id = $telegramUser;
    }
    /**
     * Greetings to start the conversation.
     *
     * @return void
     */
    public function greetings()
    {
        $text = 'Добро пожаловать в оформление заказа, выберите услугу, который вас интересует! Перед оформлением заказа советуем ознакомиться с прайс-листом';
        $question = Question::create($text)
            ->fallback('Спасибо, что пообщались со мной:)!')
//            ->addButtons(
//                [
//                    Button::create('Визитки')->value('Визитки'),
//                    Button::create('')->value(''),
//                    Button::create('')->value('')
//                ]
//            )->addButtons(
//                [
//                    Button::create('Визитки')->value('Визитки'),
//                    Button::create('')->value(''),
//                    Button::create('')->value('')
//                ]
//            )
        ;
        return $this->ask($question, function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "Визитки", "callback_data" => "Визитки"],
                    ["text" => "Листовки", "callback_data" => "/service  "],
                    ["text" => "Бланки", "callback_data" => "/service  "],
                ],
                [
                    ["text" => "Буклеты", "callback_data" => "/service  "],
                    ["text" => "Наклейки на бумаге", "callback_data" => "/service  "],
                ],
                [
                    ["text" => "Пластиковые карты", "callback_data" => "/service  "],
                    ["text" => "Чертежи", "callback_data" => "/service  "],
                ],
                [
                    ["text" => "Штендеры", "callback_data" => "/service  "],
                    ["text" => "Баннеры", "callback_data" => "/service  "],
                ],
                [
                    ["text" => "Таблички на акриле", "callback_data" => "/service  "],
                    ["text" => "Наклейки на плёнке", "callback_data" => "/service  "],

                ],
                [
                    ["text" => "Таблички на ПВХ", "callback_data" => "/service  "],
                    ["text" => "Трафареты", "callback_data" => "/service  "],

                ],

            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
            $this->service = $answer->getText();
            Log::info("answer=$answer");
            Log::info("service=$this->service");
//            if ($answer->isInteractiveMessageReply()) {
//                $this->service = $answer->getText();
                $this->askSize();
//            }

        });

//        $this->askSize();
    }
    public function askSize()
    {
        $this->ask('Выберите желаемый размер:'.$this->service, function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "90х50", "callback_data" => ""],
                    ["text" => "85х55", "callback_data" => ""],
                    ["text" => "100х70", "callback_data" => ""],
                ],
                [
                    ["text" => "100х90", "callback_data" => ""],
                    ["text" => "105х74", "callback_data" => ""],
                    ["text" => "148х70", "callback_data" => ""],
                ],
                [
                    ["text" => "148х105", "callback_data" => ""],
                    ["text" => "180х105", "callback_data" => ""],
                    ["text" => "200х70", "callback_data" => ""],
                ],
                [
                    ["text" => "204х101", "callback_data" => ""],
                    ["text" => "210х90", "callback_data" => ""],
                    ["text" => "210х99", "callback_data" => ""],
                ],
                [
                    ["text" => "210х148", "callback_data" => ""],
                    ["text" => "210х200", "callback_data" => ""],
                    ["text" => "297х210", "callback_data" => ""],

                ],
            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
//            $this->size = $answer->getText();
//            if ($answer->isInteractiveMessageReply()) {
//                $this->size = $answer->getText();
//                $this->askMaterial();
//            }
        });
//        $this->askMaterial();
    }
   public function askMaterial()
    {
        $this->ask('Выберите материал:', function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "Raflatak с просечками", "callback_data" => ""],
                    ["text" => "Крафт 70", "callback_data" => ""],
                ],
                [
                    ["text" => "Офсет 80", "callback_data" => ""],
                    ["text" => "Мел МАТ 115", "callback_data" => ""],
                    ["text" => "Мел ГЛ 90", "callback_data" => ""],
                ],
                [
                    ["text" => "Мел ГЛ 115", "callback_data" => ""],
                    ["text" => "Мел ГЛ 130", "callback_data" => ""],
                    ["text" => "Мел ГЛ 150", "callback_data" => ""],
                ],
                [
                    ["text" => "Мел ГЛ 170", "callback_data" => ""],
                    ["text" => "Мел ГЛ 200", "callback_data" => ""],
                    ["text" => "Мел ГЛ 250", "callback_data" => ""],
                ],
                [
                    ["text" => "Мел ГЛ 300", "callback_data" => ""],
                    ["text" => "Мел ГЛ 350", "callback_data" => ""],

                ],
            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
            $this->material = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->material = $answer->getText();
                $this->askCoverOfset();
            }
        });
        $this->askCoverOfset();
    }

    public function askCoverOfset()
    {
        $this->ask('Выберите покрытие ОФСЕТ:', function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "Нет", "callback_data" => ""],
                    ["text" => "ГЛ лам 1+0", "callback_data" => ""],
                    ["text" => "ГЛ лам 1+1", "callback_data" => ""],
                ],
                [
                    ["text" => "МАТ лам 1+0", "callback_data" => ""],
                    ["text" => "МАТ лам 1+1", "callback_data" => ""],
                    ["text" => "УФ лак 1+0", "callback_data" => ""],
                ],
                [
                    ["text" => "Гибрид 1+0", "callback_data" => ""],
                ],
            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
            $this->cover_ofset = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->cover_ofset = $answer->getText();
                $this->askCover();
            }
        });
        $this->askCover();
    }
    public function askCover()
    {
        $this->ask('Выберите покрытие:', function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "Нет", "callback_data" => ""],
                    ["text" => "Ламинация рулонная", "callback_data" => ""],
                ],
                [
                    ["text" => "Ламинация конвертная", "callback_data" => ""],
                    ["text" => "Глянцевая УФ лак", "callback_data" => ""],
                ],
            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
            $this->cover = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->cover = $answer->getText();
                $this->askProcessing();
            }
        });
        $this->askProcessing();
    }
    public function askProcessing()
    {
        $this->ask('Выберите постпечатную обработку:', function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "Персонализация", "callback_data" => ""],
                    ["text" => "Закругление углов", "callback_data" => ""],
                ],
                [
                    ["text" => "Проклейка в блок", "callback_data" => ""],
                    ["text" => "Сверление", "callback_data" => ""],
                ],
                [
                    ["text" => "Сгиб", "callback_data" => ""],
                    ["text" => "Биговка", "callback_data" => ""],
                ],
                [
                    ["text" => "Перфорация", "callback_data" => ""],
                    ["text" => "Плоттерная прорезка", "callback_data" => ""],
                ],
                [
                    ["text" => "Фигурная прорезка", "callback_data" => ""],
                    ["text" => "Конвертная ламинация", "callback_data" => ""],
                ],
            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
            $this->processing = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->processing = $answer->getText();
                $this->askPrinting();
            }
        });
        $this->askPrinting();
    }
    public function askPrinting()
    {
        $this->ask('Выберите вариант печати:', function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "Полноцветная 4+0", "callback_data" => ""],
                    ["text" => "Полноцветная 4+4", "callback_data" => ""],
                ],
                [
                    ["text" => "Черно-белая 1+0", "callback_data" => ""],
                    ["text" => "Черно-белая 1+1", "callback_data" => ""],
                ],
                [
                    ["text" => "Белым 1+0", "callback_data" => ""],
                    ["text" => "Белым 2+0", "callback_data" => ""],
                ],
                [
                    ["text" => "Белым 1+1", "callback_data" => ""],
                    ["text" => "Белым 2+1", "callback_data" => ""],
                ],
                [
                    ["text" => "Цифровой лак 1+0", "callback_data" => ""],
                    ["text" => "Цифровой лак 1+1", "callback_data" => ""],
                ],
            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
            $this->printing = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->printing = $answer->getText();
                $this->askQuantity();
            }
        });
        $this->askQuantity();
    }
    public function askQuantity(){
        $this->ask('Выберите необходимое количество экземпляров:', function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "1", "callback_data" => ""],
                    ["text" => "5", "callback_data" => ""],
                    ["text" => "25", "callback_data" => ""],
                ],
                [
                    ["text" => "50", "callback_data" => ""],
                    ["text" => "100", "callback_data" => ""],
                    ["text" => "200", "callback_data" => ""],
                ],
                [
                    ["text" => "500", "callback_data" => ""],
                ],
            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
            $this->quantity = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->quantity = $answer->getText();
                $this->askCover();
            }
        });
        $this->askPeriod();
    }
    public function askPeriod(){
        $this->ask('Выберите срок печати:', function (Answer $answer) {
            $inline_keyboard=[
                [
                    ["text" => "Экспресс 3 часа", "callback_data" => ""],
                    ["text" => "Стандарт 1 день", "callback_data" => ""],
                ],
                [
                    ["text" => "Эконом 2 дня", "callback_data" => ""],
                    ["text" => "Супер эконом 3 дня", "callback_data" => ""],
                    ["text" => "200", "callback_data" => ""],
                ]
            ];
            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => $this->chat_id,
                    "text" => "",
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $inline_keyboard
                    ])
                ]);
            $this->period = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->period = $answer->getText();
                $this->askImage();
            }
        });
        $this->askImage();
    }
    public function askImage()
    {
        $this->askForImages('Выберите дизайн', function ($images) {
            //todo: сохранить картинки на серваке и получить ссылки
            $this->askPhone();
        });
    }
    public function askPhone()
    {
        $question = Question::create('Скажите мне Ваш телефонный номер')
            ->fallback('Спасибо что пообщались со мной:)!');
        $this->ask($question, function (Answer $answer) {
            $vowels = array("(", ")", "-", " ");
            $tmp_phone = $answer->getText();
            $tmp_phone = str_replace($vowels, "", $tmp_phone);
            if (strpos($tmp_phone, "+7") === false)
                $tmp_phone = "+7" . $tmp_phone;
            Log::info("phone=$tmp_phone");
            $pattern = "/^\+7\d{3}\d{3}\d{2}\d{2}$/";
            if (preg_match($pattern, $tmp_phone) == 0) {
                $this->bot->reply("Номер введен не верно...\n");
                $this->askPhone();
                return;
            } else {

                $this->phone = $tmp_phone;

                $this->askName();
            }
        });
    }
    public function askName()
    {
        $question = Question::create('Как Вас зовут?')
            ->fallback('Спасибо что пообщались со мной:)!');
        $this->ask($question, function (Answer $answer) {
            $this->user_name = $answer->getText();
            $this->sendOrder();

        });
    }

    public function addOrder()
    {
        $orders = json_decode($this->bot->userStorage()->get("orders")) ?? [];
        $orders_tmp = [];

        foreach ($orders as $key => $order) {
            $order->id = $key;
            array_push($orders_tmp, $order);

        }
        $index = count($orders_tmp);
        $order=[ 'id'=>$index, 'title'=> $this->title, 'code' => $this->code,'quantity'=> $this->quantity, 'note' => $this->note];
        array_push($orders_tmp, $order);

        $this->bot->userStorage()->save([
            'orders' => json_encode($orders_tmp)
        ]);

        $this->title = '';
        $this->code = '';
        $this->quantity = '';
        $this->note = '';
    }

//    public function endQuestion()
//    {
//        $orders = json_decode($this->bot->userStorage()->get("orders")) ?? [];
//        $order=[ 'title'=> $this->title, 'code' => $this->code,'quantity'=> $this->quantity, 'note' => $this->note];
////        $order->title = $this->title;
////        $order->code = $this->code;
////        $order->quantity = $this->quantity;
////        $order->note = $this->note;
//        array_push($orders, $order);
//        $this->bot->userStorage()->save([
//            'orders' => json_encode($orders)
//        ]);
//        $question = Question::create('Что дальше?')
//            ->addButtons([
//                Button::create('Добавить запчасть')->value('add'),
//                Button::create('Оформить заказ')->value('send'),
//                Button::create('Просмотреть заказы')->value('menu'),
//                Button::create('Главное меню')->value('menu'),
//
//            ]);
//        $this->ask($question, function (Answer $answer) {
//            if ($answer->isInteractiveMessageReply()) {
//                switch ($answer->getValue()) {
//                    case 'add':
//                        $this->title= '';
//                        $this->code= '';
//                        $this->quantity= '';
//                        $this->note= '';
//                        $this->askTitle();
//                        break;
//                    case 'send':
//                        $this->sendOrder();
//                        break;
//                    case 'menu':
//                        $this->mainMenu("Ваши заказы сохранены");
//                        break;
//                }
//            }
//        });
//
//
//    }
    public function sendOrder()
    {
        $orders = json_decode($this->bot->userStorage()->get("orders")) ?? [];
        $order_tmp = "*Ваш заказ*:\n"
            . "*Услуга*:" . $this->service . "\n"
            . "*Размер*:" . $this->size . "\n"
            . "*Материал*:" . $this->material . "\n"
            . "*Офсет*:" . $this->cover_ofset . "\n"
            . "*Покрытие*:" . $this->cover . "\n"
            . "*Постобработка*:" . $this->processing . "\n"
            . "*Печать*:" . $this->printing . "\n"
            . "*Количество экземпляров*:" . $this->quantity . "\n"
            . "*Срок печати*:" . $this->period . "\n"
//            . "*Сумма к оплате*:" . $this->sum . "\n"
            . "*Дата заказа*:" . (Carbon::now('+3:00')) . "\n*Заказ*:\n";


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

        $this->bot->userStorage()->save([
            'orders' => json_encode([])
        ]);
        $this->mainMenu("Ваш заказ успешно отправлен");
    }
    private function mainMenu($message)
    {
        $telegramUser = $this->bot->getUser();
        $id = $telegramUser->getId();

        $basket = json_decode($this->bot->userStorage()->get("basket")) ?? [];
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
            ["О Нас"],
        ];


        $this->bot->sendRequest("sendMessage",
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
    private function serviceOrderMenu($message)
    {
        $telegramUser = $this->bot->getUser();
        $id = $telegramUser->getId();

        $orders = json_decode($this->bot->userStorage()->get("orders")) ?? [];

        $keyboard = [
            ["Отправить заказ"],
            ["Удалить заказ"],
        ];

        array_push($keyboard, ["Главное меню"]);
        $this->bot->sendRequest("sendMessage",
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
    /**
     * Start the conversation
     *
     * @return void
     */
    public function run()
    {
        $this->greetings();
    }
}