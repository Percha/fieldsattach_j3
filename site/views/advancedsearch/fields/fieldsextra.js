//OBJECT ARTICLES =========================================
function fields(id, title)
{
    this.array_of_fields = new Array(); 
   
}
//OBJECT ARTICLE =========================================
function field(id, title){
        this.id = id;
        this.title = title;
        this.textfilter;
        this.type;
        this.rule;
    }
    
 
    
//FUNCTION ADD ID =========================================
/*fields.prototype.RenderLI = function(id, title)    // Define Method
{ 
   
   var ids = document.id("fieldsid_name").value;
   
    var sid = new Array(); 
    sid = (ids.split(",")); 
    
    for(var i=0; i<ids.length();i++)
        {
            alert("ids");
            
        }
   
   
}*/

//FUNCTION INIT LINK CONDITION =========================================
fields.prototype.initLinkcondition = function()    // Define Method
{
    
    
    //FIND IF EXIST ---------------------------------------
    var find = false;
    var rules = $('jform_params_paramlinkconditions').get('value'); 
    var tmprules=rules.split(","); 
     
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        
        if(IsNumeric(this.array_of_fields[cont].id)){
            var obj = $("linkcondition"+this.array_of_fields[cont].id);
            if(obj){
                
                if(tmprules.length>cont){
                    
                    $(obj).set('value', tmprules[cont]);
                }
                //alert($("rules"+this.array_of_fields[cont].id).get("value"));
                //var valor= obj.get("value");
                //var tmp = $('jform_request_rules').get("value");
                //if(cont>0) tmp += ",";
                //tmp += valor;
                //$('jform_request_rules').set('value',tmp); 
            }
        }
         
    }

}

//FUNCTION EVENT LINK CONDITION =========================================
fields.prototype.eventLink = function()    // Define Method
{   
    //FIND IF EXIST ---------------------------------------
    var find = false;
    $('jform_params_paramlinkconditions').set('value',""); 
    
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    { 
        if(IsNumeric(this.array_of_fields[cont].id)){
            var obj = $("linkcondition"+this.array_of_fields[cont].id);
            if(obj){
                //alert($("rules"+this.array_of_fields[cont].id).get("value"));
                var valor= obj.get("value");
                var tmp = $('jform_params_paramlinkconditions').get("value");
                if(cont>0) tmp += ",";
                tmp += valor;
                $('jform_params_paramlinkconditions').set('value',tmp); 
            }
        }
         
    }

}


//FUNCTION INIT RULE =========================================
fields.prototype.initRule = function()    // Define Method
{
    
    
    //FIND IF EXIST ---------------------------------------
    var find = false;
    var rules = $('jform_params_paramrules').get('value'); 
    var tmprules=rules.split(","); 
     
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        
        if(IsNumeric(this.array_of_fields[cont].id)){
            var obj = $("rules"+this.array_of_fields[cont].id);
            if(obj){
                
                if(tmprules.length>cont){
                    $(obj).set('value', tmprules[cont]);
                    
                    if(tmprules[cont] == "BETWEEN") {
                        $("jform_request_fields_"+this.array_of_fields[cont].id+"_value_2").setStyle("display", "block"); 
                        $("fields_"+this.array_of_fields[cont].id+"_text_2").setStyle("display", "block");
                    }else{
                        $("jform_request_fields_"+this.array_of_fields[cont].id+"_value_2").setStyle("display", "none");
                        $("fields_"+this.array_of_fields[cont].id+"_text_2").setStyle("display", "none");

                    }
                }
                
                
                //alert($("rules"+this.array_of_fields[cont].id).get("value"));
                //var valor= obj.get("value");
                //var tmp = $('jform_request_rules').get("value");
                //if(cont>0) tmp += ",";
                //tmp += valor;
                //$('jform_request_rules').set('value',tmp); 
            }
        }
         
    }

}



