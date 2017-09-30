<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
class PropertiesTableshowresults extends JTable
{
	function __construct(&$db)
		{
			parent::__construct( '#__properties_showresults', 'id', $db );
		}
	function check()
	{		
		if(empty($this->date)) {		
			$datenow = JFactory::getDate();
			$this->date = $datenow->toSql();			
		}
		return true;
	}
}
?>