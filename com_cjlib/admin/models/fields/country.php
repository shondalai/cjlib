<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

\Joomla\CMS\Form\FormHelper::loadFieldClass('list');

class JFormFieldCountry extends \Joomla\CMS\Form\Field\ListField
{
	public $type = 'country';
	
	protected function getOptions()
	{
		$db = \Joomla\CMS\Factory::getDbo();
		$language = \Joomla\CMS\Factory::getLanguage();
		
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
