<?php header("Content-type: application/x-javascript");?>
<?php
$title = "Title";
$value = "Value";
$defaul = "Default";
if(isset($_GET["title"])) { $title = $_GET["title"]; }
if(isset($_GET["value"])) { $value = $_GET["value"]; }
if(isset($_GET["defaul"])) { $defaul = $_GET["defaul"]; }
?>
/*
Copyright (c) 2007 John Dyer (http://percha.com)
MIT style license
*/
/*
if (!window.Refresh) Refresh = {};
if (!Refresh.Web) Refresh.Web = {};
 */




ObjSelect = new Class({
	_bar: null,
	_sortable: null ,
        _changeorder: false ,
        

    Implements: [Options],

    options: { 
        arrowImage: 'refresh_web/colorpicker/images/rangearrows.gif'
    },

	initialize: function(id, options) {
	
		this.id = id;
                this.setOptions(options);
 

		// hook up controls
		this._bar = $(this.id);
                
                $(this.id).ObjSelect = this;
                

                this.initSortable();
                this._sortable.detach();
                
                //Disable inputs
                $("jform_extras").setStyle('display','none');
                $("jform_extras-lbl").setStyle('display','none');
                $$("#fieldsattach-slider-select .updatebutton").setStyle("display","none");
                
                
                this.events();
                 
	},
        init: function(txt){
            valor = txt;
            var tmp = String(valor).split(/\r\n|\r|\n/);
            //Remove
            //myElement.removeEvent(type, fn);
            $$("#"+this.id+" li").each(
                function(el) { 
                    el.destroy();
                });
            //Add
            for(var cont=0; cont < tmp.length; cont++)
                {
                    var tmp2 = String(tmp[cont]).split("|");
                    this.addunit(this.id);
                    var tmpdef;
                     
                    if(tmp2.length > 1) tmpdef =  tmp2[2];
                    this.setunit(this.id+"_"+cont, tmp2[0],tmp2[1], tmpdef );
                     
                }
           this.revalue();
        },
        revalue: function(){
            var str ="";
             
            var last = $$("#"+this.id+" li").length;
            cont=0;
            $$("#"+this.id+" li").each(
                function(el) {  
                       name = el.get("id");
                       
                       title = el.getElement("input#title_"+name).get("value");
                       valor = el.getElement("input#value_"+name).get("value");
                       eldefault = el.getElement("input#default_"+name).get("checked");
                        
                       str += title;
                       str += "|"
                       str += valor;
                       if(eldefault == true)
                           {
                            str += "|";
                            str += eldefault;
                            }
                       if( (last-1) > cont ) str += "\r"
                       
                       cont++;
                       
                       
                    });
            //alert(str);
            if(title != ""){ 
                $("jform_extras").set("value", str);
            } 
            return str;
             
           
        },
        initSortable: function(){
               // alert(this.id);alert($(this.id));
                this._sortable = new Sortables($(this.id), { 
 
                initialize: function(){
                        var step = 0;
                        var cont = 0;
                         
                },
                onComplete: function(){
                        //console.log("fin");
                        // this.parent.reorder();
                        //alert(this.id);
                        var cont=0;
                        var idparent = this.elements[0].parentNode;
                        //alert(idparent);

                        id = idparent.get("id");
                        //alert(""+id);

                        idparent.getElements('li').each(function(el){ 
                            el.set("class", id+"_"+cont);
                            cont++;
                        }
                        );

                        idparent.ObjSelect.reorder();
                        
                }
                    

            });
        },
        reorder: function(){
             
             $$("#"+this.id+" li").each(
                function(index, value) { 
                        console.log("fin"+index)
                    });
             this.revalue();
     
            
        },
        renamedlast: function(){
             
             var max = 0;
              
             $$("#"+this.id+" li").each(
                function(el) { 
                        //console.log("fin"+index);
                        max++;
                    });
             //alert("#"+this.id+"_"+(max-1));
             
             cont = 0;;
             $$("#"+this.id+" li").each(
                function(el) { 
                        //console.log("fin"+index);
                        cont++;
                        var id= el.parentNode;
                        var name = id.get("id");
                       // alert("NAME:"+name);
                        if(cont == max)
                            {
                             el.set("id", name+"_"+ (max-1)); 
                             el.set("class", name+"_"+ (max-1));
                             
                             //delete
                             //alert(el.getElement("input#title_"+name))
                             inputtitle = el.getElement("input#title_"+name);
                             inputtitle.set("id", "title_"+name+"_"+(max-1));
                             inputtitle.set("class", "title_"+name+"_"+(max-1));
                             
                             inputvalue = el.getElement("input#value_"+name);
                             inputvalue.set("id", "value_"+name+"_"+(max-1));
                             inputvalue.set("class", "value_"+name+"_"+(max-1));
                             
                             buttondelete = el.getElement("#delete_"+name);
                             buttondelete.set("id", "delete_"+name+"_"+(max-1));
                             
                             buttondelete = el.getElement("#default_"+name);
                             buttondelete.set("id", "default_"+name+"_"+(max-1));
                             
                            // buttondelete = el.getElement("#drag_"+name);
                            // buttondelete.set("id", "drag_"+name+"_"+(max-1));
                             //buttondelete.set("class", "delete_"+name+"_"+(max-1));
                             
                             id.ObjSelect.eventdelete()  
                             id.ObjSelect.eventinput();
                             
                            }
                            
                        
                    });
              this.eventdrag();
                    
             //$$("#"+this.id+"_"+(max-1)).set("html","sss")
        },
        addunit:function(ulname)
        {
            var ul = ulname ;
            var element1 = new Element('li');
            element1.set("class","sortme");
            $(ul).adopt(element1);
            var html ="";
            //alert(ulname);
            tmphtml = $(ul).ObjSelect.addhtml(ulname);
            element1.set("html", tmphtml);
            //$(ul).ObjSelect.eventdelete(element1);

            $(ul).ObjSelect._sortable.addItems();
            $(ul).ObjSelect._sortable.detach();

            $(ul).ObjSelect.renamedlast();
            this.revalue();
        },
        addhtml:function(name){
        var tmphtml = '<div class="title name"><label><?php echo $title;?></label><input type="text" id="title_'+name+'" name="title_'+name+'" /></div>&nbsp;';
            tmphtml += '<div class="title "><label><?php echo $value;?></label><input type="text" id="value_'+name+'" name="value_'+name+'" /></div>&nbsp;';
            tmphtml += '<div class="title"><label class="checkbox"><?php echo $defaul;?></label><input type="checkbox" id="default_'+name+'" name="default_'+name+'" /></div>&nbsp;';
            tmphtml += '<div class="drag"><img id="drag_'+name+'"  src="../plugins/fieldsattachment/select/img/drag.png" /></div>';
            tmphtml += '<a href="#" id="delete_'+name+'" class="delete"><img src="../plugins/fieldsattachment/select/img/delete.png" /></a>&nbsp;';
            return tmphtml;   
        },
        setunit:function(li, name, value, check)
        {
             
            $('title_'+li).set("value", name);
            $('value_'+li).set("value", value);
            $('default_'+li).set("checked", check);
        },
        eventdelete:function(){  
            /*DELETE ****************************************/ 
           $$("#"+this.id+" .delete").removeEvent('click', function() {});
           $$("#"+this.id+" .delete").addEvent('click', function(event){
                event.stop(); //Prevents the browser from following the link.
                
                
                var li = this.parentNode;
                var ul = li.parentNode;
                
                //var element1 = $('list1').getFirst(); 
                
                $(ul).ObjSelect._sortable.removeItems(li).destroy(); //the elements will be removed and destroyed
                $(ul).ObjSelect.revalue();
            });
            
           
        },
        eventdelete:function(){  
            /*DELETE ****************************************/ 
           $$("#"+this.id+" .delete").removeEvent('click', function() {});
           $$("#"+this.id+" .delete").addEvent('click', function(event){
                event.stop(); //Prevents the browser from following the link.
                
                
                var li = this.parentNode;
                var ul = li.parentNode;
                
                //var element1 = $('list1').getFirst(); 
                
                $(ul).ObjSelect._sortable.removeItems(li).destroy(); //the elements will be removed and destroyed
                $(ul).ObjSelect.revalue();
            });
            
           
        },
        eventinput:function(obj){
             
            /*CHANGE INOUT ****************************************/ 
           $$("#"+this.id+" input").removeEvent('change', function() {});
           $$("#"+this.id+" input").addEvent('change', function(event){
                event.stop(); //Prevents the browser from following the link.
                 
                ul = this.parentNode.parentNode.parentNode; 
                $(ul).ObjSelect.revalue();
            });
            
           
        },
        eventdrag:function(){  
            /*DELETE ****************************************/ 
           /*$$("#"+this.id+" .drag").removeEvent('click', function() {});
           $$("#"+this.id+" .drag").addEvent('click', function(event){
                event.stop(); //Prevents the browser from following the link. 
                
                var li = this.parentNode;
                var ul = li.parentNode;
                 
                if(!$(ul).ObjSelect._changeorder)
                    {
                         
                        $(ul).ObjSelect._sortable.attach();
                        $(ul).ObjSelect._changeorder=true;
                        //this.getElement("img").set("src", "../plugins/fieldsattachment/select/img/drag.png");
                        $$(".drag").getElement("img").set("src", "../plugins/fieldsattachment/select/img/drag.png");
                        
                    }else{
                         
                        $(ul).ObjSelect._sortable.detach(); 
                        $(ul).ObjSelect._changeorder=false;
                        //this.getElement("img").set("src", "../plugins/fieldsattachment/select/img/drag_of.png");
                         $$(".drag").getElement("img").set("src", "../plugins/fieldsattachment/select/img/drag_of.png");
                        
                        
                    }
                 event.stop(); //Prevents the browser from following the link.
            });
            */
            this._sortable.detach(); 
            this._sortable.attach();
            
           
        },
        events:function(){
           /*ADD ****************************************/
           $(this.id+'_add').addEvent('click', function(event){
                event.stop(); //Prevents the browser from following the link.
                var id = this.get("id");
                //alert(id);
                var tmp  = String(id).split("_");
                var ul = tmp[0]+"_"+tmp[1];
                $(ul).ObjSelect.addunit(ul);
                
                 
                //$(ul).ObjSelect.eventdelete('delete_'+tmp[1]);
                
                
            });
             
            /*REORDER ****************************************/
            /*$(this.id+'_reorder').addEvent('click', function(event){
                event.stop(); //Prevents the browser from following the link.
                var id = this.get("id");
                //alert(id); 
                var tmp  = String(id).split("_"); 
                var ul = tmp[0]+"_"+tmp[1];
                if(!$(ul).ObjSelect._changeorder)
                    {
                        //$(ul).ObjSelect.initSortable();
                         
                        $(ul).ObjSelect._sortable.attach();
                        $(ul).ObjSelect._changeorder=true;
                        this.set("html", "Stop change order");
                    }else{
                       // $(ul).ObjSelect.erase($(ul).ObjSelect._sortable);
                       // $(ul).ObjSelect._sortable.erase();
                        $(ul).ObjSelect._sortable.detach(); 
                        $(ul).ObjSelect._changeorder=false;
                         this.set("html", "Change order");
                        
                    }
               
            });*/
        } 
	
	 

});
