<?php header("Content-type: application/x-javascript");?>
<?php
$size = "Size";
$max_size = "Max size"; 
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
       if(isset($obj->{'COM_FIELDSATTACH_SIZE'})) $size =  $obj->{'COM_FIELDSATTACH_SIZE'}; // 
       if(isset($obj->{'COM_FIELDSATTACH_MAXWIDTH'})) $max_size =  $obj->{'COM_FIELDSATTACH_MAXWIDTH'}; // 
        
       
   }  
}

//if(isset($_GET["size"])) { $size = $_GET["size"]; }
//if(isset($_GET["max_size"])) { $max_size = $_GET["max_size"]; } 
?>
/*
Copyright (c) 2007 John Dyer (http://percha.com)
MIT style license
*/
/*
if (!window.Refresh) Refresh = {};
if (!Refresh.Web) Refresh.Web = {};
 */




ObjInput = new Class({
	_bar: null,
	_sortable: null ,
        _changeorder: false ,
        

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
                
                //Disable inputs
                $("jform_extras").setStyle('display','none');
                $("jform_extras-lbl").setStyle('display','none');
                $$("#fieldsattach-slider-input .updatebutton").setStyle("display","none");
                
                //Events
                this.eventinput();
                 
	},
        init: function(txt){
            valor = txt;
            var tmp = String(valor).split("|");
            size="";
            max_size = "";
            if(tmp.length > 0) size=tmp[0];
            if(tmp.length > 1) max_size=tmp[1];
            this.setunit(size,max_size);
             
        },
        revalue: function(){
            var str ="";  
            
            el = $(this.id);

            size = el.getElement("input#jform_params_field_size").get("value");
            max_size = el.getElement("input#jform_params_field_maxlenght").get("value");

            str += size;
            str += "|"
            str += max_size; 
 
                      
            if(size != ""){ 
                $("jform_extras").set("value", str);
            } 
            return str;
             
           
        },
        setunit:function(size, max_size)
        { 
            $('jform_params_field_size').set("value", size);
            $('jform_params_field_maxlenght').set("value", max_size); 
        },
        eventinput:function(obj){
             
            /*CHANGE INOUT ****************************************/ 
            $$("#"+this.id+" input").removeEvent('change', function() {});
            $$("#"+this.id+" input").addEvent('change', function(event){
            event.stop(); //Prevents the browser from following the link.
            
            $("wrapperextrafield_input").ObjInput.revalue();
            });
            
           
        } 
	
	 

});
