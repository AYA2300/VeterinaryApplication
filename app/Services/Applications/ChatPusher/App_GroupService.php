<?php
namespace App\Services\Applications\ChatPusher;

use App\Events\GroupMessageEvent;
use App\Http\Traits\FileStorageTrait;
use App\Models\Community;
use App\Models\Group_Message;
use App\Notifications\CommunityMessageNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class App_GroupService{

    use FileStorageTrait;



    public function sendMessage(array $input_data,$community_id)
    {
        $data = [];
        $status_code = 400;
        $msg = '';
        $result = [];


        // $breeder_id = Auth::guard('breeder')->id();
        $breeder= Auth::guard('breeder')->user(); // Get the currently authenticated user
        // Get the currently authenticated user

        if ($input_data['type'] == 'text') {
            $messageContent = $input_data['message'];
        } elseif ($input_data['type'] == 'audio') {
            $path = $input_data['audio']->store('public/audios')??null;
            $messageContent = Storage::url($path);
        } elseif ($input_data['type'] == 'image') {
            $messageContent =$this->storeFile($input_data['image'],'chats')??null;

        }

        // Find the community
        $community = Community::find($community_id);

        if (!$community) {
            $status_code = 404;
            $msg = 'Community not found';
        } else {
            // Create a new message
            $message = Group_Message::create([
                'community_id' => $community_id,
                'breeder_id' => $breeder->id,
                'message' => $messageContent,
                'type' => $input_data['type'],
            ]);


            \broadcast(new GroupMessageEvent($message, $community_id))->toOthers();
            $community_members = $community->breeders;

            // إرسال إشعار لكل مربي في المجتمع
            foreach ($community_members as $member) {
                if ($member->id != $breeder->id) { // لا ترسل إشعار لنفس الشخص الذي أرسل الرسالة
                    $member->notify(new CommunityMessageNotification($message));
                }
            }


            $data = [
                'community_id'=>$community_id,
                'message_id' => $message->id,
                'sender_id' => $breeder->id,
                'sender_name'=>$breeder->name,
                'message' => $messageContent,

            ];
            $status_code = 200;
            $msg = 'Message sent successfully';
        }

        // Prepare the result
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;
    }


    public function show_message($community_id)
    {
        $result = [];
        $data = [];
        $status_code = 400;
        $msg = '';

        $community = Community::with('group_messages.breeder')->find($community_id);
        // dd($community);

        if (!$community) {
            $status_code = 404;
            $msg = 'Community not found';
        } else {
            // Get all messages with the user who sent each message
            $messages = $community->group_messages->map(function ($message) {
                return [
                    'message_id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->breeder->id,
                    'sender_name' => $message->breeder->name, // Include other user details if needed
                    'created_at' => $message->created_at,
                ];
            });

            $data = [
                'community_id' => $community_id,
                'messages' => $messages,
            ];
            $status_code = 200;
            $msg = 'Messages retrieved successfully';
        }

        // Prepare the result
        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;



}

public function get_communities(){
    $breeder=Auth::guard('breeder')->user();

    $communities=$breeder->communities;


    $data['communities']=$communities;
    $status_code = 200;
    $msg = 'Message sent successfully';


// Prepare the result
$result = [
    'data' => $data,
    'status_code' => $status_code,
    'msg' => $msg,
];

return $result;
}





}
