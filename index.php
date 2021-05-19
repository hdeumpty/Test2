<?php

	// On prolonge la session
	session_start();
	// On teste si la variable de session existe et contient une valeur
	if(empty($_SESSION['username']))
	{
	  // Si inexistante ou nulle, on redirige vers le formulaire de login
	  header('Location: ./login.php');
	  exit;
	}

	// Appel le script d'initialisation
	require_once __DIR__ . '/init.php';

	// Définit quelle est la page courante
	$thisPage=array('index','HDC - Accueil');

	//Définition des requêtes qui seront utilisées
	$requeteNomClub            = "SELECT option_value FROM hdc_options WHERE option_key = 'nom_club'";
	$requeteNbreMembres        = "SELECT COUNT(1) AS nombre FROM hdc_members";
	$requeteCommandesEnAttente = "SELECT COUNT(1) AS nombre FROM wp_wc_order_stats WHERE status='wc-on-hold'";

	// Execution des requêtes
	$exec_requete   = mysqli_query($db,$requeteNomClub);
	$reponseNomClub = mysqli_fetch_array($exec_requete);

	$exec_requete       = mysqli_query($db,$requeteNbreMembres);
	$reponseNbreMembres = mysqli_fetch_array($exec_requete);

	$exec_requete              = mysqli_query($db,$requeteCommandesEnAttente);
	$reponseCommandesEnAttente = mysqli_fetch_array($exec_requete);

?>
<!doctype html>
<html lang="fr-FR">
	<?php require_once __DIR__ . '/head.php'; ?>
	<body>
		<div class="wrapper">
			<!-- Charge le menu latéral et la bare de navigation -->
			<?php
			require_once __DIR__ . '/header.php';
			require_once __DIR__ . '/sidebar.php';
			?>
			<div class="main-panel">
				<div class="content">
					<div class="page-inner">
						<!-- page-header -->
						<div class="page-header">
							<h4 class="page-title"><?php echo 'Bienvenue au ' . $reponseNomClub[0] ?></h4>
							<ul class="breadcrumbs">
								<li class="nav-home">
									<a href="#">
										<i class="fas fa-home"></i>
									</a>
								</li>
							</ul>
						</div>
						<!-- page-header -->
						<!-- cartes -->
						<div class="row">
							<div class="col-sm-6 col-md-3">
								<div class="card card-stats card-round">
									<div class="card-body ">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-primary bubble-shadow-small">
													<i class="fa fa-users"></i>
												</div>
											</div>
											<div class="col col-stats ml-3 ml-sm-0">
												<div class="numbers">
													<p class="card-category">Membres</p>
													<h4 class="card-title"><?php echo $reponseNbreMembres[0] ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-md-3">
								<div class="card card-stats card-round">
									<div class="card-body ">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-warning bubble-shadow-small">
													<i class="fa fa-warehouse"></i>
												</div>
											</div>
											<div class="col col-stats ml-3 ml-sm-0">
												<div class="numbers">
													<p class="card-category">Commandes en attente</p>
													<h4 class="card-title"><?php echo $reponseCommandesEnAttente[0] ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Charge le pied de page -->
				<?php require_once __DIR__ . '/footer.php'; ?>
			</div>
		</div>

		<!--   Core JS Files   -->
		<script src="assets/js/core/jquery.3.2.1.min.js"></script>
		<script src="assets/js/core/popper.min.js"></script>
		<script src="assets/js/core/bootstrap.min.js"></script>
		<!-- jQuery UI -->
		<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
		<script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
		<!-- jQuery Scrollbar -->
		<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
		<!-- Datatables -->
		<script src="assets/js/plugin/datatables/datatables.min.js"></script>
		<!-- Atlantis JS -->
		<script src="assets/js/atlantis.min.js"></script>
		<script >
			$(document).ready(function() {
				$('#basic-datatables').DataTable({
				});

				$('#multi-filter-select').DataTable( {
					"pageLength": 5,
					initComplete: function () {
						this.api().columns().every( function () {
							var column = this;
							var select = $('<select class="form-control"><option value=""></option></select>')
							.appendTo( $(column.footer()).empty() )
							.on( 'change', function () {
								var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
									);

								column
								.search( val ? '^'+val+'$' : '', true, false )
								.draw();
							} );

							column.data().unique().sort().each( function ( d, j ) {
								select.append( '<option value="'+d+'">'+d+'</option>' )
							} );
						} );
					}
				});
			});
		</script>
	</body>
</html>
