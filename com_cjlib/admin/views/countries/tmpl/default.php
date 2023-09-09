<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2018 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

$span = !empty( $this->sidebar) ? 'col-md-10' : 'col-md-12';

$languages = array(
	'af-ZA'=>'Afrikaans', 'sq-ZL'=>'Albanian', 'ar-AA'=>'Arabic Unitag', 'hy-AM'=>'Armenian', 'az-AZ'=>'Azeri', 'eu_ES'=>'Basque',
	'bn-BD'=>'Bengali (Bangladesh)', 'be-BY'=>'Belarusian', 'bs-BA'=>'Bosnian', 'bg-BG'=>'Bulgarian', 'ca-ES'=>'Catalan',
	'ckb-IQ'=>'Central Kurdish', 'zh-CN'=>'Chinese Simplified', 'zh-TW'=>'Chinese Traditional', 'hr-HR'=>'Croatian', 'cs-CZ'=>'Czech',
	'da-DK'=>'Danish', 'nl-NL'=>'Dutch', 'en-AU'=>'English (Australia)', 'en-US'=>'English (USA)', 'eo-XX'=>'Esperanto', 'et-EE'=>'Estonian',
	'fi-FI'=>'Finnish', 'nl-BE'=>'Flemish', 'fr-FR'=>'French', 'gl-ES'=>'Galician', 'ka-GE'=>'Georgian', 'de-DE'=>'German', 'el-GR'=>'Greek',
	'he-IL'=>'Hebrew', 'hi-IN'=>'Hindi', 'hu-HU'=>'Hungarian', 'id-ID'=>'Indonesian', 'it-IT'=>'Italian', 'ja-JP'=>'Japanese', 'km-KH'=>'Khmer',
	'ko-KR'=>'Korean', 'lo-LA'=>'Laotian', 'lv-LV'=>'Latvian', 'lt-LT'=>'Lithuanian', 'mk-MK'=>'Macedonian', 'ml-IN'=>'Malayalam',
	'mn-MN'=>'Mongolian', 'nb-NO'=>'Norwegian (Bokmål)', 'nn-NO'=>'Norwegian (Nynorsk)', 'fa-IR'=>'Persian', 'pl-PL'=>'Polish',
	'pt-BR'=>'Portuguese (Brazil)', 'pt-PT'=>'Portuguese (Portugal)', 'ro-RO'=>'Romanian', 'ru-RU'=>'Russian', 'gd-GB'=>'Scottish Gaelic',
	'sr-RS'=>'Serbian (Cyrillic)', 'sr-YU'=>'Serbian (Latin)', 'sk-SK'=>'Slovak', 'es-ES'=>'Spanish', 'sw-KE'=>'Swahili', 'sv-SE'=>'Swedish',
	'sy-IQ'=>'Syriac (East)', 'ta-IN'=>'Tamil (India)', 'te-IN'=>'Telugu (India)', 'th-TH'=>'Thai', 'tr-TR'=>'Turkish', 'uk-UA'=>'Ukrainian',
	'ur-PK'=>'Urdu', 'ug-CN'=>'Uyghur', 'vi-VN'=>'Vietnamese', 'cy-GB'=>'Welsh');
