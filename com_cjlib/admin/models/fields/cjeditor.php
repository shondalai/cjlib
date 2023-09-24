<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2015 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\EditorField;
use Joomla\CMS\Form\FormHelper;

defined('_JEXEC') or die;

FormHelper::loadFieldClass('editor');

class JFormFieldCjeditor extends EditorField
{
	public $type = 'Cjeditor';
	
	protected function getEditor()
	{
		$jinput = Factory::getApplication()->input;
		$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('option', 'com_content');
		$params = ComponentHelper::getParams($extension);
		$this->editorType = array($params->get('default_editor'));
		
		return parent::getEditor();
	}
	
	public function save()
	{
		parent::save();
	}
}
