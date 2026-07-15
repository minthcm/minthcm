<?php

/**
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2025 MintHCM
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
 */

namespace MintHCM\Api\EntityTraits;

trait PersonTrait
{
    public function getFullName(): string
    {
        $names = [];
        if (!empty($this->first_name)) {
            $names[] = $this->first_name;
        }
        if (!empty($this->last_name)) {
            $names[] = $this->last_name;
        }

        return !empty($names) ? implode(' ', $names) : '';
    }

    public function getName(): ?string
    {
        return $this->getFullName();
    }

    public function getEmail1(): string
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT ea.email_address
            FROM email_addresses ea
            INNER JOIN email_addr_bean_rel eabr
                ON eabr.email_address_id = ea.id
            WHERE eabr.bean_id = :bean_id
            AND eabr.bean_module = :bean_module
            AND eabr.primary_address = :primary_address
            AND eabr.deleted = :deleted
            LIMIT 1
        ';

        $module_name = $this->getModuleName();
        if (in_array($module_name, ['Employees'])) {
            $module_name = 'Users';
        }

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'bean_id' => $this->id,
            'bean_module' => $module_name,
            'primary_address' => 1,
            'deleted' => 0,
        ]);

        return $result->fetchOne() ?: '';
    }

    public function getSerialized(bool $json = false): array|string
    {
        $data = parent::getSerialized($json);
        $data['email1'] = $this->getEmail1();
        return $data;
    }
}
