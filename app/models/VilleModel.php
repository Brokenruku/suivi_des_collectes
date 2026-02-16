<?php

namespace app\models;

use PDO;

class VilleModel
{
  public static function getVilleAvecBesoin(PDO $pdo): array
  {
    $sql = "
    SELECT
      v.id,
      v.nom AS ville,
      r.nom AS region,
      GROUP_CONCAT(DISTINCT bn.nom SEPARATOR '||') AS besoin_nature,
      GROUP_CONCAT(DISTINCT bm.nom SEPARATOR '||') AS besoin_materiaux,
      ba.vola AS besoin_argent
    FROM ville v
    JOIN region r ON v.id_region = r.id
    LEFT JOIN besoin_nature bn ON bn.id_ville = v.id
    LEFT JOIN besoin_materiaux bm ON bm.id_ville = v.id
    LEFT JOIN besoin_argent ba ON ba.id_ville = v.id
    GROUP BY v.id, v.nom, r.nom, ba.vola
    ORDER BY v.id
  ";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
  }
}
