<?php
namespace App\Test\TestCase\Controller\Api\Auth;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Api/AuthsController Test Case
 *
 * @uses \App\Controller\Api/AuthsController
 */
class AuthsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    public $fixtures = ['app.Users', 'app.Posts'];
    /** INVALID */

    public function testINVALIDmissingFields()
    {
        $data = [];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains(422);
        $this->assertResponseContains('data');
        $this->assertResponseContains('Username is required');
        $this->assertResponseContains('Email is required');
        $this->assertResponseContains('Mobile is required');
        $this->assertResponseContains('First name is required');
        $this->assertResponseContains('Last name is required');
        $this->assertResponseContains('Birthdate is required');
        $this->assertResponseContains('Country is required');
        $this->assertResponseContains('Zipcode is required');
    }
}
