<?php

namespace App\Conversations;

use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
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
    protected $images;

    protected $quantity;
    protected $phone;
    protected $user_name;

    public function __construct($bot)
    {
        $telegramUser = $bot->getUser()->getId();

        Log::info("telegram chat id=$telegramUser");
        $this->bot = $bot;

        $this->chat_id =  $this->bot->getUser()->getId();
    }
    /**
     * Greetings to start the conversation.
     *
     */
    public function greetings()
    {
        $text = 'Добро пожаловать в оформление заказа, выберите услугу, который вас интересует! Перед оформлением заказа советуем ознакомиться с прайс-листом';
        $question = Question::create($text)
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->callbackId('greetings')
            ->addButtons(
                [
                    Button::create('Визитки')->value('Визитки'),
                    Button::create('Листовки')->value('Листовки'),
                    Button::create('Бланки')->value('Бланки'),
                    Button::create('Буклеты')->value('Буклеты'),
                    Button::create('Наклейки на бумаге')->value('Наклейки на бумаге'),
                    Button::create('Пластиковые карты')->value('Пластиковые карты'),
                    Button::create('Чертежи')->value('Чертежи'),
                    Button::create('Штендеры')->value('Штендеры'),
                    Button::create('Баннеры')->value('Баннеры'),
                    Button::create('Таблички на акриле')->value('Таблички на акриле'),
                    Button::create('Наклейки на плёнке')->value('Наклейки на плёнке'),
                    Button::create('Таблички на ПВХ')->value('Таблички на ПВХ'),
                    Button::create('Трафареты')->value('Трафареты'),
                ]
            );
        $this->ask($question, function (Answer $answer) {
            $this->service = $answer->getText();
//            $inline_keyboard=[
//                [
//                    ["text" => "Визитки", "callback_data" => "Визитки"],
//                    ["text" => "Листовки", "callback_data" => "/service  "],
//                    ["text" => "Бланки", "callback_data" => "/service  "]
//                ],
//                [
//                    ["text" => "Буклеты", "callback_data" => "/service  "],
//                    ["text" => "Наклейки на бумаге", "callback_data" => "/service  "]
//                ],
//                [
//                    ["text" => "Пластиковые карты", "callback_data" => "/service  "],
//                    ["text" => "Чертежи", "callback_data" => "/service  "]
//                ],
//                [
//                    ["text" => "Штендеры", "callback_data" => "/service  "],
//                    ["text" => "Баннеры", "callback_data" => "/service  "]
//                ],
//                [
//                    ["text" => "Таблички на акриле", "callback_data" => "/service  "],
//                    ["text" => "Наклейки на плёнке", "callback_data" => "/service  "]
//                ],
//                [
//                    ["text" => "Таблички на ПВХ", "callback_data" => "/service  "],
//                    ["text" => "Трафареты", "callback_data" => "/service  "]
//                ]
//            ];
//            $telegramUser = $this->bot->getUser();
//            $id = $telegramUser->getId();
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => "$id",
//                    "text" => "1",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' => $inline_keyboard
//                    ])
//                ]);
            $this->service = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->service = $answer->getText();
                $this->askSize();
            }

        });

//        $this->askSize();
    }
    public function askSize()
    {
        $question = Question::create('Выберите желаемый размер:')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                [
                    Button::create('90х50')->value('Визитки'),
                    Button::create('85х55')->value('Листовки'),
                    Button::create('100х70')->value('Бланки'),
                    Button::create('100х90')->value('Буклеты'),
                    Button::create('105х74')->value('Наклейки на бумаге'),
                    Button::create('148х70')->value('Пластиковые карты'),
                    Button::create('148х105')->value('Чертежи'),
                    Button::create('180х105')->value('Штендеры'),
                    Button::create('200х70')->value('Баннеры'),
                    Button::create('204х101')->value('Таблички на акриле'),
                    Button::create('210х90')->value('Наклейки на плёнке'),
                    Button::create('210х99')->value('Таблички на ПВХ'),
                    Button::create('210х148')->value('Трафареты'),
                    Button::create('210х200')->value('Трафареты'),
                    Button::create('297х210')->value('Трафареты'),
                ]
            );
        $this->ask( $question , function (Answer $answer) {
//            $inline_keyboard=[
//                [
//                    ["text" => "90х50", "callback_data" => ""],
//                    ["text" => "85х55", "callback_data" => ""],
//                    ["text" => "100х70", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "100х90", "callback_data" => ""],
//                    ["text" => "105х74", "callback_data" => ""],
//                    ["text" => "148х70", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "148х105", "callback_data" => ""],
//                    ["text" => "180х105", "callback_data" => ""],
//                    ["text" => "200х70", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "204х101", "callback_data" => ""],
//                    ["text" => "210х90", "callback_data" => ""],
//                    ["text" => "210х99", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "210х148", "callback_data" => ""],
//                    ["text" => "210х200", "callback_data" => ""],
//                    ["text" => "297х210", "callback_data" => ""],
//
//                ],
//            ];
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => $this->chat_id,
//                    "text" => "",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $inline_keyboard
//                    ])
//                ]);
            $this->size = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->size = $answer->getText();
                $this->askMaterial();
            }
        });
