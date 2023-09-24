<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjforum
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;

defined( '_JEXEC' ) or die();

class CjLibHelper extends ContentHelper {

	public static $extension = 'com_cjlib';

	public static function addSubmenu( $vName ) {
		Sidebar::addEntry( Text::_( 'COM_CJLIB_DASHBOARD' ), 'index.php?option=com_cjlib', $vName == '' );
		Sidebar::addEntry( Text::_( 'COM_CJLIB_EMAIL_QUEUE' ), 'index.php?option=com_cjlib&amp;view=queue', $vName == 'queue' );
		Sidebar::addEntry( Text::_( 'COM_CJLIB_COUNTRIES' ), 'index.php?option=com_cjlib&amp;view=countries', $vName == 'countries' );
	}

}
