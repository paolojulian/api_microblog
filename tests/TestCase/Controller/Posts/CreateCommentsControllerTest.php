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

    private function url($postId) {
        return "/posts/$postId/comments";
    }

    public function testINVALIDblankPresenceWillReturn422()
    {
        $data = [];
        $postId = 1;
        $this->post($this->url($postId), $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDemptyStringWillReturn422()
    {
        $data = ['body' => ''];
        $postId = 1;
        $this->post($this->url($postId), $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDwhiteSpacesWillReturn422()
    {
        $data = ['body' => ' '];
        $postId = 1;
        $this->post($this->url($postId), $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDmaxLengthWillReturn422()
    {
        $data = [
            'body' => 'LJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjaaaaaaaaaaaaaaaaaaaaaaaaaaaaLJdlaskdjalskdjaslkdja',
        ];
        $postId = 1;
        $this->post($this->url($postId), $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('body');
        $this->assertResponseContains('Up to 140 characters only');
    }

    public function testInvalidNonExistingPostWillReturn404()
    {
        $data = ['body' => 'This is a comment'];
        $postId = 1232131;
        $this->post($this->url($postId), $data);
        $this->assertResponseCode(404);
    }

    public function testValidComment()
    {
        $data = ['body' => 'This is a comment'];
        $postId = 1;
        $this->post($this->url($postId), $data);
        $this->assertResponseCode(201);
        $this->assertResponseContains('body');
        $this->assertResponseContains('This is a comment');
    }
}
