<?php
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api/AuthsController Test Case
 *
 * @uses \App\Controller\Api/AuthsController
 */
class ApiTestCase extends TestCase
{
    protected $requireToken = false;
    protected $loggedInUser = 200002;
    protected $requestHeaders = [
        'Accept' => 'application/json',
        'Content-Type' => 'x-www-form-urlencoded'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->configRequest([
            'headers' => $this->requestHeaders
        ]);
    }

    protected function getResponseData()
    {
        return json_decode((string)$this->_response->getBody());
    }
}