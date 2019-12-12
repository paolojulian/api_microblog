<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Users\UsersController Test Case
 *
 * @uses \App\Controller\Users\UsersController
 */
class DeletePostsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    protected $requireToken = true;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = ['app.Users', 'app.Posts'];

    public function testValidDeletePost()
    {
        $postId = 10;
        $data = [];
        $this->delete("/posts/$postId", $data);
        $this->assertResponseCode(200);
    }

    public function testINVALIDcannotDeleteNotOwnedPost()
    {
        $postId = 11;
        $this->delete("/posts/$postId");
        $this->assertResponseCode(403);
    }

    public function testINVALIDcannotDeleteNotFoundPost()
    {
        $postId = 9321;
        $data = [
            'title' => 'This is a title',
            'body' => 'This is a body'
        ];
        $this->put("/posts/$postId", $data);
        $this->assertResponseCode(403);
    }
}
