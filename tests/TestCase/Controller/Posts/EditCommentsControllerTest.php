<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Comments\CommentsController Test Case
 *
 * @uses \App\Controller\Comments\CommentsController
 */
class CreateCommentsControllerTest extends ApiTestCase
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

    private function url(int $postId, int $commentId) {
        return "/posts/$postId/comments/$commentId";
    }

    public function testINVALIDblankPresenceWillReturn422()
    {
        $data = [];
        $postId = 1;
        $commentId = 3;
        $this->put($this->url($postId, $commentId), $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDemptyStringWillReturn422()
    {
        $data = ['body' => ''];
        $postId = 1;
        $commentId = 3;
        $this->put($this->url($postId, $commentId), $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDwhiteSpacesWillReturn422()
    {
        $data = ['body' => ' '];
        $postId = 1;
        $commentId = 3;
        $this->put($this->url($postId, $commentId), $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDmaxLengthWillReturn422()
    {
        $data = [
            'body' => 'LJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjaaaaaaaaaaaaaaaaaaaaaaaaaaaaLJdlaskdjalskdjaslkdja',
        ];
        $postId = 1;
        $commentId = 3;
        $this->put($this->url($postId, $commentId), $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('body');
        $this->assertResponseContains('Up to 140 characters only');
    }

    public function testInvalidNonExistingPostWillReturn404()
    {
        $data = ['body' => 'This is a comment'];
        $postId = 1321321;
        $commentId = 3;
        $this->put($this->url($postId, $commentId), $data);
        $this->assertResponseCode(404);
        $this->assertResponseContains('POST_NOT_FOUND');
    }

    public function testInvalidNonExistingCommentWillReturn404()
    {
        $data = ['body' => 'This is a comment'];
        $postId = 1;
        $commentId = 3321321;
        $this->put($this->url($postId, $commentId), $data);
        $this->assertResponseCode(404);
        $this->assertResponseContains('COMMENT_NOT_FOUND');
    }

    public function testInvalidNotOwnedCommentWillReturn403()
    {
        $data = ['body' => 'This is edited comment'];
        $postId = 1;
        $commentId = 2;
        $this->put($this->url($postId, $commentId), $data);
        $this->assertResponseCode(403);
    }

    public function testValidEditComment()
    {
        $data = ['body' => 'This is edited comment'];
        $postId = 1;
        $commentId = 3;
        $this->put($this->url($postId, $commentId), $data);
        $this->assertResponseCode(201);
        $this->assertResponseContains('body');
        $this->assertResponseContains('This is edited comment');
    }
}
