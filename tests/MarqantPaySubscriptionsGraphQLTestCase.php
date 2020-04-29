<?php

namespace Marqant\MarqantPaySubscriptionsGraphQL\Tests;

use Marqant\MarqantPay\Tests\MarqantPayTestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

abstract class MarqantPaySubscriptionsGraphQLTestCase extends MarqantPayTestCase
{
    use MakesGraphQLRequests;
}
