ALTER TABLE  `romhv_perso_item` CHANGE  `nb_piece`  `nb_piece` INT UNSIGNED NOT NULL DEFAULT  '1';
CREATE TABLE  `romhv_groupe` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;
CREATE TABLE  `romhv_users_grp` (
`idUser` INT UNSIGNED NOT NULL ,
`idGroupe` INT UNSIGNED NOT NULL ,
INDEX (  `idUser` )
) ENGINE = MYISAM ;