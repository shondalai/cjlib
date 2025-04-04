<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die();

require_once JPATH_COMPONENT.'/controller.php';
require_once JPATH_ROOT.'/components/com_cjlib/framework.php';

CJLib::import('corejoomla.framework.core');
// CJLib::import('corejoomla.ui.bootstrap', true);

JLoader::register('CjLibHelper', __DIR__ . '/helpers/cjlib.php');

$controller = Joomla\CMS\MVC\Controller\BaseController::getInstance('CjLib');
$controller->execute( Factory::getApplication()->input->get('task'));
$controller->redirect();