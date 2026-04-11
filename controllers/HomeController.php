<?php
/**
 * Contrôleur principal - Page d'accueil
 */

require_once __DIR__ . '/../models/Don.php';
require_once __DIR__ . '/../models/Verset.php';
require_once __DIR__ . '/../models/Parametre.php';

class HomeController {

    public function index(): void {
        $don       = new Don();
        $verset    = new Verset();
        $parametre = new Parametre();

        // Données pour la page d'accueil
        $data = [
            'montant_total'       => $don->montantTotal(),
            'nombre_contributeurs'=> $don->nombreContributeurs(),
            'verset_aleatoire'    => $verset->aleatoire(),
            'params'              => $parametre->tous(),
        ];

        // Calcul du pourcentage de progression
        $objectif = (float)($data['params']['objectif_financier'] ?? 500000);
        $data['pourcentage'] = $objectif > 0
            ? min(100, round(($data['montant_total'] / $objectif) * 100, 1))
            : 0;

        require __DIR__ . '/../views/home.php';
    }
}
