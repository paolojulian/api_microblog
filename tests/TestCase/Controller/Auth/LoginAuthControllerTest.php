<?php
namespace App\Test\TestCase\Controller\Api\Auth;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Api/AuthsController Test Case
 *
 * @uses \App\Controller\Api/AuthsController
 */
class LoginAuthsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    public $fixtures = ['app.Users'];
    /** INVALID */

    public function testINVALIDmissingFields()
    {
        $data = [];
        $this->post('/auth/login', $data);
        $this->assertResponseCode(401);
        $this->assertResponseContains("Username or password is incorrect");
    }

    public function testINVALIDcredentials()
    {
        $data = [
            'username' => 'activated',
            'password' => 'wrongpassword'
        ];
        $this->post('/auth/login', $data);
        $this->assertResponseCode(401);
        $this->assertResponseContains("Username or password is incorrect");
    }

    public function testINVALIDunactivated()
    {
        $data = [
            'username' => 'unactivated',
            'password' => 'qwe123'
        ];
        $this->post('/auth/login', $data);
        $this->assertResponseCode(401);
        $this->assertResponseContains("Please activate your account first");
    }

    public function testValidwillHaveToken()
    {
        $data = [
            'username' => 'activated',
            'password' => 'qwe123'
        ];
        $this->post('/auth/login', $data);
        $this->assertResponseCode(200);
        $this->assertResponseContains("token");
    }
}
