# Marqant Pay Subscriptions GraphQL

GraphQL implementation for the marqant-lab/marqant-pay-subscriptions package.

## Installation

You can require this package through composer like so:

```shell script
composer require marqant-lab/marqant-pay-subscriptions-graphql
```

Next you can decide if you want to publish the default grpahql schema. If you are happy with the default schema, you
 can also choose to just import the schema from the `vendor/marqant-lab/marqant-pay-subscriptions-graphql/graphql`
 folder.

```shell script
php artisan vendor:publish --tag=marqant_pay_graphql
```

This should provide all graphql queries needed to get the default subscriptions implementation running. The only
 thing left to do is to include the `subscriptions.graphql` file into your schema.

Open the schema in an editor and add the following line to the bottom.

```graphql
#import marqant-pay-subscriptions.graphql
```

Or if you want to use the default schema of the vendor folder, then use the following line.

```graphql
#import ../vendor/marqant-lab/marqant-pay-subscriptions-graphql/graphql/marqant-pay-subscriptions.graphql
```

And that's it! You should be able to use marqant-pay and marqant-pay-subscriptions through graphql. Try it out using
 a client like [Insomnia](https://insomnia.rest) or [Altair](https://altair.sirmuel.design/).
 
## Development

Fot development, we suggest that you include the graphql files from the vendor folder, because they will be symlinked
 into it when you are developing locally.