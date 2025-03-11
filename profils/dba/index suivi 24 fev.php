<?php session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /campuscoud.com/');
    exit();
}
/*
if (empty($_SESSION['classe'])) {
    header('location: /COUD/codif/profils/personnels/niveau.php');
    exit();
}
*/
include('../../traitement/fonction.php');

if (isset($_POST['filtre_sexe']) && !empty($_POST['filtre_sexe']) && $_POST['filtre_sexe'] == 'G') {
    $sexe = "garçons";
    $sexe_ = "G";
} else {
    $sexe = "filles";
    $sexe_ = "F";
}

$resultats = getLitsBySexeAndNiveau2($sexe_);   

$lits = $resultats['lits'];
$totaux = $resultats['totaux'];

// Vérifiez si 'totauxParEtablissement' existe
if (isset($resultats['totauxParEtablissement'])) {
    $totauxParEtablissement = $resultats['totauxParEtablissement'];
} else {
    $totauxParEtablissement = []; // Initialiser par défaut
}
if (isset($_GET['quota'])) {
    $tableau_data_etudiant = getAllDatastudentStatus($_GET['quota'], $_GET['classe'], $_GET['sexe']);
    // $tableau_data_etudiant2 = getAllDatastudentStatus($_GET['quota'], $_GET['classe'], $_GET['sexe']);
}

$comptLoger = 0;
$comptPaye = 0;
$comptVal = 0;
$comptChoix = 0;
$total_loge = 0;
$comptLoger2 = 0;
$comptPaye2 = 0;
$comptVal2 = 0;
$comptChoix2 = 0;
$total_loge2 = 0;
$openModal = isset($_GET['openModal']) && $_GET['openModal'] == 'getClasse';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION </title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        thead.print-only {
            display: none;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            thead.print-only {
                display: table-header-group;
                /* Afficher comme en-tête */
            }

            #table-to-print,
            #table-to-print * {
                visibility: visible;
            }

            #table-to-print {
                position: absolute;
                top: 0;
                left: 0;
            }
        }
    </style>
</head>

