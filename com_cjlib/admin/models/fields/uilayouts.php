<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2015 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined( '_JEXEC' ) or die;

FormHelper::loadFieldClass( 'list' );

/**
 * Supports an HTML select list of folder
 *
 * @since  11.1
 */
class JFormFieldUILayouts extends ListField {

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'UILayouts';

	/**
	 * The filter.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $filter;

	/**
	 * The exclude.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $exclude;

	/**
	 * The recursive.
	 *
	 * @var    string
	 * @since  3.6
	 */
	protected $recursive;

	/**
	 * The hideNone.
	 *
	 * @var    boolean
	 * @since  3.2
	 */
	protected $hideNone = false;

	/**
	 * The hideDefault.
	 *
	 * @var    boolean
	 * @since  3.2
	 */
	protected $hideDefault = false;

	/**
	 * The directory.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $directory;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.2
	 */
	public function __get( $name ) {
		switch ( $name )
		{
			case 'filter':
			case 'exclude':
			case 'recursive':
			case 'hideNone':
			case 'hideDefault':
			case 'directory':
				return $this->$name;
		}

		return parent::__get( $name );
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function __set( $name, $value ) {
		switch ( $name )
		{
			case 'filter':
			case 'directory':
			case 'exclude':
			case 'recursive':
				$this->$name = (string) $value;
				break;

			case 'hideNone':
			case 'hideDefault':
				$value       = (string) $value;
				$this->$name = ( $value === 'true' || $value === $name || $value === '1' );
				break;

			default:
				parent::__set( $name, $value );
		}
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     FormField::setup()
	 * @since   3.2
	 */
	public function setup( SimpleXMLElement $element, $value, $group = null ) {
		$return = parent::setup( $element, $value, $group );

		if ( $return )
		{
			$this->filter  = (string) $this->element['filter'];
			$this->exclude = (string) $this->element['exclude'];

			$recursive       = (string) $this->element['recursive'];
			$this->recursive = ( $recursive == 'true' || $recursive == 'recursive' || $recursive == '1' );

			$hideNone       = (string) $this->element['hide_none'];
			$this->hideNone = ( $hideNone == 'true' || $hideNone == 'hideNone' || $hideNone == '1' );

			$hideDefault       = (string) $this->element['hide_default'];
			$this->hideDefault = ( $hideDefault == 'true' || $hideDefault == 'hideDefault' || $hideDefault == '1' );

			// Get the path in which to search for file options.
			$this->directory = (string) $this->element['directory'];
		}

		return $return;
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions() {
		$options = [];

		$path = $this->directory;

		if ( ! is_dir( $path ) )
		{
			$path = JPATH_ROOT . '/' . $path;
		}

		// Prepend some default options based on field attributes.
		if ( ! $this->hideNone )
		{
			$options[] = HTMLHelper::_( 'select.option', '-1',
				Text::alt( 'JOPTION_DO_NOT_USE', preg_replace( '/[^a-zA-Z0-9_\-]/', '_', $this->fieldname ) ) );
		}

		if ( ! $this->hideDefault )
		{
			$options[] = HTMLHelper::_( 'select.option', '',
				Text::alt( 'JOPTION_USE_DEFAULT', preg_replace( '/[^a-zA-Z0-9_\-]/', '_', $this->fieldname ) ) );
		}

		// Get a list of folders in the search path with the given filter.
		$folders = Folder::folders( $path, $this->filter, $this->recursive, false );

		// Build the options list from the list of folders.
		if ( is_array( $folders ) )
		{
			foreach ( $folders as $folder )
			{
				// Check to see if the file is in the exclude mask.
				if ( $this->exclude )
				{
					if ( preg_match( chr( 1 ) . $this->exclude . chr( 1 ), $folder ) )
					{
						continue;
					}
				}

				// Remove the root part and the leading /
				$folder = trim( str_replace( $path, '', $folder ), '/' );

				$options[] = HTMLHelper::_( 'select.option', $folder, $folder );
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge( parent::getOptions(), $options );

		return $options;
	}

}
