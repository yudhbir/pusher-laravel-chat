# Laravel Pusher Private Chat Application
## _user to user communication setup like skype_


To setup the chat pusher funcationality please follow below steps.
Run the below commands:
```sh
- composer require pusher/pusher-php-server
- npm install
- npm install --save laravel-echo pusher-js
- npm install vue (if you are using the bootstrap ui in the laravel for auth)
```
## Broadcasting Message Sent Event
 You have to create a broadcast event that gnerate for during the comnmunication
```sh
- php artisan make:event MessageSent
```
```sh
// app/Events/MessageSent.php

use App\User;
use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message
     *
     * @var User
     */
    public $user;

    /**
     * Message details
     *
     * @var Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat');
    }
    public function broadcastAs()
    {
        return new PrivateChannel('user_chat');
    }
}
```
## Configuration setting 
```sh
// routes/channels.php
Broadcast::channel('chat', function ($user) {
  return Auth::check();
});
```
```sh
// Config/app.php
-uncomment the Below class
 App\Providers\BroadcastServiceProvider::class,
```
```sh
// Config/broadcasting.php
 'driver'  => 'pusher',
```
```sh
.env file

BROADCAST_DRIVER=pusher

PUSHER_APP_ID=app_id
PUSHER_APP_KEY=auth-key
PUSHER_APP_SECRET=secret
PUSHER_APP_CLUSTER=cluster
```
```sh
php artisan config:cache
```
### Last but not least run the below command to get the applicaion changes in frontend after setup
```sh
npm run watch
```
### Notes
- if you found the broadcast auth 404 error then check yours boradcast routes.
- For example please check the below setup for private chat.
```sh
public function broadcastOn()
{
    return new PrivateChannel('orders.'.$this->order->id);
}
```
- Then in your broadcasting routes it will as follow.
```sh
use App\Models\Order;

Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
    return $user->id === Order::findOrNew($orderId)->user_id;
});
- $user is mandatory here to authenticate the auth user
- $orderId here from from PrivateChannel() as above mentioned that (Keep it in mind during implementation)
```

