<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <style>
        .card {
            border: 1px solid #ddd;
            padding: 12px;
            margin: 12px 0;
            border-radius: 8px
        }

        ul {
            margin: 6px 0 0 18px
        }
    </style>
</head>

<body>

    <h1>Accueil - Villes & Besoins</h1>

    <?php foreach (($villes ?? []) as $v): ?>
        <?php
        $nature = !empty($v['besoin_nature']) ? explode('||', $v['besoin_nature']) : [];
        $mat = !empty($v['besoin_materiaux']) ? explode('||', $v['besoin_materiaux']) : [];

        $argent = $v["besoin_argent"] ?? null;
        ?>

        <div class="card">
            <h2><?= htmlspecialchars($v["ville"] ?? "") ?></h2>
            <p><b>Région :</b> <?= htmlspecialchars($v["region"] ?? "") ?></p>

            <p><b>Besoins nature :</b></p>
            <?php if (count($nature) === 0): ?>
                <p>Aucun</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($nature as $n): ?>
                        <li><?= htmlspecialchars($n) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <p><b>Besoins matériaux :</b></p>
            <?php if (count($mat) === 0): ?>
                <p>Aucun</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($mat as $m): ?>
                        <li><?= htmlspecialchars($m) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <p><b>Besoin argent :</b>
                <?= ($argent === null) ? "Aucun" : htmlspecialchars((string)$argent) . " Ar" ?>
            </p>
        </div>
    <?php endforeach; ?>

</body>

</html>