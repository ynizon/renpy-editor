$(document).ready(function() {	
	$.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
	
	//Only for scene editor
	if ($( "#list_actions" ).length >0){
		loadSceneActions($("#story_id").val(), $("#scene_id").val());
		
		$( "#list_actions" ).dblclick(function() {
			if (window.confirm("Confirm remove ?")){
				deleteAction($("#story_id").val(), $("#scene_id").val(), $(this).val());
			}
		});
		
		$( "#list_actions" ).click(function() {
			editAction($("#story_id").val(), $("#scene_id").val(), $(this).val());
		});
          
          $( "#list_actions" ).keyup(function(event) {
               if(event.which == 38 || event.which == 40){
                    editAction($("#story_id").val(), $("#scene_id").val(), $(this).val());
               }
		});
	}
     
     $("#country").countrySelect({
		defaultCountry: "United Kingdom",
		preferredCountries: [],
		responsiveDropdown: true
	});	
});	


/* Load actions list into the scene form */
function loadSceneActions(story_id, scene_id){
	var old_index = document.getElementById("list_actions").selectedIndex;
	$("#list_actions").load("/story/"+story_id+"/scene/"+scene_id, function(){
		document.getElementById("list_actions").selectedIndex = old_index;
	});
}
	
/* show Action parameters into the scene form */
function showAction(action){
	$(".action_type").hide();
	var values = action.split("_");
	
	$("#element").val(values[0]);
	$("#subject_id").val(values[1]);
	$("#verb").val(values[2]);
	
	switch (values[2]){
		case "say":
			$("#bloc_say").show();			
			break;
          case "iftrue":
		case "jump":
			$("#bloc_jump").show();			
			break;
		case "addscript":
			$("#bloc_addscript").show();			
			break;
		case "menu":
			$("#bloc_menu").show();
			break;		
          case "set":
               $("#bloc_setthing").show();
               break;
		case "show":
          case "showflip":               
			if ($("#element").val() == "character"){                    
                    $.ajax({
                         type: "GET",
                         format: "json",
                         url:"/story/"+$("#story_id").val()+"/character/"+$("#subject_id").val()+"/behaviours?action_id="+$("#action_id").val(),
                         success:function(data) {
                              var data = JSON.parse(data);
                              $("#behaviours").html(data.behaviours);
                              $("#move").val(data.move);
                              $("#bloc_behaviour").show();
                              $("#bloc_move").show();
                         }
                    });                    
			}
			if ($("#element").val() == "background"){
				$("#differents").load("/story/"+$("#story_id").val()+"/background/"+$("#subject_id").val()+"/differents?action_id="+$("#action_id").val());				
				$("#bloc_different").show();			
			}
			break;
	}
}

function deleteAction(story_id, scene_id, action_id){
	$.ajax({
		type: "POST",
		url:"/story/"+story_id+"/scene/"+scene_id+"/delete_action/"+action_id,
		data:{}, 
		success:function(data) {
			loadSceneActions(story_id, scene_id);
			$("#btn_update").hide();
		}
	});	
}

function addAction(story_id, scene_id){
	if ($("#actions").val() != ""){
		$("#info").val("");
		switch ($("#verb").val()){
			case "say":
				$("#info").val($("#say").val());
				break;
			
			case "jump":
               case "iftrue":
				$("#info").val($("#jump").val());
				break;

			case "addscript":
				$("#info").val($("#addscript").val());
				break;
				
			case "set":
                    $("#info").val($("#setthings").val());
                    break;
			case "show":
               case "showflip":
				if ($("#element").val() == "character"){
					var info = {
                              "behaviours":$("#behaviours").val(),
                              "move":$("#move").val(),
                         };
                         $("#info").val(JSON.stringify(info));
				}
				if ($("#element").val() == "background"){
					$("#info").val($("#differents").val());
				}
				break;
				
			case "menu":
				var info = {
					"menu_title":$("#menu_title").val()
                    };
                    for (var k=1;k<=$("#max_menu_choice").val();k++){
                         var mymenu = "info.menu" + k+"=$('#menu"+k+"').val();";
                         eval(mymenu);
                         var mymenu = "info.menu" + k+"_to=$('#menu"+k+"_to').val();";
					eval(mymenu);
                    }
				
				$("#info").val(JSON.stringify(info));
				break;		
		}
		
		var data = {
				data:{
					"element":$("#element").val(),
					"subject_id":$("#subject_id").val(),
					"verb": $("#verb").val(),
					"info":$("#info").val(),
					"action_id":$("#action_id").val()
				},
				order:document.getElementById("list_actions").selectedIndex
			};
		
		$.ajax({
			type: "POST",
			url:"/story/"+story_id+"/scene/"+scene_id+"/add_action",
			data:data, 
			success:function(data) {
				$("#action_id").val(0);
				loadSceneActions(story_id, scene_id);
				$("input.action_info").val('');
				$("textarea.action_info").val('');
				$("select.action_info").val(0);				
				$("#btn_update").hide();
			}
		});
	}
}

/* Change order action */
function orderAction(upOrDown, story_id, scene_id, action_id){
	$.get("/story/"+story_id+"/scene/"+scene_id+"/order_action/"+action_id+"?order="+upOrDown, function(data){
		eval(data);
	});
}

/* Edit action */
function editAction(story_id, scene_id, action_id){
	$.get("/story/"+story_id+"/scene/"+scene_id+"/edit_action/"+action_id, function(data){
		eval(data);
	});
}
/* Add one scene */
function addScene(story_id, select_to){
	var name = window.prompt("Name of the scene");
	if (name != ""){
		$.get("/story/"+story_id+"/scene/addone?name="+name, function(data){
			data = JSON.parse(data);
			$(".menus").each(function( index ) {
				$( this ).append(new Option("Go to "+data.name, data.id));
				$("#"+select_to).val(data.id);
			});
		});
	}
}

/* Zoom pictures */
function zoom(){
     $(".pic").each(function( index ) {
          $(this).parent().css("width","500px");
          $(this).parent().css("height","500px");
     });
}