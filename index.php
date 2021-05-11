<?php

	/* Charge le fichier de configuration */
	require_once __DIR__ . '/init.php';

	/* Définit quelle est la page courante */
	$current_page = 'home';
	$page_title = 'Accueil';

	/* Requête nombre de membres */
	$sql="SELECT COUNT(1) AS nombre FROM hdc_members";
	if (($result1 = $mysqli->query($sql))===false ) {
	  printf("index.php - Requête invalide: %s\nWhole query: %s\n", $mysqli->error, $sql);
	  exit();
	}
	else {
	  $row = $result1->fetch_array();
	  $nbMembres = $row["nombre"];
	}

	/* Requête nombre de commandes en attente */
	$sql="SELECT COUNT(1) AS nombre FROM wp_wc_order_stats WHERE status='wc-on-hold'";
	if (($result2 = $mysqli->query($sql))===false ) {
	  printf("index.php - Requête invalide: %s\nWhole query: %s\n", $mysqli->error, $sql);
	  exit();
	}
	else {
	  $row = $result2->fetch_array();
	  $nbCdesAttente = $row["nombre"];
	}

?>
<!doctype html>
<html>
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
							<h4 class="page-title"><?php echo 'Bienvenue au ' . NOM_CLUB ?></h4>
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
													<h4 class="card-title"><?php echo $nbMembres ?></h4>
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
													<h4 class="card-title"><?php echo $nbCdesAttente ?></h4>
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
