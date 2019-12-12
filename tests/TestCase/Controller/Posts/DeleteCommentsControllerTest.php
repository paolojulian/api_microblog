<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Users\UsersController Test Case
 *
 * @uses \App\Controller\Users\UsersController
 */
class DeleteCommentsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    protected $requireToken = true;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = ['app.Users', 'app.Posts', 'app.Comments'];

    private function url(int $postId, int $commentId) {
        return "/posts/$postId/comments/$commentId";
    }

    public function testInvalidDeleteNotOwnedCommentWillReturn403()
    {
        $postId = 1;
        $commentId = 1;
        $this->delete($this->url($postId, $commentId));
        $this->assertResponseCode(403);
    }

    public function testInvalidDeleteNonExistingCommentWillReturn404()
    {
        $postId = 1;
        $commentId = 332131;
        $this->delete($this->url($postId, $commentId));
        $this->assertResponseCode(404);
        $this->assertResponseContains('COMMENT_NOT_FOUND');
    }

    public function testInvalidDeleteNonExistingPostWilReturn404()
    {
        $postId = 9321;
        $commentId = 3;
        $this->delete($this->url($postId, $commentId));
        $this->assertResponseCode(404);
        $this->assertResponseContains('POST_NOT_FOUND');
    }

    public function testValidDeletePostWillReturn200()
    {
        $postId = 1;
        $commentId = 3;
        $this->delete($this->url($postId, $commentId));
        $this->assertResponseCode(200);
    }
}
