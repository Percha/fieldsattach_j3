<?php header("Content-type: application/x-javascript");?>
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
            opt2 = "";
            
            if(tmp.length > 0) opt1=tmp[0];  
            if(tmp.length > 1) opt2=tmp[1]; 
            

            this.setunit(opt1, opt2);
             
        },
        revalue: function(){
            var str ="";  
            
            el = $(this.id);

             
            if($('jform_params_field_textarea') == null)  opt1 = el.getElement("select#params_field_textarea").get("value"); 
            else  opt1 = el.getElement("select#jform_params_field_textarea").get("value");  

            opt2 =  el.getElement("select#params_field_textareaeditor").get("value");  

            str += opt1; 
            str += '|';   
            str += opt2;   

            
            $("jform_extras").set("value", str);
             
            return str;
             
           
        },
        setunit:function(opt1, opt2)
        { 
            if($('jform_params_field_textarea') == null) $('params_field_textarea').set("value", opt1); 
            else $('jform_params_field_textarea').set("value", opt1); 

            if($('jform_params_field_textareaeditor') == null) $('params_field_textareaeditor').set("value", opt2); 
            else $('jform_params_field_textareaeditor').set("value", opt2); 

        },
	timerevent:function(obj){
             
            $("wrapperextrafield_textarea").ObjInput.revalue(); 
           
        } 
	
	 

});
