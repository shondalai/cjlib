<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibViewDefault extends JViewLegacy 
{
	function display($tpl = null) 
	{
		CjLibHelper::addSubmenu('default');
		JToolBarHelper::title(JText::_('TITLE_CJLIB'), 'cjlib.png');
		JToolbarHelper::custom('config.updategeoip', 'download.png', 'download.png', JText::_('COM_CJLIB_UPDATE_GEOIP_DATABASE'), false);
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
		    $this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}
}
?>