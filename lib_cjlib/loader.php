<?php
/**
 * @package     corejoomla.site
 * @subpackage  CjLib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibLoader {
	
	public function __construct() {
	}
	
	public static function load() {
		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filter.output');
		jimport('joomla.utilities.date');
		jimport('joomla.log.log');
		jimport('joomla.mail.helper');
		jimport('joomla.session.session');
		
		require_once __DIR__ . '/vendor/autoload.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/misc/xssclean.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/constants.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/mailhelper.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/uihelper.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/api.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/script.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/html.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/utils.php';
		require_once JPATH_ROOT.'/components/com_cjlib/lib/corejoomla/dateutils.php';
		
		\Joomla\CMS\Factory::getLanguage()->load('com_cjlib.sys', JPATH_ADMINISTRATOR);
		$document = \Joomla\CMS\Factory::getDocument();
		
		if(method_exists($document, 'addCustomTag') && $document->getType() != 'pdf' && $document->getType() != 'feed') {
				
			jimport('joomla.html.html.list');
			jimport('joomla.html.html.access');
		}
	}
}