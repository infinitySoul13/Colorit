<?php

namespace App\Conversations;

use App\Vinrequest;
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

class VinConversation extends Conversation
{

    protected $bot;
    protected $user;
    protected $chat_id;
    protected $phone;
    protected $name;
    protected $vincode;
    protected $year;
    protected $month;
    protected $category;
    protected $category_id;
    protected $carmake;
    protected $carmake_id;
    protected $model;
    protected $power;
    protected $volume;
    protected $additional_info;
    protected $parts_info;
    protected $page;
    protected $marks;
    protected $models;
    protected $model_id;

    public function __construct($bot)
    {
        $telegramUser = $bot->getUser()->getId();

        Log::info("telegram chat id=$telegramUser");
        $this->bot = $bot;

        $this->user = User::where("telegram_chat_id", $telegramUser)->first();
        $this->chat_id = $telegramUser;
    }

//    public function askPhone()
//    {
//        $question = Question::create('Скажие мне свой номер телефона')
//            ->fallback('Спасибо что пообщались со мной:)!');
//
//        $this->ask($question, function (Answer $answer) {
//            $vowels = array("(", ")", "-", " ");
//            $tmp_phone = $answer->getText();
//            $tmp_phone = str_replace($vowels, "", $tmp_phone);
//            if (strpos($tmp_phone, "+38") === false)
//                $tmp_phone = "+38" . $tmp_phone;
//
//            Log::info("phone=$tmp_phone");
//
//            $pattern = "/^\+380\d{3}\d{2}\d{2}\d{2}$/";
//            if (preg_match($pattern, $tmp_phone) == 0) {
//                $this->bot->reply("Номер введен не верно...\n");
//                $this->askPhone();
//                return;
//            } else {
//                $tmp_user = User::where("phone", $tmp_phone)->first();
//                if ($tmp_user == null) {
//                    $this->user->phone = $tmp_phone;
//                    $this->user->save();
//                }
//                else
//                {
//                    $tmp_user->phone = $tmp_phone;
//                    $tmp_user->save();
//                    if ($tmp_user->name==''|| $tmp_user->name==null)
//                    {
//                        $this->askName();
//                    }
//                }
//                $this->askVincode();
//
//            }
//
//        });
//    }
//    public function askName() {
//        $question = Question::create('Скажите мне своё имя')
//            ->fallback('Спасибо что пообщались со мной:)!');
//
//        $this->ask($question, function (Answer $answer) {
//           $this->name = $answer->getText();
//           $this->askVincode();
//        });
//    }

//'carmake',//+
//'power',//+
//'category',
//'month',//+
//'model',//+
//'volume',//+
//'additional_info',//+
//'parts_info',//+

//'cylinders',//+
//'body_type',//+
//'engine_type',//+
//'gearbox_type',//+
//'steering_wheel',//+
//'options',
//'equipment',//+
//'valves',//+
//'number_of_doors',//+
//'drive',//+
//'gearbox_number',//+

    public function askVincode() {
        $question = Question::create('Введите VIN-код / № кузова')
            ->fallback('Спасибо что пообщались со мной:)!');

        $this->ask($question, function (Answer $answer) {
            $this->vincode = $answer->getText();
            $this->askYear();
        });
    }
    public function askYear() {
        $question = Question::create('Введите год выпуска')
            ->fallback('Спасибо что пообщались со мной:)!');

        $this->ask($question, function (Answer $answer) {
            $validator = Validator::make(['item' => $answer->getText()], [
                'item' => 'required|numeric|min:1983',
            ]);

            if ($validator->fails()) {
                $this->bot->reply("Год выпуска введен не верно...Пожалуйста, введите год от 1983 до 2020\n");
                $this->askYear();
                return;
            }
            $this->year = $answer->getText();
            $this->askMonth();
        });
    }
    public function askMonth() {
        $buttons = [];
        $months= [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ];
        foreach ($months as $key => $month) {
        array_push($buttons, Button::create($month)->value($month));
        }
        $question = Question::create('Выберите месяц')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                $buttons
            );
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->month = $answer->getText();
//                foreach ($month as $key => $model) {
//                    if ($model->value == $this->category_id) {
//                        $this->category = $model->name;
//                    }
//                }
                $this->askCategory();
            }
        });
