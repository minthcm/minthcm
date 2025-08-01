<?php
// Generated by the Entity Creator: 2025-06-09 11:19:24

/**
 *
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

namespace MintHCM\Api\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="email_marketing", indexes={
 *   @ORM\Index(name="emmkpk", columns={"id"}), 
 *   @ORM\Index(name="idx_emmkt_name", columns={"name"}), 
 *   @ORM\Index(name="idx_emmkit_del", columns={"deleted"})})
 */
class EmailMarketing
{
    /**
        * @ORM\Id
            * @ORM\Column(type="string", length="36")
            */
    public $id;

    /**
            * @ORM\Column(type="boolean")
            */
    public $deleted;

    /**
            * @ORM\Column(type="datetime")
            */
    public $date_entered;

    /**
            * @ORM\Column(type="datetime")
            */
    public $date_modified;

    /**
            * @ORM\Column(type="string", length="36")
            */
    public $modified_user_id;

    /**
            * @ORM\Column(type="string", length="36")
            */
    public $created_by;

    /**
            * @ORM\Column(type="string", length="255")
            */
    public $name;

    /**
            * @ORM\Column(type="string", length="100")
            */
    public $from_name;

    /**
            * @ORM\Column(type="string", length="100")
            */
    public $from_addr;

    /**
            * @ORM\Column(type="string", length="100")
            */
    public $reply_to_name;

    /**
            * @ORM\Column(type="string", length="100")
            */
    public $reply_to_addr;

    /**
            * @ORM\Column(type="string", length="36")
            */
    public $inbound_email_id;

    /**
            * @ORM\Column(type="datetime")
            */
    public $date_start;

    /**
            * @ORM\Column(type="string", length="36")
            */
    public $template_id;

    /**
            * @ORM\Column(type="string", length="100")
            */
    public $status;

    /**
            * @ORM\Column(type="string", length="36")
            */
    public $campaign_id;

    /**
            * @ORM\Column(type="string", length="36")
            */
    public $outbound_email_id;

    /**
            * @ORM\Column(type="boolean")
            */
    public $all_prospect_lists;

    /**
        * @ORM\JoinTable(name="securitygroups_records", joinColumns={@ORM\JoinColumn(name="record_id", referencedColumnName="id")}, inverseJoinColumns={@ORM\JoinColumn(name="securitygroup_id", referencedColumnName="id")})
        * @ORM\ManyToMany(targetEntity=SecurityGroups::class, inversedBy="securitygroups")
        */
    public Collection $SecurityGroups;

    /**
        * @ORM\JoinColumn(name="template_id", referencedColumnName="id")
        * @ORM\ManyToOne(targetEntity=EmailTemplates::class, inversedBy="email_marketing")
        */
    public $emailtemplate;

    /**
        * @ORM\JoinTable(name="email_marketing_prospect_lists")
        * @ORM\ManyToMany(targetEntity=ProspectLists::class, mappedBy="email_marketing")
        */
    public Collection $prospectlists;


public function __construct()
{
        $this->SecurityGroups = new ArrayCollection();
        $this->prospectlists = new ArrayCollection();
    }
}