//        $this->askMaterial();
    }
   public function askMaterial()
    {
        $question = Question::create('Выберите материал:')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                [
                    Button::create('Raflatak с просечками')->value('Визитки'),
                    Button::create('Крафт 70')->value('Листовки'),
                    Button::create('Офсет 80')->value('Бланки'),
                    Button::create('Мел МАТ 115')->value('Буклеты'),
                    Button::create('Мел ГЛ 90')->value('Наклейки на бумаге'),
                    Button::create('Мел ГЛ 115')->value('Пластиковые карты'),
                    Button::create('Мел ГЛ 130')->value('Чертежи'),
                    Button::create('Мел ГЛ 150')->value('Штендеры'),
                    Button::create('Мел ГЛ 170')->value('Баннеры'),
                    Button::create('Мел ГЛ 200')->value('Таблички на акриле'),
                    Button::create('Мел ГЛ 250')->value('Наклейки на плёнке'),
                    Button::create('Мел ГЛ 300')->value('Таблички на ПВХ'),
                    Button::create('Мел ГЛ 350')->value('Трафареты')
                ]
            );
        $this->ask($question , function (Answer $answer) {
//            $inline_keyboard=[
//                [
//                    ["text" => "Raflatak с просечками", "callback_data" => ""],
//                    ["text" => "Крафт 70", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Офсет 80", "callback_data" => ""],
//                    ["text" => "Мел МАТ 115", "callback_data" => ""],
//                    ["text" => "Мел ГЛ 90", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Мел ГЛ 115", "callback_data" => ""],
//                    ["text" => "Мел ГЛ 130", "callback_data" => ""],
//                    ["text" => "Мел ГЛ 150", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Мел ГЛ 170", "callback_data" => ""],
//                    ["text" => "Мел ГЛ 200", "callback_data" => ""],
//                    ["text" => "Мел ГЛ 250", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Мел ГЛ 300", "callback_data" => ""],
//                    ["text" => "Мел ГЛ 350", "callback_data" => ""],
//
//                ],
//            ];
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => $this->chat_id,
//                    "text" => "",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $inline_keyboard
//                    ])
//                ]);
//            $this->material = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->material = $answer->getText();
                $this->askCoverOfset();
            }
        });
//        $this->askCoverOfset();
    }

    public function askCoverOfset()
    {
        $question = Question::create('Выберите покрытие ОФСЕТ')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                [
                    Button::create('Нет')->value('Визитки'),
                    Button::create('ГЛ лам 1+0')->value('Листовки'),
                    Button::create('ГЛ лам 1+1')->value('Буклеты'),
                    Button::create('МАТ лам 1+0')->value('Наклейки на бумаге'),
                    Button::create('МАТ лам 1+1')->value('Пластиковые карты'),
                    Button::create('УФ лак 1+0')->value('Чертежи'),
                    Button::create('Гибрид 1+0')->value('Баннеры'),
                ]
            );
        $this->ask($question, function (Answer $answer) {
//            $inline_keyboard=[
//                [
//                    ["text" => "Нет", "callback_data" => ""],
//                    ["text" => "ГЛ лам 1+0", "callback_data" => ""],
//                    ["text" => "ГЛ лам 1+1", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "МАТ лам 1+0", "callback_data" => ""],
//                    ["text" => "МАТ лам 1+1", "callback_data" => ""],
//                    ["text" => "УФ лак 1+0", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Гибрид 1+0", "callback_data" => ""],
//                ],
//            ];
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => $this->chat_id,
//                    "text" => "",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $inline_keyboard
//                    ])
//                ]);
            $this->cover_ofset = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->cover_ofset = $answer->getText();
                $this->askCover();
            }
        });
