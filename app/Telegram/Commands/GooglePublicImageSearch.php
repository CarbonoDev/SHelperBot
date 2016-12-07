<?php

namespace App\Telegram\Commands;

use App\GoogleCustomSearch\Facades\GoogleCSE;
use App\Telegram\Transformers\SearchResultTransformer;
use Telegram\Bot\Commands\Command;

/**
 * Class GoogleSearch.
 */
class GooglePublicImageSearch extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'pimages';

    /**
     * @var string Command Description
     */
    protected $description = 'Search on images.google.com public domain images';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        if(empty($arguments)) {
            $text = 'You must enter at least one search term.';
            return $this->replyWithMessage(compact('text'));
        }

        $search_response = GoogleCSE::search($arguments, 1, 3, ['searchType' => 'image', 'rights' => 'cc_publicdomain']);

        if($search_response->total_results <= 0) {
            $text = 'Sorry no results.';
            return $this->replyWithMessage(compact('text'));
        }

        $html = [];
        foreach ($search_response->results as $result) {
            $text = SearchResultTransformer::transform($result);
            $parse_mode = 'HTML';

            $this->replyWithMessage(compact('text', 'parse_mode'));
        }
        $text = implode('', $html);
    }
}
