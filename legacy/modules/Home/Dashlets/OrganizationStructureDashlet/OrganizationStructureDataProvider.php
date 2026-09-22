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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Provides flat employee data for the org chart.
 *
 * Hierarchy is built solely on reports_to_id.
 * Security groups and positions are optional enrichments — their absence
 * never breaks the chart.
 */
class OrganizationStructureDataProvider
{
    /**
     * Returns a flat array of employee nodes ready for d3-org-chart.
     *
     * Each element:
     *   id             — employee user id
     *   parentId       — reports_to_id if that employee exists, otherwise null
     *   name           — "First Last"
     *   position       — position name or null
     *   department     — security group name or null
     *   departmentType — group_type (department/team/business_unit) or null
     *   photoUrl       — download URL for photo or null
     *   detailUrl      — link to employee detail view
     *
     * @param string $virtualRootName Label used for the synthetic root node
     *                                injected when multiple root employees exist.
     * @return array
     */
    public function getData(string $virtualRootName = 'Organisation'): array
    {
        global $sugar_config;

        $siteURL   = rtrim($sugar_config['site_url'] ?? '', '/');
        $employees = $this->fetchEmployees();

        if (empty($employees)) {
            return [];
        }

        $securityGroups = $this->fetchSecurityGroups();

        return $this->buildNodes($employees, $securityGroups, $siteURL, $virtualRootName);
    }

    /**
     * Fetches active employees with their optional position name.
     *
     * @return array  Associative array keyed by user id.
     */
    protected function fetchEmployees(): array
    {
        global $db;

        $sql = "
            SELECT
                u.id,
                u.first_name,
                u.last_name,
                u.reports_to_id,
                u.photo,
                u.securitygroup_id,
                p.name AS position_name
            FROM users u
            LEFT JOIN positions p ON p.id = u.position_id AND p.deleted = 0
            WHERE u.deleted = 0
              AND u.employee_status IN ('Active', 'during_termination')
              AND (u.first_name IS NOT NULL OR u.last_name IS NOT NULL)
        ";

        $result    = $db->query($sql);
        $employees = [];
        while ($row = $db->fetchByAssoc($result)) {
            $employees[$row['id']] = $row;
        }

        return $employees;
    }

    /**
     * Fetches security groups that are meaningful for an org chart.
     * standard / private / company / other types are intentionally ignored.
     *
     * @return array  Associative array keyed by group id.
     */
    protected function fetchSecurityGroups(): array
    {
        global $db;

        $sql = "
            SELECT sg.id, sg.name, sg.group_type
            FROM securitygroups sg
            WHERE sg.deleted = 0
              AND sg.group_type IN ('department', 'team', 'business_unit')
        ";

        $result         = $db->query($sql);
        $securityGroups = [];
        while ($row = $db->fetchByAssoc($result)) {
            $securityGroups[$row['id']] = $row;
        }

        return $securityGroups;
    }

    /**
     * Builds the flat node array consumed by d3-org-chart.
     *
     * When multiple employees have no manager in the dataset, a virtual root
     * node is prepended and all orphans are re-parented to it so that
     * d3-org-chart receives exactly one root (parentId === null).
     *
     * @param array  $employees      Keyed by user id (from fetchEmployees()).
     * @param array  $securityGroups Keyed by group id (from fetchSecurityGroups()).
     * @param string $siteURL        Base URL without trailing slash.
     * @param string $virtualRootName Label for the synthetic root node.
     * @return array
     */
    /**
     * Returns ids that are part of a cycle in the reports_to_id graph.
     * A node is cyclic if following its ancestors eventually leads back to itself.
     */
    protected function detectCyclicIds(array $employees): array
    {
        $cyclic  = [];
        $visited = []; // 0 = unvisited, 1 = in-progress, 2 = done

        foreach (array_keys($employees) as $startId) {
            if (isset($visited[$startId])) {
                continue;
            }

            $path     = [];
            $pathSet  = [];
            $currentId = $startId;

            while ($currentId !== null && !isset($visited[$currentId])) {
                if (isset($pathSet[$currentId])) {
                    // Found a cycle — mark all nodes in the cycle
                    $inCycle = false;
                    foreach ($path as $nodeId) {
                        if ($nodeId === $currentId) {
                            $inCycle = true;
                        }
                        if ($inCycle) {
                            $cyclic[$nodeId] = true;
                        }
                    }
                    break;
                }

                $path[]             = $currentId;
                $pathSet[$currentId] = true;
                $nextId             = $employees[$currentId]['reports_to_id'] ?? null;
                $currentId          = (!empty($nextId) && isset($employees[$nextId])) ? $nextId : null;
            }

            foreach ($path as $nodeId) {
                $visited[$nodeId] = 2;
            }
        }

        return $cyclic;
    }

    protected function buildNodes(
        array $employees,
        array $securityGroups,
        string $siteURL,
        string $virtualRootName
    ): array {
        $output    = [];
        $rootCount = 0;
        $cyclicIds = $this->detectCyclicIds($employees);

        foreach ($employees as $id => $emp) {
            // parentId is null when reports_to_id is empty, points to a
            // non-existing / non-active employee, or is part of a cycle.
            $parentId = null;
            if (
                !empty($emp['reports_to_id'])
                && isset($employees[$emp['reports_to_id']])
                && !isset($cyclicIds[$id])
            ) {
                $parentId = $emp['reports_to_id'];
            } else {
                $rootCount++;
            }

            // photoUrl is null when no photo has been uploaded.
            $photoUrl = null;
            if (!empty($emp['photo'])) {
                $photoUrl = $siteURL . '/index.php?entryPoint=download&type=Users&id=' . $id . '_photo';
            }

            // Department from security group — purely optional enrichment.
            $department     = null;
            $departmentType = null;
            if (!empty($emp['securitygroup_id']) && isset($securityGroups[$emp['securitygroup_id']])) {
                $sg             = $securityGroups[$emp['securitygroup_id']];
                $department     = $sg['name'];
                $departmentType = $sg['group_type'];
            }

            $output[] = [
                'id'             => $id,
                'parentId'       => $parentId,
                'name'           => trim(html_entity_decode($emp['first_name'], ENT_QUOTES | ENT_HTML5, 'UTF-8') . ' ' . html_entity_decode($emp['last_name'], ENT_QUOTES | ENT_HTML5, 'UTF-8')),
                'position'       => $emp['position_name'] ? html_entity_decode($emp['position_name'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : null,
                'department'     => $department ? html_entity_decode($department, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null,
                'departmentType' => $departmentType,
                'photoUrl'       => $photoUrl,
                'detailUrl'      => $siteURL . '/legacy/index.php?module=Employees&action=DetailView&record=' . $id,
            ];
        }

        if ($rootCount > 1) {
            $virtualRootId = '__org_root__';
            array_unshift($output, [
                'id'             => $virtualRootId,
                'parentId'       => null,
                'name'           => $virtualRootName,
                'position'       => null,
                'department'     => null,
                'departmentType' => null,
                'photoUrl'       => null,
                'detailUrl'      => null,
                'isVirtualRoot'  => true,
            ]);

            foreach ($output as &$node) {
                if ($node['id'] !== $virtualRootId && $node['parentId'] === null) {
                    $node['parentId'] = $virtualRootId;
                }
            }
            unset($node);
        }

        return $output;
    }
}
