<?php
/**
 * @package     extension.site
 * @subpackage  CjLib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined( '_JEXEC' ) or die();

class CjLibLoader {

	public function __construct() {}

	public static function load() {
		require_once __DIR__ . '/vendor/autoload.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/misc/xssclean.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/corejoomla/constants.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/corejoomla/mailhelper.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/corejoomla/uihelper.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/corejoomla/api.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/corejoomla/script.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/corejoomla/html.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/corejoomla/utils.php';
		require_once JPATH_ROOT . '/components/com_cjlib/lib/corejoomla/dateutils.php';

		Factory::getLanguage()->load( 'com_cjlib.sys', JPATH_ADMINISTRATOR );
		$document = Factory::getDocument();

		if ( method_exists( $document, 'addCustomTag' ) && $document->getType() != 'pdf' && $document->getType() != 'feed' )
		{
			jimport( 'joomla.html.html.list' );
			jimport( 'joomla.html.html.access' );
		}
	}

}