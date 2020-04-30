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

        $this->registerQueries();

        $this->registerMutations();
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

    /**
     * Setup queries in the lighthouse configuration in boot method.
     *
     * @return void
     */
    public function registerQueries()
    {
        config([
            'lighthouse.namespaces.queries' => array_merge((array) config('lighthouse.namespaces.queries'),
                (array) 'Marqant\\MarqantPaySubscriptionsGraphQL\\GraphQL\\Queries'),
        ]);
    }

    /**
     * Setup mutations in the lighthouse configuration in boot method.
     *
     * @return void
     */
    public function registerMutations()
    {
        config([
            'lighthouse.namespaces.mutations' => array_merge((array) config('lighthouse.namespaces.mutations'),
                (array) 'Marqant\\MarqantPaySubscriptionsGraphQL\\GraphQL\\Mutations'),
        ]);
    }
}