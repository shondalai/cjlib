<?php
/**
 * @package     shondalai.administrator
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
		<tr><td>CjLib Library</td><td>Successfully installed</td></tr>
		<tr><td>CjLib Socials</td><td>Successfully installed</td></tr>
		<tr><td>CjLib Package Updater</td><td>Successfully installed</td></tr>
		<tr><td>Shondalai Socials Plugin</td><td>Successfully installed</td></tr>
		</table>
		<p>Thank you for using Shondalai software. Please add a rating and review at Joomla&reg; Extension Directory.</p>';
	}
}