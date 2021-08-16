<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

require_once JPATH_COMPONENT.'/controller.php';
require_once JPATH_ROOT.'/components/com_cjlib/framework.php';

CJLib::import('corejoomla.framework.core');
// CJLib::import('corejoomla.ui.bootstrap', true);

JLoader::register('CjLibHelper', __DIR__ . '/helpers/cjlib.php');

$controller = JControllerLegacy::getInstance('CjLib');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();