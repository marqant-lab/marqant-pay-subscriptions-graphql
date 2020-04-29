<?php

namespace Marqant\MarqantPaySubscriptionsGraphQL;

use Illuminate\Support\ServiceProvider;

class MarqantPaySubscriptionsGraphQLServiceProvider extends ServiceProvider
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
        $this->setupGraphQLSchemaFiles();
    }

    /**
     * Setup publishable graphql files of this package in boot method.
     *
     * @return void
     */
    private function setupGraphQLSchemaFiles()
    {
        // graphql
        $this->publishes([
            __DIR__ . '/../graphql/' => base_path('graphql'),
        ], 'marqant_pay_graphql');
    }
}