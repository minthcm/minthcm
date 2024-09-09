<?php

use MassUpdate;
use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\UI\SearchThrowableHandler;

require_once 'include/ESListView/ESListViewGetRecords.php';
require_once 'lib/Search/ElasticSearch/ElasticSearchIndexer.php';

class ESListViewController
{

    protected $bean, $query, $per_page, $page, $engine, $options, $metadata, $module_name, $acl_module_name;

    public function __construct(SugarBean | null $bean)
    {
        $this->bean = $bean;
    }

    //temp
    public function getInitialData(array $options): array
    {
        $view = new ViewESList();
        $module = $options['module'];
        $view->module = $module;
        $view->seed = BeanFactory::newBean($module);
        $view->bean = BeanFactory::newBean($module);
        $data = $view->getInitialData();
        $preferences = json_decode($data->preferences, true);
        return [
            'config' => json_decode($data->config, true),
            'defs' => json_decode($data->defs, true),
            'module' => $data->module,
            'preferences' => $preferences,
        ];
    }

    public function massUpdate(): void
    {
        require_once 'include/MassUpdate.php';
        $_POST['mass'] = $_POST['IDs'];
        $_REQUEST['massupdate'] = true;
        $updater = new MassUpdate();
        $updater->setSugarBean($this->bean);
        if ('delete' === $_POST['action_name']) {
            $_POST['Delete'] = true;
        }
        $updater->handleMassUpdate();

        echo json_encode(['success' => true]);
    }

    public function getResults(array $options): array | bool
    {
        $this->loadMetadataFile($options['module']);
        if (ACLController::checkAccess($this->acl_module_name, 'list', true)) {
            $get_records = new ESListViewGetRecords($this->metadata, $this->module_name, $options['itemsPerPage'], $options['offset'], $options['page'], $options['sortBy'], $options['sortOrder'], [
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

    public function handleThrowable(Throwable $throwable, SearchQuery $query): void
    {
        $handler = new SearchThrowableHandler($throwable, $query);
        $handler->handle();
    }

    public function savePreferences(array $data): bool
    {
        global $current_user;
        $module = $data['module'];
        $preferences = $data['preferences'];
        if (!empty($preferences) && is_array($preferences) && !empty($module)) {
            (new UserPreference($current_user))->setPreference($module, $preferences, 'eslist');
        }
        return true;
    }

    public function updatePreferences(array &$options)
    {
        if (empty($options['module']) || empty($options['itemsPerPage'])) {
            return false;
        }
        global $current_user;
        $preferences = (new UserPreference($current_user))->getPreference($options['module'], 'eslist');
        if (
            empty($preferences['items_per_page'])
            || $options['itemsPerPage'] != $preferences['items_per_page']
        ) {
            $preferences['items_per_page'] = $options['itemsPerPage'];
            $this->savePreferences([
                'module' => $options['module'],
                'preferences' => $preferences,
            ]);
        }

    }

    public function deleteRecord(array $data): bool
    {
        if (empty($data['module']) || empty($data['record_id'])) {
            return false;
        }
        $this->loadMetadataFile($data['module']);
        $bean = BeanFactory::getBean($this->module_name, $data['record_id']);
        if (empty($bean->id) || !$bean->ACLAccess('delete')) {
            return false;
        }
        $bean->mark_deleted($data['record_id']);
        return true;
    }

    protected function loadMetadataFile(string $module): void
    {
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
        $this->acl_module_name = $acl_module_name ?? $module;
        $this->module_name = $module_name ?? $module;
        $this->metadata = $ESListViewDefs[$module];
    }
}
