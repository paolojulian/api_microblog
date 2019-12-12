<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Likes\LikesController Test Case
 *
 * @uses \App\Controller\Likes\LikesController
 */
class ToggleLikesControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Likes',
        'app.Users',
        'app.Posts',
    ];

    protected $requireToken = true;

    public function testVALIDLikesAnUnlikedPostWillReturn200()
    {
        $postId = 12;
        $this->post("/posts/$postId/likes");
        $this->assertResponseCode(200);
    }

    public function testVALIDLikesALikedPostWillReturn200()
    {
        $postId = 13;
        $this->post("/posts/$postId/likes");
        $this->assertResponseCode(200);
    }

    public function testINVALIDlikingNotExistingPostWillReturn404()
    {
        $postId = 23213;
        $this->post("/posts/$postId/likes");
        $this->assertResponseCode(404);
    }
}