//FUNCTION EVENT RULE =========================================
fields.prototype.eventRule = function()    // Define Method
{
    
    
    //FIND IF EXIST ---------------------------------------
    var find = false;
    $('jform_params_paramrules').set('value',""); 
    
    
     
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        
        if(IsNumeric(this.array_of_fields[cont].id)){
            
            var obj = $("rules"+this.array_of_fields[cont].id);
            
            if(obj){
                //alert($("rules"+this.array_of_fields[cont].id).get("value"));
                var valor= obj.get("value");
                var tmp = $('jform_params_paramrules').get("value");
                if(cont>0) tmp += ",";
                tmp += valor;
                
                $('jform_params_paramrules').set('value',tmp); 
            }
            
            if(obj.get("value") == "BETWEEN") {
                $("jform_request_fields_"+this.array_of_fields[cont].id+"_value_2").setStyle("display", "block");
                 $("fields_"+this.array_of_fields[cont].id+"_text_2").setStyle("display", "block");
            }else{
                $("jform_request_fields_"+this.array_of_fields[cont].id+"_value_2").setStyle("display", "none");
                 $("fields_"+this.array_of_fields[cont].id+"_text_2").setStyle("display", "none");
            }
            
        }
         
    }

}
    
//FUNCTION ADD ID =========================================
fields.prototype.AddId = function(id, title, fieldname, textfilter, type)    // Define Method
{ 
    var obj_field = new field;
    obj_field.id  = id;
    obj_field.title  = title;
    
    obj_field.textfilter  = textfilter; 
    obj_field.type  = type; 
    obj_field.rule  = "LIKE"; 
    
    
      

    //FIND IF EXIST ---------------------------------------
    var find = false;
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        
        if(this.array_of_fields[cont].id == id) {find = true;break}
    }

    //IF NOT EXIST ADD ---------------------------------------
    if(!find) {this.array_of_fields[this.array_of_fields.length] = obj_field;}

    //RENDER --------------------------------------------------
    this.render_fieldsid(fieldname);
}





//FUNCTION REMOVE ID =========================================
fields.prototype.RemoveId = function(id,fieldname)    // Define Method
{  
     
    
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        var elid = this.array_of_fields[cont].id ;
        if(elid == id) this.array_of_fields.splice(cont,1);    
    }
    //RENDER --------------------------------------------------
    this.render_fieldsid(fieldname);
    
}

//FUNCTION RENDER =========================================
fields.prototype.render_fieldsid = function(fieldname)    // Define Method
{
    
    document.id(fieldname).value ="";
    document.getElementById("fieldslist").innerHTML ="" ;
    //$("articleslist").value="";

    /*
    var myString = new String('red,green,blue');
    var myArray = myString.split(',');
    */
    var tmpid="";
    var strall="";;
    
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        var last=false;
        if(cont+1>=this.array_of_fields.length){last=true;}
        
        var str = this.array_of_fields[cont].id +"_"+this.array_of_fields[cont].textfilter ; 
        tmpid += this.array_of_fields[cont].id;
        
        if (this.array_of_fields.length-1>cont) {
            str += ",";
            tmpid += ",";
            
            
        }
        strall += str;
        
        
       
        addLI( this.array_of_fields[cont].id, this.array_of_fields[cont].title, fieldname, this.array_of_fields[cont].textfilter,last);
    }
    
     
    if($("jform_request_fields")) $("jform_request_fields").set("value", strall);
    if($("jform_params_fields")) $("jform_params_fields").set("value", strall);
     
    
}
fields.prototype.changeFilter = function(id, fieldname)
{
    // alert(document.id(fieldname+"_value").value); 
    var valor = document.id("jform_request_fields_"+id+"_value").value;
    var valor2 = document.id("jform_request_fields_"+id+"_value_2").value;
    valor = String(valor).replace(/,/gi, "_");
    // alert(valor);
    
    var input = document.id(fieldname).value;
    var tmpids = String(input).split(",");
    for(var i=0; i< tmpids.length;i++)
        {
            var elid = String(tmpids[i]).split("_");
            //alert(elid[0]);
            if(elid[0] == id){
                tmp = id+"_"+valor;
                if(valor2!="") tmp+= "|"+valor2;
                
                tmpids[i] = tmp;
                
            }
        }
     
    document.id(fieldname).value = tmpids;
    
    
    
}

