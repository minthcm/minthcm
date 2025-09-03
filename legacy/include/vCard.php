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

/**
 * vCard implementation
 * @api
 */
class vCard
{
    protected $properties = array();

    protected $name = 'no_name';

    public function clear()
    {
        $this->properties = array();
    }

    public function loadContact($contactid, $module='Contacts')
    {
        global $app_list_strings;

        require_once($GLOBALS['beanFiles'][$GLOBALS['beanList'][$module]]);
        $contact = new $GLOBALS['beanList'][$module]();
        $contact->retrieve($contactid);
        // cn: bug 8504 - CF/LB break Outlook's vCard import
        $bad = array("\n", "\r");
        $good = array("=0A", "=0D");
        $encoding = '';
        if (strpos($contact->primary_address_street, "\n") || strpos($contact->primary_address_street, "\r")) {
            $contact->primary_address_street = str_replace($bad, $good, $contact->primary_address_street);
            $encoding = 'QUOTED-PRINTABLE';
        }

        $this->setName(from_html($contact->first_name), from_html($contact->last_name), $app_list_strings['salutation_dom'][from_html($contact->salutation)]);
        if (isset($contact->birthdate)) {
            $this->setBirthDate(from_html($contact->birthdate));
        }
        $this->setPhoneNumber(from_html($contact->phone_fax), 'FAX');
        $this->setPhoneNumber(from_html($contact->phone_home), 'HOME');
        $this->setPhoneNumber(from_html($contact->phone_mobile), 'CELL');
        $this->setPhoneNumber(from_html($contact->phone_work), 'WORK');
        $this->setEmail(from_html($contact->email1));
        $this->setAddress(from_html($contact->primary_address_street), from_html($contact->primary_address_city), from_html($contact->primary_address_state), from_html($contact->primary_address_postalcode), from_html($contact->primary_address_country), 'WORK', $encoding);
        $this->setORG('', from_html($contact->department));
        $this->setTitle($contact->title);
    }

    public function setTitle($title)
    {
        $this->setProperty("TITLE", $title);
    }
    public function setORG($org, $dep)
    {
        $this->setProperty("ORG", "$org;$dep");
    }
    public function setAddress($address, $city, $state, $postal, $country, $type, $encoding='')
    {
        if (!empty($encoding)) {
            $encoding = ";ENCODING={$encoding}";
        }
        $this->setProperty("ADR;$type$encoding", ";;$address;$city;$state;$postal;$country");
    }

    public function setName($first_name, $last_name, $prefix)
    {
        $this->name = strtr($first_name.'_'.$last_name, ' ', '_');
        $this->setProperty('N', $last_name.';'.$first_name.';;'.$prefix);
        $this->setProperty('FN', "$prefix $first_name $last_name");
    }

    public function setEmail($address)
    {
        $this->setProperty('EMAIL;INTERNET', $address);
    }

    public function setPhoneNumber($number, $type)
    {
        if ($type != 'FAX') {
            $this->setProperty("TEL;$type", $number);
        } else {
            $this->setProperty("TEL;WORK;$type", $number);
        }
    }
    public function setBirthDate($date)
    {
        $this->setProperty('BDAY', $date);
    }
    public function getProperty($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        return null;
    }

    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function toString()
    {
        global $locale;
        $temp = "BEGIN:VCARD\n";
        foreach ($this->properties as $key=>$value) {
            if (!empty($value)) {
                $temp .= $key. ';CHARSET='.strtolower($locale->getExportCharset()).':'.$value."\n";
            } else {
                $temp .= $key. ':'.$value."\n";
            }
        }
        $temp.= "END:VCARD\n";


        return $temp;
    }

    public function saveVCard()
    {
        global $locale;
        $content = $this->toString();
        if (!defined('SUITE_PHPUNIT_RUNNER')) {
            header("Content-Disposition: attachment; filename={$this->name}.vcf");
            header("Content-Type: text/x-vcard; charset=".$locale->getExportCharset());
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . TimeDate::httpTime());
            header("Cache-Control: max-age=0");
            header("Pragma: public");
            //bug45856 IIS Doesn't like this to be set and it causes the vCard to not get saved
            $serverSoftvare = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : null;
            if (preg_match('/iis/i', $serverSoftvare) === 0) {
                header("Content-Length: ".strlen($content));
            }
        }

