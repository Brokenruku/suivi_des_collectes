<?php
function getVilleAvecBesoin($pdo){

    $sql = "
    SELECT 
        v.id,
        v.nom AS ville,
        r.nom AS region,

        COALESCE(
            json_agg(DISTINCT bn.nom) 
            FILTER (WHERE bn.id IS NOT NULL),
            '[]'
        ) AS besoin_nature,

        COALESCE(
            json_agg(DISTINCT bm.nom) 
            FILTER (WHERE bm.id IS NOT NULL),
            '[]'
        ) AS besoin_materiaux,

        ba.vola AS besoin_argent

    FROM ville v
    JOIN region r ON v.id_region = r.id
    LEFT JOIN besoin_nature bn ON bn.id_ville = v.id
    LEFT JOIN besoin_materiaux bm ON bm.id_ville = v.id
    LEFT JOIN besoin_argent ba ON ba.id_ville = v.id

    GROUP BY v.id, v.nom, r.nom, ba.vola
    ORDER BY v.id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

