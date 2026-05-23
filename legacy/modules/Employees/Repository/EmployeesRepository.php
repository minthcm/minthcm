<?php

class EmployeesRepository
{
    public static function getDuplicatedCandidatesEmployeesRecordsIds($bean)
    {
        $bean->phone_number_short = substr($bean->phone_mobile, -9);
        $db = DBManagerFactory::getInstance();
        $result_employees = $db->query(static::getDuplicateQueryEmployees($bean));
        $result_candidates = $db->query(static::getDuplicateQueryCandidates($bean));
        $result_candidate_employee = $db->query(static::getCandidateEmployee($bean));
        $rows = [];
        $candidate_employee_id = '';
        while (($row = $db->fetchByAssoc($result_candidate_employee)) != null) {
            if (!isset($rows[$row['id']]) && $bean->id != $rows[$row['id']]) {
                $candidate_employee_id = $row['id'];
            }
        }
        while (($row = $db->fetchByAssoc($result_employees)) != null) {
            if (!isset($rows[$row['id']]) && $bean->id != $rows[$row['id']]) {
                $rows[] = $row['id'];
            }
        }
        while (($row = $db->fetchByAssoc($result_candidates)) != null) {
            if (!isset($rows[$row['id']]) && $bean->id != $rows[$row['id']] && $row['id'] !== $candidate_employee_id) {
                $rows[] = $row['id'];
            }
        }
        return $rows;
    }

    public static function retrieveEmployee($id)
    {
        $db = DBManagerFactory::getInstance();
        $result = $db->query("SELECT id from users where id='{$id}' AND deleted=0 AND users.show_on_employees = 1 AND employee_status IN ('Active', 'during_termination')", false, "Error retrieving employee ID: ");
        $row = $db->fetchByAssoc($result);
        return $row['id'];
    }

    protected static function getDuplicateQueryEmployees($bean)
    {
        $duplicated_employees = "SELECT 
                                    users.id
                                FROM 
                                    email_addr_bean_rel as er
                                JOIN 
                                    email_addresses as ea ON er.email_address_id = ea.id
                                JOIN 
                                    users ON users.id = er.bean_id
                                WHERE
                                    users.show_on_employees = 1
                                    AND users.id != '{$bean->id}'
                                    AND
                                    (
                                        (
                                            ea.email_address = '{$bean->email1}'
                                            AND users.first_name = '{$bean->first_name}'
                                            AND users.last_name = '{$bean->last_name}'
                                        )
                                        OR
                                        (
                                            SUBSTRING(users.phone_mobile, -9) = '{$bean->phone_number_short}'
                                            AND users.first_name = '{$bean->first_name}'
                                            AND users.last_name = '{$bean->last_name}'
                                        )
                                    )
        ";
        $GLOBALS['log']->fatal($duplicated_employees);
        return $duplicated_employees;
    }

    protected static function getCandidateEmployee($bean)
    {
        $candidate_employee_query = "SELECT 
                                    candidates.id
                                FROM 
                                    email_addr_bean_rel as er
                                JOIN 
                                    email_addresses as ea ON er.email_address_id = ea.id
                                JOIN 
                                    candidates ON candidates.id = er.bean_id
                                JOIN 
                                    candidates_employees ON candidates_employees.candidate_id = candidates.id
                                WHERE 
                                    candidates_employees.candidate_id = '{$bean->id}'
                                    AND
                                    (
                                        (
                                            ea.email_address = '{$bean->email1}'
                                            AND candidates.first_name = '{$bean->first_name}'
                                            AND candidates.last_name = '{$bean->last_name}'
                                        )
                                        OR
                                        (
                                            SUBSTRING(candidates.phone_mobile, -9) = '{$bean->pphone_number_shorthone_mobile}'
                                            AND candidates.first_name = '{$bean->first_name}'
                                            AND candidates.last_name = '{$bean->last_name}'
                                        )
                                    )
                            ";
        return $candidate_employee_query;
    }

    protected static function getDuplicateQueryCandidates($bean)
    {
        $duplicated_candidates = "SELECT 
                                    candidates.id
                                FROM 
                                    email_addr_bean_rel as er
                                JOIN 
                                    email_addresses as ea ON er.email_address_id = ea.id
                                JOIN 
                                    candidates ON candidates.id = er.bean_id
                                WHERE 
                                    candidates.deleted = 0
                                    AND
                                    (
                                        (
                                            ea.email_address = '{$bean->email1}'
                                            AND candidates.first_name = '{$bean->first_name}'
                                            AND candidates.last_name = '{$bean->last_name}'
                                        )
                                        OR
                                        (
                                            SUBSTRING(candidates.phone_mobile, -9) = '{$bean->phone_number_short}'
                                            AND candidates.first_name = '{$bean->first_name}'
                                            AND candidates.last_name = '{$bean->last_name}'
                                        )
                                    )
        ";
        return $duplicated_candidates;
    }
}
