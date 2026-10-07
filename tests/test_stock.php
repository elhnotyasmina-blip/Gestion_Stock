<?php
$s1 = new Stock();
$s1->ajouter(new Produit('P001', 'Clavier', 150, 10));
$s1->ajouter(new Produit('P002', 'Souris', 50, 0));
$s1->ajouter(new Produit('P003', 'Ecran', 1000, 2));

verifier($s1->compter() === 3, 'Le stock contient 3 produits');
verifier($s1->trouver('P001') !== null, 'P001 est trouvé');
verifier($s1->trouver('XXX') === null, 'Une référence inconnue retourne null');
verifier(abs($s1->valeurTotale() - 3500) < 0.001, 'Valeur totale = 1500 + 0 + 2000');
verifier(count($s1->produitsEnRupture()) === 1, 'Un produit en rupture (P002)');
verifier(count($s1->produitsSousSeuil(3)) === 2, 'Deux produits sous le seuil 3');
verifier(count($s1->produitsSousSeuil(2)) === 1, 'Seuil strict : quantité 2 pas sous 2');

try {
    $s1->ajouter(new Produit('P001', 'Doublon', 10, 1));
    verifier(false, 'Une référence en double lève une exception');
} catch (InvalidArgumentException $es) {
    verifier(true, 'Une référence en double lève une exception');
}
