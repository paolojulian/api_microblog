<?php
namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Users\UsersController Test Case
 *
 * @uses \App\Controller\Users\UsersController
 */
class ViewUsersControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    protected $requireToken = true;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = ['app.Users'];

    public function testINVALIDunactivatedUserWillShow404()
    {
        $username = 'unactivated';
        $this->get("/users/$username");
        $this->assertResponseCode(404);
    }

    public function testINVALIDNonExistingUser()
    {
        $username = 'jkldsafjdslkj';
        $this->get("/users/$username");
        $this->assertResponseCode(404);
    }

    public function testVALIDuser()
    {
        $username = 'existingusername';
        $this->get("/users/$username");
        $this->assertResponseCode(200);
        $this->assertResponseContains(200);
        $this->assertResponseContains('data');
        $this->assertResponseContains('existingusername');
    }
}
