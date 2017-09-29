<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_properties
 * @copyright	Copyright (C) 2006 - 2016 Fabio Esteban Uzeltinger.
 * @email		fabiouz@gmail.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class PropertiesViewProduct extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		// Initialiase variables.
		$doc = JFactory::getDocument();	
	//	$doc->addStyleSheet('components/com_properties/includes/css/product.css');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');		
		$this->state	= $this->get('State');
		$this->type_js	= $this->get('type_js');		
		$this->States_js	= $this->get('States_js');
		$this->Localities_js	= $this->get('Localities_js');
		$this->Profile = $this->get('Profile');	
		$params		= JComponentHelper::getParams('com_properties');
		$this->params = $params;
		$canAddProperties=$params->get('canAddProperties',5);
		$canAddImages=$params->get('canAddImages',5);

		if(!isset($this->Profile))
			{
			$this->Profile = new JObject();
		if(!isset($this->Profile->canaddproperties))
			{
			$this->Profile->canaddproperties=$canAddProperties;
			}
		if(!isset($this->Profile->canaddimages))
			{
			$this->Profile->canaddimages=$canAddImages;
			}	
		}
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		$this->addToolbar();		
		
		JHtml::_('jquery.framework');	
		
		if($this->item->id)
			{		
		
		$Images=$this->getImages($this->item->id);
		$this->assignRef('Images',		$Images);
		
		$document = JFactory::getDocument();
		$document->addScript("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js");  
		$session = JFactory::getSession();

		}
	
	
	
	
		$canDo	= PropertiesHelper::getActions();
		if ($canDo->get('core.manage')) {
		$this->iAmAdmin=true;
		}else{
		$this->iAmAdmin=false;
		$this->checkAgent();
		}
		JHTML::_('behavior.modal'); 	
			
		parent::display($tpl);
	}
	
	protected function checkAgent()
	{
	if($this->item->id and $this->Profile->id != $this->item->agent_id)
		{
				
		JFactory::getApplication()->enqueueMessage(JText::_('USER ERROR AUTHENTICATION FAILED : '. $this->Profile->name), 'error');
		}
	}
	
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo	= PropertiesHelper::getActions();
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		
		JToolBarHelper::title($isNew ? '&nbsp; &nbsp;'.JText::_('COM_PROPERTIES_MANAGER_PRODUCT_NEW') : '&nbsp; &nbsp;'.JText::_('COM_PROPERTIES_MANAGER_PRODUCT_EDIT').' : '.$this->item->name, 'products.png');
		
		JToolBarHelper::apply('product.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('product.save', 'JTOOLBAR_SAVE');
			
			JToolBarHelper::addNew('product.save2new', 'JTOOLBAR_SAVE_AND_NEW');
			
			if (empty($this->item->id))  {
			JToolBarHelper::cancel('product.cancel', 'JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('product.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	
	
	
	
	function getImages($id,$total=1)
	{	
	$db 	= JFactory::getDBO();	
	$query = ' SELECT i.* '			
			. ' FROM #__properties_images as i '					
			. ' WHERE i.published = 1 AND i.parent = '.$id			
			. ' order by i.ordering ';		
        $db->setQuery($query);
		$Images = $db->loadObjectList();
	return $Images;
	}
	
	
	
}
