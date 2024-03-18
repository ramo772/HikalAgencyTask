<?php

namespace App\Http\Services;

use App\Models\Building;
use App\Models\City;
use App\Models\Message;
use App\Repositories\CityRepository;
use App\Repositories\ImageRepository;
use App\Repositories\MessageRepository;
use Exception;
use Illuminate\Http\Response;

class MessageService extends _Service
{
    protected $model = Message::class;

    public function __construct(
        protected MessageRepository $messageRepository,
    ) {
    }

    protected function getUsersWithPastConversations($user_id)
    {
        try {
            $conversationUsers = $this->messageRepository->findUsersWithConversations($user_id);
            return $conversationUsers;
        } catch (\Exception $e) {
            logger(
                [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );
        }
    }
    protected function getMessagesBetweenUsers($auth_user,$user_id)
    {
        try {
            $messages = $this->messageRepository->findMessagesBetweenUsers($auth_user,$user_id);
            return $messages;
        } catch (\Exception $e) {
            logger(
                [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );
        }
    }

    protected function store($data)
    {
        try {
            $data['from_id']= auth()->user()->id;
            $message = $this->messageRepository->create($data->toArray());
            return returnData(
                [],
                Response::HTTP_OK,
                $message,
                ['Created sucessfully']
            );
        } catch (\Exception $e) {
            logger(
                [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );
        }
    }

}
