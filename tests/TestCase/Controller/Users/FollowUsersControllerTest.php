<?php
namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Users\UsersController Test Case
 *
 * @uses \App\Controller\Users\UsersController
 */
class FollowUsersControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    protected $requireToken = true;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = ['app.Users', 'app.Followers'];

    public function testINVALIDNotFoundUser()
    {
        $username = 'kljdsalkfjdaslj';
        $this->get("/users/$username/follow");
        $this->assertResponseCode(404);
    }

    public function testVALIDfollow()
    {
        $username = 'tobeFollowed';
        $this->get("/users/$username/follow");
        $this->assertResponseCode(200);
    }
}
