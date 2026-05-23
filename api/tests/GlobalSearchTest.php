<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'lib/ApiTest.php';

class GlobalSearchTest extends ApiTest
{
    public function testGetAdmin()
    {
        $response = $this->request('GET', '/api/global_search?query=Admin');
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($body['results']);
    }
}