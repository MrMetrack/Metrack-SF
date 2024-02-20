<?php

/* Dit bestand wordt in de boodstrap ingeladen. Hieronder kan de realtie gelegd worden tussen
 * de combinatie van url segmenten en de controllers.
 *
 * $Router-setRouting(
 *       @param string urlsegmenten, 
 *       @param Controller = Contoller::methode, 
 *       @param geautoriseerd = true or false, 
 *       @param permission = optioneel permission voorbeeld = permissionalias = level)
 */
$Router->setRouting('/', "Home::index", false);
$Router->setRouting('login', "Login::index", false);
$Router->setRouting('logout', "Logout::index", false);
$Router->setRouting('sessieverlopen', "Logout::sessionExpired", false);
$Router->setRouting('registreren', "Registreren::index", false);
$Router->setRouting('mijngegevens', "MijnGegevens::index", true);
$Router->setRouting('blogs', "Blogs::index", false);
$Router->setRouting('blogs/create', "Blogs::create", true);
$Router->setRouting('blogs/read', "Blogs::readAndedit", false,);
$Router->setRouting('blogs/edit', "Blogs::readAndedit", true);
$Router->setRouting('gebruikersrollen', "Gebruikersrollen::index", true, "roles=2");
$Router->setRouting('gebruikersrollen/edit', "Gebruikersrollen::edit", true, "roles=2");
$Router->setRouting('gebruikers', "Gebruikers::index", true, "users=1");
$Router->setRouting('gebruikers/edit', "Gebruikers::edit", true, "users=2");
$Router->setRouting('paginanietgevonden', "Error::pagenotfound", false);
//$Router->setRouting('paginanietgevonden', "Error::pagenotfound", false);