<?php
namespace App\Test\TestCase\Controller\Posts;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Posts\PostsController index Test Case
 */
class IndexPostsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    protected $requireToken = true;
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = ['app.Users', 'app.Posts', 'app.Followers'];
    private $url = '/posts';

    public function test()
    {
        $this->get($this->url);
        $this->assertResponseCode(200);
    }
}
