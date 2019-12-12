<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Comments\CommentsController Test Case
 *
 * @uses \App\Controller\Comments\CommentsController
 */
class IndexCommentsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Comments',
        'app.Users',
        'app.Posts',
    ];

    protected $requireToken = true;

    public function testValidRetrieveComments()
    {
        $postId = 1;
        $this->get("/posts/$postId/comments");
        $this->assertResponseOk();
        $this->assertResponseContains('This is a comment on post 1');
    }

    public function testInvalidRetrieveNonExistingPostWillReturn()
    {
        $postId = 1312321;
        $this->get("/posts/$postId/comments");
        $this->assertResponseCode(404);
    }
}
