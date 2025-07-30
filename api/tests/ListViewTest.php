<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'lib/ApiTest.php';

class ListViewTest extends ApiTest
{
    public function testInitialData()
    {
        $response = $this->request('GET', '/api/Tasks');
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertIsArray($body['config'], 'Missing config in response');
        $this->assertIsArray($body['defs'], 'Missing defs in response');
        $this->assertIsArray($body['preferences'], 'Missing preferences in response');
        $this->assertIsArray($body['config']['config']['actions'], 'Missing actions in config');
        $this->assertIsArray($body['config']['config']['massActions'], 'Missing massActions in config');
    }

    public function testGetTasksListViewRecords()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 1,
            'sortOrder' => 'desc',
            'myObjects' => false,
            'searchPhrase' => '',
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $this->assertEquals(28, $body['total'], 'Total should be 28 for Tasks module');
    }

    public function testGetTasksListViewRecordsPage2()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 2,
            'sortOrder' => 'desc',
            'myObjects' => false,
            'searchPhrase' => '',
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $this->assertEquals(28, $body['total'], 'Total should be 28 for Tasks module');
    }


    public function testGetTasksListViewRecordsPage3()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 3,
            'sortOrder' => 'desc',
            'myObjects' => false,
            'searchPhrase' => '',
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $this->assertEquals(8, count($body['results']));
        $this->assertEquals(28, $body['total'], 'Total should be 28 for Tasks module');
    }

    public function testGetTasksListViewRecordsSearchPhrase()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 1,
            'sortOrder' => 'asc',
            'myObjects' => false,
            'searchPhrase' => "exit",
            'filters' => [],
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $this->assertEquals(1, count($body['results']));
        $this->assertEquals(1, $body['total'], 'Total should be 1 for Tasks module');
    }

    public function testGetTasksListViewRecordsStatusCompleted()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 1,
            'sortOrder' => 'asc',
            'myObjects' => false,
            'searchPhrase' => "",
            'filters' => [
                "filter" => [
                    [
                        "term" => [
                            "status.keyword" => "Completed"
                        ]
                    ]
                ],
            ],
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $this->assertEquals(4, count($body['results']));
        $this->assertEquals(4, $body['total'], 'Total should be 4 for Tasks module');
    }

    
    public function testGetTasksListViewRecordsStatusCompletedAndInProgress()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 1,
            'sortOrder' => 'asc',
            'myObjects' => false,
            'searchPhrase' => "",
            'filters' => [
                "filter" => [
                    [
                        "terms" => [
                            "status.keyword" => [ "Completed", "In Progress" ]
                        ]
                    ]
                ],
            ],
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $this->assertEquals(5, count($body['results']));
        $this->assertEquals(5, $body['total'], 'Total should be 5 for Tasks module');
    }

    public function testGetTasksListViewRecordsStartDateInLast3Months()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 1,
            'sortOrder' => 'asc',
            'myObjects' => false,
            'searchPhrase' => "",
            'filters' => [
                "filter" => [
                    [
                        "range" => [
                            "date_start" => [
                                "gte" => "now-3M/M"
                            ]
                        ]
                    ]
                ],
                "must_not" => []
            ],
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
    }

    public function testGetTasksListViewRecordsWithNameContainsGift()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 1,
            'sortOrder' => 'asc',
            'myObjects' => false,
            'searchPhrase' => "",
            'filters' => [
                "filter" => [
                    [
                        "match" => [
                            "name" => [
                                "query" => "gift",
                                "operator" => "and",
                            ]
                        ]
                    ]
                ],
                "must_not" => []
            ],
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
    }

    public function testGetTasksListViewRecordsSortByNameDesc()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 5,
            'page' => 1,
            'sortOrder' => 'desc',
            'sortBy' => 'name.name',
            'myObjects' => false,
            'searchPhrase' => "",
            'filters' => null,
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $nameList = array_map(function ($item) {
            return $item['name'];
        }, $body['results']);
        $this->assertArrayIsSortedDescending($nameList, 'Results should be sorted by name in descending order');
    }

    public function testGetCompletedTasksListViewRecordsSortByNameAsc()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 5,
            'page' => 1,
            'sortOrder' => 'asc',
            'sortBy' => 'name.name',
            'myObjects' => false,
            'searchPhrase' => "",
            'filters' => [
                [
                    "type" => "equals",
                    "field" => "status.keyword",
                    "value" => "Completed"
                ],
            ],
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $nameList = array_map(function ($item) {
            return $item['name'];
        }, $body['results']);
        $this->assertArrayIsSortedAscending($nameList, 'Results should be sorted by name in descending order');
    }
    
    public function testGetTasksListViewRecordsOnlyMyObjects()
    {
        $response = $this->request('POST', '/api/Tasks', [
            'items' => 10,
            'page' => 1,
            'sortOrder' => 'asc',
            'myObjects' => true,
            'searchPhrase' => "",
            'filters' => [
            ],
            'activeFilter' => null
        ]);
        $body = $this->getBody($response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Tasks', $body['module']);
        $this->assertNotEmpty($body['results'], 'Results should not be empty');
        $this->assertEquals(1, $body['total'], 'Total should be 1 for Tasks module');
    }
}
