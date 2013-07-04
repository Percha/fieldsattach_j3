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
      // if(isset($obj->{'COM_FIELDSATTACH_SIZE'})) $size =  $obj->{'COM_FIELDSATTACH_SIZE'}; // 
      // if(isset($obj->{'COM_FIELDSATTACH_MAXWIDTH'})) $max_size =  $obj->{'COM_FIELDSATTACH_MAXWIDTH'}; // 
        
       
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




ObjImage = new Class({
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
                $$("#fieldsattach-slider-image .updatebutton").setStyle("display","none");
                
                //Events
                this.eventinput();
                 
	},
        init: function(txt){
            valor = txt;
            var tmp = String(valor).split("|");
            opt1="";
            opt2 = "";
            opt3 = "";
            opt4 = "";
            if(tmp.length > 0) opt1=tmp[0];
            if(tmp.length > 1) opt2=tmp[1];
            if(tmp.length > 2) opt3=tmp[2];
            if(tmp.length > 3) opt4=tmp[3];
            this.setunit(opt1,opt2,opt3,opt4);
             
        },
        revalue: function(){
            var str ="";  
            
            el = $(this.id);

            opt1= el.getElement("input#jform_params_field_width").get("value"); 
            opt2= el.getElement("input#jform_params_field_height").get("value"); 
            opt3= el.getElement("select#jform_params_field_filter").get("value"); 
            opt4= el.getElement("select#jform_params_field_selectable2").get("value");
	    
	   

            str += opt1;
            str += "|"; 
            str += opt2;
            str += "|"; 
            str += opt3; 
            str += "|"; 
            str += opt4; 
 
            
           
            $("jform_extras").set("value", str);
            
            return str;
             
           
        },
        setunit:function(opt1, opt2, opt3, opt4)
        {
	    
            $$("#wrapperextrafield_image select#jform_params_field_selectable2").set("value", opt4); 
            $$("#wrapperextrafield_image input#jform_params_field_width").set("value", opt1);
            $$("#wrapperextrafield_image input#jform_params_field_height").set("value", opt2);
            $$("#wrapperextrafield_image select#jform_params_field_filter").set("value", opt3);
	    
        },
        eventinput:function(obj){
             
            /*CHANGE INOUT ****************************************/ 
            $$("#"+this.id+" input").removeEvent('change', function() {});
            $$("#"+this.id+" input").addEvent('change', function(event){
                event.stop(); //Prevents the browser from following the link.
            
                $("wrapperextrafield_image").ObjInput.revalue();
            });
            
            //SELECT
            $$("#"+this.id+" select").removeEvent('change', function() {});
            $$("#"+this.id+" select").addEvent('change', function(event){
                event.stop(); //Prevents the browser from following the link.
            
                $("wrapperextrafield_image").ObjInput.revalue();
            });
	    
	    $$(".chzn-results li").removeEvent('click', function() {});
	    $$(".chzn-results li").addEvent('click', function(event){
                event.stop(); //Prevents the browser from following the link.
            
                $("wrapperextrafield_image").ObjInput.revalue();
            });
            
           
        } 
	
	 

});
