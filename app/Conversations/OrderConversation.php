<?php

namespace App\Conversations;

use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class OrderConversation extends Conversation
{
    protected $bot;
    protected $user;
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
//            ["Запрос по VIN"],
//            ["Заказ уникального товара"],
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

    public function __construct($bot)
    {
        $telegramUser = $bot->getUser()->getId();

        Log::info("telegram chat id=$telegramUser");
        $this->bot = $bot;

        $this->user = User::where("telegram_chat_id", $telegramUser)->first();

    }

    public function sendOrder()
    {

        $basket = json_decode($this->bot->userStorage()->get("basket")) ?? [];

        $order_tmp = "*Бот* Новая заявка:\n"
            . "*Имя на сайте*:" . $this->user->name . "\n"
            . "*Имя в Telegram*:" . $this->user->fio_from_telegram . "\n"
            . "*Телефон*:" . $this->user->phone . "\n"
            . "*Дата заказа*:" . (Carbon::now('+3:00')) . "\n*Заказ*:\n";

        $summary = 0;

        foreach ($basket as $key => $product) {
            $summary += floatval($product->total);
            $order_tmp .= "*".($key + 1) . ")*" . $product->title ."\n"
                ."*Цена:* " . $product->price . "₽ \n*Кол-во:* ".$product->number. " шт.\n*Итого:* ".$product->total . "₽\n";
        }

        $order_tmp .= "*Сумма заказа*:" . $summary . "₽";

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
            'basket' => json_encode([])
        ]);

        $this->bot->userStorage()->delete();

        $this->mainMenu("Заказ отправлен!");

    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        //
        $this->sendOrder();
    }
}
