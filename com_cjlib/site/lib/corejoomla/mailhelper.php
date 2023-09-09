<?php
use Joomla\String\StringHelper;

/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2021 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class CjMailHelper
{
	public static function getMessage($msgid)
	{
		$db = \Joomla\CMS\Factory::getDbo();
		$messages = array();
		
		$query = $db->getQuery(true)
			->select('q.id, q.to_addr, q.cc_addr, q.bcc_addr, q.html, q.message_id, q.params AS qparams')
			->from('#__corejoomla_messagequeue AS q');
		
		// join over messages
		$query->select('m.id AS msgid, m.asset_id, m.asset_name, m.subject, m.description, m.params AS msgparams')
			->join('inner', '#__corejoomla_messages AS m ON m.id = q.message_id');
		
		// where conditions
		if(is_array($msgid))
		{
			$msgid = Joomla\Utilities\ArrayHelper::toInteger($msgid);
			$query->where('q.id IN ('.implode(',', $msgid).')');
		}
		else 
		{
			$query->where('q.id = '.(int) $msgid);
		}
		
		$db->setQuery($query);
		try
		{
			$messages = $db->loadObjectList();
		}
		catch(Exception $e)
		{
			return false;
		}
		
		if(empty($messages))
		{
			return false;
		}
		
		foreach ($messages as &$message)
		{
			// First replace all message placeholders
			$params = json_decode($message->qparams);
			$message->attachment = null;
			
			if(!empty($params->placeholders))
			{
				foreach ($params->placeholders as $key=>$value)
				{
					$message->subject = StringHelper::str_ireplace($key, $value, $message->subject);
					$message->description = StringHelper::str_ireplace($key, $value, $message->description);
				}
			}

			if(!empty($params->attachment))
			{
				$message->attachment = JPATH_ROOT . $params->attachment;
			}

			// Then replace all queue item placeholders
			$params = json_decode($message->msgparams);
			if(!empty($params->placeholders))
			{
				foreach ($params->placeholders as $key=>$value)
				{
				    $message->subject = StringHelper::str_ireplace($key, $value, $message->subject);
				    $message->description = StringHelper::str_ireplace($key, $value, $message->description);
				}
			}
			
			if(!empty($params->attachment))
			{
				$message->attachment = JPATH_ROOT . $params->attachment;
			}
		}
		
		return $messages;
	}
	
	/**
	 * A wrapper function for the Joomla mail API to send emails.
	 *
	 * @param string $from from email address
	 * @param string $fromname name of the sender
	 * @param string $recipient reciepient email
	 * @param string $subject email subject
	 * @param string $body body of the email
	 * @param boolean $mode true if html mode enabled, false otherwise
	 * @param string $cc email addresses in cc
	 * @param string $bcc email addresses in bcc
	 * @param string $attachment attachment
	 * @param string $replyto replyto email address
	 * @param string $replytoname reply to name
	 *
	 * @return mixed True if successful, a JError object otherwise
	 */
	public static function sendEmail($from, $fromname, $recipient, $subject, $body, $options = array())
	{
		// Get a JMail instance
		$mail = \Joomla\CMS\Factory::getMailer();
	
		$mail->setSender(array($from, $fromname));
		$mail->setSubject($subject);
		$mail->setBody($body);
	
		// Are we sending the email as HTML?
		if (isset($options['mode'])) {
	
			$mail->IsHTML(true);
		}
	
		if(!is_array($recipient)){
	
			$recipient = explode(',', $recipient);
		}
	
		$mail->addRecipient($recipient);
		
		if(isset($options['cc'])) 
		{
			$mail->addCC($cc);
		}
		
		if(isset($options['bcc']) && $options['bcc'])
		{
			$mail->addBCC($bcc);
		}
		
		if(isset($options['attachment']))
		{
			$mail->addAttachment($attachment);
		}
	
		// Take care of reply email addresses
		if (isset($options['replyto']))
		{
			if(is_array($options['replyto']))
			{
				$numReplyTo = count($options['replyto']);
				for ($i=0; $i < $numReplyTo; $i++)
				{
					$mail->addReplyTo(array($options['replyto'][$i], $options['replytoname'][$i]));
				}
			}
			else 
			{
				$mail->addReplyTo($options['replyto'], $options['replytoname']);
			}
		}
	
		return  $mail->Send();
	}
}