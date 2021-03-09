# SmsPilot notifications channel for Laravel 8.x

This package makes it easy to send notifications using [smspilot.ru](https://smspilot.ru) with Laravel 8.x.

## Contents

- [Installation](#installation)
    - [Setting up the SmsPilot service](#setting-up-the-SmsPilot-service)
- [Usage](#usage)
    - [Available Message methods](#available-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [License](#license)


## Installation

Install this package with Composer:

```bash
composer require chevgenio/smspilot-notification-channel
```

### Setting up the SmsPilot service

Add your SmsPilot api key, default sender name to your `config/services.php`:

```php
// config/services.php
...
'smspilot' => [
    'apikey' => env('SMSPILOT_APIKEY'),
    'sender' => env('SMSPILOT_SENDER', 'INFORM'),
    'callback' => env('SMSPILOT_CALLBACK_URL', ''),
    'callback_method' => env('SMSPILOT_CALLBACK_METHOD', 'get'),
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Chevgenio\SmsPilot\SmsPilotMessage;
use Chevgenio\SmsPilot\SmsPilotChannel;

class NewOrder extends Notification
{
    public function via($notifiable)
    {
        return [SmsPilotChannel::class];
    }

    public function toSmsPilot($notifiable)
    {
        return (new SmsPilotMessage)
            ->content("Order successfully completed.");
    }
}
```

In your notifiable model, make sure to include a `routeNotificationForSmspilot` method, which returns a phone number
or an array of phone numbers.

```php
public function routeNotificationForSmspilot()
{
    return $this->phone;
}
```

### Available methods

`from()`: Sets the sender's name. *Make sure to register the sender name at you SmsPilot dashboard.*

`content()`: Set a content of the notification message.

`sendAt()`: Set a time for scheduling the notification message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
