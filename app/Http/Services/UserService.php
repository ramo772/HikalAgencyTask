<?php

namespace App\Http\Services;

use App\Models\Building;
use App\Models\City;
use App\Models\Message;
use App\Repositories\CityRepository;
use App\Repositories\ImageRepository;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Response;

class UserService extends _Service
{

    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    protected function findUser($id)
    {
        try {
            $user = $this->userRepository->find($id);
            return $user;
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
    protected function findUsersArrayOfIds($ids)
    {
        try {
            $user = $this->userRepository->whereIn('id',$ids->toArray());
            return $user;
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
