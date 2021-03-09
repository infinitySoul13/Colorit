<?php

namespace App\Conversations;

use App\Vinrequest;
use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Validator;

class UniqueOrderConversation extends Conversation
{
    protected $bot;
    protected $user;
    protected $title;
    protected $code;
    protected $quantity;
    protected $note;

    public function __construct($bot)
    {
        $telegramUser = $bot->getUser()->getId();
        $this->bot = $bot;
        $this->user = User::where("telegram_chat_id", $telegramUser)->first();
    }
    /**
     * Greetings to start the conversation.
     *
     * @return void
     */
    public function greetings()
    {
        $this->say('Итак, начнем!');
        $this->askTitle();
    }
    public function askTitle()
    {
        $this->ask('Напишите наименование запчасти', function (Answer $answer) {
            $this->title = $answer->getText();
            $this->askCode();
        });

    }
    public function askCode()
    {
        $this->ask('Напишите артикул запчасти', function (Answer $answer) {
            $this->code = $answer->getText();
            $this->askQuantity();
        });

    }
    public function askQuantity()
    {
        $this->ask('Напишите необходимое Вам количество этой запчасти, например 1 или 2', function (Answer $answer) {
            $this->quantity = $answer->getText();
            $validator = Validator::make(['quantity' => $answer->getText()], [
                'quantity' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                Log::info("quantity=$this->quantity");

                $this->bot->reply("Количество введено не верно...\n");
                $this->askQuantity();
                return;
            }
            $question = Question::create('Хотите написать примечание?')
                ->addButtons([
                    Button::create('Да')->value('yes'),
                    Button::create('Нет')->value('no'),
                ]);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case 'yes':
                            $this->askNote();
                            break;
                        case 'no':
//                            $this->endQuestion();
//                            $orders = json_decode($this->bot->userStorage()->get("orders")) ?? [];
//                            $index = count($orders);
//                            $order=[ 'id'=>$index, 'title'=> $this->title, 'code' => $this->code,'quantity'=> $this->quantity, 'note' => $this->note];
//                            array_push($orders, $order);
//                            $this->title = '';
//                            $this->code = '';
//                            $this->quantity = '';
//                            $this->note = '';
//                            $this->bot->userStorage()->save([
//                                'orders' => json_encode($orders)
//                            ]);
                            $this->addOrder();
                            $this->uniqueOrderMenu('Заказ сохранен');
                            break;
                    }
                }
            });
        });

    }

    public function askNote()
    {
        $this->ask('Напишите примечание к запчасти', function (Answer $answer) {
            $this->note = $answer->getText();
            $this->addOrder();
            $this->uniqueOrderMenu('Заказ сохранен');
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
        $order_tmp = "*Бот* Заказ уникального товара:\n"
            . "*Имя на сайте*:" . $this->user->name . "\n"
            . "*Имя в Telegram*:" . $this->user->fio_from_telegram . "\n"
            . "*Телефон*:" . $this->user->phone . "\n"
            . "*Дата заказа*:" . (Carbon::now('+3:00')) . "\n*Заказ*:\n";
        foreach ($orders as $key => $order) {
//            $order_tmp .= ($key + 1) . ")" . $order->title . "_ " . $order->code . "_ " . $order->quantity . " шт._ ".$order->note."\n";
            $order_tmp .= "\n*".($key + 1) . ") Название:* ". $order->title ."\n"
                ."*Артикул:* " . $order->code . "\n"
                ."*Кол-во:* " . $order->quantity . "\n"
                ."*Примечание:* ".$order->note."\n";
        }
//        $order_tmp .="".$orders[0]->title;
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
        $this->mainMenu("Ваши заказы успешно отправлены");
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
            ["Запрос по VIN"],
            ["Заказ уникального товара"],
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
    private function uniqueOrderMenu($message)
    {
        $telegramUser = $this->bot->getUser();
        $id = $telegramUser->getId();

        $orders = json_decode($this->bot->userStorage()->get("orders")) ?? [];

        $keyboard = [
            ["Отправить заказ"],
        ];

        if(count($orders))
        {
            array_push($keyboard, ["Добавить запчасть"]);
        }
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
//        $orders = json_decode($this->bot->userStorage()->get("orders")) ?? [];
//        if(count($orders)){
//
//        }
        $this->greetings();
    }
}