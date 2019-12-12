<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Users\UsersController Test Case
 *
 * @uses \App\Controller\Users\UsersController
 */
class SharePostsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    protected $requireToken = true;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = ['app.Users', 'app.Posts'];

    public function testINVALIDShareNotFoundPostWillReturn404()
    {
        $postId = 23123219;
        $data = [];
        $this->post("/posts/$postId/share", $data);
        $this->assertResponseCode(404);
    }

    public function testINVALIDmaxLengthWIllReturn422()
    {
        $postId = 10;
        $data = [
            'body' => 'jdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljjjjjjjjdlkasjdklasjdsakljdaskljjdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljdlkasjdklasjdsakljdaskljjjjjjjjdlkasjdklasjdsakljdasklj'
        ];
        $this->post("/posts/$postId/share", $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains('body');
        $this->assertResponseContains('Up to 140 characters only');
    }

    public function testVALIDemptyBodyWillBeSuccessful()
    {
        $postId = 10;
        $data = ['body' => ''];
        $this->post("/posts/$postId/share", $data);
        $this->assertResponseCode(201);
    }

    public function testVALIDShare()
    {
        $postId = 10;
        $data = ['body' => 'Test'];
        $this->post("/posts/$postId/share", $data);
        $this->assertResponseCode(201);
    }
}
