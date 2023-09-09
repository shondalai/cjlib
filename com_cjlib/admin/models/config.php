<?php
/**
 * @version		$Id: config.php 01 2012-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Cjlib
 * @subpackage	Components.models
 * @copyright	Copyright (C) 2021 BulaSikku Technologies Private Limited.
 * @author		Maverick
 * @link		https://shondalai.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

class CjLibModelConfig extends JModelLegacy {

    function __construct() {
    	
        parent::__construct();
    }

    function save() {
        
    	$db = \Joomla\CMS\Factory::getDBO();
    	$app = \Joomla\CMS\Factory::getApplication();

    	$manual_cron = $app->input->getInt('manual_cron', 0);
    	$cron_emails = $app->input->getInt('cron_emails', 60);
    	$cron_delay = $app->input->getInt('cron_delay', 10);
    	
        $db->setQuery( $query );
        
        $query = "
        	insert into 
        		#__cjlib_config (config_name, config_value) 
        	values 
        		('manual_cron',".$manual_cron."),
        		('cron_emails',".$cron_emails."),
        		('cron_delay',".$cron_delay.") 
        	on duplicate key 
        		update config_value = values (config_value)";
        
        $db->setQuery($query);

        if(!$db->execute()) {
        	
            return false;
        }

        return true;
    }
}
?>