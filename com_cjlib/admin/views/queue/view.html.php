<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined( '_JEXEC' ) or die();

class CjLibViewQueue extends Joomla\CMS\MVC\View\HtmlView {

	protected $items;
	protected $item;
	protected $pagination;
	protected $state;
	protected $canDo;
	protected $params;

	function display( $tpl = null ) {

		$user = Factory::getUser();
		$app  = Factory::getApplication();

		CjLibHelper::addSubmenu( 'queue' );
		$this->params = ComponentHelper::getParams( 'com_cjlib' );

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
		ToolbarHelper::title(
			Text::_( 'COM_CJLIB' ) . ': <small><small>[ ' . Text::_( 'COM_CJLIB_EMAIL_QUEUE' ) . ' ]</small></small>',
			'cjlib.png' );

		// We don't need toolbar in the modal window.
		if ( $this->getLayout() !== 'modal' )
		{
			// Set the toolbar
			$this->addToolBar();

			$this->sidebar = Sidebar::render();
		}

		// Display the template
		parent::display( $tpl );
	}

	protected function addToolBar() {

		$user        = Factory::getUser();
		$this->state = $this->get( 'State' );

		if ( $user->authorise( 'core.edit.state', 'com_cjlib' ) )
		{

			ToolbarHelper::publish( 'queue.process', 'COM_CJLIB_PROCESS', true );
		}

		if ( $user->authorise( 'core.delete', 'com_cjlib' ) )
		{

			ToolbarHelper::deleteList( '', 'queue.delete', 'JTOOLBAR_DELETE' );
		}

		if ( $user->authorise( 'core.admin', 'com_cjlib' ) )
		{

			ToolbarHelper::divider();
		}
	}

}