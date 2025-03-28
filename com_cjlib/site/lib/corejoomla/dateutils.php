<?php
/**
 * @package     extension.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class CjLibDateUtils
{
	/**
	 * Gets the human friendly date string from a date
	 *
	 * @param string $strdate date
	 *
	 * @return string formatted date string
	 */
	public static function getHumanReadableDate($strdate, $addSuffix = true) 
	{
		if(empty($strdate) || $strdate == '0000-00-00 00:00:00')
		{
			return Text::_('LBL_NA');
		}
	
		// Given time
		$date = new Joomla\CMS\Date\Date( HTMLHelper::date($strdate, 'Y-m-d H:i:s'));
		$compareTo = new Joomla\CMS\Date\Date( HTMLHelper::date('now', 'Y-m-d H:i:s'));
	
		$diff = $compareTo->toUnix() - $date->toUnix();
		$futureDate = $diff < 0 ? true : false;
		$suffix = $addSuffix ? ($futureDate ? Text::_('COM_CJLIB_DATE_SUFFIX_FROM_NOW') : Text::_('COM_CJLIB_DATE_SUFFIX_AGO')) : '';
	
		$diff = abs($diff);
		$dayDiff = floor($diff/86400);
	
		if($dayDiff == 0) 
		{
			if($diff < 60) 
			{
				return Text::_('COM_CJLIB_JUST_NOW');
			} 
			elseif($diff < 120) 
			{
				return Text::sprintf('COM_CJLIB_DATE_ONE_MINUTE', $suffix);
			} 
			elseif($diff < 3600) 
			{
				return Text::sprintf('COM_CJLIB_DATE_N_MINUTES', floor($diff/60), $suffix);
			} 
			elseif($diff < 7200) 
			{
				return Text::sprintf('COM_CJLIB_DATE_ONE_HOUR', $suffix);
			} 
			elseif($diff < 86400) 
			{
				return Text::sprintf('COM_CJLIB_DATE_N_HOURS', floor($diff/3600), $suffix);
			}
		} 
		elseif($dayDiff == 1) 
		{
			return $futureDate ? Text::_('COM_CJLIB_TOMORROW') : Text::_('COM_CJLIB_YESTERDAY');
		} 
		elseif($dayDiff < 7) 
		{
			return Text::sprintf('COM_CJLIB_DATE_N_DAYS', $dayDiff, $suffix);
		} 
		elseif($dayDiff == 7) 
		{
			return Text::sprintf('COM_CJLIB_DATE_ONE_WEEK', $suffix);
		} 
		elseif($dayDiff < (7*6)) 
		{
			return Text::sprintf('COM_CJLIB_DATE_N_WEEKS', ceil($dayDiff/7), $suffix);
		} 
		elseif($dayDiff > 30 && $dayDiff <= 60) 
		{
			return Text::sprintf('COM_CJLIB_DATE_ONE_MONTH', $suffix);
		} 
		elseif($dayDiff < 365) 
		{
			return Text::sprintf('COM_CJLIB_DATE_N_MONTHS', ceil($dayDiff/(365/12)), $suffix);
		} 
		else 
		{
			$years = round($dayDiff/365);
			if($years == 1)
			{
				return Text::sprintf('COM_CJLIB_DATE_ONE_YEAR', $suffix);
			}
			else
			{
				return Text::sprintf('COM_CJLIB_DATE_N_YEARS', round($dayDiff/365), $suffix);
			}
		}
	}
	
	/**
	 * Returns date/time in short format. i.e. 6m, 6h, 6d, 6w, 6m, 6y etc
	 * @param unknown $date
	 * @return string <string, string, mixed, multitype:>|Ambigous <string, string, mixed>
	 */
	public static function getShortDate($date)
	{
		if(empty($date) || $date == '0000-00-00 00:00:00')
		{
			return Text::_('LBL_NA');
		}
	
		// Given time
		$date = new Date( HTMLHelper::date($date, 'Y-m-d H:i:s'));
		$compareTo = new Date( HTMLHelper::date('now', 'Y-m-d H:i:s'));
		$diff = $compareTo->toUnix() - $date->toUnix();
	
		$diff = abs($diff);
		$dayDiff = floor($diff/86400);
	
		if($dayDiff == 0)
		{
			if($diff < 120)
			{
				return '1m';
			}
			elseif($diff < 3600)
			{
				return floor($diff/60).'m';
			}
			else
			{
				return floor($diff/3600).'h';
			}
		} elseif($dayDiff < 7)
		{
			return $dayDiff.'d';
		}
		elseif($dayDiff < (7*6))
		{
			return ceil($dayDiff/7).'w';
		}
		elseif($dayDiff < 365)
		{
			return ceil($dayDiff/(365/12)).'m';
		}
		else
		{
			return round($dayDiff/365).'y';
		}
	}
	
	/**
	 * Gets the localzed date in desired format
	 * 
	 * @param string $strdate date 
	 * @param string $format format to which the date will be converted
	 * @return string converted date
	 */
	public static function getLocalizedDate($strdate, $format = 'Y-m-d'){
	
		if(empty($strdate) || $strdate == '0000-00-00 00:00:00'){
	
			return Text::_('COM_CJLIB_NA');
		}
	
		return HTMLHelper::date($strdate, $format);
	}
}
