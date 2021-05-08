<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profile;

class Chat extends Model
{
    protected $guarded = [];
	protected $table='tbl_chats';
	public $timestamps = false;

    public function user(){

        return $this->belongsTo(User::class,'from_user','id');
        
    }
	public function profile(){

        return $this->belongsTo(Profile::class,'from_user','user_id');
        
    }
}