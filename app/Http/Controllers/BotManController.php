<?php

namespace App\Http\Controllers;

use App\Conversations\OrderConversation;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\VinConversation;
use App\Conversations\UniqueOrderConversation;
use App\Conversations\ServiceOrderConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param BotMan $bot
     */
    public function serviceOrderConversation(BotMan $bot)
    {
        $bot->startConversation(new ServiceOrderConversation($bot));
    }

    public function orderConversation(BotMan $bot)
    {
        $bot->startConversation(new OrderConversation($bot));
    }
}
