<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  plg_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class pkg_cjlibInstallerScript 
{
	function preflight( $type, $parent )
	{
		return version_compare(PHP_VERSION, '7.2', '>=');
	}
	
	function postflight($type, $parent)
	{
		echo '<p>CjLib Package:</p> 
		<table class="table table-hover table-striped"> 
		<tr><td>CjLib Component</td><td>Successfully installed</td></tr>
		<tr><td>CjLib Plugin</td><td>Successfully installed</td></tr>
		<tr><td>CoreJoomla Socials Plugin</td><td>Successfully installed</td></tr>
		</table>
		<p>Thank you for using corejoomla&reg; software. Please add a rating and review at Joomla&reg; Extension Directory.</p>';
	}
}