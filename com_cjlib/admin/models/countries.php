<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CjLibModelCountries extends JModelList 
{
	public function __construct($config = array()){
	
		if (empty($config['filter_fields'])) {
				
			$config['filter_fields'] = array(
					'id', 'q.id', 'm.id',
					'country_code', 'a.country_code',
					'country_name', 'a.country_name',
					'language', 'a.language'
			);
		}
	
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null) {
	
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
	
		if ($layout = $app->input->get('layout')) {
				
			$this->context .= '.'.$layout;
		}
	
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
	
		$country_code = $app->getUserStateFromRequest($this->context.'.filter.country_code', 'country_code');
		$this->setState('filter.country_code', $country_code);

		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);
	
		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
		
		// List state information.
		parent::populateState('a.language', 'asc');
	}
	
	protected function getStoreId($id = ''){
	
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.country_code');
		$id	.= ':'.$this->getState('filter.language');
	
		return parent::getStoreId($id);
	}
	
	protected function _buildQuery(){
	
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
	
		$query->select('a.id, a.country_code, a.country_name, a.language, a.published, a.publish_up, a.publish_down');
		$query->from('#__corejoomla_countries as a');
		
		return $query;
	}
	
	protected function _buildWhere(&$query) {
	
		$db = JFactory::getDbo();
		
		$country_code = $this->getState('filter.country_code');
		if(!empty($country_code)){
				
			$query->where('a.country_code = '.$db->q($country_code));
		}

		$language = $this->getState('filter.language');
		if(!empty($language)){
		
			$query->where('a.language = '.$db->q($language));
		}
		
		$search = $this->getState('filter.search');
		if (!empty($search)) {
				
			if (stripos($search, 'id:') === 0) {
	
				$query->where('a.id = '.(int) substr($search, 3));
			} elseif (stripos($search, 'code:') === 0) {
	
				$search = $db->Quote('%'.$db->escape(substr($search, 5), true).'%');
				$query->where('(a.country_code LIKE '.$search.' OR a.country_name LIKE '.$search.')');
			} else {
	
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.country_name LIKE '.$search.' OR a.country_name LIKE '.$search.')');
			}
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}
	}
	
	protected function getListQuery() {
	
		$db = JFactory::getDbo();
	
		$orderCol	= $this->state->get('list.ordering', 'a.language, a.id');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
		$query = $this->_buildQuery();
		$this->_buildWhere($query);
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
	
	public function add_language($language){
		
		$db = JFactory::getDbo();
		$query = '
			insert into 
				#__corejoomla_countries (country_code, country_name, language) 
			select 
				country_code, country_name, '.$db->q($language).'
			from
				#__corejoomla_countries
			where 
				language = '.$db->q('*');
		
		$db->setQuery($query);
		
		try{
			
			$db->execute();
		} catch(Exception $e){
			
			return false;
		}
		
		return true;
	}
	
	public function save_country_name($id, $name){
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->update('#__corejoomla_countries')->set('country_name = '.$db->q($name))->where('id = '.$id);
		$db->setQuery($query);
		
		try {
			
			$db->execute();
		} catch(Exception $e){
			
			return false;
		}
		
		return true;
	}
}
?>