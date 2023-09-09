<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2015 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

\Joomla\CMS\Form\FormHelper::loadFieldClass('editor');

class JFormFieldCjeditor extends \Joomla\CMS\Form\Field\EditorField
{
	public $type = 'Cjeditor';
	
	protected function getEditor()
	{
		$jinput = \Joomla\CMS\Factory::getApplication()->input;
		$extension = $this->element['extension'] ? (string) $this->element['extension'] : (string) $jinput->get('option', 'com_content');
		$params = \Joomla\CMS\Component\ComponentHelper::getParams($extension);
		$this->editorType = array($params->get('default_editor'));
		
		return parent::getEditor();
	}
	
	public function save()
	{
		parent::save();
	}
}
