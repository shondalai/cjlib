<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjforum
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined( '_JEXEC' ) or die();

class CjLibControllerCountries extends \Joomla\CMS\MVC\Controller\AdminController {

	protected $text_prefix = 'COM_CJLIB';

	public function __construct( $config = [] ) {
		parent::__construct( $config );
	}

	public function add() {
		$app      = \Joomla\CMS\Factory::getApplication();
		$model    = $this->getModel( 'countries' );
		$language = $app->input->getCmd( 'filter_language' );

		if ( $model->add_language( $language ) )
		{
			$this->setRedirect( 'index.php?option=com_cjlib&view=countries', \Joomla\CMS\Language\Text::_( 'COM_CJLIB_MSG_COMPLETED' ) );
		}
		else
		{
			$this->setRedirect( 'index.php?option=com_cjlib&view=countries', \Joomla\CMS\Language\Text::_( 'COM_CJLIB_MSG_COMPLETED' ) );
		}
	}

	public function getModel( $name = 'Country', $prefix = 'CjLibModel', $config = [ 'ignore_request' => true ] ) {
		$model = parent::getModel( $name, $prefix, $config );

		return $model;
	}

	protected function postDeleteHook( JModelLegacy $model, $ids = null ) {
	}

}