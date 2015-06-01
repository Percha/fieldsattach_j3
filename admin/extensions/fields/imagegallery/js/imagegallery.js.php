<?php header("Content-type: application/x-javascript");?>

/*
Copyright (c) 2007 John Dyer (http://percha.com)
MIT style license
*/
/*
if (!window.Refresh) Refresh = {};
if (!Refresh.Web) Refresh.Web = {};
 */ 

ObjImagegallery = new Class({
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
                $$("#fieldsattach-slider-imagegallery .updatebutton").setStyle("display","none");
                 
                
                //Timer Event
		setInterval(this.timerevent, 500);
                 
	},
        init: function(txt){
            valor = txt;
            var tmp = String(valor).split("|"); 
            opt1 = "";
            opt2 = "";
            opt3 = "";
            if(tmp.length > 0) opt1=tmp[0];
            if(tmp.length > 1) opt2=tmp[1];
            if(tmp.length > 2) opt3=tmp[2];
            this.setunit(opt1,opt2,opt3);
             
        },
        revalue: function(){
            var str ="";  
            
            el = $(this.id);

            
            console.log("revalue")
            if($('select#jform_params_galleryimage2') == null)   
            {  
                opt1 = el.getElement("select#params_galleryimage2").get("value"); 
                opt2 = el.getElement("select#params_galleryimage3").get("value");  
                opt3 = el.getElement("select#params_gallerydescription").get("value"); 
            }else{ 
                opt1 = el.getElement("select#jform_params_galleryimage2").get("value"); 
                opt2 = el.getElement("select#jform_params_galleryimage3").get("value");  
                opt3 = el.getElement("select#jform_params_gallerydescription").get("value"); 
            } 
       


            str += opt1; 
            str += '|';
            str += opt2;
            str += '|';
            str += opt3;
 
                      
            
            $("jform_extras").set("value", str);
             
            return str;
             
           
        },
        setunit:function(opt1,opt2,opt3)
        { 
            
            if($('select#jform_params_galleryimage2') == null)   
            {

                $('params_galleryimage2').set("value", opt1); 
                $('params_galleryimage3').set("value", opt2); 
                $('params_gallerydescription').set("value", opt3); 
            }else{
                $('jform_params_galleryimage2').set("value", opt1); 
                $('jform_params_galleryimage3').set("value", opt2); 
                $('jform_params_gallerydescription').set("value", opt3); 
            }
           
        },
	timerevent:function(obj){
             
            $("wrapperextrafield_imagegallery").ObjInput.revalue(); 
           
        } 
	
	 

});
