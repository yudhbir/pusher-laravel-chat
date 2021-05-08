<template>
	<div >
	<div v-for="(message,index) in messages" v-bind:sendid="message.from_user" v-bind:name="message.from_user">
		<div class="msg left-msg" v-if="message.to_user==active_user">	
			<div class="msg-img" v-if="message.to_user_profile_pic!=undefined" :style="{backgroundImage: `url(${message.to_user_profile_pic})`}"></div>
			
			<div v-if="message.profile!= null || message.profile!= undefined">
				<div class="msg-img" v-if="message.profile.photo!=undefined || message.profile.photo != null" :style="getuser_image(message.profile.photo)"></div>
				<div class="msg-img" v-if="(message.profile.photo==undefined  || message.profile.photo == null) && message.to_user_profile_pic==undefined" style="background-image: url(../images/client1.jpg)"></div>
			</div>
			
			<div v-if="message.to_user_profile_pic==undefined && (message.profile== null || message.profile== undefined)">
			<div class="msg-img" style="background-image: url(../images/client2.jpg)"></div>
			</div>
			
			<div class="msg-bubble">
				<div class="msg-info">
					<div class="msg-info-name" v-if="message.sender_name!=undefined">{{message.sender_name}}</div>
					<div class="msg-info-name" v-if="message.user!=undefined">{{message.user.name}}</div>
					<div class="msg-info-time">{{message.created_on}}</div>
				</div>
				<div class="msg-text">
					<p  v-if="message.attachment==0">{{message.message}}</p>
					<p  v-if="message.attachment==1"><a :href="getimageurl(message.message)" target="_blank"><img :src="getimage()"></a></p>
				</div>
			</div>
		</div>
		<div class="msg right-msg" v-if="message.from_user==active_user">
			<div class="msg-img" :style="{backgroundImage: `url(${profile_pic})`}"></div>
			<div class="msg-bubble">
				<div class="msg-info">
					<div class="msg-info-name">{{current_user}}</div>
					<div class="msg-info-time">{{message.created_on}}</div>
				</div>
				<div class="msg-text">
					<p  v-if="message.attachment==0">{{message.message}}</p>
					<p  v-if="message.attachment==1"><a :href="getimageurl(message.message)" target="_blank"><img :src="getimage()"></a></p>
				</div>
			</div>
		</div>
		<br>
	</div>

	<!--div class="msg right-msg">
		<div class="msg-img" style="background-image: url('../images/client2.jpg')"></div>
		<div class="msg-bubble">
			<div class="msg-info">
				<div class="msg-info-name">Sajad</div>
				<div class="msg-info-time">12:46</div>
			</div>
			<div class="msg-text">
				You can change your name in JS section!
			</div>
		</div>
	</div>
	<div class="msg left-msg">
		<div class="msg-img" style="background-image: url('../images/client1.jpg')"></div>
		<div class="msg-bubble">
			<div class="msg-info">
				<div class="msg-info-name">BOT</div>
				<div class="msg-info-time">12:45</div>
			</div>
			<div class="msg-text">
				Hi, welcome to SimpleChat! Go ahead and send me a message. 😄
			</div>
		</div>
	</div>

	<div class="msg right-msg">
		<div class="msg-img" style="background-image: url('../images/client2.jpg')"></div>
		<div class="msg-bubble">
			<div class="msg-info">
				<div class="msg-info-name">Sajad</div>
				<div class="msg-info-time">12:46</div>
			</div>
			<div class="msg-text">
				You can change your name in JS section!
			</div>
		</div>
	</div-->
	</div>
</template>

<script>
  export default {
	props: ['messages','active_user','current_user'],
	data:function(){
		return {
			profile_pic:"",
		}
	},
    mounted() {
      //console.log("Chat component mounted");
	  console.log(this.current_user);
	  this.profile_pic=window.current_user_pic;
    },
	methods:{	
		getimage(){
			return window.base_url+"/images/file.png";
		},
		getimageurl(url){
			return window.base_url+"/"+url;
		},
		getuser_image(url){
		//return window.base_url+"/"+url;
		return 'background-image: url("' + base_url+"/"+url + '")';
		}
	
	}
  };
</script>