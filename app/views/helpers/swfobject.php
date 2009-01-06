<?php
/*
 * SWFObjectHelper is (c) 2006 Garrett J Woodworth aka gwoo and is released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 * @brief Class wrapper for the SWFObject.
 * @dependencies SWFObject v1.4.2: Flash Player detection and embed - http://blog.deconcept.com/swfobject/  put the js file in app/webroot/js/
 *
 */
class SwfobjectHelper extends Helper  { 
	
    var $div = 'flashcontent';   
	 
	var $flashvars = array();
	
	var $swfparams = array(); 
	
	var $attributes = array();
	 
	function display($path, $name, $width, $height, $version = '7', $bgColor = '#fff', $expressInstall = true)
	{
	    $out = '
			<div id="'.$this->div.'">

				<strong>You need to upgrade your Flash Player</strong>
				 <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Click here to upgrade your Flash Player</a>
			</div>
			'; 
		$out .= '<script type="text/javascript">
					// <![CDATA[
				';
		$out .= 'var so = new SWFObject("'.str_replace('//', '/', $this->webroot.$path).'", "'.$name.'", "'.$width.'", "'.$height.'", "'.$version.'", "'.$bgColor.'", "'.$expressInstall.'");';
			
		foreach ($this->flashvars as $flashvar=>$flashvarValue)
		{
			$out .= 'so.addVariable("'.$flashvar.'", "'.$flashvarValue.'");';
		}
		
		foreach ($this->swfparams as $swfparam=>$swfparamValue)
		{
			$out .= 'so.addParam("'.$swfparam.'", "'.$swfparamValue.'");';
		} 
		
		foreach ($this->attributes as $attr=>$attrValue)
		{
			$out .= 'so.setAttibute("'.$attr.'", "'.$attrValue.'");';
		}
		
		$out .= "so.useExpressInstall('/palmer/flash/expressinstall.swf');";
		
		$out .= 'so.write("'.$this->div.'");'; 
		
		$out .='
				// ]]>
			   	</script>
			   '; 
					
		return $out; 
	}    
	
	function addFlashvar($var, $value)
	{
		$this->flashvars[$var] = $value;
	} 
	
	function addParam($var, $value)
	{
		$this->swfparams[$var] = $value;
	}
	  
	function addAttribute($var, $value)
	{
		$this->attributes[$var] = $value;
	}
}
?>