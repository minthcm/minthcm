<?php

if (! defined('sugarEntry') || ! sugarEntry) {
    die('Not A Valid Entry Point');
}

class CallsListViewSubPanel extends ListViewSubPanel
{

    public function process_dynamic_listview_rows($data, $parent_data, $smartyTemplateSection, $html_varName, $subpanel_def)
    {
        global $subpanel_item_count;
        global $odd_bg;
        global $even_bg;
        global $hilite_bg;
        global $click_bg;

        //viewTools start #36866 #40449 #59582
        global $sugar_config;
        if (! isset($sugar_config['subpanel_count_method']) || 'not_count' != $sugar_config['subpanel_count_method']) {
            global $db;
            $count_query = "SELECT count(*) count FROM (" . $this->clear_query;
            if (isset($sugar_config['subpanel_count_method']) && 'limited_count' == $sugar_config['subpanel_count_method']) {
                $count_query .= " LIMIT 11";
                $this->smartyTemplate->assign("LIMITED_COUNT", true);
            }
            $count_query .= ") t";
            $res = $db->fetchOne($count_query);
            $this->smartyTemplate->assign("COUNT_OF_ROWS", $res['count']);
        }
        //viewTools end #36866 #40449 #59582
        $widget_contents = [];
        $button_contents = [];

        $this->smartyTemplate->assign("BG_HILITE", $hilite_bg);
        $this->smartyTemplate->assign('CHECKALL', SugarThemeRegistry::current()->getImage('blank', '', 1, 1, ".gif", ''));
        //$this->smartyTemplate->assign("BG_CLICK", $click_bg);
        $subpanel_item_count = 0;
        $oddRow              = true;
        $count               = 0;
        reset($data);

        //GETTING OFFSET
        $offset = ($this->getOffset($html_varName)) === false ? 0 : $this->getOffset($html_varName);
        //$totaltime = 0;
        $processed_ids = [];

        $fill_additional_fields = [];
        //Either retrieve the is_fill_in_additional_fields property from the lone
        //subpanel or visit each subpanel's subpanels to retrieve the is_fill_in_addition_fields
        //property
        $subpanel_list = [];
        if ($subpanel_def->isCollection()) {
            $subpanel_list = $subpanel_def->sub_subpanels;
        } else {
            $subpanel_list[] = $subpanel_def;
        }

        foreach ($subpanel_list as $this_subpanel) {
            if ($this_subpanel->is_fill_in_additional_fields()) {
                $fill_additional_fields[]                          = $this_subpanel->bean_name;
                $fill_additional_fields[$this_subpanel->bean_name] = true;
            }
        }

        if (empty($data)) {
            $thepanel = $subpanel_def;
            if ($subpanel_def->isCollection()) {
                $thepanel = $subpanel_def->get_header_panel_def();
            }
        }

        foreach ($data as $aVal => $aItem) {
            $widget_contents[$aVal] = [];
            $subpanel_item_count++;
            $aItem->check_date_relationships_load();

            if (! empty($fill_additional_fields[$aItem->object_name]) || ('Case' == $aItem->object_name && ! empty($fill_additional_fields['aCase']))) {
                $aItem->fill_in_additional_list_fields();
            }
            $aItem->call_custom_logic("process_record");

            if (isset($parent_data[$aItem->id])) {
                $aItem->parent_name = $parent_data[$aItem->id]['parent_name'];
                if (! empty($parent_data[$aItem->id]['parent_name_owner'])) {
                    $aItem->parent_name_owner = $parent_data[$aItem->id]['parent_name_owner'];
                    $aItem->parent_name_mod   = $parent_data[$aItem->id]['parent_name_mod'];
                }
            }
            $fields = $aItem->get_list_view_data();
            if (isset($processed_ids[$aItem->id])) {
                continue;
            } else {
                $processed_ids[$aItem->id] = 1;
            }

            //ADD OFFSET TO ARRAY
            $fields['OFFSET'] = ((int) $offset + $count + 1);

            if ($this->shouldProcess) {
                if ($aItem->ACLAccess('EditView')) {
                    $widget_contents[$aVal][0] = "<input type='checkbox' class='checkbox' name='mass[]' value='" . $fields['ID'] . "' />";
                } else {
                    $widget_contents[$aVal][0] = '';
                }
//                    if ($aItem->ACLAccess('DetailView')) {
//                        $this->smartyTemplate->assign('TAG_NAME', 'a');
//                    }
//                    else {
//                        $this->smartyTemplate->assign('TAG_NAME', 'span');
//                    }
                $this->smartyTemplate->assign('CHECKALL', "<input type='checkbox'  title='" . $GLOBALS['app_strings']['LBL_SELECT_ALL_TITLE'] . "' class='checkbox' name='massall' id='massall' value='' onclick='sListView.check_all(document.MassUpdate, \"mass[]\", this.checked);' />");
            }
            $oddRow = ! $oddRow;

            $layout_manager = $this->getLayoutManager();
            $layout_manager->setAttribute('context', 'List');
            $layout_manager->setAttribute('image_path', $this->local_image_path);
            $layout_manager->setAttribute('module_name', $subpanel_def->_instance_properties['module']);
            if (! empty($this->child_focus)) {
                $layout_manager->setAttribute('related_module_name', $this->child_focus->module_dir);
            }
            //AG$subpanel_data = $this->list_field_defs;
            //$bla = array_pop($subpanel_data);
            //select which sub-panel to display here, the decision will be made based on the type of
            //the sub-panel and panel in the bean being processed.
            if ($subpanel_def->isCollection()) {
                $thepanel = $subpanel_def->sub_subpanels[$aItem->panel_name];
            } else {
                $thepanel = $subpanel_def;
            }

            /* BEGIN - SECURITY GROUPS */

            //This check is costly doing it field by field in the below foreach
            //instead pull up here and do once per record....
            $aclaccess_is_owner = false;
            $aclaccess_in_group = false;

            global $current_user;
            if (is_admin($current_user)) {
                $aclaccess_is_owner = true;
            } else {
                $aclaccess_is_owner = $aItem->isOwner($current_user->id);
            }

            require_once "modules/SecurityGroups/SecurityGroup.php";
            $aclaccess_in_group = SecurityGroup::groupHasAccess($aItem->module_dir, $aItem->id);

            /* END - SECURITY GROUPS */

            //get data source name
            $linked_field     = $thepanel->get_data_source_name();
            $linked_field_set = $thepanel->get_data_source_name(true);
            static $count;
            if (! isset($count)) {
                $count = 0;
            }
            /* BEGIN - SECURITY GROUPS */
            /**
             * $field_acl['DetailView'] = $aItem->ACLAccess('DetailView');
             * $field_acl['ListView'] = $aItem->ACLAccess('ListView');
             * $field_acl['EditView'] = $aItem->ACLAccess('EditView');
             */
            //pass is_owner, in_group...vars defined above
            $field_acl['DetailView'] = $aItem->ACLAccess('DetailView', $aclaccess_is_owner, $aclaccess_in_group);
            $field_acl['ListView']   = $aItem->ACLAccess('ListView', $aclaccess_is_owner, $aclaccess_in_group);
            $field_acl['EditView']   = $aItem->ACLAccess('EditView', $aclaccess_is_owner, $aclaccess_in_group);
            /* END - SECURITY GROUPS */
            foreach ($thepanel->get_list_fields() as $field_name => $list_field) {
                //add linked field attribute to the array.
                $list_field['linked_field']     = $linked_field;
                $list_field['linked_field_set'] = $linked_field_set;

                $usage = empty($list_field['usage']) ? '' : $list_field['usage'];
                if ('query_only' == $usage && ! empty($list_field['force_query_only_display'])) {
                    //if you are here you have column that is query only but needs to be displayed as blank.  This is helpful
                    //for collections such as Activities where you have a field in only one object and wish to show it in the subpanel list
                    $count++;
                    $widget_contents[$aVal][$field_name] = '&nbsp;';
                } else {
                    if ('query_only' != $usage) {
                        $list_field['name'] = $field_name;

                        $module_field = $field_name . '_mod';
                        $owner_field  = $field_name . '_owner';
                        if (! empty($aItem->$module_field)) {
                            $list_field['owner_id']     = $aItem->$owner_field;
                            $list_field['owner_module'] = $aItem->$module_field;
                        } else {
                            $list_field['owner_id']     = false;
                            $list_field['owner_module'] = false;
                        }
                        if (isset($list_field['alias'])) {
                            $list_field['name'] = $list_field['alias'];
                            // Clone field def from origin field def to alias field def
                            $alias_field_def         = $aItem->field_defs[$field_name];
                            $alias_field_def['name'] = $list_field['alias'];
                            // Add alias field def into bean to can render field in subpanel
                            $aItem->field_defs[$list_field['alias']] = $alias_field_def;
                            if (! isset($fields[strtoupper($list_field['alias'])]) || empty($fields[strtoupper($list_field['alias'])])) {
                                global $timedate;
                                $fields[strtoupper($list_field['alias'])] = (! empty($aItem->$field_name)) ? $aItem->$field_name : $timedate->to_display_date_time($aItem->{$list_field['alias']});
                            }
                        } else {
                            $list_field['name'] = $field_name;
                        }
                        $list_field['fields']             = $fields;
                        $list_field['module']             = $aItem->module_dir;
                        $list_field['start_link_wrapper'] = $this->start_link_wrapper;
                        $list_field['end_link_wrapper']   = $this->end_link_wrapper;
                        $list_field['subpanel_id']        = $this->subpanel_id;
                        $list_field += $field_acl;
                        if (isset($aItem->field_defs[strtolower($list_field['name'])])) {
                            require_once 'include/SugarFields/SugarFieldHandler.php';
                            // We need to see if a sugar field exists for this field type first,
                            // if it doesn't, toss it at the old sugarWidgets. This is for
                            // backwards compatibility and will be removed in a future release
                            $vardef = $aItem->field_defs[strtolower($list_field['name'])];
                            if (isset($vardef['type'])) {
                                $fieldType = isset($vardef['custom_type']) ? $vardef['custom_type'] : $vardef['type'];
                                $tmpField  = SugarFieldHandler::getSugarField($fieldType, true);
                            } else {
                                $tmpField = null;
                            }

                            if (null != $tmpField) {
                                $widget_contents[$aVal][$field_name] = SugarFieldHandler::displaySmarty($list_field['fields'], $vardef, 'ListView', $list_field);
                            } else {
                                // No SugarField for this particular type
                                // Use the old, icky, SugarWidget for now
                                $widget_contents[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);
                            }

                            if (isset($list_field['widget_class']) && 'SubPanelDetailViewLink' == $list_field['widget_class']) {
                                // We need to call into the old SugarWidgets for the time being, so it can generate a proper link with all the various corner-cases handled
                                // So we'll populate the field data with the pre-rendered display for the field
                                $list_field['fields'][$field_name] = $widget_contents[$aVal][$field_name];
                                if ('full_name' == $field_name) { //bug #32465
                                    $list_field['fields'][strtoupper($field_name)] = $widget_contents[$aVal][$field_name];
                                }

                                //vardef source is non db, assign the field name to varname for processing of column.
                                if (! empty($vardef['source']) && 'non-db' == $vardef['source']) {
                                    $list_field['varname'] = $field_name;
                                }
                                $widget_contents[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);
                            } else {
                                if (isset($list_field['widget_class']) && 'SubPanelEmailLink' == $list_field['widget_class']) {
                                    if (isset($list_field['fields']['EMAIL1_LINK'])) {
                                        $widget_contents[$aVal][$field_name] = $list_field['fields']['EMAIL1_LINK'];
                                    } else {
                                        $widget_contents[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);
                                    }
                                }
                            }

                            $count++;
                            if (empty($widget_contents)) {
                                $widget_contents[$aVal][$field_name] = '&nbsp;';
                            }
                        } else {
                            // This handles the edit and remove buttons and icon widget
                            if (isset($list_field['widget_class']) && "SubPanelIcon" == $list_field['widget_class']) {
                                $count++;
                                $widget_contents[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);

                                if (empty($widget_contents[$aVal][$field_name])) {
                                    $widget_contents[$aVal][$field_name] = '&nbsp;';
                                }
                            } elseif (preg_match("/button/i", $list_field['name'])) {
                                if ((('edit_button' === $list_field['name'] && $field_acl['EditView']) || ('close_button' === $list_field['name'] && $field_acl['EditView']) || ('remove_button' === $list_field['name'] && ($field_acl['EditView'] || ("SecurityGroup" == $aItem->object_name && $subpanel_def->parent_bean->ACLAccess('edit'))))) && '' != ($_content = $layout_manager->widgetDisplay($list_field))) {
                                    $button_contents[$aVal][] = $_content;
                                    unset($_content);
                                } else {
                                    $doNotProcessTheseActions = ["edit_button", "close_button", "remove_button"];
                                    if (! in_array($list_field['name'], $doNotProcessTheseActions) && '' != ($_content = $layout_manager->widgetDisplay($list_field))) {
                                        $button_contents[$aVal][] = $_content;
                                        unset($_content);
                                    } else {
                                        $button_contents[$aVal][] = '';
                                    }
                                }
                            } else {
                                $count++;
//                                    $this->smartyTemplate->assign('CLASS', "");
                                $widget_contents[$aVal][$field_name] = $layout_manager->widgetDisplay($list_field);
//                                    $this->smartyTemplate->assign('CELL_COUNT', $count);
                                if (empty($widget_contents[$aVal][$field_name])) {
                                    $widget_contents[$aVal][$field_name] = '&nbsp;';
                                }
                            }
                        }
                    }
                }
            }

            $aItem->setupCustomFields($aItem->module_dir);
            $aItem->custom_fields->populateAllXTPL($this->smartyTemplate, 'detail', $html_varName, $fields);

            $count++;
        }

        if($subpanel_def->bean_name == 'User') {
            if(!empty($widget_contents)) {
                $parent_id = isset($subpanel_def->parent_bean->id) ? $subpanel_def->parent_bean->id : $_REQUEST['record'];
                $widget_contents = $this->getWorkScheduleTypes($widget_contents, $parent_id);
            }
        }

        $this->smartyTemplate->assign('ROWS', $widget_contents);
        $this->smartyTemplate->assign('ROWS_BUTTONS', $button_contents);
    }

