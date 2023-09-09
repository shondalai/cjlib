<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibViewDefault extends Joomla\CMS\MVC\View\HtmlView
{
	function display($tpl = null) 
	{
		CjLibHelper::addSubmenu('default');
		\Joomla\CMS\Toolbar\ToolbarHelper::title(\Joomla\CMS\Language\Text::_('TITLE_CJLIB'), 'cjlib.png');
		\Joomla\CMS\Toolbar\ToolbarHelper::custom('config.updategeoip', 'download.png', 'download.png', \Joomla\CMS\Language\Text::_('COM_CJLIB_UPDATE_GEOIP_DATABASE'), false);
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
		    $this->sidebar = \Joomla\CMS\HTML\Helpers\Sidebar::render();
		}
		
		parent::display($tpl);
	}
}
?>