<?php
/**
 * @version		$Id: properties.php 1 2006-2016 este8an $
 * @package		Joomla.Administrator
 * @subpackage	com_properties
 * @copyright	Copyright (C) 2008 - 2016 Fabio Esteban Uzeltinger.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
$params = $this->params;
$LinkHelper = new LinkHelper();
$propertyItemID=$LinkHelper->getItemid('property');
$mapItemID=$LinkHelper->getItemid('map');
$contactItemID=$LinkHelper->getItemid('contact');
$imagePath = 'images/properties/images/';

$doc = JFactory::getDocument();
?>

<div class="propertieslist">
<?php
    $k = 0;
	for ($i_p=0, $n=count( $this->items ); $i_p < $n; $i_p++)	
    {
$row = $this->items[$i_p];	
$row->cat_currency=null;
$link = $this->getPropertyViewLink($row);
$imageData = $this->Images[$row->id][0];
$style = '';

?>
<div class="product">
<div class="product_agent">
	<div class="product_inner">    
		<div class="product_header">
        	<div class="product_title">
            	<h2>
                	<a href="<?php echo $link;?>"><?php echo $row->name;?></a>
                </h2>   
            </div>
        </div>        
        
        <div class="row">
        	<div class="col-sm-4">
            <div class="product_image">
        <img class="img-responsive" src="<?php echo $imagePath.$imageData->parent.'/'.$imageData->id.'_'.$imageData->parent.'.'.$imageData->type;?>" alt="<?php echo $imageData->name;?>" />
        	</div>
            </div>
            
            <div class="col-sm-8">     
            	
                <div class="product_renglon_detalle">
        			<?php echo $row->ref.' '.$row->name_type.' '.$row->name_category.'. '.$row->address.', '.$row->name_locality.'.';?>
                    <br />
                    <?php echo $row->capacity.' ';echo $row->capacity == 1 ? JText::_('COM_PROPERTIES_LIST_PERSON') : JText::_('COM_PROPERTIES_LIST_PERSONS'); ?>
                    <?php echo $row->bedrooms.' ';echo $row->bedrooms == 1 ? JText::_('COM_PROPERTIES_LIST_BEDROOM') : JText::_('COM_PROPERTIES_LIST_BEDROOMS'); ?>
             
        		</div>
                                       
            	<div class="product_description">
        		<?php echo substr(strip_tags($row->text),0,300).' ...';?>
        		</div>
                
                
                
        	</div>
        </div>    
        <div class="row">   
         
            <div class="col-sm-4 list-button-more-left">
            
            </div>
            
            <div class="col-sm-4 list-button-more-center">
           	 
            </div>
            
            <div class="col-sm-4 list-button-more-right">
            <a class="btn btn-md btn-success btn-block" href="<?php echo $link;?>" title="">Ver Detalles</a>
            </div>
            
        </div>
        
        
        
        
        
      
        
        
  <?php
if(isset($product_details)){
?>       
        
<div class="product_details">    
     
     <div class="details_extras_text">
     <div class="details_extras_text_inner">
<?php
	$ex = null;
	$extras = null;
	 $ex = (array)$row;	 
	 
	 $z=0;
	 for($i=1;$i<11;$i++)
	 	{		
		if($ex['extratext'.$i])
			{
			 $extras[$z]['title'] = '';
			 $extras[$z]['value'] = $ex['extratext'.$i];
			 $extras[$z]['id'] = $i;
			 $z++;
			}
	 	}
	 for($i=1;$i<41;$i++)
	 	{		
		if($ex['extra'.$i])
			{
			 $extras[$z]['title'] = '';
			 $extras[$z]['value'] = JText::_('PROPERTIES_DETAIL_EXTRA_'.$i);
			 $extras[$z]['id'] = $i;
			 $z++;
			}
	 	}	 

		$extrasTotal = count($extras);
		
		if($extrasTotal%2==0){$extrasMiddle = $extrasTotal/2;}else{$extrasMiddle = (int)(($extrasTotal/2)+1);}
		 ?> 
     <ul class="details_extras">
     <?php
	 for($x=0;$x<$extrasMiddle;$x++)
	 	{
		echo '<li>'.$extras[$x]['title'].$extras[$x]['value'].' </li>';		
		}	  	 
	 ?> 
     </ul>
     
     <ul class="details_extras">
     <?php
	 for($x=$extrasMiddle;$x<$extrasTotal;$x++)
	 	{
		echo '<li>'.$extras[$x]['title'].''.$extras[$x]['value'].' </li>';
		}	  	 
	 ?> 
     </ul>   
    </div>
    </div>
</div>
      
      
<?php
}
?>   
        
    
		</div>     <!--product_inner-->  
    </div>         <!--product_agent-->          
</div>  <!--product -->   



<?php
$k = 1 - $k;
}
?>


</div><!--	postswrapper	-->


 
<?php

if($this->pagination){
?>  
<div class="pagination">	
	  <?php echo $this->pagination->getPagesLinks(); ?>
 	<div style="clear: both;"></div>
<p class="counter">
      <?php echo $this->pagination->getResultsCounter().' | '.$this->pagination->getPagesCounter(); ?>
</p>           
<div style="clear: both;"></div> 
</div>
<?php
}
?>


<?php
if(JRequest::getVar('format') != 'ajax')
{
?>

<div style="width:100%; height:100px; float:left">
<div id="loadmoreajaxloader" style="display: none; background:url(media/com_properties/img/ajax-loader.gif) center center no-repeat;"> &nbsp; </div>
<div id="loadmoreajaxnomore" style="display: none;"><center> No más propiedades para mostrar </center></div>
</div>






<div id="myContactModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
<!--
<iframe scrolling="no" frameborder="0" class="iframe" src="index.php?option=com_properties&view=contact&id=1&layout=modal" name="Tipo de elemento del menú" height="430px" width="100%"></iframe>
-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php
}
?>