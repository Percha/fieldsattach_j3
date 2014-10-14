<?php header("Content-type: application/x-javascript");?>
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

            if($('jform_params_field_selectable') == null)  option1 = el.getElement("select#params_field_selectable").get("value"); 
            else option1 = el.getElement("select#jform_params_field_selectable").get("value"); 
            
            str = option1; 
  
            $("jform_extras").set("value", str); 
            
            return str;
             
           
        },
        setunit:function(option1)
        { 
           if($('jform_params_field_selectable') == null) $$("#fieldsattach-slider-file select#params_field_selectable").set("value", option1);
           else  $$("#fieldsattach-slider-file select#jform_params_field_selectable").set("value", option1);
        },
        eventinput:function(obj){
            
            /*CHANGE INOUT ****************************************/ 
            $$("#"+this.id+" select").removeEvent('change', function() {});
            $$("#"+this.id+" select").addEvent('change', function(event){
            event.stop(); //Prevents the browser from following the link. 
             
            TT_file.revalue();
            }); 
           
        } 
	
	 

});

