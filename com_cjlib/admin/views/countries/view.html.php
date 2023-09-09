<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined( '_JEXEC' ) or die();

class CjLibViewCountries extends Joomla\CMS\MVC\View\HtmlView {

	protected $items;
	protected $item;
	protected $pagination;
	protected $state;
	protected $canDo;
	protected $params;

	function display( $tpl = null ) {
		CjLibHelper::addSubmenu( 'countries' );
		$this->params = \Joomla\CMS\Component\ComponentHelper::getParams( 'com_cjlib' );

		$this->items      = $this->get( 'Items' );
		$this->pagination = $this->get( 'Pagination' );
		$this->state      = $this->get( 'State' );

		// Check for errors.
		if ( count( $errors = $this->get( 'Errors' ) ) )
		{
			throw new Exception( implode( "\n", $errors ), 500 );
		}

		// We don't need toolbar in the modal window.
		if ( $this->getLayout() !== 'modal' )
		{
			// Set the toolbar
			$this->addToolBar();

			$this->sidebar = \Joomla\CMS\HTML\Helpers\Sidebar::render();
		}

		// Set the document
		\Joomla\CMS\Toolbar\ToolbarHelper::title(
			\Joomla\CMS\Language\Text::_( 'COM_CJLIB' ) . ': <small><small>[ ' . \Joomla\CMS\Language\Text::_( 'COM_CJLIB_COUNTRIES' ) . ' ]</small></small>',
			'cjlib.png' );

		// Display the template
		parent::display( $tpl );
	}

	protected function addToolBar() {
		$user = \Joomla\CMS\Factory::getUser();
		if ( $user->authorise( 'core.admin', 'com_cjlib' ) )
		{
			\Joomla\CMS\Toolbar\ToolbarHelper::publish( 'countries.publish', 'JTOOLBAR_PUBLISH', true );
			\Joomla\CMS\Toolbar\ToolbarHelper::unpublish( 'countries.unpublish', 'JTOOLBAR_UNPUBLISH', true );

			if ( $this->state->get( 'filter.published' ) == - 2 )
			{
				\Joomla\CMS\Toolbar\ToolbarHelper::deleteList( '', 'countries.delete', 'JTOOLBAR_EMPTY_TRASH' );
			}
			else
			{
				\Joomla\CMS\Toolbar\ToolbarHelper::trash( 'countries.trash' );
			}
		}
	}

}