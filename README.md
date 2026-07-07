# TXTMSG.lk SMS Client for Laravel — Laravel SMS Gateway Integration for Sri Lanka

Laravel SDK for [TXTMSG.lk](https://txtmsg.lk) SMS Gateway API v3. Send bulk SMS, transactional SMS, and scheduled messages from your Laravel 13 application using the TXTMSG.lk REST API. The official Laravel SMS package for Sri Lanka's reliable SMS gateway.

## Features

- **Send SMS** — Deliver single or bulk messages to any country with a REST API
- **Campaign Management** — Send SMS campaigns using contact lists
- **Contact Groups** — Create, update, and manage contact groups
- **Contacts** — Import and manage individual contacts within groups
- **Schedule Messages** — Set future delivery times for your SMS
- **Balance & Profile** — Check remaining SMS units and account details
- **DLT Support** — Send DLT-compliant messages with template IDs
- **Laravel Native** — Facade, ServiceProvider, and dependency injection support

## Requirements

- PHP 8.1+
- Laravel 13.x

## Installation

Install the TXTMSG.lk Laravel SMS SDK via Composer:

```bash
composer require txtmsg/sms-client
```

## Configuration

### Publish Config

Publish the configuration file to your Laravel project:

```bash
php artisan vendor:publish --provider="Txtmsg\SmsClient\SmsClientServiceProvider" --tag="txtmsg-config"
```

### Environment Variables

Add your API key to the `.env` file in your Laravel project root:

```env
TXTMSG_API_KEY=your-api-key-here
```

Optionally override the API base URL:

```env
TXTMSG_BASE_URL=https://sms.txtmsg.lk/api/v3
```

You can obtain your API key from your [TXTMSG.lk dashboard](https://txtmsg.lk).

## Quick Start

### Send SMS Using Facade

```php
use Txtmsg;

Txtmsg::sendSMS([
    'recipient' => '947XXXXXXXXX',
    'sender_id' => 'YourName',
    'type' => 'plain',
    'message' => 'Hello from TXTMSG.lk!',
]);
```

### Send SMS Using Dependency Injection

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
            'message' => 'Hello from TXTMSG.lk!',
        ]);
    }
}
```

### Check SMS Balance

```php
$balance = Txtmsg::viewBalance();
```

### Send a Campaign to a Contact List

```php
Txtmsg::sendCampaign([
    'contact_list_id' => '6415907d0d37a',
    'sender_id' => 'YourName',
    'type' => 'plain',
    'message' => 'Campaign message',
    'schedule_time' => '2025-12-20 07:00',
]);
```

## API Reference

All methods return an array with the API response.

### Contact Groups API

| Method | Description |
|--------|-------------|
| `viewAllContactGroups(array $params = [])` | Retrieve all contact groups with pagination |
| `createContactGroup(array $params = [])` | Create a new contact group |
| `viewContactGroup(string $groupId)` | Get details of a specific contact group |
| `updateContactGroup(string $groupId, array $params = [])` | Update an existing contact group |
| `deleteContactGroup(string $groupId)` | Delete a contact group |

### Contacts API

| Method | Description |
|--------|-------------|
| `createContact(string $groupId, array $params = [])` | Add a contact to a group |
| `viewContact(string $groupId, string $uid)` | View a specific contact |
| `updateContact(string $groupId, string $uid, array $params = [])` | Update a contact's details |
| `deleteContact(string $groupId, string $uid)` | Delete a contact |
| `viewAllContactsInGroup(string $groupId)` | List all contacts in a group |

### SMS API

| Method | Description |
|--------|-------------|
| `sendSMS(array $params = [])` | Send SMS via POST (supports single & bulk) |
| `sendSMSViaGet(array $params = [])` | Send SMS via GET (simpler alternative) |
| `sendCampaign(array $params = [])` | Send campaign to contact list(s) |
| `viewSMS(string $uid)` | View details of a sent SMS |
| `viewAllMessages(array $params = [])` | List all sent messages with pagination |
| `viewCampaign(string $uid)` | View campaign details and reports |

### Profile API

| Method | Description |
|--------|-------------|
| `viewBalance(array $params = [])` | Check remaining SMS unit balance |
| `viewProfile(array $params = [])` | View account profile and usage |

## SMS Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `recipient` | string | Yes | Phone number(s). Use comma for multiple |
| `sender_id` | string | Yes | Sender ID (max 11 characters) |
| `type` | string | Yes | Message type (`plain`) |
| `message` | string | Yes | SMS message body |
| `schedule_time` | string | No | Schedule delivery (Y-m-d H:i format) |
| `dlt_template_id` | string | No | DLT template ID for regulatory compliance |

## About TXTMSG.lk

[TXTMSG.lk](https://txtmsg.lk) is a Sri Lankan SMS gateway provider offering reliable bulk SMS services, transactional SMS APIs, and messaging solutions for businesses of all sizes. The TXTMSG.lk API v3 provides RESTful endpoints for SMS delivery, contact management, and campaign automation with worldwide coverage.

## Changelog

### v2.0.0
- Dropped PHP < 8.1 and Laravel < 13 support
- Replaced cURL with Laravel HTTP client
- Added typed properties and return types
- Added `TxtmsgException` for typed error handling
- Configurable base URL via `TXTMSG_BASE_URL` env
