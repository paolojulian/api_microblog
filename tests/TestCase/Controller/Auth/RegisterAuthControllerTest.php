<?php
namespace App\Test\TestCase\Controller\Api\Auth;

use App\Test\TestCase\Controller\ApiTestCase;
use Cake\TestSuite\IntegrationTestTrait;

/**
 * App\Controller\Api/AuthsController Test Case
 *
 * @uses \App\Controller\Api/AuthsController
 */
class RegisterAuthsControllerTest extends ApiTestCase
{
    use IntegrationTestTrait;

    public $fixtures = ['app.Users'];
    /** INVALID */

    public function testINVALIDmissingFields()
    {
        $data = [];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains(422);
        $this->assertResponseContains('data');
        $this->assertResponseContains('Username is required');
        $this->assertResponseContains('Email is required');
        $this->assertResponseContains('Mobile is required');
        $this->assertResponseContains('First name is required');
        $this->assertResponseContains('Last name is required');
        $this->assertResponseContains('Birthdate is required');
        $this->assertResponseContains('Country is required');
        $this->assertResponseContains('Zipcode is required');
    }

    public function testINVALIDblankFields()
    {
        $data = [
            'username' => '',
            'email' => '',
            'mobile' => '',
            'first_name' => '',
            'last_name' => '',
            'birthdate' => '',
            'country' => '',
            'zipcode' => '',
        ];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains(422);
        $this->assertResponseContains('data');
        $this->assertResponseContains('Username is required');
        $this->assertResponseContains('Email is required');
        $this->assertResponseContains('Mobile is required');
        $this->assertResponseContains('First name is required');
        $this->assertResponseContains('Last name is required');
        $this->assertResponseContains('Birthdate is required');
        $this->assertResponseContains('Country is required');
        $this->assertResponseContains('Zipcode is required');
        $this->assertResponseContains('Password is required');
        $this->assertResponseContains('Confirm password is required');
    }

    public function testINVALIDwhiteSpaces()
    {
        $data = [
            'username' => ' ',
            'email' => ' ',
            'mobile' => ' ',
            'first_name' => ' ',
            'last_name' => ' ',
            'birthdate' => ' ',
            'country' => ' ',
            'zipcode' => ' ',
            'password' => ' ',
            'confirm_password' => ' ',
        ];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains(422);
        $this->assertResponseContains('data');
        $this->assertResponseContains('Username is required');
        $this->assertResponseContains('Email is required');
        $this->assertResponseContains('Mobile is required');
        $this->assertResponseContains('First name is required');
        $this->assertResponseContains('Last name is required');
        $this->assertResponseContains('Birthdate is required');
        $this->assertResponseContains('Country is required');
        $this->assertResponseContains('Zipcode is required');
        $this->assertResponseContains('Password is required');
        $this->assertResponseContains('Confirm password is required');
    }

    public function testINVALIDnames()
    {
        $data = [
            'first_name' => '231',
            'last_name' => '2312',
        ];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains(422);
        $this->assertResponseContains('data');
        $this->assertResponseContains('first_name');
        $this->assertResponseContains('last_name');
        $this->assertResponseContains('Letters only');
    }

    public function testINVALIDemail()
    {
        $data = [
            'email' => 'not an email',
        ];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains(422);
        $this->assertResponseContains('data');
        $this->assertResponseContains('email');
        $this->assertResponseContains('Invalid email');
    }

    public function testINVALIDmobileNotInteger()
    {
        $data = [
            'mobile' => '2aa31321',
        ];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains(422);
        $this->assertResponseContains('data');
        $this->assertResponseContains('mobile');
        $this->assertResponseContains('Mobile should only contain number');
    }

    public function testINVALIDnotMatchingPassword()
    {
        $data = [
            'password' => 'qwe123',
            'confirm_password' => 'huehue'
        ];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(422);
        $this->assertResponseContains(422);
        $this->assertResponseContains('Password confirmation does not match password');
    }

    public function testVALIDuser()
    {
        $data = [
            'username' => 'newuserfortest',
            'email' => 'newuserfortest@gmail.com',
            'mobile' => '09279488654',
            'first_name' => 'Paolo Vincent',
            'last_name' => 'Julian',
            'birthdate' => '1994-07-30',
            'lot' => '18B',
            'block' => '',
            'street' => 'Dagsian',
            'province' => 'Benguet',
            'city' => 'Baguio',
            'country' => 'Philippines',
            'zipcode' => '2600',
            'password' => 'qwe123',
            'confirm_password' => 'qwe123'
        ];
        $this->post('/auth/register', $data);
        $this->assertResponseCode(201);
        $this->assertResponseContains(201);
        $this->assertResponseContains('data');
        $this->assertResponseContains('newuserfortest');
    }
}