        print $locale->translateCharset($content, 'UTF-8', $locale->getExportCharset());
    }

    public function importVCard($filename, $module = 'Contacts')
    {
        global $current_user;
        $lines = file($filename);
        $start = false;

        $bean = BeanFactory::getBean($module);
        $bean->assigned_user_id = $current_user->id;
        $email_suffix = 1;

        for ($index = 0; $index < count($lines); $index++) {
            $line = $lines[$index];

            // check the encoding and change it if needed
            $locale = new Localization();
            $encoding = false;
            //detect charset
            if (preg_match("/CHARSET=([A-Z]+([A-Z0-9]-?)*):/", $line, $matches)) {
                //found charset hint in vcard
                $encoding = $matches[1];
            } else {
                //use locale to detect charset automatically
                $encoding = $locale->detectCharset($line);
            }
            if ($encoding != $GLOBALS['sugar_config']['default_charset']) {
                $line = $locale->translateCharset($line, $encoding);
            }

            $line = trim($line);
            if ($start) {
                //VCARD is done
                if (substr_count(strtoupper($line), 'END:VCARD')) {
                    if (!isset($bean->last_name) && !empty($fullname)) {
                        $bean->last_name = $fullname;
                    }
                    break;
                }

                $keyvalue = explode(':', $line);
                if (count($keyvalue) == 2) {
                    $value = $keyvalue[1];
                    for ($newindex = $index + 1; $newindex < count($lines), substr_count($lines[$newindex], ':') == 0; $newindex++) {
                        $value .= $lines[$newindex];
                        $index = $newindex;
                    }
                    $values = explode(';', $value);
                    $key = strtoupper($keyvalue[0]);
                    $key = strtr($key, '=', '');
                    $key = strtr($key, ',', ';');
                    $keys = explode(';', $key);

                    if ($keys[0] == 'TEL') {
                        if (substr_count($key, 'WORK') > 0) {
                            if (substr_count($key, 'FAX') > 0) {
                                if (!isset($bean->phone_fax)) {
                                    $bean->phone_fax = $value;
                                }
                            } else {
                                if (!isset($bean->phone_work)) {
                                    $bean->phone_work = $value;
                                }
                            }
                        }

                        if (substr_count($key, 'HOME') > 0) {
                            if (substr_count($key, 'FAX') > 0) {
                                if (!isset($bean->phone_fax)) {
                                    $bean->phone_fax = $value;
                                }
                            } else {
                                if (!isset($bean->phone_home)) {
                                    $bean->phone_home = $value;
                                }
                            }
                        }
                        if (substr_count($key, 'CELL') > 0) {
                            if (!isset($bean->phone_mobile)) {
                                $bean->phone_mobile = $value;
                            }
                        }
                        if (substr_count($key, 'FAX') > 0) {
                            if (!isset($bean->phone_fax)) {
                                $bean->phone_fax = $value;
                            }
                        }
                    }

                    if ($keys[0] == 'N') {
                        if (count($values) > 0) {
                            $bean->last_name = $values[0];
                        }
                        if (count($values) > 1) {
                            $bean->first_name = $values[1];
                        }
                        if (count($values) > 2) {
                            $bean->salutation = $values[2];
                        }
                    }

                    if ($keys[0] == 'FN') {
                        $fullname = $value;
                    }
                }

                if ($keys[0] == 'ADR') {
                    if (substr_count($key, 'WORK') > 0 && (substr_count($key, 'POSTAL') > 0|| substr_count($key, 'PARCEL') == 0)) {
                        if (!isset($bean->primary_address_street) && count($values) > 2) {
                            $textBreaks = array("\n", "\r");
                            $vcardBreaks = array("=0A", "=0D");
                            $bean->primary_address_street = str_replace($vcardBreaks, $textBreaks, $values[2]);
                        }
                        if (!isset($bean->primary_address_city) && count($values) > 3) {
                            $bean->primary_address_city = $values[3];
                        }
                        if (!isset($bean->primary_address_state) && count($values) > 4) {
                            $bean->primary_address_state = $values[4];
                        }
                        if (!isset($bean->primary_address_postalcode) && count($values) > 5) {
                            $bean->primary_address_postalcode = $values[5];
                        }
                        if (!isset($bean->primary_address_country) && count($values) > 6) {
                            $bean->primary_address_country = $values[6];
                        }
                    }
                }

                if ($keys[0] == 'TITLE') {
                    $bean->title = $value;
                }
                if ($keys[0] == 'EMAIL') {
                    $field = 'email' . $email_suffix;
                    if (!isset($bean->$field)) {
                        $bean->$field = $value;
                    }
                    if ($email_suffix == 1) {
                        $_REQUEST['email1'] = $value;
                    }
                    $email_suffix++;
                }

                if ($keys[0] == 'ORG') {
                    $GLOBALS['log']->debug('I found a company name');
                    if (!empty($value)) {
                        $GLOBALS['log']->debug('I found a company name (fer real)');
                        $bean->department = $value;
                    }
                }
            }

            //FOUND THE BEGINING OF THE VCARD
            if (!$start && substr_count(strtoupper($line), 'BEGIN:VCARD')) {
                $start = true;
            }
        }

        foreach ($bean->get_import_required_fields() as $key => $value) {
            if (empty($bean->$key)) {
                $GLOBALS['log']->error("Cannot import vCard, required field is not set: $key");
                return;
            }
        }

        $beanId = $bean->save();
        return $beanId;
    }
}
