ALTER TABLE  `romhv_users` ADD  `backgroundColor` VARCHAR( 20 ) NULL ,
ADD  `backgroundOpacity` TINYINT UNSIGNED NULL ,
ADD  `textColorBlack` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1';

ALTER TABLE  `romhv_perso_item` ADD  `enchere_unite` INT UNSIGNED NOT NULL AFTER  `rachat` ,
ADD  `rachat_unite` INT UNSIGNED NOT NULL AFTER  `enchere_unite` ,
ADD  `nb_piece` TINYINT UNSIGNED NOT NULL DEFAULT  '1' AFTER  `rachat_unite`;