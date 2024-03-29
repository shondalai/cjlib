<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Form\FormHelper;

defined('JPATH_PLATFORM') or die;

FormHelper::loadFieldClass('list');

class JFormFieldCountry extends ListField
{
	public $type = 'country';
	
	protected function getOptions()
	{
		$db = Factory::getDbo();
		$language = Factory::getLanguage();
		
		$query = $db->getQuery(true)
			->select('country_code as value, country_name as text')
			->from('#__corejoomla_countries')
			->where('published = 1')
			->group('country_code, country_name');
		
		if($language->getTag())
		{
			$query->where('language IN ('.$db->q($language->getTag()).','.$db->q('*').')');
		}
		else
		{
			$query->where('language = '.$db->q('*'));
		}
		
		$db->setQuery($query);
		$options = $db->loadObjectList();
		
		return $options;
	}
}
