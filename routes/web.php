<?php

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
Route::get('s', function() {
	$messages = GoogleCSE::search('test');
    return view('welcome')->with('messages',$messages);
});
Route::get('/setWebhook', function () {
	$response = Telegram::setWebhook(['url' => url('P31ZDkeJZU2UmsWoI7/webhook')]);
	return 'ok';
});
Route::get('/removeWebhook', function () {
	$response = Telegram::removeWebhook();
	return 'ok';
});
Route::get('/getMessages', function () {
	$updates = collect(Telegram::commandsHandler());

	// $updates = collect(Telegram::getUpdates(['offset' => Cache::get('lastUpdate') + 1]));
	// $updates->each(function($update) {
	// 	$message = $update->getMessage();
	// 	Cache::forever('lastUpdate', $update->getUpdateId());

	// 	Telegram::sendMessage([
	// 		"chat_id" => $message->getChat()->getId(),
	// 		"text" => "Hola amor",
	// 		"reply_to_message_id" => $message->getMessageId()
	// 	]);
	// });

	$messages = $updates->map(function ($update)
	{
		return $update->getMessage();
	});
	// Commands handler method returns an Update object.
	// So you can further process $update object
	// to however you want.


    return view('welcome')->with('messages',$messages);
});

// Example of POST Route:
Route::post('/P31ZDkeJZU2UmsWoI7/webhook', function () {
    $updates = Telegram::commandsHandler(true);
    Log::debug('Webhook update', [$updates]);
    return 'ok';
});
