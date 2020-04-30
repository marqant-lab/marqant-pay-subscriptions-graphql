<?php

namespace Marqant\MarqantPaySubscriptionsGraphQL\Tests\GraphQL\Mutations;

use Stripe\Subscription as StripeSubscription;
use Marqant\MarqantPaySubscriptionsGraphQL\Tests\MarqantPaySubscriptionsGraphQLTestCase;

class SubscribeUserTest extends MarqantPaySubscriptionsGraphQLTestCase
{
    /**
     * Test if we can subscribe a user to a plan via graphql.
     *
     * @test
     *
     * @return void
     */
    public function test_subscribe_user_via_graphql(): void
    {
        /**
         * @var \Marqant\MarqantPaySubscriptions\Models\Plan $Plan
         * @var \App\User                                    $Billable
         */

        $provider = 'stripe';

        $Plan = $this->createPlanModel();

        $Plan->createPlan($provider);

        // assert that provider and plan are connected through a many to many relationship
        $this->assertInstanceOf(config('marqant-pay.provider_model'), $Plan->providers->first());

        // assert that the field on the plan are filled with valid data
        $this->assertNotEmpty($Plan->stripe_id);
        $this->assertNotEmpty($Plan->stripe_product);

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

        // assert that billable is subscribed via stripe
        $this->assertCount(1, StripeSubscription::all([
            'customer' => $Billable->stripe_id,
            'plan'     => $Plan->stripe_id,
        ]));

        // assert that billable is subscribed in our database
        $this->assertCount(1, $Billable->subscriptions);

        // assert that all values needed are stored in the database and valid
        $Subscription = $Billable->subscriptions->first();
        $this->assertNotEmpty($Subscription->stripe_id);
        $this->assertEquals($Billable->id, $Subscription->billable_id);
        $this->assertEquals($Plan->id, $Subscription->plan_id);
    }
}