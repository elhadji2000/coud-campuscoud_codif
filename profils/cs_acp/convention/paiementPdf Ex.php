<?php session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /campuscoud.com/');
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';
require('../../../traitement/fonction.php');

$mpdf = new \Mpdf\Mpdf();

$data = getPaiementWithDateInterval($_SESSION['debut'], $_SESSION['fin'], $_SESSION['username']);

                        $timeD= strtotime($_SESSION['debut']);
                        $timeF= strtotime($_SESSION['fin']);
                        $dateD = date('d-m-Y',$timeD);
                        $dateF = date('d-m-Y',$timeF);

$html = '
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Document</title>
    </head>
    <body>

    <div class="container">
    <div class="row">
           <header>
                <div class="row">
                    <div class="col-md-4">
                        
						<p> Republique du Sénégal<br/>
						    Ministére de l\'Enseignement<br>supérieur, de la Recherche et de l\'Innovation<br/>
                            <u>________________________</u><br/>
                            <b> Centre des Œuvres universitaires de Dakar</b><br/>
							 <u>________________________</u><br/>
                            <b>Agence Comptable</b>
                        </p>
						
                    </div>
					
					    
						
                    <div class="col-md-8">
                        <div class="data-room">
                            <h4>DU <b>' .  $dateD . '<br> AU ' . $dateF . '<br>  Regisseur: ' .  $_SESSION['username'] . ' </b></h4>    
                        </div>
                    </div> <br><br><br>

                </div> 
                   <div class="row">
                <div class="col-md-12 text-center">
                    <b> ETAT DES ENCAISSEMENTS</b>
                </div><br/>
               
              
                
               </div><br/>               
            </header>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
				<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quittance</th>
				<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Date</th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Libelle</th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Num Étudiant</th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Prenom et NOM</th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Montant</th>
                    
                </tr>
            </thead>
            <tbody>
            ';   $total=0;
            foreach ($data as $row) {
            $html .= '
               <tr>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['quittance']) . '</td>
					 <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['dateTime_paie']) . '</td>
					 <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['libelle']) . '</td>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['num_etu']) . '</td>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['prenoms']." ".$row['nom']) . '</td>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['montant']) . '</td>
               </tr>';
			   $total+=$row['montant'];
            }
			$html .= '<tr>
                    <td colspan="5" align="center" style="border: 1px solid #dddddd; text-align: left; padding: 8px;">TOTAL DE LA PERIODE</td>                    
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($total) . '</td>
               </tr>';

            $html .= '
            </tbody>
        </table>
        </div>
    </div>    
</body>';

// Charger le contenu HTML dans mPDF
$mpdf->WriteHTML($html);

// Générer le PDF et le sortir
$mpdf->Output('etat encaissement', \Mpdf\Output\Destination::INLINE);
