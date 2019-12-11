<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Users\UsersController Test Case
 *
 * @uses \App\Controller\Users\UsersController
 */
class CreatePostsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    protected $requireToken = true;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = ['app.Users', 'app.Posts'];

    public function testINVALIDblankPresenceWillReturn422()
    {
        $data = [];
        $this->post("/posts", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDemptyStringWillReturn422()
    {
        $data = [
            'body' => '',
        ];
        $this->post("/posts", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDwhiteSpacesWillReturn422()
    {
        $data = [
            'body' => ' ',
        ];
        $this->post("/posts", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }
}
