<?php

namespace App\Http\Controllers\Application\Mesaages;

use Storage;
use Throwable;
use App\Models\Breeder;
use App\Models\Conversation;
use App\Models\Veterinarian;
use Illuminate\Http\Request;
use App\Events\SendMessageEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\FileStorageTrait;
use App\Http\Resources\Breeder\Auth_BreederResource;
use App\Http\Resources\MessagesChat\MessageResource;
use App\Http\Requests\MessagesChat\App_CreateMesaageRequest;

class App_MessageController extends Controller
{
    //
    use FileStorageTrait;



    //send Message

    public function send_message(App_CreateMesaageRequest $request,$receiver_id)
    {

         try{
          DB::beginTransaction();

          $sender = null;
          $receiver = null;
          $sendType = null;
               if(Auth::guard('breeder')->check()){
                 $sender=Auth::guard('breeder')->user();
                 $receiver=Veterinarian::Where('id',$receiver_id)->first();
                 $sendType='breeder';

                }elseif (Auth::guard('veterinarian')->check()) {
                    $sender = Auth::guard('veterinarian')->user();
                    $receiver = Breeder::find($receiver_id);
                    $sendType = 'veterinary';
         }
          // التحقق من وجود المستخدم والمستقبل
                    if (!$sender || !$receiver) {
                    return response()->json(['error' => 'Invalid sender or receiver'], 400);
                 }
               $conversation=Conversation::UpdateOrCreate([
               "{$sendType}_id" => $sender->id,
                ($sendType=='breeder'?'veterinary_id':'breeder_id') => $receiver->id,
                  ]);
                  if ($request->type == 'text') {
                    $messageContent = $request->message;
                } elseif ($request->type == 'audio') {
                    $path = $request->file('audio')->store('public/audios')??null;
                    $messageContent = Storage::url($path);
                } elseif ($request->type == 'image') {
                    $messageContent =$this->storeFile($request->image,'chats')??null;

                }

                  $message=$sender->messages()->create([
                   'conversation_id' =>$conversation->id,
                   'type' => $request->type,
                   'message'=>$messageContent
                  ]);
                  $conversation_id=$conversation->id;
                  \broadcast(new SendMessageEvent($message, $conversation_id))->toOthers();
                  DB::commit();
                  return response()->json([
                   'message' => new MessageResource($message)
                  ]);
        }
         catch(Throwable $th){
            DB::rollBack();
            Log::debug($th);
            $msg = 'error ' . $th->getMessage();
           return response()->json([
            'status'=> 'error'
           ]);
        }

                }

                public function show_messages(Conversation $conversation)
                {
                    $user = null;

                    if (Auth::guard('breeder')->check()) {
                        $user = Auth::guard('breeder')->user();
                        $isAuthorized = $conversation->breeder_id === $user->id;
                    } elseif (Auth::guard('veterinarian')->check()) {
                        $user = Auth::guard('veterinarian')->user();
                        $isAuthorized = $conversation->veterinary_id === $user->id;
                    } else {
                        return response()->json(['auther' => 'Unauthorized'], 403);
                    }

                    // إذا لم يكن المستخدم جزءًا من المحادثة
                    if (!$isAuthorized) {
                        return response()->json(['auther' => 'Unauthorized'], 403);
                    }
                    // جلب الرسائل من المحادثة
                    $messages = $conversation->messages;

                    return response()->json([
                        'message' =>  MessageResource::Collection($messages),

                    ]);
                }
}
