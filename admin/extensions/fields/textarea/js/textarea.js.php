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




ObjTextArea = new Class({
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
                $$("#fieldsattach-slider-textarea .updatebutton").setStyle("display","none");
                
                 
		//Timer Event
		setInterval(this.timerevent, 500);
                 
	},
        init: function(txt){
            valor = txt;
            var tmp = String(valor).split("|"); 
            opt1 = "";
            if(tmp.length > 0) opt1=tmp[0]; 
            this.setunit(opt1);
             
        },
        revalue: function(){
            var str ="";  
            
            el = $(this.id);

            opt1 = el.getElement("select#jform_params_field_textarea").get("value"); 

            str += opt1;  
 
                      
            
            $("jform_extras").set("value", str);
             
            return str;
             
           
        },
        setunit:function(opt1)
        { 
            $('jform_params_field_textarea').set("value", opt1); 
        },
	timerevent:function(obj){
             
            $("wrapperextrafield_textarea").ObjInput.revalue(); 
           
        } 
	
	 

});
