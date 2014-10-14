// input  ========================================================================

var one_input=true;
var TT_input;

function controler_percha_input()
{ 
    var extras = $('jform_extras');  
     
    hide_input()
    
    //New 
    if(one_input){
        TT_input = new ObjInput("wrapperextrafield_input"); 
        //TT.init(extras.value);
        one_input=false;


    } 
   TT_input.init(extras.value); 
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
     
  
    
});