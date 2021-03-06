<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\ChatRepository as Chat;

class ChatController extends Controller
{
    private $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function saveChat(Request $request) 
    {
        return collect($this->chat->saveChat($request))->toJson(); 
    }
    
    public function getPrivateLetterList(Request $request) 
    {
        return collect($this->chat->getPrivateLetterList($request))->toJson(); 
    }
    /**
     * 更改已读状态
     */
    public function updateUnread(Request $request) 
    {
        return collect($this->chat->updateUnread($request))->toJson(); 
    }
    
    /**
     * 获取聊天内容
     */
    public function getChatRoomList(Request $request) 
    {
        return collect($this->chat->getChatRoomList($request))->toJson(); 
    }
    
    
}
