<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined( '_JEXEC' ) or die();

use GuzzleHttp\Exception\GuzzleException;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;

class CjLibControllerLicense extends FormController {
	private $activation_url = 'https://shondalai.com';

	public function __construct( $config = [] ) {
		parent::__construct( $config );
	}

	public function activate( $key = null, $urlVar = null ) {
		$license_key   = $this->input->getString( 'license_key' );
		$license_email = $this->input->getString( 'license_email' );

		if ( empty( $license_key ) || empty( $license_email ) )
		{
			$this->setRedirect( 'index.php?option=com_cjlib', Text::_( 'COM_CJLIB_MISSING_REQUIRED_FIELDS' ) );

			return;
		}

		// Build the arguments
		$args = [
			'wc-api'      => 'software-api',
			'request'     => 'activation',
			'email'       => $license_email,
			'license_key' => $license_key,
			'product_id'  => 'all-access-pack',
			'instance'    => Uri::getInstance()->root(),
			'secret_key'  => '',
		];

		$data = null;
		try
		{
			$client   = new GuzzleHttp\Client();
			$response = $client->request( 'GET', $this->activation_url, [
				'query'  => $args,
				'verify' => false,
			] );
			$data     = $response->getBody()->getContents();
		}
		catch ( GuzzleException $e )
		{
			$this->setRedirect( 'index.php?option=com_cjlib', Text::_( 'COM_CJLIB_ERROR_WHILE_ACTIVATING_LICENSE' ) . ' Exception: ' . $e->getMessage() );
		}

		if ( ! empty( $data ) )
		{
			$json = json_decode( $data );

			// If the license is successfully activated, we need to add it to local database
			if ( ! empty( $json->activated ) && $json->activated )
			{
				// getting the component params registry object
				$params = ComponentHelper::getParams( 'com_cjlib' );
				$params->set( 'license_key', $license_key );
				$params->set( 'license_email', $license_email );
				// var_dump($params); // you can check the values

				// saving params to database
				$component_id = ComponentHelper::getComponent( 'com_cjlib' )->id;
				$table        = Table::getInstance( 'extension' );
				$table->load( $component_id );
				$table->bind( [ 'params' => $params->toString() ] );

				// check for error
				if ( $table->check() && $table->store() )
				{
					$this->setRedirect( 'index.php?option=com_cjlib', Text::_( 'COM_CJLIB_ACTIVATION_SUCCESS' ) );

					return;
				}
			}
			elseif ( ! empty( $json->error ) )
			{
				$this->setRedirect( 'index.php?option=com_cjlib', $json->error, 'error' );

				return;
			}
		}

		$this->setRedirect( 'index.php?option=com_cjlib', Text::_( 'COM_CJLIB_ERROR_WHILE_ACTIVATING_LICENSE' ) . " Response: " . $data );
	}

	public function deactivate() {
		$params = ComponentHelper::getParams( 'com_cjlib' );
		// If the license is not found, add it else update it
		if ( $params->get( 'license_key' ) )
		{

			$args = [
				'wc-api'      => 'software-api',
				'request'     => 'deactivation',
				'email'       => $params->get( 'license_email' ),
				'license_key' => $params->get( 'license_key' ),
				'instance'    => Uri::getInstance()->root(),
				'product_id'  => 'all-access-pack',
			];

			$data = null;
			try
			{
				$client   = new GuzzleHttp\Client();
				$response = $client->request( 'GET', $this->activation_url, [
					'query'  => $args,
					'verify' => false,
				] );
				$data     = $response->getBody()->getContents();
			}
			catch ( GuzzleException $e )
			{
				$this->setRedirect( 'index.php?option=com_cjlib', Text::_( 'COM_CJLIB_ERROR_WHILE_DEACTIVATING_LICENSE' ) . ' Exception: ' . $e->getMessage() );
			}

			if ( ! empty( $data ) )
			{
				$json = json_decode( $data );

				// If the license is successfully activated, we need to add it to local database
				if ( ( ! empty( $json->reset ) && $json->reset ) || $json->code == 104 )
				{
					// getting the component params registry object
					$params = ComponentHelper::getParams( 'com_cjlib' );
					$params->set( 'license_key', '' );
					$params->set( 'license_email', '' );
					// var_dump($params); // you can check the values

					// saving params to database
					$component_id = ComponentHelper::getComponent( 'com_cjlib' )->id;
					$table        = Table::getInstance( 'extension' );
					$table->load( $component_id );
					$table->bind( [ 'params' => $params->toString() ] );

					// check for error
					if ( $table->check() && $table->store() )
					{
						$this->setRedirect( 'index.php?option=com_cjlib', Text::_( 'COM_CJLIB_DEACTIVATION_SUCCESS' ) );

						return;
					}
				}
				elseif ( ! empty( $json->error ) )
				{
					$this->setRedirect( 'index.php?option=com_cjlib', $json->error, 'error' );

					return;
				}
			}
		}

		$this->setRedirect( 'index.php?option=com_cjlib', Text::_( 'COM_CJLIB_ERROR_WHILE_DEACTIVATING_LICENSE' ) );
	}

}
