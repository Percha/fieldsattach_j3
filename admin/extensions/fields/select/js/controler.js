// TEXTAREA  ========================================================================
var one_select=true;
var TT;

function controler_percha_select()
{ 
    var extras = $('jform_extras');
   //extras.value  =  extras.value + "\n"+ $('jform_params_field_select_name').value+"|"+$('jform_params_field_select_value').value
   // if($('jform_params_field_selectplus_default').checked == true) extras.value += "|true";
    
    /*extras.set({
				'value': extras.value
			});*/
    hide_select();
    //alert("controler select");
    
    //New 
    if(one_select){
        TT = new ObjSelect("select_1"); 
        //TT.init(extras.value);
        one_select=false;
    } 
   TT.init(extras.value);
   

}

function hide_select()
{
    var nom =  'select-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
    
    
 

}
 

window.addEvent('domready', function() {
     //alert("sss percha_select-params");
     
     var html = $$('#fieldsattach-slider-select .content').get("html");
     html += '<div id="select_wrapper">';
     html += '<div class="selectheader"><button name="select_1_add" id="select_1_add" />Add</button></div>';
    // html += '<div><button name="select_1_reorder" id="select_1_reorder" />Reorder</button></div>';
     html += ' <ul id="select_1" class="sortables">';
           
     html += ' </ul>';
     html += '</div>'
     //alert($$('#fieldsattach-slider-select .content'));
     $$('#fieldsattach-slider-select .content').set("html",  html);
     /*var TT = new ObjSelect("select_1");  
     
     $('init').addEvent('click', function(event){
               alert($("texto").get("value"));
               
               TT.init($("texto").get("value"))
                
            });*/
});
      