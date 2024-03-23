<?php

use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchWrapper;
use SuiteCRM\Search\UI\SearchThrowableHandler;
use MassUpdate;
use SuiteCRM\Search\ElasticSearch\ElasticSearchIndexer;

require_once 'include/ESListView/ESListViewGetRecords.php';
require_once 'lib/Search/ElasticSearch/ElasticSearchIndexer.php';

class ESListViewController {

    protected $bean, $query, $per_page, $page, $engine, $options, $metadata;

    public function __construct($bean) {
        $this->bean = $bean;
    }

    //temp
    public function getInitialData($options) {
        $view = new ViewESList();
        $module = $options['module'];
        $view->module = $module;
        $view->seed = BeanFactory::newBean($module);
        $view->bean = BeanFactory::newBean($module);
        $data = $view->getInitialData();
        return [
            'config' => json_decode($data->config, true),
            'defs' => json_decode($data->defs, true),
            'module' => $data->module,
            'preferences' => json_decode($data->preferences, true),
        ];
    }

    public function massUpdate() {
        require_once 'include/MassUpdate.php';
        $_POST['mass'] = $_POST['IDs'];
        $_REQUEST['massupdate'] = true;
        $updater = new MassUpdate();
        $updater->setSugarBean($this->bean);
        if ($_POST['action_name'] === 'delete') {
            $_POST['Delete'] = true;
        }
        $updater->handleMassUpdate();

        echo json_encode(['success' => true]);
    }

    public function getResults($options)
    {
        $this->loadMetadataFile($options['module']);
        $module_name = $this->metadata['es_module'] ?? $options['module'];
        if (ACLController::checkAccess($module_name, 'list', true)) {
            $get_records = new ESListViewGetRecords($this->metadata, $module_name, $options['itemsPerPage'], $options['offset'], $options['page'], $options['sortBy'], $options['sortOrder'], [
                'myObjects' => $options['myObjects'],
                'searchPhrase' => $options['searchPhrase'] ?? '',
                'defaultFilters' => !empty($this->metadata['query']) ? $this->metadata['query'] : null,
                'filters' => $options['filters'] ?? [],
            ]);
            try {
                list($total, $offset, $results) = $get_records->get();
                $this->updatePreferences($options);
                return ['total' => $total, 'offset' => $offset, 'results' => $results];
            } catch (Exception $exception) {
                $GLOBALS['log']->fatal($exception->getMessage());
                return false;
            } catch (Throwable $throwable) {
                $GLOBALS['log']->fatal($throwable->getMessage());
                return false;
            }
        }
    }

    public function handleThrowable($throwable, SearchQuery $query) {
        $handler = new SearchThrowableHandler($throwable, $query);
        $handler->handle();
    }

    public function savePreferences($data) {
        global $current_user;
        $module = $data['module'];
        $preferences = $data['preferences'];
        if (!empty($preferences) && is_array($preferences) && !empty($module)) {
            (new UserPreference($current_user))->setPreference($module, $preferences, 'eslist');
        }
        return true;
    }

    public function updatePreferences($data) {
        if (empty($data['module']) || empty($data['itemsPerPage'])) {
            return false;
        }
        global $current_user;
        $preferences = (new UserPreference($current_user))->getPreference($data['module'], 'eslist');
        if (
            empty($preferences['items_per_page'])
            || $data['itemsPerPage'] != $preferences['items_per_page']
        ) {
            $preferences['items_per_page'] = $data['itemsPerPage'];
            $this->savePreferences([
                'module' => $data['module'],
                'preferences' => $preferences,
            ]);
        }
    }

    public function deleteRecord($data) {
        if (empty($data['module']) || empty($data['record_id'])) {
            return false;
        }
        $this->loadMetadataFile($data['module']);
        $module_name = $this->metadata['es_module'] ?? $data['module'];
        $bean = BeanFactory::getBean($module_name, $data['record_id']);
        if (empty($bean->id) || !$bean->ACLAccess('delete')) {
            return false;
        }
        $bean->mark_deleted($data['record_id']);
        return true;
    }

    protected function loadMetadataFile($module) {
        if (!empty($this->metadata)) {
            return;
        }
        $metadata_file = null;
        $defs_path = 'modules/' . $module . '/metadata/eslistviewdefs.php';
        if (file_exists('custom/' . $defs_path)) {
            $metadata_file = 'custom/' . $defs_path;
        } else if (file_exists($defs_path)) {
            $metadata_file = $defs_path;
        }
        if (!$metadata_file) {
            return;
        }
        require_once $metadata_file;
        $this->metadata = $ESListViewDefs[$module];
    }
}
