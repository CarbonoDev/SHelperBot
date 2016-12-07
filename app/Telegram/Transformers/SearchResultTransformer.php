<?php
namespace App\Telegram\Transformers;

class SearchResultTransformer
{
	public static function transform($result)
	{
		$html = ['<b>',$result->title,'</b>', PHP_EOL ,'<a href="', $result->link, '">', $result->link, '</a>', PHP_EOL, $result->snippet];
	    return implode('', $html);
	}
}