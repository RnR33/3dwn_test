<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Notification;
use App\Services\NotificationService;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendEmailJob;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Session;
use Mockery;
use ReflectionProperty;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_render_notifications()
    {
        // Mock the NotificationService
        $notificationServiceMock = Mockery::mock(NotificationService::class);
        $notificationServiceMock->shouldReceive('getAllNotification')->andReturn([
            (object) [
                "id" => 1,
                "title" => "Test title",
                "description" => "Test description",
            ],
        ]);

        // Bind the mock instance to the container
        $this->app->instance(NotificationService::class, $notificationServiceMock);

        Livewire::test(Notification::class)
            ->call('render')
            ->assertSee('Test title')
            ->assertSee('Test description');
    }

    /** @test */
    public function it_can_create_notification()
    {
        Queue::fake();

        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Notification::class)
        ->set('title', 'Test Notification')
        ->set('description', 'This is a test notification.')
        ->call('storeNotification')
        ->assertHasNoErrors()
        ->assertSee('Notification Created Successfully!!');

    // Assert that the notification was created and persisted to the database
        $this->assertDatabaseHas('user_notifications', [
        'title' => 'Test Notification',
        'description' => 'This is a test notification.',
    ]);

        // Assert that the email job was dispatched with the correct arguments
        Queue::assertPushed(SendEmailJob::class, function ($job) use ($user) {
            $reflectionNotification = new ReflectionProperty($job, 'notification');
            $reflectionNotification->setAccessible(true);
            $notification = $reflectionNotification->getValue($job);

            $reflectionUserId = new ReflectionProperty($job, 'userId');
            $reflectionUserId->setAccessible(true);
            $userId = $reflectionUserId->getValue($job);

            // Perform your checks here using the $notification and $userId values
            return $notification->title === 'Test Notification'
                && $notification->description === 'This is a test notification.'
                && $userId === $user->id;
        });

    }

    /** @test */
    public function it_can_update_a_notification()
    {
        Queue::fake(); // Fake the queue to prevent job dispatch

        $user = User::factory()->create();

        // Create a notification using the factory
        $notification = UserNotification::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user); // Authenticate the user

        // Manually set the session flash message
        Session::flash('success', 'Notification Updated Successfully!!');

        $component = Livewire::test(Notification::class)
            ->set('notificationId', $notification->id)
            ->set('title', 'New Title')
            ->set('description', 'New Description');

        $component->call('updateNotification'); // Call the method

        $component->assertSet('updateNotification', false);

        Queue::assertPushed(SendEmailJob::class, function ($job) use ($notification, $user) {
            $reflectionNotification = new ReflectionProperty($job, 'notification');
            $reflectionNotification->setAccessible(true);
            $jobNotification = $reflectionNotification->getValue($job);

            $reflectionUserId = new ReflectionProperty($job, 'userId');
            $reflectionUserId->setAccessible(true);
            $jobUserId = $reflectionUserId->getValue($job);

            return $jobNotification->id === $notification->id && $jobUserId === $user->id;
        });
    }
    

    /** @test */
    public function it_can_delete_a_notification()
    {
        $user = User::factory()->create();

        // Create a notification using the factory
        $notification = UserNotification::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user); // Authenticate the user

        $component = Livewire::test(Notification::class);

        $component->call('deleteNotification', $notification->id); // Call the method

        $component->assertSee('Notification Deleted Successfully!!');
    }
}
