<?php

	require_once(TOOLKIT . '/class.administrationpage.php');
	
	class ContentExtensionTooltipIndex extends AdministrationPage {
		protected $_driver = null;
		
		public function __construct(){
			parent::__construct();
		}
		
		public function __viewIndex() {
			if(isset($_GET['all']))
			{
				// Get all the tooltips:
				$tooltips = Symphony::Database()->fetch('SELECT * FROM `tbl_tooltips`;');
				echo json_encode($tooltips);
			} elseif(isset($_GET['save']))
			{
				if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
				{
					$id = $_GET['id'];
					Symphony::Database()->delete('tbl_tooltips', '`field_id` = '.$id);
					if(!empty($_GET['content']))
					{
						Symphony::Database()->insert(
							array(
								'field_id' => $id,
								'tooltip'  => General::sanitize($_GET['content'])
							),
							'tbl_tooltips'
						);
					}
				}
			}
			die();
		}
	}
	
?>