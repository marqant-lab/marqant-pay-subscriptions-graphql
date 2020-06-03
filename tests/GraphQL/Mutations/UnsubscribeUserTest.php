<?php

namespace Marqant\MarqantPaySubscriptionsGraphQL\Tests\GraphQL\Mutations;

use Marqant\MarqantPaySubscriptions\Services\SubscriptionsHandler;
use Marqant\MarqantPaySubscriptionsGraphQL\Tests\MarqantPaySubscriptionsGraphQLTestCase;

class UnsubscribeUserTest extends MarqantPaySubscriptionsGraphQLTestCase
{
    /**
     * Test if we can unsubscribe a user from a plan via graphql.
     *
     * @test
     *
     * @return void
     */
    public function test_unsubscribe_user_via_graphql_without_provider(): void
    {
        /**
         * @var \Marqant\MarqantPaySubscriptions\Models\Plan $Plan
         * @var \App\User                                    $Billable
         */

        config(['marqant-pay-subscriptions.subscription_handler' => SubscriptionsHandler::class]);

        $Plan = $this->createPlanModel();

        // assert that the field on the plan are filled with valid data
        $this->assertEmpty($Plan->stripe_id);
        $this->assertEmpty($Plan->stripe_product);

        // get billable
        $Billable = $this->createBillableUser();

        // subscribe billable to plan through graphql
        // $Billable->subscribe($Plan->slug);
        $response = $this->graphQL(/** @lang GraphQL */ '
mutation subscribeUser($email: String!, $plan: String!) {
  subscribeUser(email: $email, plan: $plan) {
    email
  }
}
        ', [
            'email' => $Billable->email,
            'plan'  => $Plan->slug,
        ])
            ->assertJson([
                'data' => [
                    "subscribeUser" => [
                        "email" => $Billable->email,
                    ],
                ],
            ]);

        // assert that billable is subscribed in our database
        $this->assertCount(1, $Billable->subscriptions);

        // assert that all values needed are stored in the database and valid
        $Subscription = $Billable->subscriptions->first();
        $this->assertEmpty($Subscription->stripe_id);
        $this->assertEquals($Billable->id, $Subscription->billable_id);
        $this->assertEquals($Plan->id, $Subscription->plan_id);

        /////////////////////////////////////////////////////////////
        // now that we have a subscribed billable, we can unsubscribe
        // him again.
        $response = $this->graphQL(/** @lang GraphQL */ '
mutation unsubscribeUser($email: String!, $plan: String!) {
  unsubscribeUser(email: $email, plan: $plan) {
    email
  }
}
        ', [
            'email' => $Billable->email,
            'plan'  => $Plan->slug,
        ])
            ->assertJson([
                'data' => [
                    "unsubscribeUser" => [
                        "email" => $Billable->email,
                    ],
                ],
            ]);

        // assert that the user does no longer have a subscription
        $Billable->refresh();
        $this->assertCount(0, $Billable->subscriptions);
    }
}