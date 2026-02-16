<?php
function insertDon($pdo, $id_user, $natures, $materiaux, $argent){

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO dons (id_users) VALUES (?) RETURNING id");
        $stmt->execute([$id_user]);
        $id_dons = $stmt->fetchColumn();

        if(!empty($natures)){
            $stmtNature = $pdo->prepare("INSERT INTO dons_nature (nom, id_dons) VALUES (?, ?)");
            
            foreach($natures as $nom){
                if($nom != ""){
                    $stmtNature->execute([$nom, $id_dons]);
                }
            }
        }

        if(!empty($materiaux)){
            $stmtMat = $pdo->prepare("INSERT INTO dons_materiaux (nom, id_dons) VALUES (?, ?)");
            
            foreach($materiaux as $nom){
                if($nom != ""){
                    $stmtMat->execute([$nom, $id_dons]);
                }
            }
        }

        if($argent != null && $argent > 0){
            $stmtArgent = $pdo->prepare("INSERT INTO dons_argent (vola, id_dons) VALUES (?, ?)");
            $stmtArgent->execute([$argent, $id_dons]);
        }

        $pdo->commit();
        return true;

    } catch(PDOException $e){

        $pdo->rollBack();
        return false;
    }
}
function getDons($pdo){

    $sql = "
        SELECT
            d.id,
            d.date,
            u.nom AS user_nom,

            COALESCE(
                json_agg(DISTINCT dn.nom)
                FILTER (WHERE dn.id IS NOT NULL),
                '[]'
            ) AS dons_nature,

            COALESCE(
                json_agg(DISTINCT dm.nom)
                FILTER (WHERE dm.id IS NOT NULL),
                '[]'
            ) AS dons_materiaux,

            COALESCE(da.vola, 0) AS dons_argent

        FROM dons d
        JOIN users u ON u.id = d.id_users
        LEFT JOIN dons_nature dn ON dn.id_dons = d.id
        LEFT JOIN dons_materiaux dm ON dm.id_dons = d.id
        LEFT JOIN dons_argent da ON da.id_dons = d.id

        GROUP BY d.id, d.date, u.nom, da.vola
        ORDER BY d.date DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>
