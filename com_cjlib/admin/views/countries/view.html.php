<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibViewCountries extends JViewLegacy
{
	protected $items;
	protected $item;
	protected $pagination;
	protected $state;
	protected $canDo;
	protected $params;
	
	function display($tpl = null) {
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		
		CjLibHelper::addSubmenu('countries');
		$this->params = JComponentHelper::getParams('com_cjlib');
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
		    // Set the toolbar
		    $this->addToolBar();
		    
		    $this->sidebar = JHtmlSidebar::render();
		}

		// Set the document
		$this->setDocument();
		
		// Display the template
		parent::display($tpl);
	}
	
	protected function addToolBar(){
		
		$user = JFactory::getUser();
		if ($user->authorise('core.admin', 'com_cjlib'))
		{
			JToolbarHelper::publish('countries.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('countries.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			
			if ($this->state->get('filter.published') == - 2)
			{
				JToolbarHelper::deleteList('', 'countries.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			else
			{
				JToolbarHelper::trash('countries.trash');
			}
		}
	}
	
	protected function setDocument(){
		
		$document = JFactory::getDocument();
		JToolBarHelper::title(JText::_('COM_CJLIB').': <small><small>[ ' . JText::_('COM_CJLIB_COUNTRIES') .' ]</small></small>', 'cjlib.png');
	}
}