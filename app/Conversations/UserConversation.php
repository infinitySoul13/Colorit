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
use Validator;

class UserConversation extends Conversation
{
    protected $bot;
    protected $id;
    protected $username;
    protected $lastName;
    protected $firstName;
    protected $user;

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
    public function askPhone()
    {
        $question = Question::create('Скажите мне свой номер телефона в формате "+38 (000) 000-00-00"')
            ->fallback('Спасибо что пообщались со мной:)!');

        $this->ask($question, function (Answer $answer) {
//            $vowels = array("(", ")", "-", " ");
            $tmp_phone = $answer->getText();
//            $tmp_phone = str_replace($vowels, "", $tmp_phone);
//            if (strpos($tmp_phone, "+38") === false)
//                $tmp_phone = "+38" . $tmp_phone;

            Log::info("phone=$tmp_phone");

//            $pattern = "/^\+38 (\d{3}) \d{2}-\d{2}-\d{2}$/";
//            Log::info(preg_match($pattern, $tmp_phone));
            $validator = Validator::make(['mobile' => $answer->getText()], [
                'mobile' => 'required|regex:/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',
            ]);
//            Log::info($validator->fails());
//            preg_match($pattern, $tmp_phone) == 1
            Log::info("phone1=$tmp_phone");

            if ($validator->fails()) {
                Log::info("phone2=$tmp_phone");

                $this->bot->reply("Номер введен не верно...\n");
                $this->askPhone();
//                return;
            } else {
                $tmp_user = \App\User::where("phone", $tmp_phone)->first();
                if ($tmp_user == null) {

                    $user = \App\User::create([
                        'name' => $this->username ?? "$this->id",
                        'fio_from_telegram' => $this->firstName." ".$this->lastName,
                        'telegram_chat_id' => $this->id,
                        'is_admin' => false,
                        'phone' => $tmp_phone,
                    ]);
                    $this->user = $user;
                    $this->askName();
//                    $this->user->phone = $tmp_phone;
//                    $this->user->save();
                }
                else
                {
                    $this->user = $tmp_user;
                    $this->user->fio_from_telegram = $this->firstName." ".$this->lastName;
                    $this->user->telegram_chat_id = $this->id;
                    $this->user->save();
                    $tmp_user->fio_from_telegram = $this->firstName." ".$this->lastName;
                    $tmp_user->telegram_chat_id = $this->id;
                    $tmp_user->save();
//                    if ($this->user->name==''|| $this->user->name==null)
//                    {
//                        $this->askName();
//                    }
                    $this->mainMenu("Мы с Вами уже знакомы. Теперь данные бота и сайта синхронизированы");
                }

//                $this->askVincode();

            }

        });
    }
    public function askName() {
        $question = Question::create('Скажите мне своё имя')
            ->fallback('Спасибо, что пообщались со мной:)!');

        $this->ask($question, function (Answer $answer) {
            $this->name = $answer->getText();
            $this->user->name = $this->name;
            $this->user->save();
            $this->mainMenu("Теперь мы с Вами знакомы. Добро пожаловать в бот магазина АВТОДОНа");
//            $this->askVincode();
        });
    }
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->say('Прежде, чем начать, давайте познакомимся.');
        $this->askPhone();
    }
}