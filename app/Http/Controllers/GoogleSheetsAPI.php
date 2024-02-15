<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Exception;
use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\ValueRange;
use GuzzleHttp\Client as HttpHandler;
use Google\Service\Sheets\AppendValuesResponse;

use function PHPUnit\Framework\isEmpty;

class GoogleSheetsAPI extends Controller
{
    /**
     * The sheet id that you can get from the URL.
     * 
     * @param string
     */

    private string $sheetId;

    /**
     * The sheet cell or column interval. Defines the region that the values are gonna be obtained.
     *
     * @param string
     */
    private string $rangeSheet;



    /**
     * Contructor
     * 
     * @param string
     * @param string
     * 
     */
    public function __construct(string $sheetId, string $rangeSheet)
    {
        $this->sheetId = $sheetId;
        $this->rangeSheet = $rangeSheet;
    }

    /**
     * Makes the connection with the Google API.
     * 
     * @return Client|Exception
     */

    public function connectToGoogle(): Client|string
    {
        $client = new Client();

        try {
            $client->useApplicationDefaultCredentials();
            $guzzleClient = new HttpHandler(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
            $client->setHttpClient($guzzleClient);
            $client->addScope(Drive::DRIVE);
            $client->setApprovalPrompt('force');
        } catch (Exception $e) {
            return $e->getMessage();
        }


        return $client;
    }

    /**
     * Get the given cell or column interval
     * 
     * @return object|bool
     */

    public function getRowsFromSheet(): Object|bool
    {
        $service = new GoogleSheets($this->connectToGoogle());
        $result = $service->spreadsheets_values->get($this->sheetId, $this->rangeSheet);

        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * Handle the received results
     *
     * @return array|string
     */

    public function handleRowsResult()
    {
        $result = $this->getRowsFromSheet();

        if (!$result) {
            return "Ocorreu um erro ao obter os valores!";
        }

        return $result->values;
    }


    /**
     * Insert the data in the sheet
     * @param string $rangeToInsert - The interval that the data will be inserted
     * @param string $value - The array of values that will be stored
     * 
     * 
     * @return array|string
     */


    public function insertData(string $rangeToInsert, array $value): array|string
    {

        $service = new GoogleSheets($this->connectToGoogle());
        $checkEmpty = $this->checkIfIsEmpty($rangeToInsert);

        if (empty($checkEmpty->getValues())) {
            $body = new ValueRange([
                'values' => $value
            ]);

            try{
                $result = $service->spreadsheets_values->update($this->sheetId, $rangeToInsert, $body, [
                    'valueInputOption' => 'USER_ENTERED'
                ]);
                return "The data has been sucessfully inserted!";
            }catch(Exception $e){
                return $e->getMessage();
            }
            
        }else{
            return "The given interval is already ocuppied!";
        }

    }


    /**
     * Checks if the given interval is already ocuppied.
     * @param string $rangeToInsert - The interval that the data will be inserted
     * 
     * 
     * @return ValueRange|bool
     */

    public function checkIfIsEmpty(string $rangeToInsert) : ValueRange|bool
    {
        $service = new GoogleSheets($this->connectToGoogle());
        $result = $service->spreadsheets_values->get($this->sheetId, $rangeToInsert);

        if ($result) {
            return $result;
        }

        return false;
    }
}
