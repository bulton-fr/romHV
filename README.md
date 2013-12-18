romHV
=====

Gestionnaire de l'hôtel des ventes sur Runes of Magic
=====


Il s'agit de simplifier la gestion de l'hôtel des ventes de runes of magic quand plusieurs joueurs sont sur l'hv et aussi pour les ventes du perso lui-même.


Le personnage peux gérer ses mises en vente, ses items non mit en vente ainsi que toutes les ventes passé avec un historique affiché à 4 semaines. Cela permet par exemple de faire des statistiques et de se rendre compte d'un personnage qui n'arrive plus à vendre.

Pour un groupe de joueurs, il leurs est possible de voir les ventes en cours et effectué qu'on chacun et donc de s'aligner entre eux afin de mieux contrôler le marcher de l'hôtel des ventes.


Tous les items, runes et stats de runes of magic sont présentes.
La mise est jour est semi automatique. Les items doivent d'abord être extrait du jeu manuellement (ItemPreview et extration du fichier string_fr.db), les 2 fichiers sont ensuite gziper et mis sur un dossier Google Drive. A partir de là, la mise à jour est automatique. L'application récupère d'elle même les fichiers, met à jour les items déjà présent et insert les nouveaux.