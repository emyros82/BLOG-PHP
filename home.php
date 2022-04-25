<?php

// Inclusion des dépendances
include 'functions.php';

// Traitements : récupérer les articles
$articles = getAllArticles();

// Affichage : inclusion du fichier de template
include 'home.phtml';
