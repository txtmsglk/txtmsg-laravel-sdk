<?php

namespace Txtmsg\SmsClient;

use Exception;

class TxtmsgClient
{
    private $apiKey;
    private $baseUrl = 'https://sms.txtmsg.lk/api/v3';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    private function request($method, $endpoint, $params = [])
    {
        foreach ($params as $key => $value) {
            $endpoint = str_replace('{'.$key.'}', $value, $endpoint);
        }

        $url = $this->baseUrl . $endpoint;
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method !== 'GET' && !empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('Request Error: ' . curl_error($ch));
        }

        curl_close($ch);
        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            throw new Exception($decoded['message'] ?? 'API Error');
        }

        return $decoded;
    }

    // Contact Groups
    public function viewAllContactGroups($params = []) {
        return $this->request('GET', '/contacts', $params);
    }

    public function createContactGroup($params = []) {
        return $this->request('POST', '/contacts', $params);
    }

    public function viewContactGroup($groupId, $params = []) {
        $params['group_id'] = $groupId;
        return $this->request('POST', '/contacts/{group_id}/show', $params);
    }

    public function updateContactGroup($groupId, $params = []) {
        $params['group_id'] = $groupId;
        return $this->request('PATCH', '/contacts/{group_id}', $params);
    }

    public function deleteContactGroup($groupId, $params = []) {
        $params['group_id'] = $groupId;
        return $this->request('DELETE', '/contacts/{group_id}', $params);
    }

    // Contacts
    public function createContact($groupId, $params = []) {
        $params['group_id'] = $groupId;
        return $this->request('POST', '/contacts/{group_id}/store', $params);
    }

    public function viewContact($groupId, $uid, $params = []) {
        $params['group_id'] = $groupId;
        $params['uid'] = $uid;
        return $this->request('POST', '/contacts/{group_id}/search/{uid}', $params);
    }

    public function updateContact($groupId, $uid, $params = []) {
        $params['group_id'] = $groupId;
        $params['uid'] = $uid;
        return $this->request('PATCH', '/contacts/{group_id}/update/{uid}', $params);
    }

    public function deleteContact($groupId, $uid, $params = []) {
        $params['group_id'] = $groupId;
        $params['uid'] = $uid;
        return $this->request('DELETE', '/contacts/{group_id}/delete/{uid}', $params);
    }

    public function viewAllContactsInGroup($groupId, $params = []) {
        $params['group_id'] = $groupId;
        return $this->request('POST', '/contacts/{group_id}/all', $params);
    }

    // SMS
    public function sendSMS($params = []) {
        return $this->request('POST', '/sms/send', $params);
    }

    public function sendSMSViaGet($params = []) {
        return $this->request('GET', '/http/sms/send', $params);
    }

    public function sendCampaign($params = []) {
        return $this->request('POST', '/sms/campaign', $params);
    }

    public function viewSMS($uid, $params = []) {
        $params['uid'] = $uid;
        return $this->request('GET', '/sms/{uid}', $params);
    }

    public function viewAllMessages($params = []) {
        return $this->request('GET', '/sms', $params);
    }

    public function viewCampaign($uid, $params = []) {
        $params['uid'] = $uid;
        return $this->request('GET', '/campaign/{uid}/view', $params);
    }

    // Profile
    public function viewBalance($params = []) {
        return $this->request('GET', '/balance', $params);
    }

    public function viewProfile($params = []) {
        return $this->request('GET', '/me', $params);
    }
}