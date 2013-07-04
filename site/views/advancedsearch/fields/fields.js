//OBJECT ARTICLES =========================================

function fields(id, title)
{
    this.array_of_fields = new Array(); 
   
}
//OBJECT ARTICLE =========================================
function field(id, title){
        this.id = id;
        this.title = title;
    }
    
 
    
//FUNCTION ADD ID =========================================
fields.prototype.RenderLI = function(id, title)    // Define Method
{ 
   
   var ids = document.id("fieldsid_name").value;
   
    var sid = new Array(); 
    sid = (ids.split(",")); 
    
    for(var i=0; i<ids.length();i++)
        {
            alert("ids");
            
        }
   
   
}
   
    
//FUNCTION ADD ID =========================================
fields.prototype.AddId = function(id, title, fieldname)    // Define Method
{ 
    var obj_field = new field;
    obj_field.id  = id;
    obj_field.title  = title;
    
    

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
   
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        var str = this.array_of_fields[cont].id ;
            

        if (this.array_of_fields.length-1>cont) str += ",";
        document.id(fieldname).value += str;
        addLI( this.array_of_fields[cont].id, this.array_of_fields[cont].title, fieldname);
    }
}
//FUNCTION AD LI =========================================
function addLI( id, text, fieldname){
    var Parent = document.getElementById("fieldslist");
    var NewLI = document.createElement("LI");

    text = '<div style="position:relative;width:100%; padding:5px 0 5px 0;border-top:#ddd dotted 1px;"><div style="  padding:5px 0 5px 0;">'+text+'</div>  <div style="position:absolute; top:10px; right:5px;"><a href="javascript:obj.RemoveId('+id+',\''+fieldname+'\')" >delete</a></div></div>';
     
    NewLI.innerHTML = text; 
    Parent.appendChild(NewLI);
} 

//CREATE OBJECT ARTICLES =========================================
obj = new fields; 


//MOOTOOLS EVENT =========================================
window.addEvent('domready', function() {
    //alert("admin");
    init_obj();
});
 