?>
<div class="row" id="cj-wrapper">
    <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="col-md-2">
    	<?php echo $this->sidebar; ?>
    </div>
    <?php endif;?>
    <div class="<?php echo $span;?>">
    	<div id="j-main-container">
        	<form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_cjlib&view=countries');?>" method="post" name="adminForm" id="adminForm">
        	
        		<div class="clearfix form-inline margin-bottom-10">
        			<div class="pull-right">
        				
        				<span class="badge badge-important tooltip-hover" title="<?php echo \Joomla\CMS\Language\Text::_('COM_CJLIB_ADD_LANGUAGE_HELP');?>">?</span>
        				
        				<select id="filter_language" name="filter[language]" size="1" onchange="document.adminForm.submit();">
        					<option value=""><?php echo \Joomla\CMS\Language\Text::_('COM_CJLIB_FILTER_LANGUAGE');?></option>
        					<?php foreach ($languages as $code=>$language):?>
        					<option value="<?php echo $code;?>"<?php echo $code == $this->state->get('filter.language') ? ' selected="selected"' : '';?>>
        						<?php echo $this->escape($language);?>
        					</option>
        					<?php endforeach;?>
        				</select>
        				
        				<?php if(strlen($this->state->get('filter.language')) > 1 && count($this->items) == 0):?>
        				<button type="button" class="btn btn-danger" onclick="document.adminForm.task.value='countries.add';document.adminForm.submit();">
        					<?php echo \Joomla\CMS\Language\Text::_('COM_CJLIB_ADD_LANGUAGE');?>
        				</button>
        				<?php endif;?>
        				
        				<select id="list_limit" name="list[limit]" class="inputbox input-mini" onchange="this.form.submit();">
        					<option value="5"<?php echo $this->state->get('list.limit') == 5 ? ' selected="selected"' : '';?>>5</option>
        					<option value="10"<?php echo $this->state->get('list.limit') == 10 ? ' selected="selected"' : '';?>>10</option>
        					<option value="15"<?php echo $this->state->get('list.limit') == 15 ? ' selected="selected"' : '';?>>15</option>
        					<option value="20"<?php echo $this->state->get('list.limit') == 20 ? ' selected="selected"' : '';?>>20</option>
        					<option value="25"<?php echo $this->state->get('list.limit') == 25 ? ' selected="selected"' : '';?>>25</option>
        					<option value="30"<?php echo $this->state->get('list.limit') == 30 ? ' selected="selected"' : '';?>>30</option>
        					<option value="50"<?php echo $this->state->get('list.limit') == 50 ? ' selected="selected"' : '';?>>50</option>
        					<option value="100"<?php echo $this->state->get('list.limit') == 100 ? ' selected="selected"' : '';?>>100</option>
        					<option value="200"<?php echo $this->state->get('list.limit') == 200 ? ' selected="selected"' : '';?>>200</option>
        					<option value="500"<?php echo $this->state->get('list.limit') == 500 ? ' selected="selected"' : '';?>>500</option>
        					<option value="0"<?php echo $this->state->get('list.limit') == 0 ? ' selected="selected"' : '';?>>All</option>
        				</select>
        			</div>
        			
        			<select id="filter_published" name="filter[published]" onchange="this.form.submit();" class="inputbox">
        				<option value=""<?php echo $this->state->get('filter.published') ? '' : ' selected="selected"';?>><?php echo \Joomla\CMS\Language\Text::_('JOPTION_SELECT_PUBLISHED')?></option>
        				<option value="-2"<?php echo $this->state->get('filter.published') == '-2' ? ' selected="selected"' : '';?>><?php echo \Joomla\CMS\Language\Text::_('JTRASHED')?></option>
        				<option value="0"<?php echo $this->state->get('filter.published') == '0' ? ' selected="selected"' : '';?>><?php echo \Joomla\CMS\Language\Text::_('JUNPUBLISHED')?></option>
        				<option value="1"<?php echo $this->state->get('filter.published') == '1' ? ' selected="selected"' : '';?>><?php echo \Joomla\CMS\Language\Text::_('JPUBLISHED')?></option>
        				<option value="2"<?php echo $this->state->get('filter.published') == '2' ? ' selected="selected"' : '';?>><?php echo \Joomla\CMS\Language\Text::_('JTRASHED')?></option>
        				<option value="*"<?php echo $this->state->get('filter.published') == '*' ? ' selected="selected"' : '';?>><?php echo \Joomla\CMS\Language\Text::_('JALL')?></option>
        			</select>
        			<input type="text" name="filter_search" id="filter_search"
        				value="<?php echo $this->state->get('filter.search');?>" placeholder="<?php echo \Joomla\CMS\Language\Text::_('COM_CJLIB_SEARCH')?>"/>
        			<input type="submit" value="<?php echo \Joomla\CMS\Language\Text::_('COM_CJLIB_SEARCH');?>" class="btn btn-primary">
        			<input type="button" value="<?php echo \Joomla\CMS\Language\Text::_('COM_CJLIB_RESET');?>" class="btn" 
        				onclick="document.adminForm.filter_search.value=''; document.adminForm.filter_published.value=''; document.adminForm.submit();">
        		</div>
        
        		<table class="adminlist table table-bordered table-striped">
        			<thead><?php echo $this->loadTemplate('head');?></thead>
        			<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
        			<tbody><?php echo $this->loadTemplate('body');?></tbody>
        		</table>
        		<div style="display: none;">
        			<input type="hidden" name="option" id="option" value="com_cjlib" />
        			<input type="hidden" name="view" id="view" value="countries" />
        			<input type="hidden" name="task" id="task" value="" />
        			<input type="hidden" name="boxchecked" value="0" />
        			<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
        			<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
        			<input type="hidden" name="cjlib_page_id" id="cjlib_page_id" value="countries">
        			<img id="progress-confirm" alt="..." src="components/com_cjlib/assets/images/ui-anim_basic_16x16.gif"/>
        			<span id="url-save-country-name"><?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_cjlib&task=country.save');?></span>
        			<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
        		</div>
        	</form>
        </div>
	</div>
</div>