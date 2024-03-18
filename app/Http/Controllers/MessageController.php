<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\WebService\Chat\ChatPageRequest;
use App\Http\Services\MessageService;
use App\Http\Services\UserService;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function __construct(protected MessageService $messageService, protected UserService $userService)
    {
        $this->messageService = $messageService;
    }

    public function index(ChatPageRequest $request)
    {
        $authUser = auth()->user()->id;
        $conversationsUsersIds = $this->messageService->getUsersWithPastConversations($authUser);
        $conversationsUsers = $this->userService->findUsersArrayOfIds($conversationsUsersIds);
        $selectedUser = null;
        $messages = null;
        if ($request->acceptedUser) {
            $selectedUser = $this->userService->findUser($request->acceptedUser);
            $messages = $this->messageService->getMessagesBetweenUsers($authUser, $request->acceptedUser);
        }
        return inertia('Chat', ['users' => $conversationsUsers, 'selectedUser' => $selectedUser, 'messages' => $messages]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $response = $this->messageService->store($request->validated());
        broadcast(new MessageSent(auth()->user()->id, $response['data']))->toOthers();
        return ['message' => $response['data']];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user()->id;
        $messages = $this->messageService->getMessagesBetweenUsers($user, $id);
        return $messages;
    }

}
