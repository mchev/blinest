<?php

namespace App\Services;

use App\Models\User;

class SendinblueService
{
    private $credentials;

    private $apiInstance;

    private $lists = [2];

    public function __construct()
    {
        $this->credentials = \SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.sendinblue.key'));
    }

    public function contacts()
    {
        $this->apiInstance = new \SendinBlue\Client\Api\ContactsApi(
            new \GuzzleHttp\Client(),
            $this->credentials
        );

        return $this;
    }

    public function create(User $user)
    {
        $createContact = new \SendinBlue\Client\Model\CreateContact([
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