//        $this->askCover();
    }
    public function askCover()
    {
        $question = Question::create('Выберите покрытие:')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                [
                    Button::create('Нет')->value('Визитки'),
                    Button::create('Ламинация рулонная')->value('Листовки'),
                    Button::create('Ламинация конвертная')->value('Бланки'),
                    Button::create('Глянцевая УФ лак')->value('Наклейки на бумаге'),
                ]
            );
        $this->ask($question, function (Answer $answer) {
//            $inline_keyboard=[
//                [
//                    ["text" => "Нет", "callback_data" => ""],
//                    ["text" => "Ламинация рулонная", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Ламинация конвертная", "callback_data" => ""],
//                    ["text" => "Глянцевая УФ лак", "callback_data" => ""],
//                ],
//            ];
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => $this->chat_id,
//                    "text" => "",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $inline_keyboard
//                    ])
//                ]);
            $this->cover = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->cover = $answer->getText();
                $this->askProcessing();
            }
        });
//        $this->askProcessing();
    }
    public function askProcessing()
    {
        $question = Question::create('Выберите постпечатную обработку:')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                [
                    Button::create('Персонализация')->value('Визитки'),
                    Button::create('Закругление углов')->value('Листовки'),
                    Button::create('Проклейка в блок')->value('Бланки'),
                    Button::create('Сверление')->value('Буклеты'),
                    Button::create('Сгиб')->value('Наклейки на бумаге'),
                    Button::create('Биговка')->value('Наклейки на бумаге'),
                    Button::create('Перфорация')->value('Наклейки на бумаге'),
                    Button::create('Плоттерная прорезка')->value('Наклейки на бумаге'),
                    Button::create('Фигурная прорезка')->value('Наклейки на бумаге'),
                    Button::create('Конвертная ламинация')->value('Наклейки на бумаге'),
                ]
            );
        $this->ask($question, function (Answer $answer) {
//            $inline_keyboard=[
//                [
//                    ["text" => "Персонализация", "callback_data" => ""],
//                    ["text" => "Закругление углов", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Проклейка в блок", "callback_data" => ""],
//                    ["text" => "Сверление", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Сгиб", "callback_data" => ""],
//                    ["text" => "Биговка", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Перфорация", "callback_data" => ""],
//                    ["text" => "Плоттерная прорезка", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Фигурная прорезка", "callback_data" => ""],
//                    ["text" => "Конвертная ламинация", "callback_data" => ""],
//                ],
//            ];
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => $this->chat_id,
//                    "text" => "",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $inline_keyboard
//                    ])
//                ]);
            $this->processing = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->processing = $answer->getText();
                $this->askPrinting();
            }
        });
//        $this->askPrinting();
    }
    public function askPrinting()
    {
        $question = Question::create('Выберите вариант печати:')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                [
                    Button::create('Полноцветная 4+0')->value('Визитки'),
                    Button::create('Полноцветная 4+4')->value('Листовки'),
                    Button::create('Черно-белая 1+0')->value('Бланки'),
                    Button::create('Черно-белая 1+1')->value('Буклеты'),
                    Button::create('Белым 1+0')->value('Наклейки на бумаге'),
                    Button::create('Белым 2+0')->value('Наклейки на бумаге'),
                    Button::create('Белым 1+1')->value('Наклейки на бумаге'),
                    Button::create('Белым 2+1')->value('Наклейки на бумаге'),
                    Button::create('Цифровой лак 1+0')->value('Наклейки на бумаге'),
                    Button::create('Цифровой лак 1+1')->value('Наклейки на бумаге'),
                ]
            );
        $this->ask($question, function (Answer $answer) {
//            $inline_keyboard=[
//                [
//                    ["text" => "Полноцветная 4+0", "callback_data" => ""],
//                    ["text" => "Полноцветная 4+4", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Черно-белая 1+0", "callback_data" => ""],
//                    ["text" => "Черно-белая 1+1", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Белым 1+0", "callback_data" => ""],
//                    ["text" => "Белым 2+0", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Белым 1+1", "callback_data" => ""],
//                    ["text" => "Белым 2+1", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Цифровой лак 1+0", "callback_data" => ""],
//                    ["text" => "Цифровой лак 1+1", "callback_data" => ""],
//                ],
//            ];
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => $this->chat_id,
//                    "text" => "",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $inline_keyboard
//                    ])
//                ]);
            $this->printing = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->printing = $answer->getText();
                $this->askQuantity();
            }
        });
