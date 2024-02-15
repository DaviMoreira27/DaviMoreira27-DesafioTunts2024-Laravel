<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\GoogleSheetsAPI;

class GoogleTest extends TestCase
{
    private $sheetId;
    private $rangeSheet;
    private $googleSheetsAPI;

    public function setUp(): void
    {
        parent::setUp();
        // Set up your sheetId and rangeSheet here
        $this->sheetId = '11noy7IP6xjwcCidWczdvB1iNOw2_3TRlDmGbET35JXk';
        $this->rangeSheet = '4:27'; // Change this to the desired range

        $this->googleSheetsAPI = new GoogleSheetsAPI($this->sheetId, $this->rangeSheet);
    }

    public function testConnectToGoogle()
    {
        $result = $this->googleSheetsAPI->connectToGoogle();
        $this->assertInstanceOf(\Google\Client::class, $result);
    }

    public function testGetRowsFromSheet()
    {
        $result = $this->googleSheetsAPI->getRowsFromSheet();
        $this->assertInstanceOf(\Google\Service\Sheets\ValueRange::class, $result);
    }

    public function testHandleRowsResult()
    {
        $result = $this->googleSheetsAPI->handleRowsResult();
        $this->assertIsArray($result);
    }

    public function testInsertData()
    {
        $rangeToInsert = 'C80:D40';
        $value = [['New Data', 'Another Data']]; 
        $result = $this->googleSheetsAPI->insertData($rangeToInsert, $value);
        $this->assertStringContainsString('successfully inserted', $result);
    }

    public function testCheckIfIsEmpty()
    {
        $rangeToInsert = 'C1:D2'; // Change this to the desired range
        $result = $this->googleSheetsAPI->checkIfIsEmpty($rangeToInsert);
        $this->assertInstanceOf(\Google\Service\Sheets\ValueRange::class, $result);
    }
}
