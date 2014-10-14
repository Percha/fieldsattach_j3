// link  ========================================================================


var one_image=true;
var TT_image;

function controler_percha_image()
{ 
    var extras = $('jform_extras');  
  
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
    
  
    
});