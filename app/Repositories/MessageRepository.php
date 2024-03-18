<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\Message;
use Illuminate\Support\Collection;

class MessageRepository extends Repository
{
    public function model(): string
    {
        return Message::class;
    }
    public function findUsersWithConversations($user_id)
    {
        return $this->model->where('from_id', $user_id)
            ->orWhere('to_id', $user_id)
            ->select('from_id', 'to_id')
            ->get()
            ->flatMap(function ($message) use ($user_id) {
                return [$message->from_id, $message->to_id];
            })
            ->unique()
            ->reject(function ($userId) use ($user_id) {
                return $userId == $user_id;
            });
    }
    public function findMessagesBetweenUsers($auth_user, $user_id)
    {
        return $this->model->where(function ($query) use ($auth_user, $user_id) {
            $query->where('from_id', $auth_user)
                ->where('to_id', $user_id);
        })
            ->orWhere(function ($query) use ($auth_user, $user_id) {
                $query->where('from_id', $user_id)
                    ->where('to_id', $auth_user);
            })
            ->get();
    }
}
