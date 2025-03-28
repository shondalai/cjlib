<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

defined('JPATH_PLATFORM') or die();

class CjLibTableCountry extends Table
{
	public function __construct ( DatabaseDriver $db)
	{
		parent::__construct('#__corejoomla_countries', 'id', $db);
	}
}
