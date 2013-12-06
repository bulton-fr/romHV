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
	url = base_url+"/"+url+".php"; //Evite les erreurs d'injection d'url. Le framework se chargeant du reste après (renvoi 404)
	
	if(idPerso != undefined) {data = {idPerso : idPerso};}
	else {data = {};}
	
	$.ajax({
		url: url,
		data: data,
		type: 'POST'
	})
	.done(function(data) {$(".cont").html(data);})
	.fail(function() {alert("Désolé j'ai crashé :o")});
}

//Redimenssionnement si on change la taille de la fenêtre
$(window).resize(function() {mainResize();});

$(document).ready(function()
{
	mainResize(); //Redimensionne dès que la page est chargé.
	
	$(".bandeau > ul > li.link").click(function() {page($(this).attr("id"));});
	$("ul.main_liste > li").not('.emptyLi').click(function() {page($(this).attr("id"));});
	$("ul.list_perso > li").click(function() {page("perso", $(this).attr("id"));});
});