// imagegallery  ========================================================================


var one_imagegallery=true;
var TT_imagegallery;

function controler_percha_imagegallery()
{ 
    var extras = $('jform_extras');   
    //contenr = $('jform_params_galleryimage2').value+"|"+$('jform_params_galleryimage3').value+"|"+$('jform_params_gallerydescription').value;
    
    //extras.value = contenr;
    
    
     
     //New 
    if(one_imagegallery){
        TT_imagegallery = new ObjImagegallery("wrapperextrafield_imagegallery"); 
        //TT.init(extras.value);
        one_imagegallery=false;
    } 
    TT_imagegallery.init(extras.value); 
    
    
}
 


window.addEvent('domready', function() {
    
    /*var extras = $('jform_extras');  
    var tmp = String(extras.value).split("|"); 
    var galleryimage2=""; 
    var galleryimage3=""; 
    var gallerydescription="";
    
    
    if(tmp.length>=1){  galleryimage2  = tmp[0]; } 
    if(tmp.length>=2){  galleryimage3 = tmp[1]; } 
    if(tmp.length>=3){  gallerydescription = tmp[2]; } 
    
    $('jform_params_galleryimage2').value = galleryimage2; 
    $('jform_params_galleryimage3').value = galleryimage3;
    $('jform_params_gallerydescription').value = gallerydescription; 
    
    //hide_imagegallery();
   
     
    //Events ----------
    $('jform_params_galleryimage2').addEvent('change', controler_percha_imagegallery); 
    $('jform_params_galleryimage3').addEvent('change', controler_percha_imagegallery ); 
    $('jform_params_gallerydescription').addEvent('change', controler_percha_imagegallery ); */
    
});
 



