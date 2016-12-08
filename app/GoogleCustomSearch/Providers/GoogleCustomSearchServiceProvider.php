<?php

namespace App\GoogleCustomSearch\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use iMarc\GoogleCustomSearch;

/**
 * Class GoogleCustomSearchServiceProvider.
 */
class GoogleCustomSearchServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Holds path to Config File.
     *
     * @var string
     */
    protected $config_filepath;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            $this->config_filepath => config_path('google-custom-search.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerGoogleCSE($this->app);

        // $this->config_filepath = __DIR__.'/config/google-custom-search.php';

        // $this->mergeConfigFrom($this->config_filepath, 'google-custom-search');
    }

    /**
     * Initialize GoogleCustomSearch with Default Config.
     *
     * @param Application $app
     */
    protected function registerGoogleCSE(Application $app)
    {
        $app->singleton(GoogleCustomSearch::class, function ($app) {
            $config = $app['config'];

            $custom_search = new GoogleCustomSearch(
                $config->get('google-custom-search.search_engine_id', ''),
                $config->get('google-custom-search.api_key', '')
            );

            return $custom_search;
        });

        $app->alias(GoogleCustomSearch::class, 'google_cse');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['google_cse', GoogleCustomSearch::class];
    }
}
