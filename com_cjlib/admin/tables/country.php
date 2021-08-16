<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_PLATFORM') or die();

class CjLibTableCountry extends JTable
{
	public function __construct (JDatabaseDriver $db)
	{
		parent::__construct('#__corejoomla_countries', 'id', $db);
	}
}
