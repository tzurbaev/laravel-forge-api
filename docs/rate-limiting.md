# Overview

At the time of writing, the Laravel Forge API imposes a request rate limit of 30 requests per minute. If you are only making a few, isolated requests then this won't present any issues. However, if you are running scripts to batch create servers or perhaps to grab details of all your sites at one time, you may need to limit your request rate.

## Usage

Add a rate limiting function of your choice by way of an optional closure, set on the main `$forge` instance.

```php
$forge->setRateLimiter(function() {
    rateLimitingFunction();
});
```

**Please note that the `rateLimitingFunction()` is not provided, and must be provided separately.**

Now each time a request is made, the `ApiProvider` calls the rate limiting closure first. This effectively blocks the request until the closure has completed its calculation.

## Examples

One way of implementing rate limiting is to use a [Token Bucket Algorithm][924f3b4d].

Start by including the package in your project:

```bash
composer require bandwidth-throttle/token-bucket
```

Next, we create an in memory token bucket which will last only as long as the request/script runs. This particular package does allow for a number of storage options which are well beyond the scope of this example.

```php
use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\BlockingConsumer;
use bandwidthThrottle\tokenBucket\storage\SingleProcessStorage;

// the Forge request rate limit
$requestsPerMinute = 30;

// create an in memory storage for the bucket
$storage = new SingleProcessStorage();

// set the request rate from above
$rate = new Rate($requestsPerMinute, Rate::MINUTE);

// create the bucket, limiting the bucket size
$bucket = new TokenBucket(1, $rate, $storage);

// set up a blocking consumer of the tokens
$blockingConsumer = new BlockingConsumer($bucket);

// create a closure which uses our new bucket
$limitingClosure = function () use ($clockingConsumer) {
    $blockingConsumer->consume(1);
};

// pass this closure to our $forge instance
$forge->setRateLimiter($limitingClosure);

// make automatically rate limited requests!
$forge->get(1234, true);
```

Of course, the rate limiting closure could be something as simple as:

```php
$limitingClosure = function () {
    sleep(2);
};
```

Alternatively you could write your own manager. For example, the [Laravel rate limiter](https://github.com/illuminate/cache/blob/master/RateLimiter.php) (which is no doubt implemented by the Forge API) uses a [Laravel Cache instance](https://laravel.com/docs/5.4/cache).

  [924f3b4d]: https://github.com/bandwidth-throttle/token-bucket "PHP Tocken Bucket implementation"
