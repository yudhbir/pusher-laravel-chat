<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Chat;
use App\Models\Profile;


class ChatNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     /**
     * User that sent the message
     *
     * @var User
     */
    public $from_user;
    public $to_user;

    /**
     * Message details
     *
     * @var Message
     */
    public $message;
    public $project_id;
    public $attachment;
    public $notification_msg;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $from_user,$to_user, $message,$attachment,$project_id,$notification_msg)
    {
        $this->from_user = $from_user;
        $this->to_user = User::find($to_user);
        $this->message = $message;
        $this->project_id = $project_id;
        $this->attachment = $attachment;
        $this->notification_msg = $notification_msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('project_notification.'.$this->to_user->id);
    }
	public function broadcastAs()
	{
		return 'private_project_notification';
	}
	public function broadcastWith()
	{
		// $from_user=$this->from_user->only(['id', 'name', 'email']);
		$photos=Profile::where('user_id',$this->from_user->id)->select('photo')->first();
		if(!empty($photos->photo) && file_exists(public_path().'/'.$photos->photo)){
			$photo=url($photos->photo);
		}else{
			$photo=url("images/client2.jpg");
		}
		return ['from_user' => $this->from_user->id,'sender_name'=>$this->from_user->name,'to_user' => $this->to_user->id,'to_user_profile_pic' => $photo,'project_id'=>$this->project_id,'message' => $this->message,'attachment' => $this->attachment,'created_on'=>date('Y-m-d H:i:s'),'notification_msg'=>$this->notification_msg];
	}
}
