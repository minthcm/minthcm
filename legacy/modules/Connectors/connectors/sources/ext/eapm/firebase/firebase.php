<?php

class ext_eapm_firebase extends source
{
    protected $_enable_in_wizard = false;
    protected $_enable_in_hover = false;
    protected $_has_testing_enabled = false;
    protected $_enable_in_admin_mapping = false;
    protected $_enable_in_admin_display = false;

    /** {@inheritdoc} */
    public function getItem($args = array(), $module = null)
    {
    }

    /** {@inheritdoc} */
    public function getList($args = array(), $module = null)
    {
        return null;
    }

    /** {@inheritdoc} */
    public function saveConfig()
    {
        if (file_exists('include/Integrations/Firebase/Cache/token.cache')) {
            unlink('include/Integrations/Firebase/Cache/token.cache');
        }
        parent::saveConfig();
    }

}
