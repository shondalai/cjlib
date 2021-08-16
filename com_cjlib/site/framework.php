<?php
/**
 * @package     corejoomla.site
 * @subpackage  CjLib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

JLoader::registerPrefix('CjLib', JPATH_LIBRARIES . '/cjlib');
CjLibLoader::load();