<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_properties
 * @copyright	Copyright (C) 2006 - 2016 Fabio Esteban Uzeltinger.
 * @email		fabiouz@gmail.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt -31.503629 -66.093750
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class PropertiesControllerMap extends JControllerForm
{	
public function mapgetcoord()
	{ 
	JFactory::getApplication()->input->set('tmpl', 'component');
	$params	= JComponentHelper::getParams('com_properties');
	$UseCountryDefault=$params->get('UseCountryDefault',0);
			
$apikey    = $params->get( 'MapApiKey' );
$distancia= $params->get( 'MapDistance',15 );
$DefaultLat= $params->get( 'DefaultLat',0 );
$DefaultLng= $params->get( 'DefaultLng',0 );
$jinput = JFactory::getApplication()->input;
$Pid 		= $jinput->get('id');
$db 	= JFactory::getDBO();
if($Pid)
{
$query = 'SELECT p.*,t.name AS name_category '
				. ' FROM #__properties_products AS p '
				. 'LEFT JOIN #__properties_category AS t ON t.id = p.cid '	
				. 'WHERE p.id = '.$Pid ;
$db->setQuery($query);	        
$Prod = $db->loadObject();
}

$lat= $DefaultLat;
$lng=$DefaultLng;
if(isset($Prod))
{
$lat=$Prod->lat!=0 ? $Prod->lat : $DefaultLat;
$lng=$Prod->lng!=0 ? $Prod->lng : $DefaultLng;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
  <head> 
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/> 
    <title>Google Maps JavaScript API Example</title> 
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $apikey;?>"
      type="text/javascript"></script> 
   
  </head> 
<body style="width: 750px; height: 400px; padding:0px; margin:0px;"> 
<div id="map" style="width: 750px; height: 400px;"></div>  
<form action="" name="formgetcoord" method="post">
<input type="text" id="getlat" name="getlat" value="<?php echo $lat;?>" />
<input type="text" id="getlng" name="getlng" value="<?php echo $lng;?>" />

<button onclick="window.parent.jSelectCoord(document.getElementById('getlat').value,document.getElementById('getlng').value);SqueezeBox.close();" value="<?php echo JText::_( 'Add Coord' );?>"><?php echo JText::_( 'Add Coord' );?></button>



</form>  


    <script type="text/javascript"> 

   var map = new GMap2(document.getElementById("map")); 
        map.setCenter(new GLatLng(<?php echo $lat;?>, <?php echo $lng;?>), <?php echo $distancia;?>); 
	<!--map.setMapType(G_HYBRID_MAP); -->
 
	map.addControl(new GSmallMapControl()); 
	/*map.addControl(new GScaleControl()); */
	map.addControl(new GMapTypeControl()); 
/*	map.addControl(new GOverviewMapControl());*/ 
 
	var marker = new GMarker(new GLatLng(<?php echo $lat;?>, <?php echo $lng;?>)); 
	map.addOverlay(marker); 
	
GEvent.addListener(map, 'click', function(overlay, point) {
			if (overlay) {
				map.removeOverlay(overlay);
			} else if (point) {
				/*map.recenterOrPanToLatLng(point);*/
				var marker = new GMarker(point);
				map.addOverlay(marker);
				var matchll = /\(([-.\d]*), ([-.\d]*)/.exec( point );
				if ( matchll ) { 
					var lat = parseFloat( matchll[1] );
					var lon = parseFloat( matchll[2] );
					lat = lat.toFixed(6);
					lon = lon.toFixed(6);
					var message = "lat=" + lat + "<br>lon=" + lon + " "; 
					var messageRoboGEO = lat + ";" + lon + ""; 
				} else { 
					var message = "<b>Error extracting info from</b>:" + point + ""; 
					var messagRoboGEO = message;
				}

				marker.openInfoWindowHtml(message);

				document.getElementById("getlat").value = lat;
				document.getElementById("getlng").value = lon;

			}
		});
		
			/*
document.getElementById("frmLat").value = setLat;
		document.getElementById("frmLon").value = setLon;
        */
        
        
    </script> 
</body> 
</html> 
<?php }
}
