<?php
namespace App\Telegram\Transformers;

use App\Telegram\Transformers\SafeHTMLTransformer;

class SearchResultTransformer
{
	public static function transform($result)
	{
		$content = SafeHTMLTransformer::transform($result->htmlSnippet);
		$html = ['<b>',$result->title,'</b>', PHP_EOL ,'<a href="', $result->link, '">', $result->link, '</a>', PHP_EOL, $content];
	    return implode('', $html);
	}
}