<body>
    <?php include('../../head.php'); ?>
    <!-- Tableau detaillé des etudiant qui ont logés -->
    <div class="modal fade" id="getClasse" tabindex="-1" role="dialog" -labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">SUIVI DES CODIFICATIONS </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <tr class="table-secondary" style="font-size: 16px; font-weight: 400;">
                                <td>N° Etudiant</td>
                                <td>PRENOM</td>
                                <td>NOM</td>
                                <td>RANG</td>
                                <td>STATUT</td>
                                <td>SITUATION</td>
                            </tr>
                            <?php
                            if ($_GET['quota']) {
                                for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                                    if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') { ?>
                                        <tr class="table-success" style="font-size: 14px;">
                                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                                            <td>
                                                <?php
                                                if (isLoger_titulaire($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "LOGER";
                                                } else if (isPaie_titulaire($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "Payer et non loger";
                                                } else if (isValider($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "Valider";
                                                } else if (isChoix($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "A choisi";
                                                } else {
                                                    echo "N'en pas choisi";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    } else if ($tableau_data_etudiant[$i]['statut'] == 'forclus') { ?>
                                        <tr class="table-dark" style="font-size: 14px;">
                                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                                            <td>Forclus</td>
                                        </tr>
                                    <?php
                                    } else if ($tableau_data_etudiant[$i]['statut'] == 'suppleant') { ?>
                                        <tr class="table-primary" style="font-size: 14px;">
                                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                                            <td>
                                                <?php
                                                if (isLoger($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "LOGER";
                                                } else if (isValider($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "Valider";
                                                } else if (isChoix($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "A choisi";
                                                } else {
                                                    echo "N'en pas choisi Lit";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } else if ($tableau_data_etudiant[$i]['statut'] == 'non attributaire') { ?>
                                        <tr class="table-danger" style="font-size: 14px;">
                                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                                            <td>
                                                <?php
                                                if (isLoger($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "LOGER";
                                                } else if (isValider($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "Valider";
                                                } else if (isChoix($tableau_data_etudiant[$i]['num_etu'])) {
                                                    echo "Agjhklmljhg choisi";
                                                } else {
                                                    echo "N'en pas choisi Lit";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } else if ($tableau_data_etudiant[$i]['statut'] == 'non defini') { ?>
                                        <tr class="table-danger" style="font-size: 14px;">
                                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                                            <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                                            <td>Non Défini</td>
                                        </tr>
                            <?php }
                                }
                            }
                            ?>
                        </table>
                        <script>
                            function printTable() {
                                window.print();
                            }
                        </script>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">FERMER</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Tableau detaillé des etudiant qui ont logés -->
    <div class="container-fluid mt-2">
        <form action="" method="post">
            <div class="row mb-2">
                <div class="col">
                    <button class="btn btn-success" onclick="printTable()">Imprimer</button>
                </div>
                <div class="col">
                    <h1>
                        <center>Liste des Quotas <?= $sexe; ?></center>
                    </h1>
                </div>
                <div class="col-md-2">
                    <select class="form-select" aria-label="Default select example" name="filtre_sexe">
                        <option selected disabled>SEXE CLASSE</option>
                        <option value="G">Garçon</option>
                        <option value="F">Fille</option>
                    </select>
                </div>
                <div class="col-md-1 mt-2">
                    <button type="submit" class="btn btn-secondary btn-lg">FILTRER</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-hover" style="font-size:14px;" id="table-to-print">
            <thead class="print-only">
                <!-- <thead> -->
                <tr style="border: 0;">
                    <td style="border: 0;" colspan="4">
                        <h4>RÉPUBLIQUE DU SÉNÉGAL</h4><br>
                        <p style="margin-top: -3%; margin-left:4%">Un Peuple – Un But – Une Foi</p><br>
                        <img src="../../assets/images/drapeau.png" alt="" width="100" style="margin-left: 10%; margin-top:-7%">
                        <h6 style="margin-left: 1%;">MINISTÈRE DE L’ENSEIGNEMENT SUPÉRIEUR,<br>
                            DE LA RECHERCHE ET DE L’INNOVATION</h6>
                        <u style="margin-left: 8%;">_______________</u><br>
                        <b>CENTRE DES ŒUVRES UNIVERSITAIRES DE DAKAR
                            COUD</b><br>
                        <h4 style="margin-left: 5%;">
                            Département des Cités universitaires
                        </h4>
                        <h4 style="margin-left: 8%;">
                            Service de l’Hébergement
                        </h4><br><br>
                    </td>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th>Facultés</th>
                    <th>Niveaux Et Formations</th>
                    <th>Quota</th>
                    <th>Choisi</th>
                    <th>Validé</th>
                    <th>Payé</th>
                    <th>Logé</th>
                    <th>Logé</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totaux_choix_fac_0 = 0;
                $totaux_choix_fac_1 = 0;
                $totaux_val_fac_0 = 0;
                $totaux_val_fac_1 = 0;
                $totaux_paye_fac_0 = 0;
                $totaux_paye_fac_1 = 0;
                $totaux_loge_fac_0 = 0;
                $totaux_loge_fac_1 = 0;

                $chx_lit = 0;
                $chx_lit1 = 0;
                $val_lit = 0;
                $val_lit1 = 0;
                $paye_lit = 0;
                $paye_lit1 = 0;
                $loge_lit = 0;
                $loge_lit1 = 0;

                $total_l0=0;
                $total_l1=0;

                foreach ($lits as $etablissement => $niveaux):
                    $firstRow = true; // Indicateur pour le premier niveau
                    $rowCount = count($niveaux); // Compter le nombre de niveaux
                    foreach ($niveaux as $niveau => $data):
                        if ($firstRow): // Si c'est le premier niveau, afficher l'établissement 
                ?>
                            <?php
                            $tableau_data_etudiant1 = getAllDatastudentStatus($data[$sexe], $niveau, $sexe_);
                            for ($i = 0; $i < count($tableau_data_etudiant1); $i++) {
                                if (isChoix($tableau_data_etudiant1[$i]['num_etu'])) {
                                    $comptChoix++;
                                }
                                if (isValider($tableau_data_etudiant1[$i]['num_etu'])) {
                                    $comptVal++;
                                }
                                if (isPaie_titulaire($tableau_data_etudiant1[$i]['num_etu'])) {
                                    $comptPaye++;
                                }
                                if (isLoger($tableau_data_etudiant1[$i]['num_etu']) || isLoger_titulaire($tableau_data_etudiant1[$i]['num_etu'])) {
                                    $comptLoger++;
                                }
                            ?>
                            <?php } ?>
                            <tr class="table-primary">
                                <td rowspan="<?php echo $rowCount; ?>" style="text-align: center; vertical-align: middle; font-size: 16px;">
                                    <?= ($etablissement); ?>
                                </td>
                                <td><?= ($niveau); ?></td>
                                <td><a title="VOIRE DETAIL DE LA CLASSE" href="request_suivi.php?classe=<?= $niveau ?>&sexe=<?= $sexe_; ?>" style="color: inherit;">
                                        <?= ($data[$sexe]); ?>
                                    </a>
                                </td>
                                <td><?php echo $comptChoix;
                                    $totaux_choix_fac_0 = $totaux_choix_fac_0 + $comptChoix;
                                    $chx_lit1 += $totaux_choix_fac_0;
                                    // $compt_choix=$compt_choix+$comptChoix;
                                    ?></td>
                                <td><?php echo $comptVal;
                                    $totaux_val_fac_0 = $totaux_val_fac_0 + $comptVal;
                                    $val_lit1 += $totaux_val_fac_0;
                                    ?></td>
                                <td><?php echo $comptPaye;
                                    $totaux_paye_fac_0 = $totaux_paye_fac_0 + $comptPaye;
                                    $paye_lit1 += $totaux_paye_fac_0;
                                    ?></td>
                                <td><?php echo $comptLoger;
                                    $totaux_loge_fac_0 = $totaux_loge_fac_0 + $comptLoger;
                                    $loge_lit1 += $totaux_loge_fac_0;
                                    $total_l1 += $totaux_loge_fac_0;
                                    ?></td>
                                <td>
                                    <?php if (isset($data[$sexe]) && $data[$sexe] != 0) { ?>
                                        <?= (($comptLoger * 100) / ($data[$sexe])); ?>%
                                    <?php
                                        $comptChoix = 0;
                                        $comptVal = 0;
                                        $comptPaye = 0;
                                        $comptLoger = 0;
                                    } ?>
                                </td>
                            </tr>
                        <?php
                            $firstRow = false; // Ne plus afficher l'établissement pour les lignes suivantes
                        else: // Pour les lignes suivantes
                        ?>
                            <?php
                            // print_r($niveau);
                            $tableau_data_etudiant2 = getAllDatastudentStatus($data[$sexe], $niveau, $sexe_);
                            for ($j = 0; $j < count($tableau_data_etudiant2); $j++) {
                                if (isChoix($tableau_data_etudiant2[$j]['num_etu'])) {
                                    $comptChoix2++;
                                }
                                if (isValider($tableau_data_etudiant2[$j]['num_etu'])) {
                                    $comptVal2++;
                                }
                                if (isPaie_titulaire($tableau_data_etudiant2[$j]['num_etu'])) {
                                    $comptPaye2++;
                                }
                                if (isLoger($tableau_data_etudiant2[$j]['num_etu']) || isLoger_titulaire($tableau_data_etudiant2[$j]['num_etu'])) {
                                    $comptLoger2++;
                                }
                            ?>
                            <?php } ?>
                            <tr class="table-primary">
                                <td><?= ($niveau); ?></td>
                                <td>
                                    <a title="VOIRE DETAIL DE LA CLASSE" href="request_suivi.php?classe=<?= $niveau ?>&sexe=<?= $sexe_; ?>" style="color: inherit;">
                                        <?= ($data[$sexe]); ?>
                                    </a>
                                </td>
                                <td><?php echo $comptChoix2;
                                    $totaux_choix_fac_1 = $comptChoix2 + $totaux_choix_fac_1; ?></td>
                                <td><?php echo $comptVal2;
                                    $totaux_val_fac_1 = $totaux_val_fac_1 + $comptVal2; ?></td>
                                <td><?php echo  $comptPaye2;
                                    $totaux_paye_fac_1 = $totaux_paye_fac_1 + $comptPaye2; ?></td>
                                <td><?php echo $comptLoger2;
                                    $totaux_loge_fac_1 = $totaux_loge_fac_1 + $comptLoger2; ?></td>
                                <td>
                                    <?php if (($data[$sexe]) != 0) { ?>
                                        <?= $poucentage = (($comptLoger2 * 100) / ($data[$sexe])); ?>%
                                    <?php
                                        $comptChoix2 = 0;
                                        $comptVal2 = 0;
                                        $comptPaye2 = 0;
                                        $comptLoger2 = 0;
                                    } else { ?>
                                        <?= $poucentage = 0; ?>%
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php
                        endif;
                    endforeach; ?>
                    <!-- Afficher les totaux par établissement -->
                    <tr class="table-success">
                        <td>Total quota <?= ($etablissement); ?></td>
                        <td></td>
                        <td style="color: red;"><?= ($totauxParEtablissement[$etablissement][$sexe] ?? 0); ?></td>
                        <td style="color: red;"><?php echo $totaux_choix_fac_1 + $totaux_choix_fac_0;
                            $chx_lit += $totaux_choix_fac_1;
                            $totaux_choix_fac_1 = $totaux_choix_fac_0 = 0; ?></td>
                        <td style="color: red;"><?php echo $totaux_val_fac_1 + $totaux_val_fac_0;
                            $val_lit += $totaux_val_fac_1;
                            $totaux_val_fac_1 = $totaux_val_fac_0 = 0; ?></td>
                        <td style="color: red;"><?php echo $totaux_paye_fac_1 + $totaux_paye_fac_0;
                            $paye_lit += $totaux_paye_fac_1;
                            $totaux_paye_fac_1 = $totaux_paye_fac_0 = 0; ?></td>
                        <td style="color: red;"><?php echo $totaux_loge_fac_1 + $totaux_loge_fac_0;
                            $loge_lit += $totaux_loge_fac_1;
                            $total_l0=$loge_lit;
                            $totaux_loge_fac_1 = $totaux_loge_fac_0 = 0; ?></td>
                        <td style="color: red;">
                            <?php if (($data[$sexe]) != 0) {
                                $quota_pourcentage=((($total_l0 + $total_l1)*100)/($totauxParEtablissement[$etablissement][$sexe]));
                                echo $quota_pourcentage; $total_l0=0; $total_l1=0;
                             } ?>%
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="table-success" style="border-top: 2px solid black;">
                    <td>Total Global</td>
                    <td></td>
                    <td><?= ($totaux[$sexe]); ?></td>
                    <td><?= $chx_lit + $chx_lit1; ?></td>
                    <td><?= $val_lit + $val_lit1; ?></td>
                    <td><?= $paye_lit + $paye_lit1; ?></td>
                    <td><?= $loge_lit + $loge_lit1; ?></td>
                    <td><?= ((($loge_lit + $loge_lit1) * 100) / ($totaux[$sexe])) ?>%</td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="../../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../../assets/js/plugins.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script src="../../assets/js/script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($openModal): ?>
            var myModal = new bootstrap.Modal(document.getElementById('getClasse'));
            myModal.show();
        <?php endif; ?>
    });
</script>

</html>