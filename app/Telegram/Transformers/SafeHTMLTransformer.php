<?php
namespace App\Telegram\Transformers;

use Illuminate\Support\Facades\Log;


class SafeHTMLTransformer
{

	public static function transform($unsafe_html)
	{
		$with_spaces = str_replace('&nbsp;', ' ', $unsafe_html);
		$decoded_html = html_entity_decode($with_spaces);
		$clean_html = strip_tags($decoded_html, '<a><b><i><code><pre>');
		$encoded_html = htmlentities($clean_html);
		$safe_html = str_replace(['&lt;b&gt;','&lt;/b&gt;'], ['<b>','</b>'], $encoded_html);
		return  $safe_html;
	}
}