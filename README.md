# TXTMSG.lk SMS Client for Laravel

Laravel SDK for [TXTMSG.lk](https://txtmsg.lk) SMS Gateway API v3.

## Requirements

- PHP 8.3+
- Laravel 13.x

## Installation

```bash
composer require txtmsg/sms-client
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Txtmsg\SmsClient\SmsClientServiceProvider" --tag="txtmsg-config"
```

Set your API key in `.env`:

```env
TXTMSG_API_KEY=your-api-key-here
```

Optionally set a custom base URL:

```env
TXTMSG_BASE_URL=https://sms.txtmsg.lk/api/v3
```

## Usage

### Facade

```php
use Txtmsg;

// Send SMS
Txtmsg::sendSMS([
    'recipient' => '947XXXXXXXXX',
    'sender_id' => 'YourName',
    'type' => 'plain',
    'message' => 'Hello from TXTMSG.lk!',
]);

// Check balance
$balance = Txtmsg::viewBalance();
```

### Dependency Injection

```php
use Txtmsg\SmsClient\TxtmsgClient;

class SmsController
{
    public function __construct(
        private TxtmsgClient $txtmsg,
    ) {}

    public function send()
    {
        return $this->txtmsg->sendSMS([
            'recipient' => '947XXXXXXXXX',
            'sender_id' => 'YourName',
            'type' => 'plain',
            'message' => 'Hello World!',
        ]);
    }
}
```

## Available Methods

### Contact Groups
- `viewAllContactGroups(array $params = [])`
- `createContactGroup(array $params = [])`
- `viewContactGroup(string $groupId, array $params = [])`
- `updateContactGroup(string $groupId, array $params = [])`
- `deleteContactGroup(string $groupId, array $params = [])`

### Contacts
- `createContact(string $groupId, array $params = [])`
- `viewContact(string $groupId, string $uid, array $params = [])`
- `updateContact(string $groupId, string $uid, array $params = [])`
- `deleteContact(string $groupId, string $uid, array $params = [])`
- `viewAllContactsInGroup(string $groupId, array $params = [])`

### SMS
- `sendSMS(array $params = [])`
- `sendSMSViaGet(array $params = [])`
- `sendCampaign(array $params = [])`
- `viewSMS(string $uid, array $params = [])`
- `viewAllMessages(array $params = [])`
- `viewCampaign(string $uid, array $params = [])`

### Profile
- `viewBalance(array $params = [])`
- `viewProfile(array $params = [])`

## Changelog

### v2.0.0
- Dropped PHP < 8.3 and Laravel < 13 support
- Replaced cURL with Laravel HTTP client
- Added typed properties and return types
- Added `TxtmsgException` for typed error handling
- Configurable base URL via `TXTMSG_BASE_URL` env
