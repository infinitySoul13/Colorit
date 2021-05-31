<?php
namespace App\Conversations;

use App\User;
use BotMan\BotMan\BotMan;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Validator;

class UserConversation extends Conversation
{
    protected $bot;
    protected $id;
    protected $username;
    protected $lastName;
    protected $firstName;
    protected $user;
    protected $phone;

    public function __construct($bot)
    {
        $this->bot = $bot;
        $telegramUser = $bot->getUser();
        $this->id = $telegramUser->getId();
        $this->username = $telegramUser->getUsername();
        $this->lastName = $telegramUser->getLastName();
        $this->firstName = $telegramUser->getFirstName();
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
            ["Products"],
            ["Cart" . ($count == null ? "(0₽)" : "(" . $count . "₽)")],
            ["Make an order"],
            ["About us"],
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
    public function askPhone()
    {
        $question = Question::create('Tell me your phone number in the format "+38 (000) 000-00-00"')
            ->fallback('Thank you for chatting with me:)!');

        $this->ask($question, function (Answer $answer) {
            $this->phone = $answer->getText();
            $validator = Validator::make(['mobile' => $answer->getText()], [
                'mobile' => 'required|regex:/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',
            ]);

            if ($validator->fails()) {
                $this->bot->reply("Phone number entered incorrectly...\n");
                $this->askPhone();
            } else {
                $this->askName();
            }
        });
    }
    public function askName() {
        $question = Question::create('Tell me your name')
            ->fallback('Thank you for chatting with me:)!');

        $this->ask($question, function (Answer $answer) {
            $tmp_user = User::where("phone", $this->phone)->first();
            if ($tmp_user == null) {

                $user = User::create([
                    'name' => $answer->getText(),
                    'fio_from_telegram' => $this->firstName." ".$this->lastName,
                    'telegram_chat_id' => $this->id,
                    'is_admin' => false,
                    'phone' => $this->phone,
                ]);
                $this->user = $user;
            }
            else
            {
                $this->user = $tmp_user;
                $this->user->name = $answer->getText();
                $this->user->fio_from_telegram = $this->firstName." ".$this->lastName;
                $this->user->telegram_chat_id = $this->id;
                $this->user->save();
                $tmp_user->name = $answer->getText();
                $tmp_user->fio_from_telegram = $this->firstName." ".$this->lastName;
                $tmp_user->telegram_chat_id = $this->id;
                $tmp_user->save();
                $this->mainMenu("We are already familiar with you.");
//                    $this->bot->reply("We are already familiar with you.");
            }

            $this->mainMenu("Now we are familiar with you. Welcome to the ColorIt store bot");
        });
    }
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->say("Before we start, let's get to know each other.");
        $this->askPhone();
    }
}