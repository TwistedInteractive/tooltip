<?php

Class extension_tooltip extends Extension
{
	/**
	 * About this extension
	 * @return array
	 */
	public function about()
	{
		return array(
			'name' => 'Tooltip',
			'version' => '1.1',
			'release-date' => '2012-05-08',
			'author' => array(
				'name' => 'Twisted Interactive',
				'website' => 'http://www.twisted.nl'
			)
		);
	}


	/**
	 * Return an array with delegates
	 * @return array
	 */
	public function getSubscribedDelegates()
	{
		return array(
			// Delegates
			array(
				'page' => '/backend/',
				'delegate' => 'InitaliseAdminPageHead',
				'callback' => 'addScriptToHead'
			)
		);
	}

	/**
	 * Add script to the <head>-section of the admin area
	 *
	 * @param $context
	 */
	public function addScriptToHead($context)
	{
		$callback   = Administration::instance()->getPageCallback();

		if($callback['driver'] == 'publish' && ($callback['context']['page'] == 'new' || $callback['context']['page'] == 'edit'))
		{
			// User Type:
			$javaScript = "var tooltip_user_type = '".Administration::instance()->Author->get('user_type')."';";
			$javaScript.= "var tooltip_url = '".URL."';";

			$tag = new XMLElement('script', $javaScript, array('type'=>'text/javascript'));
		    Administration::instance()->Page->addElementToHead($tag);
		    Administration::instance()->Page->addScriptToHead(URL.'/extensions/tooltip/assets/tooltip.js', 999);
		    Administration::instance()->Page->addStylesheetToHead(URL.'/extensions/tooltip/assets/tooltip.css');
		}

	}

	/**
	 * Installation script
	 * @return void
	 */
	public function install()
	{
		Symphony::Database()->query("CREATE TABLE IF NOT EXISTS `tbl_tooltips` (
	            `id` int(11) unsigned NOT NULL auto_increment,
	            `field_id` int(11) unsigned NOT NULL,
				`tooltip` TEXT NOT NULL,
	            PRIMARY KEY  (`id`),
	            KEY `field_id` (`field_id`)
	        )");
	}

	/**
	 * Uninstallation script
	 * @return void
	 */
	public function uninstall()
	{
		Symphony::Database()->query('DROP TABLE `tbl_tooltips`');
	}
}
