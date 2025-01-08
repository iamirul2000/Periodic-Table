<?php

namespace App\Http\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetServices
{
    public $client, $service, $documentId, $range;

    public function __construct()
    {
        $this->client = $this -> getClient();
        $this->service = new Sheets($this->client);
        $this->documentId = config("services.google.sheet_id");
        $this->range = 'A1:F300';
    }
    public function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Laravel Periodic Table');
        $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/google-service-account.json'));

        return $client;
    }

    public function readSheet()
    {
        $doc = $this->service->spreadsheets_values->get($this->documentId,$this->range);
        
        return $doc;
    }

}