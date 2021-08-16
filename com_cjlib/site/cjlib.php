<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  plg_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once 'framework.php';
require_once JPATH_COMPONENT.'/controller.php';

CJLib::import('corejoomla.framework.core');

$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_cjlib');

$task = $app->input->getCmd('task', '');
$secret = $app->input->getCmd('secret', null);

if($task == 'process' && !empty($secret) && !empty($params->get('cron_secret')) && strcmp($params->get('cron_secret'), $secret) == 0)
{
    $emails = (int) $params->get('num_emails_in_batch', 50);
    $delay = (int) $params->get('max_delay_per_batch', 10);
	$sent = CJFunctions::send_messages_from_queue($emails, $delay, false);
	
	if(!empty($sent))
	{
		echo json_encode($sent);
	}
} 
else if($task = 'socialcounts')
{
	require_once CJLIB_PATH.'/lib/misc/socialcounts.php';
	$url = base64_decode(JFactory::getApplication()->input->getString('url'));
	
	if( !SocialCount::REQUIRE_LOCAL_URL || SocialCount::isLocalUrl( $url ) ) 
	{
		try 
		{
			$social = new SocialCount( $url );
			$social->addNetwork(new Twitter());
			$social->addNetwork(new Facebook());
			$social->addNetwork(new GooglePlus());
			// $social->addNetwork(new ShareThis());
	
			echo $social->toJSON();
		} 
		catch(Exception $e) 
		{
			echo '{"error": "' . $e->getMessage() . '"}';
		}
	} 
	else 
	{
		echo '{"error": "URL not authorized."}';
	}
}

jexit();