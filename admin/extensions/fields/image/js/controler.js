// link  ========================================================================


var one_image=true;
var TT_image;

function controler_percha_image()
{ 
    var extras = $('jform_extras');  
   // contenr = $('jform_params_field_width').value+"|"+$('jform_params_field_height').value;
    //contenr += "|"+$('jform_params_field_filter').value+"|"+$('jform_params_field_selectable2').value;
    //extras.value = contenr;
    hide_image();
    
     //New 
    if(one_input){
        TT_image = new ObjImage("wrapperextrafield_image"); 
        //TT.init(extras.value);
        one_image=false;
    } 
    TT_image.init(extras.value); 
    
}

function hide_image()
{
    var nom =  'image-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
 
window.addEvent('domready', function() {
    /*var extras = $('jform_extras');  
    var tmp = String(extras.value).split("|"); 
    var width="";
    var height  ="";
    var filter  ="";
    var selectable  ="";
    
    if(tmp.length>=1){ width = tmp[0]; }
    if(tmp.length>=2){ height = tmp[1]; }
    if(tmp.length>=3){ filter = tmp[2]; }
    if(tmp.length>=4){ selectable = tmp[3]; }
    
    $('jform_params_field_width').value = width;
    $('jform_params_field_height').value = height; 
    $('jform_params_field_filter').value = filter; 
    $('jform_params_field_selectable2').value = selectable; */
  
    
});