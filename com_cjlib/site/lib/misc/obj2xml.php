<?php
/**
 * @package     extension.administrator
 * @subpackage  plg_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2016 BulaSikku Technologies Private Limited. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once 'Serializer.php';

class Object2Xml
{
	/**
	 * @deprecated use XML_Serializer directly
	 *
	 * @param unknown $obj
	 * @param string $node_block
	 * @param string $node_name
	 * @return string
	 */
	public static function generateValidXmlFromObj($obj, $node_block = 'items', $node_name = 'item')
	{
		$options = array(
				"indent"    => "    ",
				"linebreak" => "\n",
				"typeHints" => false,
				"addDecl"   => true,
				"encoding"  => "UTF-8",
				"rootName"   => $node_block,
				"defaultTagName" => "item"
		);
		
		$serializer = new XML_Serializer($options);
		$xml = '<quiz></quiz>';
		
		if($serializer->serialize($obj))
		{
			$xml = $serializer->getSerializedData();
		}
		
		return $xml;
	}
}