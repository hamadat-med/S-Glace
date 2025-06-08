<?php
session_start();
require_once '../config.php';  // adapte ce chemin selon ta config

// Suppression d'un avis sans condition d'admin
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_avis.php?deleted=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Avis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #fff0f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 15px;
        }
        h1 {
            color: #d63384;
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        table {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(214, 51, 132, 0.3);
        }
        thead tr {
            background: #f66ba9;
            color: white;
            font-weight: 600;
        }
        tbody tr:hover {
            background: #ffe3f1;
            transition: background-color 0.3s ease;
        }
        td, th {
            vertical-align: middle !important;
            text-align: center;
            word-wrap: break-word;
        }
        td.message-cell {
            text-align: left;
            max-width: 350px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* Bouton supprimer pink avec animation */
        .btn-supprimer {
            background-color: #ff4d6d;
            border: none;
            color: white;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(255, 77, 109, 0.4);
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            user-select: none;
            text-decoration: none;
            display: inline-block;
        }
        .btn-supprimer:hover {
            background-color: #ff1a40;
            transform: scale(1.1);
            box-shadow: 0 6px 14px rgba(255, 26, 64, 0.7);
        }
        .btn-supprimer:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(255, 77, 109, 0.8);
        }

        .alert-success {
            background-color: #ffd4e0;
            color: #d63384;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            max-width: 400px;
            margin: 0 auto 30px auto;
            text-align: center;
            box-shadow: 0 4px 10px rgba(214, 51, 132, 0.3);
        }




        .btn-retour-achat {
            background-color: #ff69b4; /* rose vif */
            border: none;
            color: white;
            font-weight: bold;
            padding: 14px 28px;
            border-radius: 30px;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(255, 105, 180, 0.4);
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-block;
            animation: pulseRetour 2.5s infinite;
        }

        .btn-retour-achat:hover {
            background-color: #ff1493;
            transform: scale(1.05);
            box-shadow: 0 6px 14px rgba(255, 20, 147, 0.6);
        }

        @keyframes pulseRetour {
            0%, 100% {
                box-shadow: 0 4px 10px rgba(255, 105, 180, 0.4);
            }
            50% {
                box-shadow: 0 6px 20px rgba(255, 105, 180, 0.6);
            }
        }

    </style>
</head>
<body>

<h1>Gestion des Avis</h1>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert-success">Avis supprimé avec succès !</div>
<?php endif; ?>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Nom</th>
        <th>Message</th>
        <th>Note</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $stmt = $pdo->query("SELECT * FROM avis ORDER BY date_avis DESC");
    while ($avis = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
        <tr>
            <td><?= htmlspecialchars($avis['nom']) ?></td>
            <td class="message-cell"><?= nl2br(htmlspecialchars($avis['message'])) ?></td>
            <td><?= intval($avis['note']) ?> / 5</td>
            <td><?= date('d/m/Y', strtotime($avis['date_avis'])) ?></td>
            <td>
                <a href="admin_avis.php?delete=<?= $avis['id'] ?>"
                   class="btn-supprimer"
                   onclick="return confirm('Voulez-vous vraiment supprimer cet avis ?');">
                    Supprimer
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<div class="text-center mt-5">
    <a href="dashboard.php" class="btn-retour-achat">← Retour au tableau de bord </a>
</div>


</body>
</html>
