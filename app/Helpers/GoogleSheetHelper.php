<?php

namespace App\Helpers;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetHelper {
    public static function getSheetData($spreadsheetId, $range) {
        $client = new Client();
        $client->setApplicationName('Laravel Periodic Table');
        $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/google-service-account.json'));

        $service = new Sheets($client);
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues(); // Returns the data from the sheet
        } catch (\Google_Service_Exception $e) {
            // Log detailed Google Sheets API error
            \Log::error('Google Sheets API Error: ' . $e->getMessage());
            \Log::error('Google Sheets API Error Details: ' . json_encode($e->getErrors()));
            return null; // Return null if there was an error
        } catch (\Exception $e) {
            // Log any general exception
            \Log::error('General Error: ' . $e->getMessage());
            return null; // Return null if there was an error
        }
    }
}


