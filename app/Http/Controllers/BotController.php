<?php

namespace App\Http\Controllers;

use App\Http\TelegramSender;
use App\Models\Farmer;
use App\Models\Share;
use Illuminate\Http\Request;

class BotController extends Controller
{
    //
    public function init(Request $request){
        $telegram=new TelegramSender();

        $message=$request->get('message');
        $telegram->sendMessage($message['from']['id'],$message);

        $farmer=Farmer::where('telegram_id',$message['from']['id'])->get()->first();

        if($farmer){
          if($message['text']=="/stats"){
              $stats=$farmer->stats();
              $telegram->sendMessage($message['from']['id'],$stats);
          }
          else{
              $telegram->sendMessage($message['from']['id'],"Я тебя не понимаю");
          }
        }
        else{
            $shares=Share::where('worker',$message['text'])->first();
            if($shares){
                $farmer=new Farmer ();
                $farmer->telegram_id=$message['from']['id'];
                $farmer->address=$message['text'];
                $farmer->save();
                $telegram->sendMessage($message['from']['id'],"Кошелек добавлен");

            }
            else{
               $telegram->sendMessage($message['from']['id'],"Введите адрес кошелька");
            }
        }
    }
}
