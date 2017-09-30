<?php
/**
* @copyright	Copyright(C) 2008-2010 Fabio Esteban Uzeltinger
* @license 		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @email		admin@com-property.com
**/
defined( '_JEXEC' ) or die( 'Restricted access' );


class PropertiesModelShowresults extends JModelForm
{
	public $_context = 'com_properties.showresults';
	protected $_extension = 'com_properties';


protected function populateState()
	{
		$app = JFactory::getApplication('site');
		$pkc = JRequest::getInt('id');
		$this->setState('product.id', $pkc);
	}
	
	public function getForm($data = array(), $loadData = true)
	{			
		return '';
	}
	
	protected function loadFormData()
	{		
		return '';
	}		
					
	function store($data)
	{		
		$db		 = JFactory::getDBO();		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_properties/tables');			
		$row = JTable::getInstance('showresults', 'PropertiesTable');			
		if (!$row->bind($data)) {
			 $this->setError( $this->_db->getErrorMsg() );
			echo $this->_db->getErrorMsg();
			JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}
		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError( $this->_db->getErrorMsg() );
			echo $this->_db->getErrorMsg();
			JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}
		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );	
			echo $this->_db->getErrorMsg();	
				JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}		
}//fin clase