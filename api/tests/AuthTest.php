<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'lib/ApiTest.php';

class AuthTest extends ApiTest
{
    public function testGetLogInData()
    {
        $response = $this->request('GET', '/api/login');
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('global', $body);
        $this->assertArrayHasKey('languages', $body);
    }

    public function testLoginFail()
    {
        $bodyData = [
            'username' => 'xxx',
            'password' => 'yyy'
        ];
        $response = $this->request('POST', '/api/login', $bodyData);
        $this->assertEquals(401, $response->getStatusCode());
        $authController = \AuthenticationController::getInstance();
        $authController->loggedIn = false;
    }

    public function testLoginSuccess()
    {
        global $test_login, $test_password;
        $bodyData = [
            'username' => $test_login,
            'password' => $test_password
        ];
        $response = $this->request('POST', '/api/login', $bodyData);
        $this->assertEquals(200, $response->getStatusCode());
    }
    
}
