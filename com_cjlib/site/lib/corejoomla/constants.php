<?php
/**
 * @package     corejoomla.site
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2015 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

defined("T_CJ_RATING") or define("T_CJ_RATING",								"#__corejoomla_rating");
defined("T_CJ_RATING_DETAILS") or define("T_CJ_RATING_DETAILS",				"#__corejoomla_rating_details");
defined("T_CJ_MESSAGES") or define("T_CJ_MESSAGES",							"#__corejoomla_messages");
defined("T_CJ_MESSAGEQUEUE") or define("T_CJ_MESSAGEQUEUE",					"#__corejoomla_messagequeue");

defined('CJLIB_VER') or define('CJLIB_VER', 								'@version@');
defined('CJLIB_PATH') or define('CJLIB_PATH', 								JPATH_ROOT.'/components/com_cjlib');
defined('CJLIB_URI') or define('CJLIB_URI', 								JURI::root(true).'/components/com_cjlib');
defined('CJLIB_MEDIA_PATH') or define('CJLIB_MEDIA_PATH',					JPATH_ROOT.'/media/com_cjlib');
defined('CJLIB_MEDIA_URI') or define('CJLIB_MEDIA_URI',						JURI::root(true).'/media/com_cjlib');
defined('CJLIB_CRON_SECRET') or define('CJLIB_CRON_SECRET', 				'cron_secret');

if(!defined('APP_VERSION'))
{
    if(version_compare(JVERSION,'4.0','ge'))
    {
        define('APP_VERSION', 4.0);
    }
    else if(version_compare(JVERSION,'3.5','ge'))
	{
		define('APP_VERSION', 3.5);
	} 
	else if(version_compare(JVERSION,'3.4','ge'))
	{
		define('APP_VERSION', 3.4);
	} 
	else if(version_compare(JVERSION,'3.3','ge'))
	{
		define('APP_VERSION', 3.3);
	} 
	else if(version_compare(JVERSION,'3.2','ge'))
	{
		define('APP_VERSION', 3.2);
	} 
	else if(version_compare(JVERSION,'3.0','ge'))
	{
		define('APP_VERSION', 3);
	} 
	else if(version_compare(JVERSION,'2.5','ge'))
	{
		define('APP_VERSION', 2.5);
	}
}