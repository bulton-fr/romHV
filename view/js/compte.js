function changeColor(color)
{
	$(".main").css("background-color", color);
	$("#colorBackground").val(color);
}


$(document).ready(function()
{
	$(".cont").on('submit', 'form#SendChangeMDP', function() {
		$(".bandeau li.wait").show();
		
		var oldMDP = $("input#oldMDP").val();
		var newMDP = $("input#newMDP").val();
		
		if(oldMDP == "" || oldMDP == undefined || newMDP == "" || newMDP == undefined)
		{
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o");
			
			return false;
		}
		
		$.ajax({
			url: base_url+"/monCompte",
			data: {oldMDP: oldMDP, newMDP: newMDP},
			type: 'POST',
			dataType: 'json'
		})
		.done(function(data) {
			if(data.status == 200) {page($(".menuSelected"), "monCompte/ok");}
			else
			{
				$(".bandeau li.wait").hide();
				alert("Désolé j'ai crashé :o");
			}
		})
		.fail(function() {
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o");
		});
		
		return false;
	});
	
	$(".cont").on("click", "#colorBackground", function() {
		$("#pickerColor").fadeIn();
	});
	
	$(".cont").on("blur", "#colorBackground", function() {
		$("#pickerColor").fadeOut();
	});
	
	$(".cont").on("click", "#ColorText", function() {
		if($("input#ColorText").is(":checked")) {$(".main").css("color", "black");}
		else {$(".main").css("color", "white");}
	});
	
	$(".cont").on("click", "#Init", function() {
		$(".bandeau li.wait").show();
		
		$.ajax({
			url: base_url+"/monCompte/init",
			dataType: 'json'
		})
		.done(function(data) {
			$(".bandeau li.wait").hide();
			
			$(".main").css("background-color", data.backgroundColor);
			$(".main").css("opacity", data.backgroundOpacity);
			$(".main").css("color", data.textColor);
			
			$("#colorBackground").val(data.backgroundColor);
			$("#valOpacity").text(data.backgroundOpacity);
			
			pickerColor.setColor(data.backgroundColor);
			$("#sliderOpacity").slider("value", data.backgroundOpacity);
			
			BackColor = data.backgroundColor;
			BackOpacity = parseInt(data.backgroundOpacity);
			TextColorBlack = parseInt(data.textColorBlack);
		})
		.fail(function() {
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o");
		});
		
		return false;
	});
	
	$(".cont").on("submit", "form#changeBackground", function() {
		$(".bandeau li.wait").show();
		
		$.ajax({
			url: base_url+"/monCompte",
			data: {
				backColor: $("#colorBackground").val(),
				backOpacity: $("#valOpacity").text(),
				textColor: $("input#ColorText").is(":checked")
			},
			type: 'POST'
		})
		.done(function(data) {
			$(".bandeau li.wait").hide();
			
			BackColor = $("#colorBackground").val();
			BackOpacity = parseInt($("#valOpacity").text());
			TextColorBlack = parseInt($("input#ColorText").is(":checked"));
			
			$(".main").css("background-color", BackColor);
			$(".main").css("opacity", BackOpacity);
		})
		.fail(function() {
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o");
		});
		
		return false;
	});
});