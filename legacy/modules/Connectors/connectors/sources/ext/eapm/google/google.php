<?php

/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */


/**
 * Class ext_eapm_google
 */
class ext_eapm_google extends source
{
    protected $_enable_in_wizard = false;
    protected $_enable_in_hover = false;
    protected $_has_testing_enabled = false;

    const CONTACTS_FEED = 'https://www.google.com/m8/feeds/contacts/default/full';
    const GDATA_VERSION = '3.0';

    /** {@inheritdoc} */
    public function getItem($args = array(), $module = null)
    {
    }

    /** {@inheritdoc} */
    public function getList($args = array(), $module = null)
    {
        /** @var Google\Client $client */
        $client = $this->_eapm->getClient();

        try {
            $httpClient = $client->authorize();
            $http_request = $this->create_http_request($args);
            $response = $httpClient->send($http_request);
        } catch (\Exception $e) {
            $GLOBALS['log']->fatal('Unable to retrieve item list for google contact connector: ' . $e->getMessage());
            return false;
        }

        if ($response->getStatusCode() != 200) {
            return false;
        }

        $feed = new Zend_Gdata_Contacts_ListFeed();
        list($major, $minor) = explode('.', self::GDATA_VERSION);
        $feed->setMajorProtocolVersion($major);
        $feed->setMinorProtocolVersion($minor);
        $xml = $response->getBody()->getContents();

        try {
            $feed->transferFromXML($xml);
        } catch (Zend_Gdata_App_Exception $e) {
            $GLOBALS['log']->fatal('Unable to retrieve item list for google contact connector: ' . $e->getMessage());
        }

        $rows = array();
        foreach ($feed->entries as $entry) {
            $rows[] = $entry->toArray();
        }

        return array(
            'totalResults' => $feed->getTotalResults()->getText(),
            'records' => $rows,
        );
    }

    /**
     * @param array $args
     * @return \Psr\Http\Message\RequestInterface
     */
    private function create_http_request(array $args)
    {
        $params = array();

        if (isset($args['maxResults'])) {
            $params['max-results'] = $args['maxResults'];
        }

        if (!empty($args['startIndex'])) {
            $params['start-index'] = $args['startIndex'];
        } else {
            $params['start-index'] = 1;
        }

        return new \GuzzleHttp\Psr7\Request(
            'GET',
            self::CONTACTS_FEED . '?' . http_build_query($params),
            ['GData-Version' => self::GDATA_VERSION]
        );
    }
}
