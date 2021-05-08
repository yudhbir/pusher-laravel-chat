<?php
namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\ChatMessageSent;
use App\Events\ChatNotification;
use Auth;
use File;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	return view('chat.chat');
    }

    public function fetchAllMessages(Request $request)
    {
		// dd( $request->project_id);
		$project_id=$request->project_id;
    	return $messages= Chat::with(['user','profile'])->where('project_id',$project_id)->get()->toJson(JSON_PRETTY_PRINT);
		//dd($messages);
    }

    public function sendMessage(Request $request)
    {
		$project_id=$request->project_id;
		$from_user = Auth::user();		
    	$chat = Chat::create([
            'project_id' => $project_id,
            'from_user' => $from_user->id,
            'to_user' => $request->to_user,
            'message' => $request->message
        ]);
		$project_info=Project::where('id',$project_id)->select('project_name')->first();
		$notification_msg=(!empty($project_info->project_name))?"You have a new message related to ".$project_info->project_name." Project":"You have a new project message";
		$notification = Notification::create([
            'project_id' => $project_id,
            'from_user' => $from_user->id,
            'to_user' => $request->to_user,
            'message' => $request->message,
            'notification_msg' => $notification_msg,
        ]);

    	broadcast(new ChatMessageSent($from_user,$request->to_user, $request->message,$attachment=0,$project_id,$notification_msg))->toOthers();
		// broadcast(new ChatNotification($from_user,$request->to_user, $request->message,$attachment=0,$project_id,$notification_msg))->toOthers();

    	return ['status' => 'Message Sent!'];
    }
	public function upload_attachment(Request $request){
		$file_url="";
		$project_id=$request->project_id;
		$from_user = Auth::user();
		$input = $request->all();
		if ($request->hasFile('file')) {
			// $validator=$this->validate($request, [
				// 'file' => 'required|max:30048',
			// ]);
			$validator = \Validator::make($input, [
				'file' => 'required|max:30048',
			]);
			if ($validator->fails()) {
				$error=$validator->errors()->first('file');
				return response()->json(['error'=>$error]);
			}
			$store_path="chat/project/".$project_id;
			$path=public_path()."/chat/project/".$project_id;
			if (! File::exists($path)) {
				File::makeDirectory($path,0777,true);
			}
			$imageName = time().'.'.$request->file->extension();    
			$request->file->move($path, $imageName);
			$file_url=$store_path.'/'.$imageName;
		}		
		$chat = Chat::create([
            'project_id' => $project_id,
            'from_user' => $from_user->id,
            'to_user' => $request->to_user,
            'message' => $file_url,
            'attachment' => 1,
        ]);
		$project_info=Project::where('id',$project_id)->select('project_name')->first();
		$notification_msg=(!empty($project_info->project_name))?"You have a new attachment related to ".$project_info->project_name." Project":"You have a new project attachment";
		$notification = Notification::create([
            'project_id' => $project_id,
            'from_user' => $from_user->id,
            'to_user' => $request->to_user,
            'message' => $file_url,
            'notification_msg' => $notification_msg,
        ]);
		if(!empty($file_url)){
			broadcast(new ChatMessageSent($from_user,$request->to_user, $file_url,$attachment=1,$project_id,$notification_msg))->toOthers();
			// broadcast(new ChatNotification($from_user,$request->to_user, $request->message,$attachment=0,$project_id,$notification_msg))->toOthers();
			return response()->json(['success'=>true,'file'=>$file_url]);
		}else{
			return response()->json(['error'=>"some thing went wrong please check your file size"]);
		}
	}
}

