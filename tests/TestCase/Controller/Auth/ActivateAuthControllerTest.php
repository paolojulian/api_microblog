<?php
namespace App\Test\TestCase\Controller\Api\Auth;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Api/AuthsController Test Case
 *
 * @uses \App\Controller\Api/AuthsController
 */
class ActivateAuthsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    public $fixtures = ['app.Users'];

    public function testINVALIDkey()
    {
        $this->get('/auth/activate/dddee');
        $this->assertRedirect('/not-found');
    }

    public function testVALIDActivateUser()
    {
        $this->get('/auth/activate/70b45cd46d274bd1374324a7dcc877e4ed1600aa807188001574299686');
        $this->assertRedirect('/login');
    }
}
