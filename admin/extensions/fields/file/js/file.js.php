<?php header("Content-type: application/x-javascript");?>
<?php
$option1 = "Select";  
//JSON *************** 
if(isset($_GET["dictionary"])) 
{
  
   $langfile = $_GET["dictionary"]; 
   if(file_exists($langfile))
   { 
       $strlang = file_get_contents($langfile); 
       $strlang = str_replace('=', '":', $strlang);
       $tmp = array();
       
       $strlang = explode("\n", $strlang);
       foreach ($strlang as $line)
       {
            
           $pos = strpos($line, ";");

           if(!empty($line) && ($pos === false)) $tmp[count($tmp)] = '"'.$line;
       }
       $strlang = implode( $tmp ,",");
       
       $strlang = "{".$strlang."}";
       $obj = json_decode($strlang);
       if(isset($obj->{'COM_FIELDSATTACH_SELECTABLE'})) $option1 =  $obj->{'COM_FIELDSATTACH_SELECTABLE'}; //   n
        
       
   }  
}
?>
/*
Copyright (c) 2007 John Dyer (http://percha.com)
MIT style license
*/
/*
if (!window.Refresh) Refresh = {};
if (!Refresh.Web) Refresh.Web = {};
 */




ObjFile = new Class({
	_bar: null,
        

    Implements: [Options],

    options: { 
        arrowImage: 'refresh_web/colorpicker/images/rangearrows.gif'
    },

	initialize: function(id, options) {
	
		this.id = id;
                this.setOptions(options);
		
	       
	       

		// hook up controls
		this._bar = $(this.id);
                
                $(this.id).ObjInput = this; 
                
                $$("#fieldsattach-slider-file .updatebutton").setStyle("display","none");
                this.eventinput();
                 
	},
        init: function(txt){
            valor = txt;
            var tmp = String(valor).split("|");
            option1=""; 
            if(tmp.length > 0) 
             {
                option1=tmp[0]; 
                this.setunit(option1);
             }
             
        },
        revalue: function(){
            var str ="";
	    
	    
            
            el = $(this.id);

            option1 = el.getElement("select#jform_params_field_selectable").get("value"); 
            
            str = option1; 
  
            $("jform_extras").set("value", str); 
            
            return str;
             
           
        },
        setunit:function(option1)
        { 
            $$("#fieldsattach-slider-file select#jform_params_field_selectable").set("value", option1); 
        },
        eventinput:function(obj){
            
            /*CHANGE INOUT ****************************************/ 
            $$("#"+this.id+" select").removeEvent('change', function() {});
            $$("#"+this.id+" select").addEvent('change', function(event){
            event.stop(); //Prevents the browser from following the link.
            
            $("wrapperextrafield_file").ObjInput.revalue();
            });
	    
	   
            
	    
	    
           
        } 
	
	 

});

