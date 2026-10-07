<?php
require __DIR__ . '/autoload.php';

function saisir(string $question): string {
    echo $question;
    $ligne = fgets(STDIN);
    if ($ligne === false) { exit(0); }
    return trim($ligne);
}

$stock = new Stock();
$numeroCommande = 1;

while (true) {
    echo "\n=== GESTION DE STOCK ===\n";
    echo "1. Ajouter un produit\n";
    echo "2. Lister le stock\n";
    echo "3. Réapprovisionner\n";
    echo "4. Nouvelle commande\n";
    echo "5. Produits en rupture ou sous seuil\n";
    echo "0. Quitter\n";
    $choix = saisir("Votre choix : ");

    try {
        switch ($choix) {
            case '1':
                $ref = saisir("Référence : ");
                $nom = saisir("Nom : ");
                $prix = (float) saisir("Prix : ");
                $qte = (int) saisir("Quantité : ");
                $stock->ajouter(new Produit($ref, $nom, $prix, $qte));
                echo "Produit ajouté.\n";
                break;

            case '2':
                foreach ($stock->tous() as $p) {
                    printf("%-8s %-15s %8.2f  qté: %d\n",
                        $p->getReference(), $p->getNom(), $p->getPrix(), $p->getQuantite());
                }
                printf("Valeur totale du stock : %.2f\n", $stock->valeurTotale());
                break;

            case '3':
                $p = $stock->trouver(saisir("Référence : "));
                if ($p === null) { echo "Produit introuvable.\n"; break; }
                $p->ajouterQuantite((int) saisir("Quantité à ajouter : "));
                echo "Stock mis à jour.\n";
                break;

            case '4':
                $cmd = new Commande($numeroCommande);
                while (true) {
                    $ref = saisir("Référence du produit (vide pour terminer) : ");
                    if ($ref === '') { break; }
                    $p = $stock->trouver($ref);
                    if ($p === null) { echo "Produit introuvable.\n"; continue; }
                    try {
                        $cmd->ajouterLigne($p, (int) saisir("Quantité : "));
                    } catch (InvalidArgumentException $e) {
                        echo "Erreur : " . $e->getMessage() . "\n";
                    }
                }
                $cmd->valider();
                echo $cmd->afficher();
                $numeroCommande++;
                break;

            case '5':
                $seuil = (int) saisir("Seuil : ");
                echo "--- En rupture ---\n";
                foreach ($stock->produitsEnRupture() as $p) { echo $p->getNom() . "\n"; }
                echo "--- Sous le seuil $seuil ---\n";
                foreach ($stock->produitsSousSeuil($seuil) as $p) {
                    echo $p->getNom() . " (" . $p->getQuantite() . ")\n";
                }
                break;

            case '0':
                echo "Au revoir.\n";
                exit(0);

            default:
                echo "Choix invalide.\n";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage() . "\n";
    }
}
