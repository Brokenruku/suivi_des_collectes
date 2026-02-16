<?php
namespace app\models;

use PDO;

class RecapModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getStats(): array
    {
        $besoinArgent = (float)$this->pdo->query("
            SELECT COALESCE(SUM(vola),0) FROM besoin_argent
        ")->fetchColumn();

        $besoinNatureMontant = (float)$this->pdo->query("
            SELECT COALESCE(SUM(bn.qte * onat.prix_unitaire),0)
            FROM besoin_nature bn
            JOIN objet_nature onat ON onat.id = bn.id_objet_nature
        ")->fetchColumn();

        $besoinMatMontant = (float)$this->pdo->query("
            SELECT COALESCE(SUM(bm.qte * om.prix_unitaire),0)
            FROM besoin_materiaux bm
            JOIN objet_materiaux om ON om.id = bm.id_objet_materiaux
        ")->fetchColumn();

        $besoinsTotauxMontant = $besoinArgent + $besoinNatureMontant + $besoinMatMontant;

        $donsRecus = (float)$this->pdo->query("
            SELECT COALESCE(SUM(vola),0) FROM dons_argent
        ")->fetchColumn();

        $dispatches = (float)$this->pdo->query("
            SELECT COALESCE(SUM(total_argent),0) FROM achats
        ")->fetchColumn();

        $besoinsSatisfaits = $dispatches;

        $resteDonsArgent = max($donsRecus - $dispatches, 0);
        $resteBesoins = max($besoinsTotauxMontant - $besoinsSatisfaits, 0);

        return [
            'besoins' => [
                'total_montant' => $besoinsTotauxMontant,
                'satisfait_montant' => $besoinsSatisfaits,
                'reste_montant' => $resteBesoins,
                'details' => [
                    'argent' => $besoinArgent,
                    'nature' => $besoinNatureMontant,
                    'materiaux' => $besoinMatMontant,
                ]
            ],
            'dons' => [
                'recus_montant' => $donsRecus,
                'dispatches_montant' => $dispatches,
                'reste_montant' => $resteDonsArgent,
            ]
        ];
    }
}
