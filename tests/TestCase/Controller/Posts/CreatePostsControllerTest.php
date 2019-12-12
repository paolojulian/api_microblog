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

    public function testINVALIDmaxLengthWillReturn422()
    {
        $data = [
            'title' => 'jdsflakjflkdsajfklasjfklasjflkasjfklsajflkajsdklfjasdklfjaslkfja',
            'body' => 'LJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjaaaaaaaaaaaaaaaaaaaaaaaaaaaaLJdlaskdjalskdjaslkdja',
        ];
        $this->post("/posts", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('title');
        $this->assertResponseContains('body');
        $this->assertResponseContains('Up to 30 characters only');
        $this->assertResponseContains('Up to 140 characters only');
    }

    public function testValidPost()
    {
        $data = [
            'title' => 'This is a title',
            'body' => 'This is a body',
        ];
        $this->post("/posts", $data);
        $this->assertResponseCode(201);
        $this->assertResponseContains('title');
        $this->assertResponseContains('body');
        $this->assertResponseContains('This is a title');
        $this->assertResponseContains('This is a body');
    }

    public function testVALIDaddingNotIncludedKeyWillNotSave()
    {
        $data = [
            'title' => 'This is a title',
            'body' => 'This is a body',
            'retweet_post_id' => 133
        ];
        $this->post("/posts", $data);
        $this->assertResponseCode(201);
        $this->assertResponseNotContains('retweet_post_id');
    }
}