    protected function getWorkScheduleTypes($widget_contents, $parent_id) {
        $user_ids = '(';
        foreach($widget_contents as $key => $value) {
            $user_ids .= "'". $key . "'" . ",";
        }
        $user_ids = rtrim($user_ids, ',');
        $user_ids .= ')';

        $sql = "SELECT workschedules.assigned_user_id user_id, workschedules.type workschedule_type
        FROM workschedules
        LEFT JOIN calls ON calls.id = '$parent_id'
        WHERE workschedules.assigned_user_id IN $user_ids
        AND (
            (workschedules.date_start >= calls.date_start AND workschedules.date_start < calls.date_end)
            OR
            (workschedules.date_end > calls.date_start AND workschedules.date_end <= calls.date_end)
            OR
            (workschedules.date_start <= calls.date_start AND workschedules.date_end >= calls.date_end)
        )
        AND workschedules.deleted = 0 
        ORDER BY workschedules.date_end ASC";
        $db = DBManagerFactory::getInstance();
        $result = $db->query($sql);
        $ws_types = [];
        while($rows = $db->fetchByAssoc($result)) {
            $ws_types[$rows['user_id']][] = $rows['workschedule_type'];
        }

        $ws_types_names = $this->translateWorkScheduleTypes($ws_types);

        foreach($ws_types_names as $key => $value) {
            $widget_contents[$key]['workschedule_type'] = $value;
        }

        return $widget_contents;
    }

    protected function translateWorkScheduleTypes($ws_types) {
        global $app_list_strings;
        foreach($ws_types as $key => $values) {
            foreach($values as &$value) {
                $value = $app_list_strings['workschedule_type_list'][$value] ?? $value;
            }
            $ws_types[$key] = implode(", ", $values);
        }
        return $ws_types;
    }
}
