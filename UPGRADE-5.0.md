# Upgrade from 4.X to 5.X

## PHP Version Requirement Bumped to ~7.1

Stick with version 4.X should PHP 7.0 support be required.

## No More `Message` Names by Default

Previously the `PMG\Queue\Message` interface required a `getName` method. It
no longer does. Instead the names default to the fully qualified class name
(FQCN) of the message.

Should the old behavior still be desired, implement the `PMG\Queue\NamedMessage`
interface which still includes the `getName` method.

The `PMG\Queue\MessageTrait` which provided the FQCN as a name behavior was also
removed.

#### Version 4.X

```php

namespace Acme\QueueExample;

use PMG\Queue\Message;

final class SomeMessage implements Message
{
    public function getName()
    {
        return 'SomeMessage';
    }

    // ...
}
```

#### Version 5.X

```php

namespace Acme\QueueExample;

use PMG\Queue\Message;

final class SomeMessage implements Message
{
    // message name is now `Acme\QueueExample\SomeMessage`
    // ...
}
```

### Router Updates for Message Names

Additionally, the producers routing configuration may need to be updated.

#### Version 4.X

```php
use PMG\Queue\Router\MappingRouter;

$router = new MappingRouter([
  'SomeMessage' => 'queueName',
]);
```

#### Version 5.x

```php
use Acme\QueueExample\SomeMessage;
use PMG\Queue\Router\MappingRouter;

$router = new MappingRouter([
   SomeMessage::class => 'queueName',
]);
```

## `MessageLifecycle` Has a New `retrying` Method

Rather than have an `$isRetrying` flag in the `failed` method. If the message is
being retried the `retrying` method will be invoked, otherwise `failed` will.

This should be a pretty easy upgrade:

#### Version 4.X

```php
use PMG\Queue\Message;
use PMG\Queue\Consumer;
use PMG\Queue\Lifecycle\NullLifecycle;

class CustomLifecycle extends NullLifecycle
{
    public function failed(Message $message, Consumer $consumer, bool $isRetrying)
    {
        if ($isRetrying) {
            // do retrying thing
        } else {
            // do failed thing
        }
    }

    // ...
}
```

#### Version 5.X

```php
use PMG\Queue\Message;
use PMG\Queue\Consumer;
use PMG\Queue\Lifecycle\NullLifecycle;

class CustomLifecycle extends NullLifecycle
{
    public function retrying(Message $message, Consumer $consumer)
    {
        // do retrying thing
    }

    public function failed(Message $message, Consumer $consumer, bool $isRetrying)
    {
        // do failed thing
    }

    // ...
}
```