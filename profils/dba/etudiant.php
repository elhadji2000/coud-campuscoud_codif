<?php session_start(); 

include('../../traitement/fonction.php');

verif_type_mdp_2($_SESSION['username']);

$etudiant = null;

if (isset($_GET["numCarte"])) {
    $numCarte = $_GET["numCarte"];
    $etudiant = studentConnect($numCarte); // Doit retourner un tableau ou null
}

 ?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION</title>
    <!-- CSS================================================== -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <!-- script================================================== -->
    <script src="../../assets/js/modernizr.js"></script>
    <script src="../../assets/js/pace.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="../../assets/css/base.css" />
    <link rel="stylesheet" href="../../assets/css/login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <?php include('../../head.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" />

    <style>
    /* Amélioration des champs de saisie */
    .form-control {
        background-color: rgba(161, 187, 228, 0.1);
        font-size: 16px;
        height: 60px;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    /* Centrage et style du conteneur */
    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Style du tableau */
    table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
        font-size: 13px;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Message d'erreur */
    .alert {
        font-size: 18px;
        text-align: center;
        padding: 15px;
        margin-top: 20px;
    }

    /* Style des boutons */
    .btn-custom {
        font-size: 14px;
        padding: 10px 15px;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
    }

    .btn-custom:hover {
        transform: scale(1.05);
    }
    </style>

<body>
    <div class="container">
        <h2 class="text-center text-primary">Rechercher un étudiant</h2>

        <form method="GET" action="etudiant.php" class="text-center mt-4">
            <center>
                <div class="text-center row">

                    <div class="col-md-6">
                        <input type="text" required placeholder="Numéro Étudiant" name="numCarte"
                            class="form-control" />
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block btn-custom">
                            <i class="fas fa-search"></i>RECHERCHER
                        </button>
                    </div>

                </div>
            </center>
        </form>


        <!-- Affichage des résultats -->
        <?php if ($etudiant) : ?>
        <div class="mt-4">
            <h3 class="text-center text-success">Informations de l'étudiant</h3>
            <table class="table table-bordered table-hover mt-3">
                <thead class="table-info">
                    <tr>
                        <th>Num_Carte</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Departement</th>
                        <th>date_naissance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><?= htmlspecialchars($etudiant['num_etu']) ?></th>
                        <td><?= htmlspecialchars($etudiant['prenoms']) ?></td>
                        <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                        <td><?= htmlspecialchars($etudiant['telephone']) ?></td>
                        <td><?= htmlspecialchars($etudiant['departement']) ?></td>
                        <td><?= htmlspecialchars($etudiant['dateNaissance']) ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <a href="etudiant.php" class="btn btn-secondary btn-custom"><i class="fas fa-arrow-left"></i> Retour à
                    la recherche</a>
            </div>
        </div>
        <?php else : ?>
        <?php if (isset($_GET["numCarte"])) : ?>
        <div class="alert alert-danger">
            <h3>Étudiant non trouvé</h3>
            <p>Aucun étudiant trouvé avec ce numéro.</p>
            <a href="etudiant.php?ajouter" class="btn btn-success btn-custom">
                <i class="fas fa-user-plus"></i> Ajouter un étudiant
            </a>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!-- Condition pour afficher le formulaire d'ajout d'étudiant -->
        <?php if (isset($_GET["ajouter"])) : ?>
        <div class="mt-4">
            <h3 class="text-center text-primary">Ajouter un étudiant</h3>
            <form action="etudiant.php" method="POST" class="mt-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Numéro Étudiant</label>
                        <input type="text" name="num_etu" required class="form-control" placeholder="Ex: 2023123456">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenoms" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Département</label>
                        <input type="text" name="departement" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de Naissance</label>
                        <input type="date" name="dateNaissance" required class="form-control">
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success btn-custom">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="etudiant.php" class="btn btn-secondary btn-custom"><i class="fas fa-times"></i> Annuler</a>
                </div>
            </form>
        </div>
        <?php endif; ?>


    </div>
    <!-- footer ================================================== -->
    <footer>
        <div class="row">
            <div class="col-full">
                <div class="footer-logo">
                    <a class="footer-site-logo" href="#0"><img src="/campuscoud.com/assets/images/logo.png"
                            alt="Homepage"></a>
                </div>
            </div>
        </div>
        <div class="row footer-bottom">
            <div class="col-twelve">
                <div class="copyright">
                    <span>&copy;Copyright Centre des Oeuvres universitaires de Dakar</span>
                </div>
                <div class="go-top">
                    <a class="smoothscroll" title="Back to Top" href="#top"><i class="im im-arrow-up"
                            aria-hidden="true"></i></a>
                </div>
            </div>
        </div> <!-- end footer-bottom -->
    </footer> <!-- end footer -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php //include('footer.php'); ?>
    <script src="../../assets/js/script.js"></script>
    <script src="../../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../../assets/js/plugins.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>