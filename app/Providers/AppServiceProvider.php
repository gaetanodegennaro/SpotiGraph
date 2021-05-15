<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $client = \Laudis\Neo4j\ClientBuilder::create()
            ->addHttpConnection('backup', 'http://neo4j:test1234@localhost')
            ->addBoltConnection('default', 'bolt://neo4j:test1234@localhost')
            ->setDefaultConnection('default')
            ->build();

        Config::set(['client' => $client]);
    }
}
