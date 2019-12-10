<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\APIResponseComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\APIResponseComponent Test Case
 */
class APIResponseComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\APIResponseComponent
     */
    public $APIResponse;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->APIResponse = new APIResponseComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->APIResponse);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
