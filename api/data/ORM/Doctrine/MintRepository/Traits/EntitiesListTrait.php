<?php 

namespace MintHCM\Data\ORM\Doctrine\MintRepository\Traits;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\InvalidArgumentException;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;
use MintHCM\Lib\Search\ElasticSearch\ElasticResult;
use MintHCM\Lib\Search\ElasticSearch\ElasticSearch;
use MintHCM\Lib\Search\Search;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;

trait EntitiesListTrait
{
    /**
     * Get list of entities
     *
     * @param array $search_params The search parameters for elastic search
     * @return MintEntity[]|null The list view data or null if not found
     */
    public function getList(array $search_params = [], bool $check_acl = true): ?array
    {
        $params = [
            'filters' => [
                'filter' => [],
                'must_not' => [],
                'must' => [],
            ],
            'search' => 'list',
            'size' => 10000,
            'type' => $this->getEntityShortName(),
        ];

        foreach ($search_params as $key => $value) {
            $params[$key] = $value;
        }

        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $disable_date_format = $GLOBALS['disable_date_format'];
        $GLOBALS['disable_date_format'] = true;

        try {
            global $current_user;
            /** @var ElasticSearch */
            $search_manager = Search::getManager();
            $search_manager->setElasticACL(!is_admin($current_user));
            $search_manager->setQuery($params);
            /** @var ElasticResult */
            $search_result = $search_manager->search($check_acl);
        } catch (BadRequest400Exception $e) {
            throw new HttpBadRequestException($this->request, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }

        date_default_timezone_set($current_time_zone);
        $GLOBALS['disable_date_format'] = $disable_date_format;

        $ids = $search_result->getHits(true)[$this->getEntityShortName()] ?? [];
        return $this->findBy(['id' => $ids]);
    }
}