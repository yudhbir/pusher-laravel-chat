import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

let token = document.head.querySelector('meta[name="csrf-token"]').content;
var base_url = document.head.querySelector('meta[name="base_url"]').content;
window.base_url=base_url;

Pusher.logToConsole = true;
Pusher.log = function (msg) {
 // console.log(msg);
};
window.Echo = new Echo({
	//authEndpoint: base_url+'/public/broadcasting/auth', //on live server uncomment this.
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

import Vue from 'vue';
import axios from 'axios'
 Vue.component('example-component', require('./components/ExampleComponent.vue').default);
 Vue.component('chat-component', require('./components/ChatComponent.vue').default);
 
 const app = new Vue({
	el: '#app',
    data: {
		current_user: window.current_user,		
		newMessage: '',
		file: '',
        messages: [],
		selectedAction:false,
    },

    created() {
		// console.log(base_url);
        // this.fetchMessages();		
        this.chatUsers="Lorem ipsum";		
    },
	mounted() {
	  this.$nextTick(function () {			
			const elem = this.$refs['project_'+window.pid];
			elem.click();
	  });
	},
    methods: {
		initialte_chat(project_id){
			 window.Echo.private('project_chat.'+project_id)
			  .listen('.private_project_chat', (e) => {
				console.log(JSON.stringify(e));				
				if(e.to_user==parseInt(window.auth_user)){
					var noti_counter=$("#notification_counter").text();
					noti_counter=parseInt(noti_counter)+1;
					$("#notification_counter").text(noti_counter);
					$("#notification_body").prepend("<li>"+e.notification_msg+"</li>");
				}
				this.messages.push(e);
			  });
		},
			
        fetchMessages(id) {
            axios.get(base_url+'/messages',{ params:{project_id:id}}).then(response => {
                this.messages = response.data;
            });
        },

        sendMessage() {
			if(this.newMessage.length == 0){
				alert('Please type some message.');
				return false;
			}
			var message={
				   'to_user' : window.to_user,
				   'project_id' : window.project_id,
				   'from_user' : window.auth_user,
				   'message' : this.newMessage
			   };            
			// this.initialte_chat(window.project_id);
            axios.post(base_url+'/messages', message).then(response => {
              console.log(response.data);
            });
			this.newMessage=""
			this.$refs.messageField.value="";
			
        },
		load_project_chat(project_id,to_user,chat_user){
			window.to_user=to_user;
			window.project_id=project_id;			
			this.initialte_chat(project_id);
			this.selectedAction=true;
			$("#chat_user").text(chat_user);
			this.fetchMessages(project_id);
			
		},		
		async  upload_attachment(){			
			this.file = this.$refs.file.files[0];
			var file_upload=$('#file-input')[0].files;
			if (file_upload.length === 0) {
				//alert('Please select a file.');
				$.toaster({ message : 'Please select a file', title : 'Error', priority : 'danger',timeout:10000 });
				return false;
			}
			//console.log(file_upload.length);return false;
			if (file_upload.length > 0 && file_upload[0].size >= 30000000) {				
				//alert("Please upload it to any third party platform and share the link");
				$.toaster({ message : 'Please upload it to any third party platform and share the link', title : 'Error', priority : 'danger',timeout:10000 });
				return false;
			}
			$.loadingBlockShow({
				imgPath: window.base_url+'/images/default.svg',
				text: 'Uploading Please wait ...',
				style: {
					position: 'fixed',
					width: '100%',
					height: '100%',
					background: 'rgba(0, 0, 0, .8)',
					left: 0,
					top: 0,
					zIndex: 10000
				}
			});
			let formData = new FormData();
			formData.append('file', this.file);
			formData.append('to_user', window.to_user);
			formData.append('project_id', window.project_id);
			formData.append('from_user', window.auth_user);
			const file_info =	await  axios.post(base_url+'/add_attachment', formData,{headers: {'Content-Type': 'multipart/form-data'}})
				.then(response => {
				  return response.data;
				});
			// console.log(file_info);
			var result=file_info
			if(result.success){
				$.loadingBlockHide();
				$.toaster({ message : 'Attachment has been sent successfully', title : 'Success', priority : 'success',timeout:3500 });
				// alert('Attachment has been sent successfully');
				console.log(result.file);
				$('#Attachment_modal').modal('hide');
			}else{
				$.loadingBlockHide();
				$.toaster({ message : result.error, title : 'Error', priority : 'danger',timeout:3500 });
				$('#Attachment_modal').modal('hide');
			}
			
		}
    }
 });
