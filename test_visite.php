<?php

require "config/connexion.php";
require "fonctions.php";

enregistrerVisite($pdo);

echo "Visite enregistrée";