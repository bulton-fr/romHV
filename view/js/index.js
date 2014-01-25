/**
 * Redimensionne la taille du bloc main
 */
function mainResize()
{
	var BodyHeight = $(window).height(); //Récupère la hauteur de la fenêtre
	
	if(BodyHeight > 180) //Si c'est supérieur au min-height+margin-top+10
	{
		//Alors on calcul la nouvelle taille avec margin-top et 70px en bas.
		var height = BodyHeight-120;
		$(".main").height(height); //Et on actualise la taille du bloc
		
		height -= 40;
		$(".cont").height(height);
	}
}

/**
 * Change de page
 * 
 * @param object context
 * @param string nom
 * @param string idPerso
 */
function page(context, url, idPerso)
{
	$(".bandeau li.wait").show();
	url = base_url+"/"+url; //Evite les erreurs d'injection d'url. Le framework se chargeant du reste après (renvoi 404)
	
	if(idPerso != undefined) {data = {idPerso : idPerso};}
	else {data = {};}
	
	$.ajax({
		url: url,
		data: data,
		type: 'POST',
		context: context
	})
	.done(function(data)
	{
		$(".bandeau li.wait").hide();
		
		if(data != "RedirectLogin")
		{
			$(".menuSelected").removeClass('menuSelected');
			$(this).addClass('menuSelected');
			
			$(".cont").html(data);
			$("#poPerso").hide();
			dialogDetail();
			
			if($(this).attr("id") == "ventes") {contVentes($("#buttonVentes button#me"), "me");}
			if($(this).attr("id") == "vendu") {contVendu($("#buttonVendu button#semaine"), "semaine");}
			
			if($(this).parent().attr("class") == "list_perso" && $(this).parents('div').attr("class") == "menu_left")
			{
				maj_po_bandeau();
				var idPerso = $("#PersoViewId").val();
				contPersoView($("#buttonPersoView button#vente"), "vente", idPerso);
				
				dialogVendu();
				dialogMeV();
				$('#dialogMeVDate').datetimepicker({
					timeFormat: 'HH:mm:ss',
					dateFormat: 'dd/mm/yy'
				});
			}
			
			if($(this).attr("id") == "monCompte")
			{
				//Picker Color
				pickerColor = $.farbtastic($('#pickerColor'));
				pickerColor.linkTo(changeColor);
				pickerColor.setColor(BackColor);
				$("#pickerColor").hide();
				
				//Slider
				slider = $("#sliderOpacity").slider({
					range: "max",
					min: 0,
					max: 100,
					value: BackOpacity,
					slide: function( event, ui ) {
						$("#valOpacity").text(ui.value);
						$(".main").css("opacity", (ui.value/100));
					}
				});
			}
		}
		else {window.location.href = base_url;}
	})
	.fail(function()
	{
		$(".bandeau li.wait").hide();
		
		alert("Désolé j'ai crashé :o")
	});
}

/**
 * Mise à jour des po de l'user 
 */
function majPoUser()
{
	$.ajax({
		url: base_url+"/majBlock",
		data: {block: 'poUser'},
		type: 'POST'
	})
	.done(function(data)
	{
		$("#poUser").text(data);
	});
}

/**
 *Mise à jour de la liste des perso 
 */
function majPersoListe()
{
	$.ajax({
		url: base_url+"/majBlock",
		data: {block: 'listPerso'},
		type: 'POST',
		dataType: 'json'
	})
	.done(function(data)
	{
		var nbPersoRcv = data.length;
		var nbPerso = $(".list_perso li").length
		var diff = nbPersoRcv - nbPerso;
		
		if(diff >= 0)
		{
			for(var i=0; i<nbPerso; i++)
			{
				if(data[i].nom != $(".list_perso li").eq(i).text())
				{
					$(".list_perso li").eq(i).text(data[i].nom);
				}
			}
			
			
			if(diff > 0)
			{
				var j = nbPerso;
				for(var i=0; i<diff; i++)
				{
					$(".list_perso").append('<li class="perso" id="'+data[j].idPerso+'">'+data[j].nom+'</li>');
					j++;
				}
			}
		}
		else //Supprime un perso
		{
			$(".list_perso li").remove();
			
			for(var i=0; i<nbPersoRcv; i++)
			{
				$(".list_perso").append('<li class="perso" id="'+data[i].idPerso+'">'+data[i].nom+'</li>');
			}
		}
	});
}

google.load("visualization", "1", {packages:["corechart"]});

/**
 * Graph page récap
 */
function graphRecap(typeGraph, idPerso)
{
	if(typeGraph == undefined) {typeGraph = "general";}
	if(idPerso == undefined) {idPerso = 0;}
	
	$("#GraphRecap").html("<p>Génération du graphique en cours</p><p><img src='"+path+"img/ajax-loader.gif' /></p>");
	
	$.ajax({
		url: base_url+"/infosGraph/"+typeGraph+"/"+idPerso,
		dataType: 'json'
	})
	.done(function(data)
	{
		try {
			var options = {
				title: "Mes ventes de ces dernières semaines",
				width: 670,
				height: 400,
				vAxis: {logScale: false}
			};
			
			if($("input#logGraph").is(":checked"))
			{
				options.vAxis.logScale = true;
			}

			var dataGraph = google.visualization.arrayToDataTable(data);
			var chart = new google.visualization.LineChart(document.getElementById('GraphRecap'));
			
			$("p.GraphRecap").hide();
			chart.draw(dataGraph, options);
		}
		catch(e) {$("#GraphRecap").html("Crash à la génération du graphique. : "+e);}
	})
	.fail(function()
	{
		$("#GraphRecap").html("Crash à la génération du graphique.");
	});
}

//Redimenssionnement si on change la taille de la fenêtre
$(window).resize(function() {mainResize();});

$(document).ready(function()
{
	if(document.URL != base_url+"/import") {page($("ul.main_liste > li#recap"), "recap");}
	mainResize(); //Redimensionne dès que la page est chargé.
	
	$(".link").click(function() {page(this, $(this).attr("id"));});
	$("ul.main_liste > li").not('.emptyLi').click(function() {page(this, $(this).attr("id"));});
	$("body").on('click', "ul.list_perso > li", function() {page(this, "perso/view", $(this).attr("id"));});
	
	$(".cont").on("click", ".GraphRecap button", function() {
		$(".GraphRecap button.selected").removeClass("selected");
		$(this).addClass("selected");
		
		var typeGraph = $(this).attr("id");
		graphRecap(typeGraph);
	});
	
	$(".cont").on("click", ".statPerso", function() {
		$(".GraphRecap button.selected").removeClass("selected");
		
		var typeGraph = $(this).attr("id");
		var idPerso = $(this).parent("tr").attr("id");
		
		graphRecap(typeGraph, idPerso);
	});
	
	if(BackColor != undefined)
	{
		var textColor = "black";
		if(TextColorBlack == 0) {textColor = "white";}
		
		$(".main").css("background-color", BackColor);
		$(".main").css("opacity", (BackOpacity/100));
		$(".main").css("color", textColor);
	}
});