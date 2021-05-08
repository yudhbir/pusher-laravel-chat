@extends("layouts.master")
@section('content')
<div class="projecttop_col">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="projectername">Hi {{Auth::user()->name}}!</div>
				<a href="javascript:void(0);" onclick="$('#Query_modal').modal('show');"class="btn_design">Help/Support</a>
			</div>
		</div>
	</div>
</div>
<section class="project_detail_sec" id="app">
	<div class="container">
			@include("layouts.flash")
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="project_detail_col">
					<h3>Project Details</h3>	
					<ul>
						<?php if(!empty($projects)){
						foreach($projects as $val){
						$user="";
						if(Auth::user()->role=='designer'){
							$user=$val['user_id'];
						}
						if(Auth::user()->role=='user'){
							$user=$val['designer'];
						}
						?>
						<?php if(!empty($val['id']) && !empty($user) && !empty($val['name'])){?>
							<li class="active_project1"><a v-on:click="load_project_chat({{$val['id']}},{{$user}},'{{$val['name']}}')" class="cursor" ref="project_{{$val['id']}}"><?php echo (!empty($val['project_name']))?$val['project_name']:"";?></a></li>						
						<?php }?>
						<?php }}?>
					</ul>
					<?php if(Auth::user()->role=='user'){?>
					<a href="{{route('feedback')}}" class="btn_design">Job Completed</a>
					<?php }	?>
					
				</div>
				
			</div>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<div class="msger" >
					<header class="msger-header">
						<div class="msger-header-title">
							<i class="fas fa-comment-alt"></i> 
							<span id="chat_user">Lorem ipsum</span>							
						</div>
						<div class="msger-header-options">
							<span><i class="fas fa-cog"></i></span>
						</div>
					</header>

					<main class="msger-chat">
						<div v-if="selectedAction == false">
							<example-component></example-component>
						</div>
						<div v-else>
				<chat-component v-bind:current_user="current_user" v-bind:active_user="{{Auth::user()->id}}"  v-bind:messages="messages"><chat-component>
						</div>
					</main>
					<div class="msger-inputarea" v-if="selectedAction != false">
						<input type="text" ref="messageField" class="msger-input" placeholder="Enter your message..."   v-model="newMessage">
						<div class="dropdown">
						<div class="dropdown-content">
							<a data-toggle="modal" data-target="#Attachment_modal" class="cursor"><i class="fa fa-paperclip" aria-hidden="true"style="padding: 12px 8px 0 15px;"></i>Upload a file</a>
							<a href="https://www.dropbox.com/" target="_blank"><i class="fab fa-dropbox" aria-hidden="true"style="padding: 12px 8px 0 15px;"></i>Share by Dropbox</a>
							
						</div>
						<a style="padding:0;" class="cursor dropbtn"><i class="fa fa-paperclip" aria-hidden="true"style="padding: 12px 8px 0 15px;"></i></a>
						</div>
						<button type="button" class="msger-send-btn"  v-on:click="sendMessage">Send</button>
					</div>
				</div>
			</div>

			
		</div>
	</div>
	<!-- The Attachment Modal -->
	<div class="modal" id="Attachment_modal">
	  <div class="modal-dialog">
		<div class="modal-content">

		  <!-- Modal Header -->
		  <div class="modal-header">
			<h4 class="modal-title">Add Attachment</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		  </div>
		  <form name="attachment_frm" id="attachment_frm" enctype="multipart/form-data" >
		  <div class="modal-body">
			<div class="text-center">Click the upload icon below to upload a file.</div>
			<div class="image-upload">
				<label for="file-input" class="text-center">
					<i class="fa fa-upload" aria-hidden="true"></i>
				</label>
				<input id="file-input" name="attachment_file" type="file" ref="file"  />
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-primary btn_design mod_fiy" v-on:click="upload_attachment();">Send</button>
			<button type="button" class="btn btn-danger btn_design mod_fiy" data-dismiss="modal">Close</button>
		  </div>
		</form>
		</div>
	  </div>
	</div>
		<!-- The Help/Support Modal -->
	<div class="modal" id="Query_modal">
	  <div class="modal-dialog">
		<div class="modal-content">
		
		  <div class="modal-header">
			<h4 class="modal-title">Post Your Query</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		  </div>
		  <form name="query_frm" id="query_frm" enctype="multipart/form-data" method="post" action="{{route('send_query')}}">
		  @csrf
		  <div class="modal-body">			
			<div class="form-group">
				<label for="exampleInputPassword1">Your query</label>
				<textarea class="form-control" name="post_query"></textarea>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary btn_design mod_fiy">Submit</button>
			<button type="button" class="btn btn-danger btn_design mod_fiy" data-dismiss="modal">Close</button>
		  </div>
		</form>
		</div>
	  </div>
	</div>
</section>
<script>
window.auth_user='<?php echo Auth::user()->id;?>';
window.current_user='<?php echo Auth::user()->name;?>';
<?php if(!empty($profile_img->photo) && file_exists(public_path().'/'.$profile_img->photo)){?>
window.current_user_pic='<?php echo url($profile_img->photo);?>';
<?php }else{?>
window.current_user_pic='<?php echo url("images/client2.jpg");?>';
<?php }?>
window.pid='<?php echo $project_id;?>'
$(document).ready(function(){
	//$("#project_"+pid).trigger('click');
});
</script>
<script src="{{ asset('js/jquery.loading.block.js') }}"></script>
<script src="{{ asset('js/jquery.toaster.js') }}"></script>
<script src="{{ asset('js/app.js') }}" defer></script>

<style>.cursor{cursor: pointer;}
.image-upload > input{display: none;}
.text-center{text-align: center;}
.image-upload .fa-upload{padding: 34px;width:460px;cursor: pointer;}
.active_project{background: #ccc; padding: 15px 35px;}
.mod_fiy{padding: 7px 52px 5px 27px !important;}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 240px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  top: 44px;
  left: -188px;
}
.dropdown-content a {
  color: black;
  padding: 12px 0px;
  text-decoration: none;
  display: block;
}
.dropdown-content a:hover {background-color: #ddd;}

.dropdown:hover .dropdown-content {display: block;}
</style>


@endsection		