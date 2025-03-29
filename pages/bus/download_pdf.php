<?php
require_once dirname(__DIR__, 2) . '/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Mpdf\Mpdf;

$query = "SELECT bus.*, lignes.numero AS ligne_numero 
          FROM bus 
          LEFT JOIN lignes ON bus.ligne_id = lignes.id 
          ORDER BY bus.id DESC";
$result = $connexion->query($query);

$mpdf = new Mpdf();
$mpdf->SetTitle('Liste des Bus');

$html = '
<h2 style="text-align: center; color: #007bff;">🚌 Liste des Bus</h2>
<table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #007bff; color: white;">
            <th>ID</th>
            <th>Immatriculation</th>
            <th>Type</th>
            <th>Kilométrage</th>
            <th>Places</th>
            <th>État</th>
            <th>Ligne</th>
            <th>Localisation</th>
        </tr>
    </thead>
    <tbody>';

while ($bus = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . $bus['id'] . '</td>
                <td>' . htmlspecialchars($bus['immatriculation']) . '</td>
                <td>' . htmlspecialchars($bus['type']) . '</td>
                <td>' . number_format($bus['kilometrage'], 0, ',', ' ') . ' km</td>
                <td>' . htmlspecialchars($bus['nbre_place']) . '</td>
                <td>' . htmlspecialchars($bus['etat']) . '</td>
                <td>' . ($bus['ligne_numero'] ? "Ligne " . htmlspecialchars($bus['ligne_numero']) : "Non assigné") . '</td>
                <td>' . htmlspecialchars($bus['localisation']) . '</td>
              </tr>';
}

$html .= '</tbody></table>';
$mpdf->WriteHTML($html);
$mpdf->Output('Liste_des_Bus.pdf', 'D');