//        $this->ask($question, function (Answer $answer) {
//            $this->month = $answer->getText();
//            $this->askCategory();
//        });
    }
    public function askCategory() {
        $buttons = [];
        $categories = (new \App\Services\RiaService)->getCategories();
        $this->bot->userStorage()->save([
            'categories' => json_encode($categories)
        ]);
        foreach ($categories as $key => $category) {
            array_push($buttons, Button::create($category['name'])->value($category['value']));
        }
        $question = Question::create('Выберите категорию')
            ->fallback('Спасибо, что пообщались со мной:)!')
            ->addButtons(
                $buttons
            );

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->category = $answer->getText();
                $this->category_id = $answer->getValue();
                $this->askCarmake();
            }
        });
    }
    public function marksButton($page)
    {

////        $marks = (new \App\Services\RiaService)->getMarks(intval($this->category_id));
//        $marks_request = json_decode($this->bot->userStorage()->get("marks")) ?? [];
//        $marks= array_chunk($marks_request, 10);
////        Log::info([$marks]);
////        $marks=[];
////        foreach ($marks as $key => $mark) {
////            array_push($marks, $mark);
////        }
//
////        for ($i = count($marks); $i<count($marks)*($page+1); $i++)
////        {
////            array_push($marks, $mark);
////        }
//        Log::info('page = '.$page);
//        foreach ($marks[$page] as $key => $mark) {
//            $keyboard = [
//                [
//                    ['text' => "Выбрать", 'callback_data' => '$this->askModel($mark->value)'],
//                ],
//            ];
//
//            if (count($marks[$page]) - 1 == $key && $page == 0 && count($marks[$page]) == 10)
//            {
//                $new_page = $page + 1;
//                array_push($keyboard, [
//                    ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => function ($page) {
//                        $new_page = $page + 1;
//                        $this->marksButton($new_page);
//                    }]
//                ]);
//            }
//
//
//
//            if (count($marks[$page]) - 1 == $key && $page != 0 && count($marks[$page]) == 10)
//            {
//                $new_page = $page + 1;
//                $old_page = $page - 1;
//                array_push($keyboard, [
//                    ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => '$this->marksButton($old_page)'],
//                    ['text' => "\xE2\x8F\xA9Далее", 'callback_data' => '$this->marksButton($new_page)']
//                ]);
//            }
//
//
//            if (count($marks[$page]) - 1 == $key && $page != 0 && count($marks[$page]) < 10)
//            {
//                $old_page = $page - 1;
//                array_push($keyboard, [
//                    ['text' => "\xE2\x8F\xAAНазад", 'callback_data' => '$this->marksButton($old_page)'],
//                ]);
//            }
//
//
//            $this->bot->sendRequest("sendMessage",
//                [
//                    "chat_id" => "$this->chat_id",
//                    "text" => $mark->name,
//                    'reply_markup' => json_encode([
//                        'inline_keyboard' =>
//                            $keyboard
//                    ])
//                ]);
//        }Log::info('page = '.$page);

        $marks_request = json_decode($this->bot->userStorage()->get("marks")) ?? [];
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

            $this->bot->sendRequest("sendMessage",
                [
                    "chat_id" => "$this->chat_id",
                    "text" => $mark->name,
                    'reply_markup' => json_encode([
                        'inline_keyboard' =>
                            $keyboard
                    ])
                ]);
        }
    }
    public function chooseCarmake($page)
    {
        $this->page=$page;
        $buttons = [];
        foreach ($this->marks[$page] as $key => $mark) {
            array_push($buttons, Button::create($mark['name'])->value($mark['value']));
        }

        if (count($this->marks[$page]) - 1 == $key && $page == 0 && count($this->marks[$page]) == 10)
        {
            array_push($buttons, Button::create("\xE2\x8F\xA9Далее")->value('next'));
        }

        if (count($this->marks[$page]) - 1 == $key && $page != 0 && count($this->marks[$page]) == 10)
        {
            array_push($buttons, Button::create("\xE2\x8F\xAAНазад")->value('prev'));
            array_push($buttons, Button::create("\xE2\x8F\xA9Далее")->value('next'));
        }

        if (count($this->marks[$page]) - 1 == $key && $page != 0 && count($this->marks[$page]) < 10)
        {
            array_push($buttons, Button::create("\xE2\x8F\xAAНазад")->value('prev'));
        }
        $question = Question::create('Выберите марку')
        ->fallback('Спасибо что пообщались со мной:)!')
        ->addButtons(
            $buttons
        );

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()=='next')
                {
                    $this->page++;
                    $this->chooseCarmake( $this->page);
                }
                if($answer->getValue()=='prev')
                {
                    $this->page--;
                    $this->chooseCarmake($this->page);
                }
                if($answer->getValue()!='next' && $answer->getValue()!='prev')
                {
                    $this->carmake = $answer->getText();
                    Log::info($answer->getText());
                    $this->carmake_id = $answer->getValue();
                    Log::info($answer->getValue());
                    $this->askModel();
                }

            }
        });
    }
    public function chooseModel($page)
    {
        $this->page=$page;
        $buttons = [];
        foreach ($this->models[$page] as $key => $model) {
            array_push($buttons, Button::create($model['name'])->value($model['value']));
        }

        if (count($this->models[$page]) - 1 == $key && $page == 0 && count($this->models[$page]) == 10)
        {
            array_push($buttons, Button::create("\xE2\x8F\xA9Далее")->value('next'));
        }

        if (count($this->models[$page]) - 1 == $key && $page != 0 && count($this->models[$page]) == 10)
        {
            array_push($buttons, Button::create("\xE2\x8F\xAAНазад")->value('prev'));
            array_push($buttons, Button::create("\xE2\x8F\xA9Далее")->value('next'));
        }

        if (count($this->models[$page]) - 1 == $key && $page != 0 && count($this->models[$page]) < 10)
        {
            array_push($buttons, Button::create("\xE2\x8F\xAAНазад")->value('prev'));
        }
        $question = Question::create('Выберите модель')
            ->fallback('Спасибо что пообщались со мной:)!')
            ->addButtons(
                $buttons
            );

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue()=='next')
                {
                    $this->page++;
                    $this->chooseModel( $this->page);
                }
                if($answer->getValue()=='prev')
                {
                    $this->page--;
                    $this->chooseModel($this->page);
                }
                if($answer->getValue()!='next' && $answer->getValue()!='prev')
                {
                    $this->model = $answer->getText();
                    Log::info($answer->getText());
                    $this->model_id = $answer->getValue();
                    Log::info($answer->getValue());
                    $this->askPower();
                }

            }
        });
    }
    public function askCarmake() {
//        $this->bot->reply('Выберите марку');
        $categories = json_decode($this->bot->userStorage()->get("categories")) ?? [];

        foreach ($categories as $key => $model) {
            if ($model->value == $this->category_id) {
                $this->category = $model->name;
            }
        }
        $marks = [];
        $marks_request = (new \App\Services\RiaService)->getMarks(intval($this->category_id));
//        $marks = json_decode($this->bot->userStorage()->get("marks")) ?? [];
        foreach ($marks_request as $key => $mark) {
            array_push($marks, $mark);
        }
        $this->bot->userStorage()->save([
            'marks' => json_encode($marks)
        ]);
//        $this->bot->userStorage()->save([
//            'marks' => json_encode($marks)
//        ]);
        $marks= array_chunk($marks, 10);
        $this->marks = $marks;
        $this->chooseCarmake(0);
//        vinMark($this->bot, 0);
//        $this->askModel();
//        $this->marksButton(0);
//        foreach ($marks as $key => $mark) {
//            array_push($buttons, Button::create($mark['name'])->value($mark['value']));
//        }
//
//    $question = Question::create('Марка')
//        ->fallback('Спасибо что пообщались со мной:)!')
//        ->addButtons(
//            $buttons
//        );
//
//    $this->ask($question, function (Answer $answer) {
//        if ($answer->isInteractiveMessageReply()) {
//            $this->carmake = $answer->getText();
//            Log::info($this->carmake);
//            $this->carmake_id = $answer->getValue();
//            Log::info($this->carmake_id);
//            $this->askModel();
//        }
//    });
}
    public function askModel() {
        $marks = json_decode($this->bot->userStorage()->get("marks")) ?? [];
        foreach ($marks as $key => $model) {
            if ($model->value == $this->carmake_id) {
                $this->carmake = $model->name;
            }
        }
        $models = [];
//        $mark_id = json_decode($this->bot->userStorage()->get("mark_id")) ?? '';
        $models_request = (new \App\Services\RiaService)->getModels($this->category_id, $this->carmake_id);
        foreach ($models_request as $key => $model) {
            array_push($models, $model);
        }
        $this->bot->userStorage()->save([
            'models' => json_encode($models)
        ]);
        $models= array_chunk($models, 10);
        $this->models = $models;
        $this->chooseModel(0);

//        foreach ($models as $key => $model) {
//            array_push($buttons, Button::create($model['name'])->value($model['value']));
//        }
//        $question = Question::create('Выберите модель')
//            ->fallback('Спасибо, что пообщались со мной:)!')
//        ->addButtons(
//            $buttons
//        );
//
//        $this->ask($question, function (Answer $answer) {
//            if ($answer->isInteractiveMessageReply()) {
//                $this->model = $answer->getText();
//                $this->askPower();
//            }
//        });
    }
    public function askPower() {
        $models = json_decode($this->bot->userStorage()->get("models")) ?? [];
        foreach ($models as $key => $model) {
            if ($model->value == $this->model_id) {
                $this->model = $model->name;
                Log::info('model = ' . $this->model);
            }
        }
        $question = Question::create('Введите мощность')
            ->fallback('Спасибо, что пообщались со мной:)!');

        $this->ask($question, function (Answer $answer) {
            $validator = Validator::make(['item' => $answer->getText()], [
                'item' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $this->bot->reply("Мощность введена не верно...Пожалуйста, введите только цифры\n");
                $this->askPower();
                return;
            }
            $this->power = $answer->getText();
            $this->askVolume();
        });
    }
    public function askVolume() {
        $question = Question::create('Объем двигателя')
            ->fallback('Спасибо что пообщались со мной:)!');
        $this->ask($question, function (Answer $answer) {

            $validator = Validator::make(['item' => $answer->getText()], [
                'item' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                $this->bot->reply("Объем двигателя введен не верно...Пожалуйста, введите только цифры\n");
                $this->askVolume();
                return;
            }
            $this->volume = $answer->getText();
            $this->askPartsInfo();
        });
    }
    public function askPartsInfo() {
        $question = Question::create('Какие запчасти Вас интересуют?')
            ->fallback('Спасибо, что пообщались со мной:)!');

        $this->ask($question, function (Answer $answer) {
            $this->parts_info = $answer->getText();
            $question = Question::create('Хотите написать дополнительную информацию?')
                ->addButtons([
                    Button::create('Да')->value('yes'),
                    Button::create('Нет')->value('no'),
                ]);
            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case 'yes':
                            $this->askAdditionalInfo();
                            break;
                        case 'no':
                            $this->sendVinRequest();
                            break;
                    }
                }
            });
        });
    }
    public function askAdditionalInfo() {
        $question = Question::create('Дополнительная информация')
            ->fallback('Спасибо что пообщались со мной:)!');

        $this->ask($question, function (Answer $answer) {
            $this->additional_info = $answer->getText();
            $this->sendVinRequest();
        });
    }
    public function sendVinRequest() {
        $text ="\n<b>Бот</b> Запрос по VIN\n"
            . "<b>Имя на сайте: </b>".$this->user->name."\n"
            . "<b>Имя в Telegram: </b>".$this->user->fio_from_telegram."\n"
            . "<b>Номер: </b>".$this->user->phone."\n"
            . "<b>Дата запроса: </b>".Carbon::now('+3:00')."\n"
            . "\n<b>*Запрос :</b>\n"
            . "<b>VinCode: </b>$this->vincode\n"
            . "<b>Год: </b>$this->year\n"
            . "<b>Месяц: </b>$this->month\n"
            . "<b>Категория: </b>$this->category\n"
            . "<b>Марка: </b>$this->carmake\n"
            . "<b>Модель: </b>$this->model\n"
            . "<b>Объем: </b>$this->volume\n"
            . "<b>Мощность: </b>$this->power\n"
            . "<b>Доп.информация: </b>$this->additional_info\n"
            . "<b>Интересующие запчасти: </b>$this->parts_info\n";

        Telegram::sendMessage([
            'chat_id' => env("CHANNEL_ID"),
            'parse_mode' => 'HTML',
            'text' => sprintf($text),
            'disable_notification' => 'false'
        ]);
        $vin = new Vinrequest;
        $vin->vincode = $this->vincode;
        $vin->year =$this->year;
        $vin->month =$this->month;
        $vin->category =$this->category;
        $vin->carmake=$this->carmake;
        $vin->model=$this->model;
        $vin->volume=$this->volume;
        $vin->power=$this->power;
        $vin->additional_info=$this->additional_info;
        $vin->parts_info=$this->parts_info;
        $vin->created_at = Carbon::now('+3:00');
        $vin->user_id = $this->user->id;
        $vin->save();
        $this->mainMenu('VIN запрос успешно отправлен');
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
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askVincode();
    }
}
