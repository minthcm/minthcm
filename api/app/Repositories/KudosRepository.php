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
 * Copyright (C) 2018-2023 MintHCM
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

namespace MintHCM\Api\Repositories;

use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;

class KudosRepository extends MintEntityRepository
{
    public function getDrawerKudoses($page, $list_type)
    {
        global $current_user;

        $basic_select = 'CASE
                            WHEN kudos.announcement_date IS NULL THEN 1
                            ELSE 2
                        END AS HIDDEN announcement_order,
                        CASE
                            WHEN kudos.announcement_date IS NULL THEN kudos.date_entered
                            ELSE kudos.announcement_date
                        END AS HIDDEN date_order';
        
        $select_string = $list_type !== 'all' 
                        ? 'CASE
                                WHEN (alerts.is_read = 0 OR alerts.id IS NULL) THEN 1
                                ELSE 2 
                            END AS HIDDEN alert_order,' . $basic_select 
                        : $basic_select;
        
        $qb = $this->createQueryBuilder('kudos')
            ->addSelect('employee')
            ->innerJoin('kudos.employee_link', 'employee', 'WITH', 'employee.deleted = 0 AND employee.status = \'active\'')
            ->addSelect('assigned_user')
            ->innerJoin('kudos.assigned_user_link', 'assigned_user', 'WITH', 'assigned_user.deleted = 0 AND assigned_user.status = \'active\'')
            ->addSelect('reactions')
            ->leftJoin('kudos.reactions', 'reactions', 'WITH', 'reactions.deleted = 0')
            ->addSelect('alerts')
            ->leftJoin('kudos.alerts', 'alerts', 'WITH', "alerts.assigned_user_id = '{$current_user->id}'")
            ->addSelect($select_string)
        ;

        switch ($list_type) {
            case 'received':
                $qb->andWhere('kudos.announced = 1 AND kudos.employee_id = :current_user_id')
                    ->orderBy('alert_order', 'ASC');
                break;
            case 'given':
                $qb->andWhere('kudos.assigned_user_id = :current_user_id')
                    ->orderBy('alert_order', 'ASC');
                break;
            default:
                $qb->andWhere('((kudos.announced IS NULL OR kudos.announced = 0) AND kudos.assigned_user_id = :current_user_id) OR kudos.announced = 1');
                break;
        }

        $qb->addOrderBy('announcement_order', 'ASC')
            ->addOrderBy('date_order', 'DESC')
            ->addOrderBy('employee.last_name', 'ASC')
            ->setFirstResult($page == 1 ? 0 : 20 * ($page - 1))
            ->setMaxResults(20)
        ;
        
        $qb->setParameter('current_user_id', $current_user->id);

        return $qb->getQuery()->getResult();
    }
}
