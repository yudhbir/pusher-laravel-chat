<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/chat', [ChatController::class, 'index'])->middleware(['auth']);
Route::get('/messages', [ChatController::class, 'fetchAllMessages'])->middleware(['auth']);
Route::post('/messages', [ChatController::class, 'sendMessage'])->middleware(['auth']);
Route::post('/add_attachment', [ChatController::class, 'upload_attachment'])->middleware(['auth']);

Route::get('/mail',function(){
	 $data = array('name'=>"Virat dev");
	\Mail::send([], $data, function($message) {
         $message->to('test@test.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         
      });
echo "hello";exit;
});

