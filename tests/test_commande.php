<?php
$cp = new Produit('P010', 'Cable', 10, 5);
$cc = new Commande(1);
$cc->ajouterLigne($cp, 3);
verifier(abs($cc->total() - 30) < 0.001, 'Le total vaut 3 x 10');
verifier($cc->estValidee() === false, 'La commande n\'est pas validée au départ');
$cc->valider();
verifier($cc->estValidee() === true, 'La commande est validée');
verifier($cp->getQuantite() === 2, 'Le stock du produit est décrémenté');

try {
    $cc->valider();
    verifier(false, 'Valider deux fois lève une exception');
} catch (LogicException $ec1) {
    verifier(true, 'Valider deux fois lève une exception');
}

try {
    (new Commande(2))->valider();
    verifier(false, 'Valider une commande vide lève une exception');
} catch (LogicException $ec2) {
    verifier(true, 'Valider une commande vide lève une exception');
}

try {
    (new Commande(3))->ajouterLigne($cp, 999);
    verifier(false, 'Quantité > stock lève une exception');
} catch (InvalidArgumentException $ec3) {
    verifier(true, 'Quantité > stock lève une exception');
}
