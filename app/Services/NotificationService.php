<?php

namespace App\Services;

use App\Repositories\Contracts\NotificationRepositoryInterface;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }
    
    /**
     * Get all notifications.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllNotification()
    {
        return $this->notificationRepository->get();
    }

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return \App\Models\Notification
     */
    public function createNotification(array $data)
    {
        return $this->notificationRepository->create($data);
    }

    /**
     * Find a notification by ID.
     *
     * @param int $id
     * @return \App\Models\Notification|null
     */
    public function findNotification($id)
    {
        return $this->notificationRepository->find($id);
    }

    /**
     * Update a notification by ID.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateNotification($id, array $data)
    {
        return $this->notificationRepository->updateById($id, $data);
    }

    /**
     * Delete a notification by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteNotification($id)
    {
        return $this->notificationRepository->deleteById($id);
    }

    /**
     * Count the number of notifications.
     *
     * @return int
     */
    public function countNotification()
    {
        return $this->notificationRepository->count();
    }
}
