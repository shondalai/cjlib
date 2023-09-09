<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined( '_JEXEC' ) or die();

use Joomla\Utilities\ArrayHelper;

class CjLibControllerQueue extends \Joomla\CMS\MVC\Controller\FormController {

	public function __construct( $config = [] ) {
		parent::__construct( $config );
	}

	function delete() {
		$app = \Joomla\CMS\Factory::getApplication();
		$ids = $app->input->getArray( [ 'cid' => 'array' ] );

		$ids['cid'] = ArrayHelper::toInteger( $ids['cid'] );

		if ( empty( $ids['cid'] ) )
		{
			$this->setRedirect( 'index.php?option=com_cjlib&view=queue', \Joomla\CMS\Language\Text::_( 'COM_CJLIB_MSG_NO_ITEM_SELECTED' ) );
		}
		else
		{
			$model = $this->getModel( 'queue' );
			if ( $model->delete_queue( $ids['cid'] ) )
			{
				$this->setRedirect( 'index.php?option=com_cjlib&view=queue', \Joomla\CMS\Language\Text::_( 'COM_CJLIB_MSG_COMPLETED' ) );
			}
			else
			{
				$this->setRedirect( 'index.php?option=com_cjlib&view=queue', \Joomla\CMS\Language\Text::_( 'MSG_ERROR_PROCESSING' ) );
			}
		}
	}

	function process() {
		$app = \Joomla\CMS\Factory::getApplication();
		$ids = $app->input->getArray( [ 'cid' => 'array' ] );

		$ids['cid'] = ArrayHelper::toInteger( $ids['cid'] );

		if ( empty( $ids['cid'] ) )
		{
			$this->setRedirect( 'index.php?option=com_cjlib&view=queue', \Joomla\CMS\Language\Text::_( 'COM_CJLIB_MSG_NO_ITEM_SELECTED' ) );
		}
		else
		{
			$model = $this->getModel( 'queue' );
			if ( $model->process_queue( $ids['cid'] ) )
			{
				$this->setRedirect( 'index.php?option=com_cjlib&view=queue', \Joomla\CMS\Language\Text::_( 'COM_CJLIB_MSG_COMPLETED' ) );
			}
			else
			{
				$this->setRedirect( 'index.php?option=com_cjlib&view=queue', \Joomla\CMS\Language\Text::_( 'MSG_ERROR_PROCESSING' ) );
			}
		}
	}

}
