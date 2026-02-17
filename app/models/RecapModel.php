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
        // montant besoins par type
        $besoinArgent = (float)$this->pdo->query("SELECT COALESCE(SUM(vola),0) FROM besoin_argent")->fetchColumn();

        $besoinNatureMontant = (float)$this->pdo->query(
            "SELECT COALESCE(SUM(bn.qte * onat.prix_unitaire),0) FROM besoin_nature bn JOIN objet_nature onat ON onat.id = bn.id_objet_nature"
        )->fetchColumn();

        $besoinMatMontant = (float)$this->pdo->query(
            "SELECT COALESCE(SUM(bm.qte * om.prix_unitaire),0) FROM besoin_materiaux bm JOIN objet_materiaux om ON om.id = bm.id_objet_materiaux"
        )->fetchColumn();

        // qte totales demandees
        $besoinNatureQte = (int)$this->pdo->query("SELECT COALESCE(SUM(qte),0) FROM besoin_nature")->fetchColumn();
        $besoinMatQte = (int)$this->pdo->query("SELECT COALESCE(SUM(qte),0) FROM besoin_materiaux")->fetchColumn();

        // qte satisfaites via achats (achat_lignes)
        $satisfaitNatureQte = (int)$this->pdo->query("SELECT COALESCE(SUM(al.qte),0) FROM achat_lignes al WHERE al.type_objet = 'nature'")->fetchColumn();
        $satisfaitMatQte = (int)$this->pdo->query("SELECT COALESCE(SUM(al.qte),0) FROM achat_lignes al WHERE al.type_objet = 'materiaux'")->fetchColumn();

        // montant dons et dispatches (achats)
        $donsRecus = (float)$this->pdo->query("SELECT COALESCE(SUM(vola),0) FROM dons_argent")->fetchColumn();
        $dispatches = (float)$this->pdo->query("SELECT COALESCE(SUM(total_argent),0) FROM achats")->fetchColumn();

        $besoinsTotauxMontant = $besoinArgent + $besoinNatureMontant + $besoinMatMontant;
        $besoinsSatisfaits = $dispatches;

        $resteDonsArgent = max($donsRecus - $dispatches, 0);
        $resteBesoins = max($besoinsTotauxMontant - $besoinsSatisfaits, 0);

        // Details par objet pour nature
        $stmt = $this->pdo->query(
            "SELECT onat.id, onat.nom, onat.prix_unitaire,
              COALESCE((SELECT SUM(bn.qte) FROM besoin_nature bn WHERE bn.id_objet_nature = onat.id),0) AS qte_besoin,
              COALESCE((SELECT SUM(al.qte) FROM achat_lignes al JOIN achats a ON a.id = al.id_achat WHERE al.type_objet='nature' AND al.id_objet = onat.id),0) AS qte_satisfait
             FROM objet_nature onat ORDER BY onat.nom"
        );
        $natureDetails = [];
        foreach ($stmt->fetchAll() as $r) {
            $qteBesoin = (int)$r['qte_besoin'];
            $qteSatisfait = (int)$r['qte_satisfait'];
            $qteReste = max($qteBesoin - $qteSatisfait, 0);
            $pu = (float)$r['prix_unitaire'];
            $natureDetails[] = [
                'id' => (int)$r['id'],
                'nom' => $r['nom'],
                'pu' => $pu,
                'qte_besoin' => $qteBesoin,
                'qte_satisfait' => $qteSatisfait,
                'qte_reste' => $qteReste,
                'montant_besoin' => $pu * $qteBesoin,
                'montant_satisfait' => $pu * $qteSatisfait,
            ];
        }

        // Details par objet pour materiaux
        $stmt = $this->pdo->query(
            "SELECT om.id, om.nom, om.prix_unitaire,
              COALESCE((SELECT SUM(bm.qte) FROM besoin_materiaux bm WHERE bm.id_objet_materiaux = om.id),0) AS qte_besoin,
              COALESCE((SELECT SUM(al.qte) FROM achat_lignes al JOIN achats a ON a.id = al.id_achat WHERE al.type_objet='materiaux' AND al.id_objet = om.id),0) AS qte_satisfait
             FROM objet_materiaux om ORDER BY om.nom"
        );
        $matDetails = [];
        foreach ($stmt->fetchAll() as $r) {
            $qteBesoin = (int)$r['qte_besoin'];
            $qteSatisfait = (int)$r['qte_satisfait'];
            $qteReste = max($qteBesoin - $qteSatisfait, 0);
            $pu = (float)$r['prix_unitaire'];
            $matDetails[] = [
                'id' => (int)$r['id'],
                'nom' => $r['nom'],
                'pu' => $pu,
                'qte_besoin' => $qteBesoin,
                'qte_satisfait' => $qteSatisfait,
                'qte_reste' => $qteReste,
                'montant_besoin' => $pu * $qteBesoin,
                'montant_satisfait' => $pu * $qteSatisfait,
            ];
        }

        // Dons: argent entries (recent) and per-object donated quantities
        $stmt = $this->pdo->query(
            "SELECT da.id AS id, da.vola AS vola, d.date AS date, u.nom AS user
             FROM dons_argent da
             JOIN dons d ON d.id = da.id_dons
             LEFT JOIN users u ON u.id = d.id_users
             ORDER BY d.date DESC LIMIT 100"
        );
        $argentEntries = [];
        foreach ($stmt->fetchAll() as $r) {
            $argentEntries[] = [
                'id' => (int)$r['id'],
                'vola' => (float)$r['vola'],
                'date' => $r['date'],
                'user' => $r['user'] ?? null,
            ];
        }

        // per-object donations for nature
        $stmt = $this->pdo->query(
            "SELECT onat.id, onat.nom, onat.prix_unitaire,
              COALESCE((SELECT SUM(dn.qte) FROM dons_nature dn WHERE dn.id_objet_nature = onat.id),0) AS qte_don
             FROM objet_nature onat ORDER BY onat.nom"
        );
        $natureDonDetails = [];
        foreach ($stmt->fetchAll() as $r) {
            $qteDon = (int)$r['qte_don'];
            $pu = (float)$r['prix_unitaire'];
            $natureDonDetails[] = [
                'id' => (int)$r['id'],
                'nom' => $r['nom'],
                'pu' => $pu,
                'qte_don' => $qteDon,
                'montant_don' => $pu * $qteDon,
            ];
        }

        // per-object donations for materials
        $stmt = $this->pdo->query(
            "SELECT om.id, om.nom, om.prix_unitaire,
              COALESCE((SELECT SUM(dm.qte) FROM dons_materiaux dm WHERE dm.id_objet_materiaux = om.id),0) AS qte_don
             FROM objet_materiaux om ORDER BY om.nom"
        );
        $matDonDetails = [];
        foreach ($stmt->fetchAll() as $r) {
            $qteDon = (int)$r['qte_don'];
            $pu = (float)$r['prix_unitaire'];
            $matDonDetails[] = [
                'id' => (int)$r['id'],
                'nom' => $r['nom'],
                'pu' => $pu,
                'qte_don' => $qteDon,
                'montant_don' => $pu * $qteDon,
            ];
        }

        return [
            'besoins' => [
                'total_montant' => $besoinsTotauxMontant,
                'satisfait_montant' => $besoinsSatisfaits,
                'reste_montant' => $resteBesoins,
                'details' => [
                    'argent' => $besoinArgent,
                    'nature' => $besoinNatureMontant,
                    'materiaux' => $besoinMatMontant,
                    'qte' => [
                        'nature' => $besoinNatureQte,
                        'materiaux' => $besoinMatQte,
                        'satisfait_nature' => $satisfaitNatureQte,
                        'satisfait_materiaux' => $satisfaitMatQte,
                    ],
                    'nature_details' => $natureDetails,
                    'materiaux_details' => $matDetails,
                ]
            ],
            'dons' => [
                'recus_montant' => $donsRecus,
                'dispatches_montant' => $dispatches,
                'reste_montant' => $resteDonsArgent,
                'argent_entries' => $argentEntries,
                'nature_don_details' => $natureDonDetails,
                'materiaux_don_details' => $matDonDetails,
            ]
        ];
    }
}
