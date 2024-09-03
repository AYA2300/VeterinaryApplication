<?php
namespace App\Services\Applications\ChatPusher;

use App\Models\Conversation;
use App\Models\Veterinarian;
use App\Events\SendMessageEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\FileStorageTrait;
use App\Models\Breeder;
use Illuminate\Support\Facades\Storage;
use Throwable;

 class App_MessageService
 {
    use FileStorageTrait;

    public function send_message(array $input_data,$receiver_id)
    {
         $result=[];
         $data=[];
         $status_code=400;
         $msg='';


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
                        $status_code=400;
                        $msg='Invalid sender or receiver';
                   }
                 $conversation=Conversation::UpdateOrCreate([
                 "{$sendType}_id" => $sender->id,
                  ($sendType=='breeder'?'veterinary_id':'breeder_id') => $receiver->id,
                    ]);
                    if ($input_data['type'] == 'text') {
                      $messageContent = $input_data['message'];
                  } elseif ($input_data['type'] == 'audio') {
                      $path = $input_data['audio']->store('public/audios')??null;
                      $messageContent = Storage::url($path);
                  } elseif ($input_data['type'] == 'image') {
                      $messageContent =$this->storeFile($input_data['image'],'chats')??null;

                  }

                    $message=$sender->messages()->create([
                     'conversation_id' =>$conversation->id,
                     'type' => $input_data['type'],
                     'message'=>$messageContent
                    ]);


                    $conversation_id=$conversation->id;
                    \broadcast(new SendMessageEvent($message, $conversation_id))->toOthers();
                    DB::commit();
                    $data['sender']=[
                        'name'=>  $sender->name,
                              'id' => $sender->id,

                            ];
                    $data['receiver']=[
                        'name' => $receiver->name,
                        'id' => $receiver->id,

                    ];

                    $data['message']=$message;
                    $status_code=200;
                    $msg='تم ارسال رسالتك بنجاح';

          }
           catch(Throwable $th){
              DB::rollBack();
              Log::debug($th);
              $msg = 'error ' . $th->getMessage();
              $status_code = 500;
             $data = $th;

        }
        $result = [
            'data' =>$data,

            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;


    }
    ///------------------------------------------------
    //show messages in conversation
    public function show_messages(Conversation $conversation)
    {

        $result=[];
        $data=[];
        $status_code=400;
        $msg='';

        $user = null;

        if (Auth::guard('breeder')->check()) {
            $user = Auth::guard('breeder')->user();
            $isAuthorized = $conversation->breeder_id === $user->id;
            $receiver_id = $conversation->veterinary_id;
            $receiver_name = $conversation->Veterinarian->name;

        } elseif (Auth::guard('veterinarian')->check()) {
            $user = Auth::guard('veterinarian')->user();
            $isAuthorized = $conversation->veterinary_id === $user->id;
            $receiver_id = $conversation->breeder_id;
            $receiver_name = $conversation->breeder->name;


        } else {
            $status_code=403;
           $msg='Unauthorized';
        }

        // إذا لم يكن المستخدم جزءًا من المحادثة
        if (!$isAuthorized) {
           $status_code=403;
           $msg='Unauthorized';
        }else{
            $messages = $conversation->messages;
        $data['messages']=$messages;
        $data['sender']=[
            'name'=>  $user->name,
                  'id' => $user->id,

                ];
        $data['receiver']=[
            'name' => $receiver_name,
            'id' => $receiver_id,

        ];
        $status_code=200;
        $msg='عرض الرسائل الخاصة بهذه المحادثة';

        }
        // جلب الرسائل من المحادثة
        ;


        $result = [
            'data' =>$data,

          'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

    }

//get messages
public function get_messages($user_id){
    $result = [];
    $data = [];
    $status_code = 400;
    $msg = '';


    // Check if the user is authenticated as a breeder
    if (Auth::guard('breeder')->check()) {
        $user = Auth::guard('breeder')->user();
        $veterinarian = Veterinarian::find($user_id);


        // Ensure the veterinarian exists
        if (!$veterinarian) {
            $msg = 'الطبيب البيطري غير موجود';  // Veterinarian not found
            $status_code = 404;
        } else {
            // Check if the breeder is authorized to view messages from this veterinarian
            $conversation = Conversation::where('breeder_id', $user->id)
                                        ->where('veterinary_id', $veterinarian->id)
                                        ->first();
                                    $receiver=$conversation->veterinary_id;
                                    $receiver_name=$conversation->Veterinarian->name;


            if (!$conversation) {
                $msg = 'لا تملك الصلاحيات';  // Unauthorized access
                $status_code = 403;
            } else {
                // Retrieve messages from the conversation
                $messages = $conversation->messages;
                $data['messages'] = $messages;
                $msg = 'عرض الرسائل';  // Displaying messages
                $status_code = 200;
            }
        }
    }
    // Check if the user is authenticated as a veterinarian
    elseif (Auth::guard('veterinarian')->check()) {
        $user = Auth::guard('veterinarian')->user();
        $breeder = Breeder::find($user_id);

        // Ensure the breeder exists
        if (!$breeder) {
            $msg = 'المربي غير موجود';  // Breeder not found
            $status_code = 404;
        } else {
            // Check if the veterinarian is authorized to view messages with this breeder
            $conversation = Conversation::where('breeder_id', $breeder->id)
                                        ->where('veterinary_id', $user->id)
                                        ->first();
                                        $receiver=$conversation->breeder_id;
                                        $receiver_name=$conversation->breeder->name;

            if (!$conversation) {
                $msg = 'لا تملك الصلاحيات';  // Unauthorized access
                $status_code = 403;
            } else {
                // Retrieve messages from the conversation
                $messages = $conversation->messages;
                $data['sender']=[
                    'name'=>  $user->name,
                          'id' => $user->id,

                        ];
                $data['receiver']=[
                    'name' => $receiver_name,
                    'id' => $receiver,

                ];

                $data['messages'] = $messages;
                $msg = 'عرض الرسائل';  // Displaying messages
                $status_code = 200;
            }
        }
    }
    // If the user is neither a breeder nor a veterinarian
    else {
        $msg = 'لم يتم التحقق من الهوية';  // Authentication failed
        $status_code = 401;
    }



        // Construct the response
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;
    }




}


?>
