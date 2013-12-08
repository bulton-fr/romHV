/**
 * Met à jour les po du perso dans le bandeau
 */
function maj_po_bandeau()
{
	$("#poPerso").show();
	$("#poPerso").html($("#PersoViewPo").val());
}

/**
 * Change de page
 * 
 * @param string nom
 * @param bool   suite
 * @param string idPerso
 */
function contPersoView(context, url, idPerso, suite, tri)
{
	$(".bandeau li.wait").show();
	url = base_url+"/perso/"+url; //Evite les erreurs d'injection d'url. Le framework se chargeant du reste après (renvoi 404)
	
	if(suite == undefined || suite == false) {$("#suite").val("0");}
	suite = $("#suite").val();
	
	var triRow = $("#triRow").val();
	var triSens = $("#triSens").val();
	
	$.ajax({
		url: url,
		data: {suite: suite, triRow: triRow, triSens: triSens, idPerso: idPerso},
		type: 'POST',
		dataType: 'html',
		context: context
	})
	.done(function(data)
	{
		$(".bandeau li.wait").hide();
		
		$(".selected").removeClass('selected');
		$(this).addClass('selected');
		
		suite = parseInt($("#suite").val());
		suite++;
		$("#suite").val(suite);
		
		if(suite == 1) {$(".cont_persoView").html(data);}
		else {$("#ViewPersoTbody").append(data);}
		
		if($(this).attr("id") == "addItem") {$('#AddItem_date').datetimepicker();}
	})
	.fail(function()
	{
		$(".bandeau li.wait").hide();
		
		alert("Désolé j'ai crashé :o")
	});
}

$(document).ready(function()
{
	//Liste des persos
	$(".cont")
	
	.on('submit', 'form#createPerso', function() {
		
		var nom = $("form#createPerso input#nomPerso").val();
		var po = $("form#createPerso input#po").val();
		
		$(".bandeau li.wait").show();
		
		$.ajax({
			url: base_url+"/perso/create",
			data: {nom: nom, po: po},
			type: 'POST'
		})
		.done(function() {page("perso/liste");})
		.fail(function()
		{
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o")
		});
		
		return false;
	})
	
	.on('click', 'span.nomPerso', function() {
		var nom = $(this).html();
		$(this).replaceWith('<input type="text" id="nomPerso" value="'+nom+'" />');
		$('p.ListeDesPersos input#nomPerso').focus();
	})
	.on('click', 'span.poPerso', function() {
		var po = $(this).html();
		$(this).replaceWith('<input type="text" class="po" id="poPerso" value="'+po+'" />');
		$('p.ListeDesPersos input#poPerso').focus();
	})
	
	.on('blur', 'p.ListeDesPersos input#nomPerso', function() {
		var idPerso = $(this).parent().attr('id');
		var nom = $(this).val();
		$(".bandeau li.wait").show();
		
		$.ajax({
			url: base_url+"/perso/edit",
			data: {idPerso: idPerso, nom: nom},
			type: 'POST'
		})
		.done(function(data) {
			$(".bandeau li.wait").hide();
			$('p.ListeDesPersos input#nomPerso').replaceWith('<span class="nomPerso">'+data+'</span>');
		})
		.fail(function() {
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o")
		});
	})
	.on('blur', 'p.ListeDesPersos input#poPerso', function() {
		var idPerso = $(this).parent().attr('id');
		var po = $(this).val();
		
		if(po == "") {po = 0;}
		$(".bandeau li.wait").show();
		
		$.ajax({
			url: base_url+"/perso/edit",
			data: {idPerso: idPerso, po: po},
			type: 'POST'
		})
		.done(function(data) {
			$(".bandeau li.wait").hide();
			$('p.ListeDesPersos input#poPerso').replaceWith('<span class="poPerso">'+data+'</span>');
		})
		.fail(function() {
			$(".bandeau li.wait").hide();
			alert("Désolé j'ai crashé :o")
		});
	})
	
	.on('click', 'p.ListeDesPersos img', function(){
		var idPerso = $(this).parent().attr('id');
		
		if(confirm('Voulez-vous vraiment supprimer le personnage ?'))
		{
			$.ajax({
				url: base_url+"/perso/delete",
				data: {idPerso: idPerso},
				type: 'POST'
			})
			.done(function(data) {page("perso/liste");})
			.fail(function() {
				$(".bandeau li.wait").hide();
				alert("Désolé j'ai crashé :o")
			});
		}
	});
	//Liste des persos
	
	
	
	//Vue d'un perso
	$(".cont").on("click", "#buttonPersoView button", function() {
		$("#buttonPersoView button.selected").removeClass("selected");
		$(this).addClass("selected");
		
		var idPerso = $("#PersoViewId").val();
		contPersoView(this, $(this).attr("id"), idPerso);
	})
	
	$(".cont").on("click", "#ViewPersoSuite", function() {
		$(this).remove();
		
		var button = $("button.selected");
		var idPerso = $("#PersoViewId").val();
		contPersoView(button, $(button).attr("id"), idPerso, true);
	});
	
	$(".cont").on("click", "table#ViewPerso thead td", function() {
		var triRow = $("#triRow").val();
		
		if(triRow == $(this).attr("class"))
		{
			var triSens = $("#triSens").val();
			if(triSens == "ASC") {$("#triSens").val("DESC");}
			else {$("#triSens").val("ASC");}
		}
		else
		{
			$("#triRow").val($(this).attr("class"));
			
			if($(this).attr("class") == "venduDate") {$("#triSens").val("DESC");}
			else {$("#triSens").val("ASC");}
		}
		
		var button = $("button.selected");
		var idPerso = $("#PersoViewId").val();
		contPersoView(button, $(button).attr("id"), idPerso, false, $(this).attr("class"));
	});
	
	
	//$(".cont").on('datetimepicker', '#AddItem_date', function() {});
	
	//Vue d'un perso
});