<?php
/**
 * @version		$Id: default_body.php 2013-07-29 11:37:09Z maverick $
 * @package		CoreJoomla.cjlib
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 BulaSikku Technologies Private Limited. All rights reserved.
 * @author		Maverick
 * @link		https://shondalai.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;
$user = \Joomla\CMS\Factory::getUser();
$canChange = $user->authorise('core.edit.state');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td><?php echo $item->id; ?></td>
		<td><?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.id', $i, $item->id); ?></td>
		<td><?php echo \Joomla\CMS\HTML\HTMLHelper::_('jgrid.published', $item->published, $i, 'countries.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?></td>
		<td><?php echo $this->escape($item->country_code); ?></td>
		<td><input name="country_name" type="text" style="margin-bottom: 0;" value="<?php echo $this->escape($item->country_name);?>"></td>
		<td><?php echo $this->escape($item->language);?></td>
	</tr>
<?php endforeach; ?>

