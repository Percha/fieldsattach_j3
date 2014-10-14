// FILE  ========================================================================
var one_file=true;
var TT_file;

function controler_percha_file()
{ 
    var extras = $('jform_extras');  
    //contenr = $('jform_params_field_width').value+"|"+$('jform_params_field_height').value;
   // contenr  = $('jform_params_field_selectable').value;
   // extras.value = contenr;
    hide_file();
    
    
    //New 
    if(one_file){
        TT_file = new ObjFile("wrapperextrafield_file"); 
        //TT.init(extras.value);
        one_file=false;
    } 
   TT_file.init(extras.value);
   
    $("jform_extras").setStyle('display','none');
    $("jform_extras-lbl").setStyle('display','none');
}

function hide_file()
{
    var nom =  'file-params'; 
    if($(nom)!=null)
    { 
        $($(nom).getParent( )).setStyle('display','none');
    } 

}
 


window.addEvent('domready', function() {
    /*var extras = $('jform_extras');  
    var tmp = String(extras.value).split("|"); 
    var selectable=""; 
    
    if(tmp.length>=1){ selectable = tmp[0]; } 
    
    $('jform_params_field_selectable').value = selectable; 
    
    */
     /*var html = $$('#fieldsattach-slider-file .content').get("html");
     html += '<div id="file_wrapper">';
      
     html += '</div>'
     //alert($$('#fieldsattach-slider-select .content'));
     $$('#fieldsattach-slider-file .content').set("html",  html);
    setInterval('$("wrapperextrafield_file").ObjInput.revalue();', 500);*/
  
    
});
