<?php

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2024 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Api\Controllers;

use BeanFactory as LegacyBeanFactory;
use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Data\BeanFactory as MintBeanFactory;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;
use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;
use MintHCM\Lib\MintLogic\MintLogic;
use MintHCM\Utils\CyclicRecordsSaver;
use MintHCM\Utils\LegacyConnector;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

#[\AllowDynamicProperties]
class ModuleController
{
    protected EntityManagerInterface $entity_manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entity_manager = $entityManager;

        global $app_list_strings, $current_language;
        if (!$app_list_strings) {
            $app_list_strings = return_app_list_strings_language($current_language);
        }
    }

    public function detail(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'detail'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    function list(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'list'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);
        $links = $request->getAttribute("links") ?? [];
        $record_data = $request->getAttribute("record_data") ?: [];

        /** @var MintEntityRepository */
        $repository = $this->entity_manager->getRepository($module);

        /** @var MintEntity */
        $entity = $repository->getNewEntity();
        if (empty($entity)) {
            return $response->withStatus(404);
        }
        if (!$entity->hasAccess('edit')) {
            return $response->withStatus(403);
        }

        foreach ($record_data as $field_name => $value) {
            $entity->$field_name = $value;
        }

        $this->entity_manager->persist($entity);

        $repository->save($entity, false);
        $this->handleRelateFieldsFromRecordData($entity, $record_data);
        $this->handleLinks($entity, $links);

        if (!empty($record_data['repeat_type']) && '' != $record_data['repeat_type']) {
            $this->handleCyclicalRecords($entity);
        }

        $this->entity_manager->flush();

        if (empty($entity) || !$entity->getId()) {
            return $response->withStatus(500);
        }

        $response = $response->withStatus(201);
        $response->getBody()->write(json_encode($this->mergeRecordData($entity)));
        return $response;
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);

        $record_id = $request->getAttribute("id");
        $record_data = $request->getAttribute("record_data");
        $files = $request->getAttribute("files") ?? [];
        $links = $request->getAttribute("links") ?? [];

        /** @var MintEntityRepository */
        $entity_repository = $this->entity_manager->getRepository($module);

        /** @var MintEntity */
        $entity = !empty($record_id) ? $entity_repository->find($record_id) : $entity_repository->getNewEntity();

        if (empty($entity) || ($entity->getId() !== $record_id)) {
            return $response->withStatus(404);
        }

        if (!$entity->hasAccess('edit')) {
            return $response->withStatus(403);
        }

        foreach ($record_data as $field_name => $value) {
            $entity->$field_name = $value;
        }
        $this->entity_manager->persist($entity);

        $validationResult = (new MintLogic($entity->getMintBean()))->validateBean();
        if (!$validationResult['isValid']) {
            $response = $response->withStatus(422);
            $response->getBody()->write(json_encode($validationResult));
            return $response;
        }

        $this->handleFiles($entity, $files);
        $entity_repository->save($entity, false);
        $this->handleRelateFieldsFromRecordData($entity, $record_data);
        $this->handleLinks($entity, $links);

        if (!empty($record_data['repeat_type']) && '' != $record_data['repeat_type']) {
            $this->handleCyclicalRecords($entity);
        }

        $this->entity_manager->flush();

        if (!empty($record_id) && $entity->getId() !== $record_id) {
            return $response->withStatus(500);
        }

        $response = $response->withStatus(200);
        $response->getBody()->write(json_encode($this->mergeRecordData($entity)));
        return $response;
    }

    public function getRecord(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $module = $this->getModuleFromRoute($request);
        $record_id = $request->getAttribute("id");

        /** @var MintEntityRepository */
        $entity_repository = $this->entity_manager->getRepository($module);

        /** @var MintEntity */
        $entity = !empty($record_id) ? $entity_repository->find($record_id) : $entity_repository->getNewEntity();
        if (!$entity || $entity->getId() !== $record_id) {
            return $response->withStatus(404);
        }
        $links = $request->getAttribute("links");
        $relation_list = [];
        foreach($links as $link){
            $bean = MintBeanFactory::getBean($module, $record_id);
            if($bean->load_relationship($link)){
                $related_beans = $bean->$link->getBeans();
                foreach($related_beans as $record){
                    $record->fill_in_additional_detail_fields();
                    $relation_list[$link][$record->id] = [
                        'id' => $record->id,
                        'module' => $record->module_name,
                        'attributes' => $record->toArray(),
                        'acl_access' => [
                            'edit' => $record->ACLAccess('edit'),
                            'delete' => $record->ACLAccess('delete'),
                            'view' => $record->ACLAccess('view'),
                        ],
                    ];
                }
            }
        }
        if (!$entity->hasAccess('view')) {
            return $response->withStatus(403);
        }

        $record_data = !empty($entity) && $entity->id === $record_id ? $this->mergeRecordData($entity, $relation_list) : null;
        $bean = MintBeanFactory::getBean($module, $record_id);
        $class_metadata = $this->entity_manager->getClassMetadata(get_class($entity));
        foreach ($bean->field_defs as $field => $defs) {
            // Fields mapped as Doctrine associations (ManyToOne/JoinColumn without a separate @Column)
            // are not serialized as scalars by getSerialized() — fetch them from the legacy bean instead.
            // Non-db, non-link fields (e.g. relate/computed) are absent from the entity entirely.
            // Only scalar associations (ManyToOne/OneToOne) are included — Collection types (ManyToMany/OneToMany)
            // must not be fetched from the legacy bean as null, as they cannot be safely written back during update.
            $isScalarAssociation = $class_metadata->hasAssociation($field)
                && in_array($class_metadata->getAssociationMapping($field)['type'], [
                    \Doctrine\ORM\Mapping\ClassMetadata::MANY_TO_ONE,
                    \Doctrine\ORM\Mapping\ClassMetadata::ONE_TO_ONE,
                ]);
            if (
                $isScalarAssociation
                || (
                    ($defs['source'] ?? '') === 'non-db'
                    && ($defs['type'] ?? '') !== 'link'
                    && ($defs['name'] ?? '') !== 'email1'
                )
            ) {
                $record_data['attributes'][$field] = $bean->$field;
            }
        }
        $response->getBody()->write(json_encode($record_data));
        return $response;
    }

    public function getRecordLogic(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $record_id = $request->getAttribute("id");
        $attributes = $request->getAttribute("attributes");
        $triggerFields = $request->getAttribute("triggerFields");

        /** @var MintEntityRepository */
        $repository = $this->entity_manager->getRepository($module);

        /** @var MintEntity */
        $entity = !empty($record_id) ? $repository->find($record_id) : $repository->getNewEntity();

        if (empty($entity)) {
            return $response->withStatus(404);
        }
        $bean_attributes = [];
        foreach ($attributes as $field => $value) {
            if (!property_exists($entity, $field)) {
                $bean_attributes[$field] = $value;
                continue;
            }
            $entity->{$field} = $value;
        }

        $mint_bean = $entity->getMintBean();
        foreach($bean_attributes as $field => $value){
            $mint_bean->$field = $value;
        }
        $result = (new MintLogic($mint_bean))->getChanged($triggerFields);
        $response->getBody()->write(json_encode($result));
        return $response;
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');

        /** @var MintEntityRepository */
        $repository = $this->entity_manager->getRepository($module);

        /** @var MintEntity */
        $entity = $repository->find($id);
        if (empty($entity) || $entity->getId() !== $id) {
            return $response->withStatus(404);
        }
        if (!$entity->hasAccess('delete')) {
            return $response->withStatus(403);
        }

        if (!$repository->delete($entity, true)) {
            $response = $response->withStatus(500);
            return $response;
        }

        $response = $response->withStatus(200);
        return $response;
    }

    public function subpanelRecords(Request $request, Response $response, array $args): Response
    {
        //TODO add relationship management in Entity to get subpanel list with acls
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        chdir('../legacy/');
        $focus = LegacyBeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        $related_name = $request->getAttribute('relation_name');
        $page = $request->getQueryParams()['page'] ?? 0;
        $records_per_page = $request->getQueryParams()['paginate_by'] ?? -1;
        $_REQUEST['sort_order'] = !empty($request->getAttribute('sortOrder')) ? $request->getAttribute('sortOrder') : 'asc';
        $_REQUEST['subpanel_sort_by'] = !empty($request->getAttribute('sortBy')) ? $request->getAttribute('sortBy') : 'id';
        require_once 'include/SubPanel/SubPanelDefinitions.php';
        $spd = new \SubPanelDefinitions($focus, $module);
        if (isset($spd->layout_defs['subpanel_setup'][$related_name])) {

            $target_module = $spd->layout_defs['subpanel_setup'][$related_name]['module'];
            $target_bean = LegacyBeanFactory::getBean($target_module);
            if (!$target_bean || !$target_bean->ACLAccess('list')) {
                return $response->withStatus(403);
            }

            require_once 'include/ListView/ListViewSubPanel.php';
            $list_view = new \ListViewSubPanel();
            $subpanel_def = $spd->load_subpanel($related_name);
            $data = $list_view->process_dynamic_listview($module, $focus, $subpanel_def, true, $page, $records_per_page);
            $list = $data['list'];
            chdir('../api/');
            $response = $response->withStatus(200);
            $return_list = [];
            foreach ($list as $record_id => $record) {
                $record->fill_in_additional_detail_fields();
                $return_list[$record_id] = [
                    'id' => $record->id,
                    'module' => $record->module_name,
                    'attributes' => $record->toArray(),
                    'acl_access' => [
                        'edit' => $record->ACLAccess('edit'),
                        'delete' => $record->ACLAccess('delete'),
                        'view' => $record->ACLAccess('view'),
                    ],
                ];
            }
            $return_list['total'] = $data['row_count'];
            $return_list['page'] = (int) $page;
            $response->getBody()->write(json_encode($return_list));
            return $response;
        }
        chdir('../api/');
        $response = $response->withStatus(404);
        return $response;
    }

    public function link(Request $request, Response $response, array $args): Response
    {
        //TODO add relationship management in Entity
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        $link_name = $request->getAttribute('link_name');

        chdir('../legacy/');
        $focus = LegacyBeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$focus->ACLAccess('edit')) {
            $response = $response->withStatus(403);
            return $response;
        }
        $ids = $request->getAttribute('ids');

        if (!$focus->load_relationship($link_name) || empty($ids)) {
            $response = $response->withStatus(400);
            return $response;
        }
        $errors = [];
        foreach ($ids as $related_id) {
            $result = $focus->$link_name->add($related_id);
            if (!$result) {
                $errors[] = 'Failed to link ' . $related_id . ' to ' . $focus->id . ' via ' . $link_name;
            }
        }

        chdir('../api/');

        if (!empty($errors)) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response;
        }

        $response = $response->withStatus(200);
        return $response;
    }

    public function unlink(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        $link_name = $request->getAttribute('link_name');

        chdir('../legacy/');
        $focus = LegacyBeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$focus->ACLAccess('edit')) {
            $response = $response->withStatus(403);
            return $response;
        }
        $ids = $request->getAttribute('ids');

        if (!$focus->load_relationship($link_name) || empty($ids)) {
            $response = $response->withStatus(400);
            return $response;
        }
        $errors = [];
        foreach ($ids as $related_id) {
            $result = $focus->$link_name->delete($id, $related_id);
            if (!$result) {
                $errors[] = 'Failed to unlink ' . $related_id . ' from ' . $focus->id . ' via ' . $link_name;
            }
        }

        chdir('../api/');

        if (!empty($errors)) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response;
        }

        $response = $response->withStatus(200);
        return $response;
    }

    protected function getModuleFromRoute(Request $request): ?string
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        return explode('/', $route->getPattern())[1] ?? null;
    }

    protected function mergeRecordData(MintEntity $entity, $related_records = []): array
    {
        return [
            'id' => $entity->getId(),
            'module' => $entity->getModuleName(),
            'attributes' => $entity->getSerialized(),
            'acl_access' => [
                'edit' => $entity->hasAccess('edit'),
                'delete' => $entity->hasAccess('delete'),
                'view' => $entity->hasAccess('view'),
                'admin' => $entity->hasAccess('admin'),
            ],
            'logic' => (new MintLogic($entity->getMintBean()))->getInitial(),
            'related_records' => $related_records
        ];
    }
    protected function handleFiles(MintEntity $mint_entity, array | null $files = []): void
    {
        if (empty($files)) {
            return;
        }

        global $sugar_config;

        $bean = $mint_entity->getMintBean();
        if (empty($bean->id)) {
            return;
        }

        $upload_dir = $sugar_config['upload_dir'] ?? 'upload/';
        foreach ($files as $field_name => $base64) {
            $field_type = $bean->field_defs[$field_name]['type'] ?? '';
            if (!in_array($field_type, ['file', 'image'])) {
                continue;
            }

            $file_name = 'image' === $field_type ? $bean->id . "_{$field_name}" : $bean->id;
            $file_name = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $file_name); // Sanitize file name

            if (empty($base64)) {
                unlink($upload_dir . $file_name);
                continue;
            }

            $base64_prefix = '';
            if (strpos($base64, 'data:') === 0) {
                $base64_prefix = substr($base64, 0, strpos($base64, ';base64,') + 8);
            }
            $base64_decoded = base64_decode(str_replace($base64_prefix, '', $base64), true);

            $tmp_file = tmpfile();
            fwrite($tmp_file, $base64_decoded);
            $tmp_file_path = stream_get_meta_data($tmp_file)['uri'];
            $_FILES[$field_name] = [
                'name' => $file_name,
                'type' => 'application/octet-stream',
                'tmp_name' => $tmp_file_path,
                'error' => 0,
                'size' => strlen($base64_decoded),
            ];
            $_FILES['filename_file'] = $file_name;
            $upload_file = new LegacyConnector('UploadFile', 'include/upload_file.php', [$field_name]);
            $upload_file->set_is_http_upload(false);
            if ($upload_file->confirm_upload()) {
                $upload_file->final_move($file_name, $field_name);
            }
            fclose($tmp_file);
        }
    }

    protected function handleRelateFieldsFromRecordData(MintEntity $entity, array $record_data): void
    {
        $bean = $entity->getMintBean(false);
        if (empty($bean) || empty($bean->field_defs)) {
            return;
        }

        // Build map: id_name => relate field def, for relate fields with save: true
        $relate_fields_by_id_name = [];
        foreach ($bean->field_defs as $field_def) {
            if (($field_def['type'] ?? '') === 'relate'
                && !empty($field_def['save'])
                && !empty($field_def['id_name'])
                && !empty($field_def['link'])) {
                $relate_fields_by_id_name[$field_def['id_name']] = $field_def;
            }
        }

        $synthesized_links = [];
        foreach ($record_data as $field_name => $value) {
            if (!isset($relate_fields_by_id_name[$field_name])) {
                continue;
            }

            $link_name = $relate_fields_by_id_name[$field_name]['link'];

            // Get current related IDs via legacy relationship to handle one-to-one replace
            $existing_ids = [];
            if ($bean->load_relationship($link_name)) {
                $existing_ids = array_values($bean->$link_name->get() ?? []);
            }

            $link_ops = [];
            if (!empty($value)) {
                // Remove existing entries that differ from the new value (one-to-one replace)
                $ids_to_remove = array_values(array_filter($existing_ids, fn($id) => $id !== $value));
                if (!empty($ids_to_remove)) {
                    $link_ops['beansToRemove'] = $ids_to_remove;
                }
                $link_ops['beansToAdd'] = [$value => []];
            } else {
                // Clearing: remove all existing entries
                if (!empty($existing_ids)) {
                    $link_ops['beansToRemove'] = $existing_ids;
                }
            }

            if (!empty($link_ops)) {
                $synthesized_links[$link_name] = $link_ops;
            }
        }

        if (!empty($synthesized_links)) {
            $this->handleLinks($entity, $synthesized_links);
        }
    }

    protected function handleLinks(MintEntity $mint_entity, array $links = []): void
    {
        if (empty($links)) {
            return;
        }

        $bean = $mint_entity->getMintBean();
        foreach ($links as $link_name => $link_data) {
            if (empty($link_data)) {
                continue;
            }
            if (!$bean->load_relationship($link_name)) {
                $GLOBALS['log']->error("Failed to load relationship {$link_name} for module {$bean->module_name} and record {$bean->id}");
                continue;
            }
            if (!empty($link_data['beansToAdd']) && is_array($link_data['beansToAdd'])) {
                foreach ($link_data['beansToAdd'] as $related_id => $related_bean) {
                    $additionalValues = $related_bean['additionalValues'] ?? [];
                    $bean->$link_name->add($related_id, $additionalValues);
                }
            }
            if (!empty($link_data['beansToRemove']) && is_array($link_data['beansToRemove'])) {
                foreach ($link_data['beansToRemove'] as $related_id) {
                    $bean->$link_name->delete($bean->id, $related_id);
                }
            }
        }
    }

    public function getChecklistItems(Request $request, Response $response, array $args): Response
    {
        $module = $this->getModuleFromRoute($request);
        $id = $request->getAttribute('id');
        $focus = MintBeanFactory::getBean($module, $id);
        if (empty($focus->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        $response = $response->withHeader('Content-type', 'application/json');
        if (isset($focus->checklist) && !empty($focus->checklist)) {
            if (!array($focus->checklist)) {
                $checklistRaw = html_entity_decode($focus->checklist, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $data = json_decode($checklistRaw, true);
            } else {
                $data = $focus->checklist;
            }
            $response->getBody()->write($data);
            return $response;
        }
        $data = [];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    protected function handleCyclicalRecords(MintEntity $mint_entity)
    {
        (new CyclicRecordsSaver($mint_entity->getMintBean(), $this->entity_manager))->run();
    }
}
