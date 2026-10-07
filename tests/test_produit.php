<?php
$p1 = new Produit('P001', 'Clavier', 150, 10);
verifier($p1->getQuantite() === 10, 'La quantité initiale est 10');
$p1->retirerQuantite(3);
verifier($p1->getQuantite() === 7, 'Après retrait de 3, il en reste 7');
verifier(abs($p1->valeurStock() - 1050) < 0.001, 'La valeur du stock vaut 7 x 150');

$p1->ajouterQuantite(5);
verifier($p1->getQuantite() === 12, 'Après ajout de 5, il y en a 12');

try {
    new Produit('P002', 'Souris', -5, 1);
    verifier(false, 'Un prix négatif lève une exception');
} catch (InvalidArgumentException $e1) {
    verifier(true, 'Un prix négatif lève une exception');
}

try {
    $p1->retirerQuantite(100);
    verifier(false, 'Retirer plus que le stock lève une exception');
} catch (InvalidArgumentException $e2) {
    verifier(true, 'Retirer plus que le stock lève une exception');
}

try {
    $p1->ajouterQuantite(0);
    verifier(false, 'Ajouter 0 lève une exception');
} catch (InvalidArgumentException $e3) {
    verifier(true, 'Ajouter 0 lève une exception');
}