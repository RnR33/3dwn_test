<?php

namespace App\Repositories\Eloquent;

use App\Models\UserNotification;
use App\Repositories\Contracts\NotificationRepositoryInterface;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(UserNotification $model)
    {
        $this->model = $model;
    }
}