<?php
/**
 * @package     corejoomla.administrator
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

class CjLibViewCountries extends Joomla\CMS\MVC\View\HtmlView {

	protected $items;
	protected $item;
	protected $pagination;
	protected $state;
	protected $canDo;
	protected $params;

	function display( $tpl = null ) {
		CjLibHelper::addSubmenu( 'countries' );
		$this->params = ComponentHelper::getParams( 'com_cjlib' );

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

			$this->sidebar = Sidebar::render();
		}

		// Set the document
		ToolbarHelper::title(
			Text::_( 'COM_CJLIB' ) . ': <small><small>[ ' . Text::_( 'COM_CJLIB_COUNTRIES' ) . ' ]</small></small>',
			'cjlib.png' );

		// Display the template
		parent::display( $tpl );
	}

	protected function addToolBar() {
		$user = Factory::getUser();
		if ( $user->authorise( 'core.admin', 'com_cjlib' ) )
		{
			ToolbarHelper::publish( 'countries.publish', 'JTOOLBAR_PUBLISH', true );
			ToolbarHelper::unpublish( 'countries.unpublish', 'JTOOLBAR_UNPUBLISH', true );

			if ( $this->state->get( 'filter.published' ) == - 2 )
			{
				ToolbarHelper::deleteList( '', 'countries.delete', 'JTOOLBAR_EMPTY_TRASH' );
			}
			else
			{
				ToolbarHelper::trash( 'countries.trash' );
			}
		}
	}

}