<?php
/*------------------------------------------------------------------------
# com_properties
# ------------------------------------------------------------------------
# author Fabio Esteban Uzeltinger
# copyright Copyright (C) 2011 com-property.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites:  www.com-property.com
# Technical Support: www.com-property.com/forum-v4
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class PropertiesControllerSearchResults extends JControllerForm
{		
	function getAlias($table,$id)
		{
		$db 	= JFactory::getDBO();
		$query = 'SELECT alias FROM #__properties_'.$table.' WHERE id = '.$id;		
        $db->setQuery($query);        
		$result = $db->loadResult();
		return $result;
		}		
	function showResults()
		{
		JRequest::checkToken() or jexit( 'Invalid Token' );	
		$post = JRequest::get( 'post' );
		$paramsC = JComponentHelper::getParams('com_contact');		
		$SaveSearchResults		=	$paramsC->get('SaveSearchResults');	
		$badchars = array('#','>','<','\\'); 		
		$urlVars['option'] = $post['option'];		
		$urlVars['view'] = 'search';				
		if($country = JRequest::getInt('cyid', '', 'post'))
			{	
			$urlVars['cy'] = $country;
			}			
		if($state = JRequest::getInt('sid', 0, 'post'))
			{	
			$urlVars['s'] = $state;
			}
		if($locality = JRequest::getInt('lid', 0, 'post'))
			{		
			$urlVars['l'] = $locality;
			}		
		
		if($category = JRequest::getInt('cid', 0, 'post'))
			{			
			$categoryAlias = $this->getAlias('category',$category);			
			$urlVars['c'] = $category;//.':'.$categoryAlias;
			$saveData['cid'] = $category;
			}	
		
		if($type = JRequest::getInt('tid', 0, 'post'))
			{		
			$typeAlias = $this->getAlias('type',$type);			
			$urlVars['t'] = $type;//.':'.$typeAlias;
			$saveData['tid'] = $type;
			}	
		
		if($capacity = JRequest::getInt('p', 0, 'post'))
			{	
			$capacityAlias = $capacity == 1 ? 'persona' : 'personas';	
			$urlVars['p'] = $capacity.':'.$capacityAlias;
			$saveData['capacity'] = $capacity;
			}
				
		/*if($bedrooms = JRequest::getInt('bedrooms', 0, 'post'))
			{		
			$bedroomsAlias = $bedrooms == 1 ? 'dormitorio' : 'dormitorios';	
			$urlVars['bedrooms'] = $bedrooms.':'.$bedroomsAlias;
			$saveData['bedrooms'] = $bedrooms;
			}*/	
			
		if($bedrooms = JRequest::getInt('bedrooms', 0, 'post'))
			{		
			$urlVars['bd'] = $bedrooms;
			$saveData['bedrooms'] = $bedrooms;
			}
				
		if($bathrooms = JRequest::getInt('bathrooms', 0, 'post'))
			{		
			$urlVars['bt'] = $bathrooms;
			}	
			
		if($garage = JRequest::getInt('garage', 0, 'post'))
			{		
			$urlVars['g'] = $garage;
			}	

		$signs = array('#','>','<','\\',',','.'); 

		if($minprice = JRequest::getVar('minprice', 0, 'post'))
			{		
			$minprice = trim(str_replace($signs, '', $minprice));
			$urlVars['minprice'] = (int)$minprice;
			}
		
		if($maxprice = JRequest::getVar('maxprice', 0, 'post'))
			{		
			$maxprice = trim(str_replace($signs, '', $maxprice));
			$urlVars['maxprice'] = (int)$maxprice;
			}									
		
		if($currency = JRequest::getVar('currency', 0, 'post'))
			{		
			$currency = trim(str_replace($signs, '', $currency));
			$urlVars['currency'] = $currency;
			}	
					
		if($minarea = JRequest::getInt('minarea', 0, 'post'))
			{
			$urlVars['minarea'] = $minarea;	
			}	
							
		if($maxarea = JRequest::getInt('maxarea', 0, 'post'))
			{
			$urlVars['maxarea'] = $maxarea;	
			}
		if($minareacov = JRequest::getInt('minareacov', 0, 'post'))
			{
			$urlVars['minareacov'] = $minareacov;	
			}	
							
		if($maxareacov = JRequest::getInt('maxareacov', 0, 'post'))
			{
			$urlVars['maxareacov'] = $maxareacov;	
			}
		
		if($extra1 = JRequest::getInt('e1', 0, 'post'))
			{
			$urlVars['e1'] = $extra1;	
			}
		if($extra2 = JRequest::getInt('e2', 0, 'post'))
			{
			$urlVars['e2'] = $extra2;	
			}
		if($extra3 = JRequest::getInt('e3', 0, 'post'))
			{
			$urlVars['e3'] = $extra3;	
			}	
		if($extra4 = JRequest::getInt('e4', 0, 'post'))
			{
			$urlVars['e4'] = $extra4;	
			}	
		if($extra5 = JRequest::getInt('e5', 0, 'post'))
			{
			$urlVars['e5'] = $extra5;	
			}
		if($extra6 = JRequest::getInt('e6', 0, 'post'))
			{
			$urlVars['e6'] = $extra6;	
			}	
		if($extra7 = JRequest::getInt('e7', 0, 'post'))
			{
			$urlVars['e7'] = $extra7;	
			}	
		if($extra8 = JRequest::getInt('e8', 0, 'post'))
			{
			$urlVars['e8'] = $extra8;	
			}	
		if($extra9 = JRequest::getInt('e9', 0, 'post'))
			{
			$urlVars['e9'] = $extra9;	
			}	
		if($extra10 = JRequest::getInt('e10', 0, 'post'))
			{
			$urlVars['e10'] = $extra10;	
			}
		
		$textsearch = trim(str_replace($badchars, '', JRequest::getString('textsearch', null, 'post')));
		if($textsearch)
			{
			$urlVars['textsearch'] = $textsearch;	
			}				
				
			$menu = JFactory::getApplication()->getMenu();
			$items	= $menu->getItems('link', 'index.php?option=com_properties&view=search');
			
			if(isset($items[0])) {
				$urlVars['Itemid'] = $items[0]->id;
				$urlVars['view'] = 'search';	
			}else{			
			$items	= $menu->getItems('link', 'index.php?option=com_properties&view=properties');
			$urlVars['Itemid'] = $items[0]->id;		
			$urlVars['view'] = 'properties';				
			}			
		
		$uri = JURI::getInstance();
		$uri->setQuery($urlVars);

		$SaveSearchResults = true;
		
		if($SaveSearchResults)
		{
		$model = $this->getModel('showresults');
		$db 	= JFactory::getDBO();
		$query = 'SELECT id,hits FROM #__properties_showresults WHERE url = \''.JRoute::_('index.php'.$uri->toString(array('query', 'fragment')), false).'\'';		
        $db->setQuery($query);        
		$result = $db->loadObject();
		if($result)
			{
			$saveData['id']=$result->id;
			$saveData['hits']=$result->hits+1;			
			}else{		
		/*$saveData = $urlVars;
		unset($saveData['option']);
		unset($saveData['view']);
		unset($saveData['task']);*/
		$saveData['url']=JRoute::_('index.php'.$uri->toString(array('query', 'fragment')), false);
		//$saveData['garage'] = $parking;
		//$datenow = JFactory::getDate();			
		//$saveData['date'] = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		$saveData['hits']=1;	
			}			
			$model->store($saveData);
		}
//parent::display();

//echo str_replace('%3A',':',$uri->toString(array('query', 'fragment')));
//require('a');

$redirectLink = JRoute::_('index.php'.str_replace('%3A',':',$uri->toString(array('query', 'fragment'))), false);
$redirectLink = str_replace('%3A',':',$redirectLink);

$this->setRedirect($redirectLink);	
		
		//$this->setRedirect('index.php'.$uri->toString(array('query', 'fragment')));		
	
	}
	
	
	
	
	
	
}
?>