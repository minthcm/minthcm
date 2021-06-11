<?php

require_once 'include/LastNextContacts/LastNextContactsBase.php';
/**
 * THIS IS NOT USED PART OF CODE
 */
class LastNextContactsDeadCode extends LastNextContactsBase
{
    public function updateTriggerFields($last_next_trigger_fields)
    {
        $this->last_next_trigger_fields = $last_next_trigger_fields;
        $this->last_next_trigger_fields['Emails'][] = $this->getDateSentFieldName();
    }

    protected function filterArrayForRelated($arguments)
    {
        $allowed = array(
            'related_module',
            'related_id',
            'bean_module_name',
            'bean_id',
            'related_modules',
        );
        return array_intersect_key($arguments, array_flip($allowed));
    }

}
