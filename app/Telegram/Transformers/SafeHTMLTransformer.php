<?php
namespace App\Telegram\Transformers;

use Illuminate\Support\Facades\Log;


class SafeHTMLTransformer
{

	public static function transform($unsafe_html)
	{
		$safe_html = $unsafe_html;
		// $safe_html = str_replace('&nbsp;', ' ', $unsafe_html);
		// $safe_html = static::entities_to_unicode($safe_html);
		$safe_html = html_entity_decode($safe_html, ENT_QUOTES, 'UTF-8');
		$safe_html = strip_tags($safe_html, '<a><b><i><code><pre>');
		$safe_html = htmlspecialchars($safe_html, ENT_QUOTES, 'UTF-8');
		$safe_html = str_replace([
			'&lt;b&gt;','&lt;/b&gt;',
			'&lt;i&gt;','&lt;/i&gt;',
			'&lt;code&gt;','&lt;/code&gt;',
			'&lt;pre&gt;','&lt;/pre&gt;',
			], [
			'<b>','</b>',
			'<i>','</i>',
			'<code>','</code>',
			'<pre>','</pre>',
			], $safe_html);
		return  $safe_html;
	}
}