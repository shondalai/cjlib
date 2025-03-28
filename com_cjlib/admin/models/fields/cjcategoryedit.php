<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2015 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

FormHelper::loadFieldClass('list');

class JFormFieldCjCategoryEdit extends ListField
{
	public $type = 'CjCategoryEdit';

	protected function getOptions()
	{
		$options = array();
		$published = $this->element['published'] ? $this->element['published'] : array(0, 1);
		$name = (string) $this->element['name'];

		// Let's get the id for the current item, either category or content item.
		$jinput = Factory::getApplication()->input;

		// Load the category options for a given extension.
		$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('option', 'com_content');

		// For categories the old category is the category id or 0 for new category.
		if ($this->element['parent'] || $jinput->get('option') == 'com_categories')
		{
			$oldCat = $jinput->get('id', 0);
			$oldParent = $this->form->getValue($name, 0);
		}
		else
			// For items the old category is the category they are in when opened or 0 if new.
		{
			$oldCat = $this->form->getValue($name, 0);
		}

		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT a.id AS value, a.title AS text, a.level, a.published, a.lft');
		$subQuery = $db->getQuery(true)
			->select('id,title,level,published,parent_id,extension,lft,rgt')
			->from('#__categories');

		// Filter by the extension type
		if ($this->element['parent'] == true || $jinput->get('option') == 'com_categories')
		{
			$subQuery->where('(extension = ' . $db->quote($extension) . ' OR parent_id = 0)');
		}
		else
		{
			$subQuery->where('(extension = ' . $db->quote($extension) . ')');
		}

		// Filter language
		$languages = array( Factory::getLanguage()->getTag(), '*');
		if (!empty($this->element['language']))
		{
			$languages[] = $this->element['language'];
		}
		
		$subQuery->where('language IN (' . implode(',', $db->quote($languages)).')');

		// Filter on the published state
		if (is_numeric($published))
		{
			$subQuery->where('published = ' . (int) $published);
		}
		elseif (is_array($published))
		{
			$published = Joomla\Utilities\ArrayHelper::toInteger($published);
			$subQuery->where('published IN (' . implode(',', $published) . ')');
		}

		$query->from('(' . $subQuery->__toString() . ') AS a')
			->join('LEFT', $db->quoteName('#__categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
		$query->order('a.lft ASC');
		// If parent isn't explicitly stated but we are in com_categories assume we want parents
		if ($oldCat != 0 && ($this->element['parent'] == true || $jinput->get('option') == 'com_categories'))
		{
			// Prevent parenting to children of this item.
			// To rearrange parents and children move the children up, not the parents down.
			$query->join('LEFT', $db->quoteName('#__categories') . ' AS p ON p.id = ' . (int) $oldCat)
				->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');

			$rowQuery = $db->getQuery(true);
			$rowQuery->select('a.id AS value, a.title AS text, a.level, a.parent_id')
				->from('#__categories AS a')
				->where('a.id = ' . (int) $oldCat);
			$db->setQuery($rowQuery);
			$row = $db->loadObject();
		}

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new Exception( $e->getMessage(), 500 );
		}

		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			// Translate ROOT
			if ($this->element['parent'] == true || $jinput->get('option') == 'com_categories')
			{
				if ($options[$i]->level == 0)
				{
					$options[$i]->text = Text::_('JGLOBAL_ROOT_PARENT');
				}
			}

			if ($options[$i]->published == 1)
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
			}
			else
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . '[' . $options[$i]->text . ']';
			}
		}

		// Get the current user object.
		$user = Factory::getUser();

		// For new items we want a list of categories you are allowed to create in.
		foreach ($options as $i => $option)
		{
			/* To take save or create in a category you need to have create rights for that category
			 * unless the item is already in that category.
			 * Unset the option if the user isn't authorised for it. In this field assets are always categories.
			 */
			if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true && $option->level != 0)
			{
				unset($options[$i]);
			}
		}

		if (($this->element['parent'] == true || $jinput->get('option') == 'com_categories')
			&& (isset($row) && !isset($options[0]))
			&& isset($this->element['show_root']))
		{
			if ($row->parent_id == '1')
			{
				$parent = new stdClass;
				$parent->text = Text::_('JGLOBAL_ROOT_PARENT');
				array_unshift($options, $parent);
			}

			array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('JGLOBAL_ROOT')));
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