//        $this->askQuantity();
    }
    public function askQuantity(){
        $question = Question::create('Выберите необходимое количество экземпляров:')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                [
                    Button::create('1')->value('Визитки'),
                    Button::create('5')->value('Листовки'),
                    Button::create('25')->value('Бланки'),
                    Button::create('50')->value('Буклеты'),
                    Button::create('100')->value('Наклейки на бумаге'),
                    Button::create('200')->value('Наклейки на бумаге'),
                    Button::create('500')->value('Наклейки на бумаге'),
                ]
            );
        $this->ask( $question , function (Answer $answer) {
//            $inline_keyboard=[
//                [
//                    ["text" => "1", "callback_data" => ""],
//                    ["text" => "5", "callback_data" => ""],
//                    ["text" => "25", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "50", "callback_data" => ""],
//                    ["text" => "100", "callback_data" => ""],
//                    ["text" => "200", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "500", "callback_data" => ""],
//                ],
//            ];
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => $this->chat_id,
//                    "text" => "",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $inline_keyboard
//                    ])
//                ]);
            $this->quantity = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->quantity = $answer->getText();
                $this->askPeriod();
            }
        });
//        $this->askPeriod();
    }
    public function askPeriod(){
        $question = Question::create('Выберите срок печати:')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                [
                    Button::create('Экспресс 3 часа')->value('Визитки'),
                    Button::create('Стандарт 1 день')->value('Листовки'),
                    Button::create('Эконом 2 дня')->value('Бланки'),
                    Button::create('Супер эконом 3 дня')->value('Буклеты'),
                ]
            );
        $this->ask( $question, function (Answer $answer) {
//            $inline_keyboard=[
//                [
//                    ["text" => "Экспресс 3 часа", "callback_data" => ""],
//                    ["text" => "Стандарт 1 день", "callback_data" => ""],
//                ],
//                [
//                    ["text" => "Эконом 2 дня", "callback_data" => ""],
//                    ["text" => "Супер эконом 3 дня", "callback_data" => ""],
//                    ["text" => "200", "callback_data" => ""],
//                ]
//            ];
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => $this->chat_id,
//                    "text" => "",
//                    "parse_mode" => "Markdown",
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $inline_keyboard
//                    ])
//                ]);
            $this->period = $answer->getText();
            if ($answer->isInteractiveMessageReply()) {
                $this->period = $answer->getText();
                $this->askImage();
            }
        });
//        $this->askImage();
    }
    public function askImage()
    {
        $this->askForImages('Please upload an image.', function ($images) {
            //
            $this->images = $images;
        }, function(Answer $answer) {
            $this->images = $answer;
            // This method is called when no valid image was provided.
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
            . "*Сумма к оплате*:1015 руб\n"
            . "*Дата заказа*:" . (Carbon::now('+3:00'));

        $this->bot->sendRequest("sendMessage",
        [
            "chat_id" => $this->chat_id,
            "text" =>  $order_tmp,
            "parse_mode" => "Markdown",
        ]);
//        try {
////            Telegram::sendMessage([
////                'chat_id' => env("CHANNEL_ID"),
////                'parse_mode' => 'Markdown',
////                'text' => $order_tmp,
////                'disable_notification' => 'false'
////            ]);
//        } catch (\Exception $e) {
//            Log::info("Ошибка отправки заказа в канал!");
//        }

        $this->bot->userStorage()->save([
            'orders' => json_encode([])
        ]);
        $this->serviceOrderMenu("Ваш заказ успешно сформирован");
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