<?php header("Content-type: application/x-javascript");?>
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

            //alert('init:'+max_size);

            this.setunit(size,max_size);
             
        },
        revalue: function(){
            var str ="";  
            
            el = $(this.id); 
            
            if($('jform_params_field_size') == null)  size = el.getElement("input#params_field_size").get("value");
            else  size = el.getElement("input#jform_params_field_size").get("value");

            if($('jform_params_field_maxlenght') == null)   max_size = el.getElement("input#params_field_maxlenght").get("value");
            else  max_size = el.getElement("input#jform_params_field_maxlenght").get("value");
 

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
            
            if($('jform_params_field_size') == null) $('params_field_size').set("value", size);
            else $('jform_params_field_size').set("value", size);

            if($('jform_params_field_maxlenght') == null) $('params_field_maxlenght').set("value", max_size);
            else $('jform_params_field_maxlenght').set("value", max_size); 
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
