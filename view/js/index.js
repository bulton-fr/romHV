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
	}
}

//Redimenssionnement si on change la taille de la fenêtre
$(window).resize(function() {mainResize();});

$(document).ready(function()
{
	mainResize(); //Redimensionne dès que la page est chargé.
	
});