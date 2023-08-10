<?php

namespace App\Http\Livewire;

use App\Jobs\SendEmailJob;
use Livewire\Component;
use App\Models\UserNotification as Notifications;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Notification extends Component
{
 
    public $notifications;
    public $title;
    public $description;
    public $notificationId;
    public $updateNotification = false;
    public $addNotification = false;
    public $errorMessage = 'Something goes wrong!!';
 
    /**
     * delete action listener
     */
    protected $listeners = [
        'deleteNotificationListner'=>'deleteNotification'
    ];
 
    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'title' => 'required',
        'description' => 'required'
    ];

    private  $notificationService;

    /**
     * Boot the application's services.
     *
     * @param NotificationService $notificationService The service responsible for handling notifications.
     * @return void
     */

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
 
    /**
     * Reseting all inputted fields
     * @return void
     */
    public function resetFields(){
        $this->title = '';
        $this->description = '';
    }
 
    /**
     * render the notification data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->notifications = $this->notificationService->getAllNotification();
        return view('livewire.notification');
    }
 
    /**
     * Open Add notification form
     * @return void
     */
    public function addNotification()
    {
        $this->resetFields();
        $this->addNotification = true;
        $this->updateNotification = false;
    }
     /**
      * store the user inputted post data in the notifications table
      * @return void
      */
    public function storeNotification()
    {
        $this->validate();

        try {
            $notification = $this->notificationService->createNotification(
                [
                    'title' => $this->title,
                    'description' => $this->description,
                    'user_id' => auth()->user()->id,
                ]
            );

            session()->flash('success','Notification Created Successfully!!');

            SendEmailJob::dispatch($notification, auth()->user()->id);

            $this->resetFields();

            $this->addNotification = false;

        } catch (\Exception $ex) {

            Log::error(json_decode($ex->getMessage()));

            session()->flash('error', $this->errorMessage);
        }
    }
 
    /**
     * show existing notification data in edit notification form
     * @param mixed $id
     * @return void
     */
    public function editNotification($id){

        try {

            $notification = $this->notificationService->findNotification($id);

            if( !$notification) {
                session()->flash('error','Notification not found');
            } else {
                $this->title = $notification->title;
                $this->description = $notification->description;
                $this->notificationId = $notification->id;
                $this->updateNotification = true;
                $this->addNotification = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error', $this->errorMessage);
        }
 
    }
 
    /**
     * update the notification data
     * @return void
     */
    public function updateNotification()
    {
        $this->validate();
        
        try {
            $notification = $this->notificationService->updateNotification(
                $this->notificationId,
                [
                    'title' => $this->title,
                    'description' => $this->description
                ]
            );
            
            SendEmailJob::dispatch($notification, auth()->user()->id);

            session()->flash('success','Notification Updated Successfully!!');

            $this->resetFields();

            $this->updateNotification = false;

        } catch (\Exception $ex) {
            session()->flash('error', $this->errorMessage);
        }
    }
 
    /**
     * Cancel Add/Edit form and redirect to notification listing page
     * @return void
     */
    public function cancelNotification()
    {
        $this->addNotification = false;
        $this->updateNotification = false;
        $this->resetFields();
    }
 
    /**
     * delete specific notification data from the notifications table
     * @param mixed $id
     * @return void
     */
    public function deleteNotification($id)
    {
        try{

            $this->notificationService->deleteNotification($id);
            session()->flash('success',"Notification Deleted Successfully!!");

        }catch(\Exception $e){
            session()->flash('error', $this->errorMessage);
        }
    }
}
