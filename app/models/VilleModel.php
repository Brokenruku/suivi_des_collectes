<?php

namespace app\models;

use PDO;

class VilleModel
{
  public static function getVilleAvecBesoin(PDO $pdo): array
  {
    $sql = "SELECT
              v.id,
              v.nom AS ville,
              r.nom AS region,

              GROUP_CONCAT(DISTINCT onat.nom SEPARATOR '||') AS besoin_nature,
              GROUP_CONCAT(DISTINCT omat.nom SEPARATOR '||') AS besoin_materiaux,
              MAX(ba.vola) AS besoin_argent,

              COALESCE(a.total_achats, 0) AS total_achats

            FROM ville v
            JOIN region r ON v.id_region = r.id

            LEFT JOIN besoin_nature bn ON bn.id_ville = v.id
            LEFT JOIN objet_nature onat ON onat.id = bn.id_objet_nature

            LEFT JOIN besoin_materiaux bm ON bm.id_ville = v.id
            LEFT JOIN objet_materiaux omat ON omat.id = bm.id_objet_materiaux

            LEFT JOIN besoin_argent ba ON ba.id_ville = v.id

            LEFT JOIN (
              SELECT id_ville, SUM(total_argent) AS total_achats
              FROM achats
              GROUP BY id_ville
            ) a ON a.id_ville = v.id

            GROUP BY v.id, v.nom, r.nom, a.total_achats
            ORDER BY v.id
          ";


  $stmt = $pdo->query($sql);
  return $stmt->fetchAll();
  }
}
