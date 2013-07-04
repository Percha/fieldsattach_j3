// TEXTAREA  ========================================================================

var one_textarea=true;
var TT_textarea;

function controler_percha_textarea()
{ 
    var extras = $('jform_extras');
   // extras.set({
	//			'value':$('jform_params_field_textarea').value
	//		});
    hide_textarea();
    
    
     //New 
    if(one_textarea){
        TT_textarea = new ObjTextArea("wrapperextrafield_textarea"); 
        //TT.init(extras.value);
        one_textarea=false;
    } 
    TT_textarea.init(extras.value); 
    
}

function hide_textarea()
{
    var nom =  'input-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
 
window.addEvent('domready', function() {
    
  
    
});



