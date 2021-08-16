<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibViewQueue extends JViewLegacy
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
		
		CjLibHelper::addSubmenu('queue');
		$this->params = JComponentHelper::getParams('com_cjlib');
		
		switch ($this->getLayout()){
			
			default:

				$this->items		= $this->get('Items');
				$this->pagination	= $this->get('Pagination');
				$this->state		= $this->get('State');
				
				// Check for errors.
				if (count($errors = $this->get('Errors'))) {
					
					JError::raiseError(500, implode("\n", $errors));
					return false;
				}
		
				break;
		}

		// Set the document
		$this->setDocument();
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
		    // Set the toolbar
		    $this->addToolBar();
		    
		    $this->sidebar = JHtmlSidebar::render();
		}
		
		// Display the template
		parent::display($tpl);
	}
	
	protected function addToolBar(){
		
		$user = JFactory::getUser();
		$this->state = $this->get('State');
		
		if ($user->authorise('core.edit.state', 'com_cjlib')){

			JToolBarHelper::publish('queue.process', 'COM_CJLIB_PROCESS', true);
		}

		if ($user->authorise('core.delete', 'com_cjlib')){
			
			JToolBarHelper::deleteList('', 'queue.delete', 'JTOOLBAR_DELETE');
		}
			
		if ($user->authorise('core.admin', 'com_cjlib')){

			JToolBarHelper::divider();
// 			JToolBarHelper::preferences('com_cjlib');
		}
	}
	
	protected function setDocument(){
		
		$document = JFactory::getDocument();
		JToolBarHelper::title(JText::_('COM_CJLIB').': <small><small>[ ' . JText::_('COM_CJLIB_EMAIL_QUEUE') .' ]</small></small>', 'cjlib.png');
	}
}