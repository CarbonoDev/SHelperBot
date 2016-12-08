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
	$update = Telegram::commandsHandler(true);

	if($update->isType('inline_query')) {
		$inline_query = $update->getInlineQuery();
		$options = [];

		$query = $inline_query->getQuery();
		$query_type = substr($query, 0, 1);
		if(in_array($query_type, ['g', 'i', 'l', 'p', 'pi'])) {
			$query = substr($query, 1);
		} else {
			$query_type = 'g';
		}

		switch ($query_type) {
			case 'i':
				$options['searchType'] = 'image';
				$options['fileType'] = 'jpg';
				break;

			case 'l':
				$options['siteSearch'] = 'https://laravel.com/docs';
				break;

			case 'p':
				$options['siteSearch'] = 'http://php.net/';
				break;

			case 'pi':
				$options['searchType'] = 'image';
				$options['rights'] = 'cc_publicdomain';
				break;

			default:
				# code...
				break;
		}

		$search_response = GoogleCSE::search($query, 1, 10, $options);
		if($search_response->total_results <= 0) {
			return;
		}
		$results = collect($search_response->results);

		if($query_type == 'i' || $query_type == 'pi') {

			$telegram_results = $results->map(function($result) {
				$inline_query_result = new \Telegram\Bot\Objects\InlineQuery\InlineQueryResultPhoto([

					'id'        => gmp_strval(gmp_init(substr(md5($result->link), 0, 16), 16), 10),
					'title'     => $result->title,
					'photo_url' => $result->link,
					'thumb_url' => $result->link,
					'caption'   => $result->title,
					'description'   => $result->snippet,
				]);
				return $inline_query_result;
			});
		} else {

			$telegram_results = $results->map(function($result) {
				$inline_query_result = new \Telegram\Bot\Objects\InlineQuery\InlineQueryResultArticle([
					'id'                       => gmp_strval(gmp_init(substr(md5($result->link), 0, 16), 16), 10),
					'title'                    => $result->title,
					'url'                      => $result->link,
					'description'              => $result->snippet,
					'input_message_content'    => new \Telegram\Bot\Objects\InputContent\InputTextMessageContent([
						"parse_mode" => "html",
						"message_text" => \App\Telegram\Transformers\SearchResultTransformer::transform($result)
						])
				]);
				return $inline_query_result;
			});

		}


		return Telegram::answerInlineQuery([
			'inline_query_id' => $inline_query->getId(),
			'results'=> json_encode($telegram_results),
		]);

	}
	return 'ok';
});
