<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;

defined( '_JEXEC' ) or die();

class CjLibControllerCountry extends FormController {

	public function __construct( $config = [] ) {
		parent::__construct( $config );
	}

	public function save( $key = null, $urlVar = null ) {
		$user = Factory::getUser();
		if ( ! $user->authorise( 'core.edit', 'com_cjlib' ) )
		{
			echo json_encode( [ 'error' => Text::_( 'COM_CJLIB_MSG_NOT_AUTHORIZED' ) ] );
		}
		else
		{
			$app   = Factory::getApplication();
			$model = $this->getModel( 'countries' );

			$id   = $app->input->getInt( 'id', 0 );
			$name = $app->input->getString( 'country_name', '' );

			if ( $id && ! empty( $name ) && $model->save_country_name( $id, $name ) )
			{
				echo json_encode( [ 'data' => 1 ] );
			}
			else
			{
				echo json_encode( [ 'error' => Text::_( 'MSG_ERROR_PROCESSING' ) ] );
			}
		}

		jexit();
	}

}
