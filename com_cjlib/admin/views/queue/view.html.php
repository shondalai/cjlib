<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined( '_JEXEC' ) or die();

class CjLibViewQueue extends Joomla\CMS\MVC\View\HtmlView {

	protected $items;
	protected $item;
	protected $pagination;
	protected $state;
	protected $canDo;
	protected $params;

	function display( $tpl = null ) {

		$user = \Joomla\CMS\Factory::getUser();
		$app  = \Joomla\CMS\Factory::getApplication();

		CjLibHelper::addSubmenu( 'queue' );
		$this->params = \Joomla\CMS\Component\ComponentHelper::getParams( 'com_cjlib' );

		switch ( $this->getLayout() )
		{
			default:
				$this->items      = $this->get( 'Items' );
				$this->pagination = $this->get( 'Pagination' );
				$this->state      = $this->get( 'State' );

				// Check for errors.
				if ( count( $errors = $this->get( 'Errors' ) ) )
				{
					throw new Exception( implode( "\n", $errors ), 500 );
				}
				break;
		}

		// Set the document
		\Joomla\CMS\Toolbar\ToolbarHelper::title(
			\Joomla\CMS\Language\Text::_( 'COM_CJLIB' ) . ': <small><small>[ ' . \Joomla\CMS\Language\Text::_( 'COM_CJLIB_EMAIL_QUEUE' ) . ' ]</small></small>',
			'cjlib.png' );

		// We don't need toolbar in the modal window.
		if ( $this->getLayout() !== 'modal' )
		{
			// Set the toolbar
			$this->addToolBar();

			$this->sidebar = \Joomla\CMS\HTML\Helpers\Sidebar::render();
		}

		// Display the template
		parent::display( $tpl );
	}

	protected function addToolBar() {

		$user        = \Joomla\CMS\Factory::getUser();
		$this->state = $this->get( 'State' );

		if ( $user->authorise( 'core.edit.state', 'com_cjlib' ) )
		{

			\Joomla\CMS\Toolbar\ToolbarHelper::publish( 'queue.process', 'COM_CJLIB_PROCESS', true );
		}

		if ( $user->authorise( 'core.delete', 'com_cjlib' ) )
		{

			\Joomla\CMS\Toolbar\ToolbarHelper::deleteList( '', 'queue.delete', 'JTOOLBAR_DELETE' );
		}

		if ( $user->authorise( 'core.admin', 'com_cjlib' ) )
		{

			\Joomla\CMS\Toolbar\ToolbarHelper::divider();
		}
	}

}