<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Project;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('project_chat.{project_id}', function ($user,$project_id) {
	$current_user=Auth::user()->id;
	if(Auth::user()->role=="designer"){
		$project_info=Project::find($project_id);
		return (int) $project_info->designer === (int) $current_user;
	}
	if(Auth::user()->role=="user"){
		$project_info=Project::find($project_id);
		return (int) $project_info->user_id === (int) $current_user;
	}
});
Broadcast::channel('project_notification.{user_id}', function ($user,$user_id) {	
	return (int) $user->id;
});
