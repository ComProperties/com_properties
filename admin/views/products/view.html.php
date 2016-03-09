<?php
/**
 * @version		$Id: properties.php 1 2006-2016 este8an $
 * @package		Joomla.Administrator
 * @subpackage	com_properties
 * @copyright	Copyright (C) 2006 - 2016 Fabio Esteban Uzeltinger.
 * @email		fabiouz@gmail.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );

class PropertiesViewProducts extends JViewLegacy
	{
	protected $items;
	protected $pagination;
	protected $state;	
	
	public function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			PropertiesHelper::addSubmenu('properties');
		}
		// Initialise variables.
		$doc = JFactory::getDocument();
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->Profile = $this->get('Profile');	

		$params		= JComponentHelper::getParams('com_properties');
		$canAddProperties=$params->get('canAddProperties',5);
		$canAddImages=$params->get('canAddImages',5);
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}		
		if($this->items)
		{
			foreach($this->items as $item)
			{
			$this->Images[$item->id]=$this->getImages($item->id);				
			}	
		}		
		$canDo	= PropertiesHelper::getActions();
		if ($canDo->get('core.manage')) 
		{
			$this->iAmAdmin=true;
			}else{
			$this->iAmAdmin=false;		
		}
		
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();	
			$this->sidebar = JHtmlSidebar::render();		
		}

	if(!$this->iAmAdmin)
		{		
		if(!$this->Profile->id)
			{
			JFactory::getApplication()->enqueueMessage('Error in your account', 'error');
			return false;
			}			
		if(!$this->Profile->canaddproperties)
			{
			$this->Profile->canaddproperties=$canAddProperties;
			}
			if(!$this->Profile->canaddimages)
			{
			$this->Profile->canaddimages=$canAddImages;
			}	
		}		
		parent::display($tpl);
	}	
		
	
	protected function addToolbar()
	{
		$state	= $this->get('State');	
	//	print_r($state);
		$canDo	= PropertiesHelper::getActions();
		if(!$this->iAmAdmin)
		{
		$subTitle = count($this->items).' '.JText::_('Properties added of: ').' '.$this->Profile->canaddproperties;
		}else{
		$subTitle = count($this->items);
		}
		JToolBarHelper::title('&nbsp; &nbsp;'.JText::_('COM_PROPERTIES_MANAGER_PRODUCTS').' '.' <small><small>[ '.$subTitle.' ]</small></small>', 'products.png');
		
		if ($canDo->get('core.manage.product')) {
			JToolBarHelper::custom('product.add', 'new.png', 'new_f2.png','JTOOLBAR_NEW', false);		
			JToolBarHelper::custom('product.edit', 'edit.png', 'edit_f2.png','JTOOLBAR_EDIT', true);		
			JToolBarHelper::divider();			
			JToolBarHelper::custom('products.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('products.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);					
		}
	if($this->items)
		{	
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.admin')) 
			{
			JToolBarHelper::divider();
			JToolBarHelper::deleteList('', 'products.delete','JTOOLBAR_EMPTY_TRASH');
			} else if ($canDo->get('core.manage.product')) {
			JToolBarHelper::divider();
			JToolBarHelper::trash('products.trash','JTOOLBAR_TRASH');
			}	
		}		
	}		
		
	protected function getSortFields()
	{
		return array(
			'a.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
			'a.published'        => JText::_('JSTATUS'),
			'a.name'        => JText::_('JGLOBAL_TITLE'),
			'a.id'           => JText::_('JGRID_HEADING_ID')
		);
	}	
	
	function getImages($id,$total=1)
	{	
	$db 	= JFactory::getDBO();	
	$query = ' SELECT i.* '			
			. ' FROM #__properties_images as i '					
			. ' WHERE i.published = 1 AND i.parent = '.$id			
			. ' order by i.ordering limit 1';		
        $db->setQuery($query);  
		$Images = $db->loadObject();
	return $Images;
	}	
	
	function getStats($product,$modify,$fecha)
	{
	$db = JFactory::getDBO();
	$d = new DateTime();
	$dateTime = $d->format('Y-m-d');
	if($modify)
		{
		$d->modify('-30 day');
		$dateYester = $d->format('Y-m-d');
		$query = 'SELECT * FROM #__properties_stats where pid = "'.$product->id.'" AND date BETWEEN "'.$dateYester.'" AND "'.$dateTime.'"';
		}else{		
		$query = 'SELECT * FROM #__properties_stats where pid = "'.$product->id.'" AND date = "'.$fecha.'"';		
		}	
	$db->setQuery($query);
	//echo $query;
	$stats = $db->loadObjectList();			
	return $stats;		
	}		
}