//FUNCTION AD LI =========================================
function addLI( id, text, fieldname, textfilter,last){
    var Parent = document.getElementById("fieldslist");
    var NewLI = document.createElement("LI");
    
    textfilter = String(textfilter).replace(/_/gi, ",");
    
   
    
    var tmptext = String(textfilter).split("|")
    if(tmptext.length==1) tmptext[1]="";
    
   
     
    //var tmptext = text;
    
    var title = text;
    text = '<div style="position:relative;width:100%; padding:10px 0 25px 0;border-bottom:#fff dotted 2px; ">';
    text += '<h3 style=" padding:0p 0px 10px 0;  ">'+title+'</h3>';
    text += '<h6>Condition</h6>';
    text += '<select name="rules'+id+'" id="rules'+id+'" class="rules" onchange="javascript:obj.eventRule()">';
    text += '<option value="LIKE">LIKE</option>';
    text += '<option value="EQUAL">EQUAL</option>';
    text += '<option value="NOTEQUAL">NOT EQUAL</option>';
    text += '<option value="HIGHER">HIGHER</option>';
    text += '<option value="LOWER">LOWER</option>';
    text += '<option value="BETWEEN">BETWEEN</option>';
    text += '</select>';
    
    //text += '<div style="width:100%; overflow:hidden;"><input type="text" name="'+fieldname+'_'+id+'_value" id="'+fieldname+'_'+id+'_value" onchange="javascript:obj.changeFilter('+id+',\''+fieldname+'\')" value="'+tmptext[0]+'" size="80" />';
    text += '<h5>Default value</h5>';
    text += '<div style="width:100%; overflow:hidden;"><input type="text" name="jform_request_fields_'+id+'_value" id="jform_request_fields_'+id+'_value" onchange="javascript:obj.changeFilter('+id+',\''+fieldname+'\')" value="'+tmptext[0]+'" size="80" />';
    
    text += '</div> ';
    text += '<div id="fields_'+id+'_text_2" style="width:100%; overflow:hidden;display:none;">AND</div>'
    //text += '<div style="width:100%; overflow:hidden;"><input type="text" name="'+fieldname+'_'+id+'_value_2" id="'+fieldname+'_'+id+'_value_2" style="display:visible;" onchange="javascript:obj.changeFilter('+id+',\''+fieldname+'\')" value="'+tmptext[1]+'" size="80" />';
    
    text += '<div style="width:100%; overflow:hidden;"><input type="text" name="jform_request_fields_'+id+'_value_2" id="jform_request_fields_'+id+'_value_2" style="display:hidden;" onchange="javascript:obj.changeFilter('+id+',\''+fieldname+'\')" value="'+tmptext[1]+'" size="80" />';
    text += '</div> ';
    /*if(!last) {
        text += '<div style="position:relative;width:100%;margin:10px 0 0 0; padding:5px 0 5px 0; overflow:hidden;">';
        text += '<select name="linkcondition'+id+'" id="linkcondition'+id+'" class="linkcondition" onchange="javascript:obj.eventLink()">';
        text += '<option value="AND">AND</option>';
        text += '<option value="OR">OR</option>'; 
        text += '</select></div>';
        
    }*/
    text +='<div style="position:absolute; top:10px; right:5px;"><a class="btn btn-primary" href="javascript:obj.RemoveId('+id+',\''+fieldname+'\')" >delete</a></div></div>';
    NewLI.innerHTML = text; 
    Parent.appendChild(NewLI);
    
    
    //ADD TO ORDER
    
    //get the current options selectId's options
    var options = $('jform_params_ordering').get('html'); 
    
    //Search if it is in select
    var forsearch = 'value="'+tmptext+'"'
   
    if(String(options).indexOf(forsearch)<0)
    {
         
         options = options + '<option value="'+tmptext+'">'+tmptext+'</option>'; 
         //$('jform_params_ordering').set('html', options); 
    }
    
   obj.initRule();
  // obj.initLinkcondition();
    
}



//CREATE OBJECT ARTICLES =========================================
obj = new fields; 


//MOOTOOLS EVENT =========================================
window.addEvent('domready', function() {
    //alert("admin");
   // alert($('[name=jform_params_ordering]').val());
    init_obj();
});
 
function IsNumeric(num) {
     
     return (num >=0 || num < 0);
}