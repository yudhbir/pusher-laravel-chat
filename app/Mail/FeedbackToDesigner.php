<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use App\Models\User;

class FeedbackToDesigner extends Mailable
{
    // use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	protected $project;	
	protected $feedback;	
	
    public function __construct($projects,$feedback="")
    {
         $this->project = $projects;         
         $this->feedback = $feedback;         
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$view=(!empty($this->feedback))?'emails.designer.feedback_admin':'emails.designer.feedback';
        return $this->view($view,['project'=>$this->project,'feedback'=>$this->feedback]);
    }
}
