<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  plg_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die;

/**
 * Script file of CjLib component
 */
class com_cjlibInstallerScript {

	function postflight( $type, $parent ) {
		// CJLib includes
		$cjlib = JPATH_ROOT . '/components/com_cjlib/framework.php';
		if ( file_exists( $cjlib ) )
		{
			require_once $cjlib;
		}
		else
		{
			die( 'CJLib (CoreJoomla API Library) component files not found. Please check if the component installed properly and try again.' );
		}

		CJLib::import( 'corejoomla.framework.core' );
		CJFunctions::download_geoip_databases();

		echo '<p>CJLib component successfully installed.</p>';
	}

}
