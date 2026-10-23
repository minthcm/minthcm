<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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


$dictionary['SchedulersJob'] = array('table' => 'job_queue',
    'comment' => 'Job queue keeps the list of the jobs executed by this instance',
	'doctrineEntity' => [],
	'fields' => array (
		'id' => array (
			'name' => 'id',
			'vname' => 'LBL_NAME',
			'type' => 'id',
			'len' => '36',
			'required' => true,
			'reportable'=>false,
			'comment' => 'Record unique identifier',
		),
	   'name'=>
	    array(
    	    'name'=>'name',
    	    'vname'=> 'LBL_NAME',
    	    'type'=>'name',
    	    'link' => true, // bug 39288
    		'dbType' => 'varchar',
    	    'len'=>255,
            'required'=>true,
            'comment' => 'The name or title of the record',
	    ),
		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
			'reportable'=>false,
			'comment' => 'A flag indicating whether the record has been deleted',
		),
		'date_entered' => array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required' => true,
			'comment' => 'The date when the record was created',
		),
		'date_modified' => array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required' => true,
			'comment' => 'The date when the record was last modified',
		),
		'scheduler_id' => array (
			'name' => 'scheduler_id',
			'vname' => 'LBL_SCHEDULER',
			'type' => 'id',
			'required' => false,
			'reportable' => false,
			'comment' => 'The unique identifier of the scheduler instance that initiated or manages this job execution',
		),
		'execute_time' => array (
			'name' => 'execute_time',
			'vname' => 'LBL_EXECUTE_TIME',
			'type' => 'datetime',
			'required' => true,
			'comment' => 'The timestamp when the job was actually executed or attempted to run',
		),
		'status' => array (
			'name' => 'status',
			'vname' => 'LBL_STATUS',
			'type' => 'enum',
			'options'	=> 'schedulers_times_dom',
			'len' => 20,
			'required' => true,
			'reportable' => true,
			'readonly' => true,
			'comment' => 'The current status of the record',
		),
		'resolution' => array (
			'name' => 'resolution',
			'vname' => 'LBL_RESOLUTION',
			'type' => 'enum',
			'options'	=> 'schedulers_resolution_dom',
			'len' => 20,
			'required' => true,
			'reportable' => true,
		    'readonly' => true,
		    'comment' => 'The final outcome or resolution status of the job execution (e.g., success, failure, or partial completion)',
		),
		'message' => array (
			'name' => 'message',
			'vname' => 'LBL_MESSAGE',
			'type' => 'text',
			'required' => false,
			'reportable' => false,
			'comment' => 'A descriptive message or log entry detailing the result, error, or status of the job execution',
		),
		'target' => array (
			'name' => 'target',
			'vname' => 'LBL_TARGET',
			'type' => 'varchar',
			'len' => 255,
			'required' => true,
			'reportable' => true,
			'comment' => 'The target module, record, or endpoint that the job was intended to process or affect',
		),
		'data' => array (
			'name' => 'data',
			'vname' => 'LBL_DATA',
			'type' => 'text',
			'required' => false,
			'reportable' => true,
			'comment' => 'Additional data or parameters associated with the job, such as input values or configuration details',
		),
		'requeue' => array (
			'name' => 'requeue',
			'vname' => 'LBL_REQUEUE',
			'type' => 'bool',
		    'default' => 0,
			'required' => false,
			'reportable' => true,
			'comment' => 'A flag indicating whether the job should be automatically requeued for retry after failure or interruption',
		),
		'retry_count' => array (
			'name' => 'retry_count',
			'vname' => 'LBL_RETRY_COUNT',
			'type' => 'tinyint',
			'dbType' => 'int',
			'required' => false,
			'reportable' => true,
			'comment' => 'The number of times the job has been retried due to previous failures or timeouts',
		),
		'failure_count' => array (
			'name' => 'failure_count',
			'vname' => 'LBL_FAIL_COUNT',
			'type' => 'tinyint',
			'dbType' => 'int',
			'required' => false,
			'reportable' => true,
		    'readonly' => true,
		    'comment' => 'The total count of execution failures for this job, used to determine retry limits or escalation',
		),
		'job_delay' => array (
			'name' => 'job_delay',
			'vname' => 'LBL_INTERVAL',
			'type' => 'int',
			'required' => false,
			'reportable' => false,
			'comment' => 'The configured delay period before the job is executed, allowing for scheduling or dependency management',
		),
		'client' => array (
			'name' => 'client',
			'vname' => 'LBL_CLIENT',
			'type' => 'varchar',
			'len' => 255,
			'required' => true,
			'reportable' => true,
			'comment' => 'The client or user context associated with the job, such as the initiating user or system component',
		),
		'percent_complete' => array (
			'name' => 'percent_complete',
			'vname' => 'LBL_PERCENT',
			'type' => 'int',
			'required' => false,
			'comment' => 'The percentage of completion for the job, indicating progress for long-running or multi-step tasks',
	    	),
	    'schedulers' => array (
			'name'            => 'schedulers',
			'vname'            => 'LBL_SCHEDULER_ID',
			'type'            => 'link',
			'module'          => 'Schedulers',
			'relationship'    => 'schedulers_jobs_rel',
			'source'        => 'non-db',
	        'link_type' => 'one',
	   ),
		),
	'indices' => array (
		array(
			'name' =>'job_queuepk',
			'type' =>'primary',
			'fields' => array(
				'id'
			)
		),
		array(
    		'name' =>'idx_status_scheduler',
    		'type'=>'index',
    		'fields' => array(
                'status',
	        	'scheduler_id',
   			)
   		),
		array(
    		'name' =>'idx_status_time',
    		'type'=>'index',
    		'fields' => array(
                'status',
				'execute_time',
				'date_entered',
   			)
   		),
		array(
    		'name' =>'idx_status_entered',
    		'type'=>'index',
    		'fields' => array(
                'status',
	        	'date_entered',
   			)
   		),
		array(
    		'name' =>'idx_status_modified',
    		'type'=>'index',
    		'fields' => array(
                'status',
	        	'date_modified',
   			)
   		),
   	),
);

VardefManager::createVardef('SchedulersJobs','SchedulersJob', array('assignable'));

