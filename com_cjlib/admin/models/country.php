<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

defined( '_JEXEC' ) or die();

JLoader::register( 'CjLibHelper', JPATH_ADMINISTRATOR . '/components/com_cjlib/helpers/cjlib.php' );

class CjLibModelCountry extends AdminModel {

	protected $text_prefix = 'COM_CJLIB';

	public $typeAlias = 'com_cjlib.country';

	protected $_item = null;

	public function __construct( $config ) {
		parent::__construct( $config );
	}

	protected function prepareTable( $table ) {
		// Set the publish date to now
		$db = $this->getDbo();
		if ( $table->published == 1 && (int) $table->publish_up == 0 )
		{
			$table->publish_up = Factory::getDate()->toSql();
		}

		if ( $table->published == 1 && intval( $table->publish_down ) == 0 )
		{
			$table->publish_down = $db->getNullDate();
		}
	}

	public function getTable( $type = 'Country', $prefix = 'CjLibTable', $config = [] ) {
		return Table::getInstance( $type, $prefix, $config );
	}

	public function getItem( $pk = null ) {
		$item = parent::getItem( $pk );

		return $item;
	}

	public function getForm( $data = [], $loadData = true ) {
		// Get the form.
		$form = $this->loadForm( 'com_cjlib.country', 'country', [ 'control' => 'jform', 'load_data' => $loadData ] );
		if ( empty( $form ) )
		{
			return false;
		}
		$jinput = Factory::getApplication()->input;

		// The front end calls this model and uses t_id to avoid id clashes so
		// we need to check for that first.
		if ( $jinput->get( 'c_id' ) )
		{
			$id = $jinput->get( 'c_id', 0 );
		}
		// The back end uses id so we use that the rest of the time and set it
		// to 0 by default.
		else
		{
			$id = $jinput->get( 'id', 0 );
		}
		// Determine correct permissions to check.
		if ( $this->getState( 'country.id' ) )
		{
			$id = $this->getState( 'country.id' );
			// Existing record. Can only edit in selected categories.
		}

		$user = $this->getUser();

		// Check for existing topic.
		// Modify the form based on Edit State access controls.
		if ( ! $user->authorise( 'core.edit.state', 'com_cjlib' ) )
		{
			// Disable fields for display.
			$form->setFieldAttribute( 'publish_up', 'disabled', 'true' );
			$form->setFieldAttribute( 'publish_down', 'disabled', 'true' );
			$form->setFieldAttribute( 'published', 'disabled', 'true' );

			// Disable fields while saving.
			// The controller has already verified this is an topic you can edit.
			$form->setFieldAttribute( 'publish_up', 'filter', 'unset' );
			$form->setFieldAttribute( 'publish_down', 'filter', 'unset' );
			$form->setFieldAttribute( 'published', 'filter', 'unset' );
		}

		return $form;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$app  = Factory::getApplication();
		$data = $app->getUserState( 'com_cjlib.edit.country.data', [] );

		if ( empty( $data ) )
		{
			$data = $this->getItem();
		}

		$this->preprocessData( 'com_cjlib.country', $data );

		return $data;
	}

	protected function preprocessForm( Form $form, $data, $group = 'content' ) {
		parent::preprocessForm( $form, $data, $group );
	}

	protected function getUser() {
		$user = $this->getState( 'user' );
		if ( empty( $user ) )
		{
			$user = Factory::getUser();
		}

		return $user;
	}

}