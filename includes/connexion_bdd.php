<?php

$bdd = mysqli_connect("localhost", "root", "", "pharma") or die("Problème de connection dans bdd");

mysqli_query($bdd, "SET NAMES utf8") or die("Requête non conforme1111"); //mettre les caracteres en français

mysqli_query($bdd, "SET lc_time_names = 'fr_FR'") or die("Requête non conforme2222");//mettre la date en francais pour mysql

date_default_timezone_set('Africa/Abidjan'); //Fuseau horaire php

setlocale(LC_TIME, "fr_FR");//Pour Date en français php;   Ex: utf8_encode(ucwords(strftime("%B %G", strtotime($date_montant)))) 

?>