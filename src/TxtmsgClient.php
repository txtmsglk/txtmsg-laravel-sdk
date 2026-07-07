<?php

namespace Txtmsg\SmsClient;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class TxtmsgClient
{
    private string $apiKey;

    private string $baseUrl;

    public function __construct(string $apiKey, ?string $baseUrl = null)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl ?? 'https://sms.txtmsg.lk/api/v3';
    }

    private function request(string $method, string $endpoint, array $params = []): array
    {
        $baseUrl = $this->baseUrl;

        $urlParams = [];
        foreach ($params as $key => $value) {
            if (str_contains($endpoint, '{' . $key . '}')) {
                $endpoint = str_replace('{' . $key . '}', (string) $value, $endpoint);
                $urlParams[] = $key;
            }
        }

        foreach ($urlParams as $key) {
            unset($params[$key]);
        }

        $http = Http::withToken($this->apiKey)->withHeaders([
            'Accept' => 'application/json',
        ]);

        try {
            $response = match (strtoupper($method)) {
                'GET' => $http->get($baseUrl . $endpoint, $params),
                'POST' => $http->post($baseUrl . $endpoint, $params),
                'PATCH' => $http->patch($baseUrl . $endpoint, $params),
                'DELETE' => $http->delete($baseUrl . $endpoint, $params),
                default => throw new TxtmsgException("Unsupported HTTP method: {$method}"),
            };

            $response->throw();

            return $response->json();
        } catch (RequestException $e) {
            $body = $e->response->json();
            throw new TxtmsgException(
                $body['message'] ?? $e->getMessage(),
                $e->response->status()
            );
        }
    }

    public function viewAllContactGroups(array $params = []): array
    {
        return $this->request('GET', '/contacts', $params);
    }

    public function createContactGroup(array $params = []): array
    {
        return $this->request('POST', '/contacts', $params);
    }

    public function viewContactGroup(string $groupId, array $params = []): array
    {
        $params['group_id'] = $groupId;

        return $this->request('POST', '/contacts/{group_id}/show', $params);
    }

    public function updateContactGroup(string $groupId, array $params = []): array
    {
        $params['group_id'] = $groupId;

        return $this->request('PATCH', '/contacts/{group_id}', $params);
    }

    public function deleteContactGroup(string $groupId, array $params = []): array
    {
        $params['group_id'] = $groupId;

        return $this->request('DELETE', '/contacts/{group_id}', $params);
    }

    public function createContact(string $groupId, array $params = []): array
    {
        $params['group_id'] = $groupId;

        return $this->request('POST', '/contacts/{group_id}/store', $params);
    }

    public function viewContact(string $groupId, string $uid, array $params = []): array
    {
        $params['group_id'] = $groupId;
        $params['uid'] = $uid;

        return $this->request('POST', '/contacts/{group_id}/search/{uid}', $params);
    }

    public function updateContact(string $groupId, string $uid, array $params = []): array
    {
        $params['group_id'] = $groupId;
        $params['uid'] = $uid;

        return $this->request('PATCH', '/contacts/{group_id}/update/{uid}', $params);
    }

    public function deleteContact(string $groupId, string $uid, array $params = []): array
    {
        $params['group_id'] = $groupId;
        $params['uid'] = $uid;

        return $this->request('DELETE', '/contacts/{group_id}/delete/{uid}', $params);
    }

    public function viewAllContactsInGroup(string $groupId, array $params = []): array
    {
        $params['group_id'] = $groupId;

        return $this->request('POST', '/contacts/{group_id}/all', $params);
    }

    public function sendSMS(array $params = []): array
    {
        return $this->request('POST', '/sms/send', $params);
    }

    public function sendSMSViaGet(array $params = []): array
    {
        return $this->request('GET', '/http/sms/send', $params);
    }

    public function sendCampaign(array $params = []): array
    {
        return $this->request('POST', '/sms/campaign', $params);
    }

    public function viewSMS(string $uid, array $params = []): array
    {
        $params['uid'] = $uid;

        return $this->request('GET', '/sms/{uid}', $params);
    }

    public function viewAllMessages(array $params = []): array
    {
        return $this->request('GET', '/sms', $params);
    }

    public function viewCampaign(string $uid, array $params = []): array
    {
        $params['uid'] = $uid;

        return $this->request('GET', '/campaign/{uid}/view', $params);
    }

    public function viewBalance(array $params = []): array
    {
        return $this->request('GET', '/balance', $params);
    }

    public function viewProfile(array $params = []): array
    {
        return $this->request('GET', '/me', $params);
    }
}
