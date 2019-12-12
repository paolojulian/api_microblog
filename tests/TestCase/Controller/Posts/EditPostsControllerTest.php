<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Users\UsersController Test Case
 *
 * @uses \App\Controller\Users\UsersController
 */
class EditPostsControllerTest extends ApiTestCase
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
        $postId = 10;
        $data = [];
        $this->put("/posts/$postId", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDemptyStringWillReturn422()
    {
        $postId = 10;
        $data = [
            'body' => ''
        ];
        $this->put("/posts/$postId", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDwhiteSpacesWillReturn422()
    {
        $postId = 10;
        $data = [
            'body' => ' ',
        ];
        $this->put("/posts/$postId", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('Body is required');
    }

    public function testINVALIDmaxLengthWillReturn422()
    {
        $postId = 10;
        $data = [
            'title' => 'jdsflakjflkdsajfklasjfklasjflkasjfklsajflkajsdklfjasdklfjaslkfja',
            'body' => 'LJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjLJdlaskdjalskdjaslkdjaaaaaaaaaaaaaaaaaaaaaaaaaaaaLJdlaskdjalskdjaslkdja',
        ];
        $this->put("/posts/$postId", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('title');
        $this->assertResponseContains('body');
        $this->assertResponseContains('Up to 30 characters only');
        $this->assertResponseContains('Up to 140 characters only');
    }

    public function testValidEditPost()
    {
        $postId = 10;
        $data = [
            'title' => 'Edited',
            'body' => 'Hehe',
        ];
        $this->put("/posts/$postId", $data);
        $this->assertResponseCode(200);
        $this->assertResponseContains('title');
        $this->assertResponseContains('body');
        $this->assertResponseContains('Edited');
        $this->assertResponseContains('Hehe');
    }

    public function testVALIDaddingNotIncludedKeyWillNotSave()
    {
        $postId = 10;
        $data = [
            'title' => 'This is a title',
            'body' => 'This is a body',
            'retweet_post_id' => 133
        ];
        $this->put("/posts/$postId", $data);
        $this->assertResponseCode(200);
        $this->assertResponseNotContains(133);
    }

    public function testINVALIDcannotEditNotOwnedPost()
    {
        $postId = 11;
        $data = [
            'title' => 'This is a title',
            'body' => 'This is a body'
        ];
        $this->put("/posts/$postId", $data);
        $this->assertResponseCode(403);
    }

    public function testINVALIDcannotEditNotFoundPost()
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
