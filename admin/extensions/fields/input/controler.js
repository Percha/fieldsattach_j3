// input  ========================================================================
function controler_percha_input()
{ 
    var extras = $('jform_extras');  
    contenr = $('jform_params_field_size').value+"|"+$('jform_params_field_maxlenght').value;
    
    extras.value = contenr;
    hide_input()
}

function hide_input()
{
    var nom =  'image-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
 
window.addEvent('domready', function() {
    var extras = $('jform_extras');  
    var tmp = String(extras.value).split("|"); 
    var maxlenght=""; 
    var size=""; 
    
    
    if(tmp.length>=1){ size  = tmp[0]; } 
    if(tmp.length>=2){  maxlenght = tmp[1]; } 
    
    $('jform_params_field_maxlenght').value = maxlenght; 
    $('jform_params_field_size').value = size; 
  
    
});