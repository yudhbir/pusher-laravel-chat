<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use App\Models\User;

class DesignerNotify extends Mailable
{
    // use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	protected $project;
	protected $designer;
	protected $client_name;
	
    public function __construct(User $designer,Project $projects,$client_name="")
    {
         $this->project = $projects;
         $this->designer = $designer;
         $this->client_name = $client_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.designer.selction_notify',['designer'=>$this->designer,'project'=>$this->project,'client_name'=>$this->client_name]);
    }
}
