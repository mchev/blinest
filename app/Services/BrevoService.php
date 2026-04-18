<?php

namespace App\Services;

use App\Models\User;
use Brevo\Client\Api\ContactsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateContact;
use GuzzleHttp\Client;

class BrevoService
{
    private $credentials;

    private $apiInstance;

    private $lists = [2];

    public function __construct()
    {
        $this->credentials = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.key'));
    }

    public function contacts()
    {
        $this->apiInstance = new ContactsApi(
            new Client,
            $this->credentials
        );

        return $this;
    }

    public function create(User $user)
    {
        $createContact = new CreateContact([
            'email' => $user->email,
            'updateEnabled' => true,
            'attributes' => (object) [
                'NOM' => $user->name,
                'INSCRIPTION' => $user->created_at,
                'PROVIDER' => $user->provider,
            ],
            'listIds' => $this->lists,
        ]);

        try {
            return $this->apiInstance->createContact($createContact);
        } catch (\Exception $e) {
            echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function delete(User $user)
    {
        try {
            return $this->apiInstance->deleteContact($user->email);
        } catch (\Exception $e) {
            echo 'Exception when calling ContactsApi->deleteContact: ', $e->getMessage(), PHP_EOL;
        }
    }
}
