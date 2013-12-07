/**
 * Redimensionne la taille du bloc main
 */
function mainResize()
{
	var BodyHeight = $(window).height(); //Récupère la hauteur de la fenêtre
	
	if(BodyHeight > 160) //Si c'est supérieur au min-height+margin-top+10
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
 * @param string nom
 * @param string idPerso
 */
function page(url, idPerso)
{
	$(".bandeau li.wait").show();
	url = base_url+"/"+url; //Evite les erreurs d'injection d'url. Le framework se chargeant du reste après (renvoi 404)
	
	if(idPerso != undefined) {data = {idPerso : idPerso};}
	else {data = {};}
	
	$.ajax({
		url: url,
		data: data,
		type: 'POST'
	})
	.done(function(data)
	{
		$(".bandeau li.wait").hide();
		
		if(data != "RedirectLogin") {$(".cont").html(data);}
		else {window.location.href = base_url;}
	})
	.fail(function()
	{
		$(".bandeau li.wait").hide();
		
		alert("Désolé j'ai crashé :o")
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
				height: 400
			};

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
	page("recap");
	mainResize(); //Redimensionne dès que la page est chargé.
	
	$(".link").click(function() {page($(this).attr("id"));});
	$("ul.main_liste > li").not('.emptyLi').click(function() {page($(this).attr("id"));});
	$("ul.list_perso > li").click(function() {page("perso", $(this).attr("id"));});
	
	$(".cont").on("click", ".GraphRecap button", function() {
		$(".GraphRecap button.selected").removeClass("selected");
		$(this).addClass("selected");
		
		var typeGraph = $(this).attr("id");
		graphRecap(typeGraph);
	})
	
	$(".cont").on("click", ".statPerso", function() {
		$(".GraphRecap button.selected").removeClass("selected");
		
		var typeGraph = $(this).attr("id");
		var idPerso = $(this).parent("tr").attr("id");
		
		graphRecap(typeGraph, idPerso);
	})
});