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

	$thisPage=array('membres','HDC - Liste des membres');
	$chartDataset="";
	$chartLabels="";
	$chartColorCodes="";

	//Définition des requêtes qui seront utilisées
	$requeteListeMembre   = "SELECT * FROM hdc_members";
	$requeteNbreMembres   = "SELECT COUNT(1) FROM hdc_members";
	$requeteNbreInpayes   = "SELECT COUNT(1) FROM hdc_members WHERE member_contribution_payed=0";
	$requeteMembersXGroup = "SELECT * FROM (SELECT member_group, COUNT(member_id) AS 'theNumber' FROM hdc_members GROUP BY member_group) S ORDER BY theNumber desc";
	$requeteCouleursGraph = "SELECT * FROM hdc_chart_colors";

	// Execution des requêtes
	$exec_requete       = mysqli_query($db,$requeteNbreMembres);
	$reponseNbreMembres = mysqli_fetch_array($exec_requete);

	$exec_requete       = mysqli_query($db,$requeteNbreInpayes);
	$reponseNbreInpayes = mysqli_fetch_array($exec_requete);

	// Collecte des données pourle graphique
	$exec_requete       = mysqli_query($db,$requeteMembersXGroup);
	while($reponseMembersXGroup = mysqli_fetch_array($exec_requete))
	{
		if ($chartDataset=="")
		{
			$chartDataset=$reponseMembersXGroup["theNumber"];
		}
		else
		{
			$chartDataset=$chartDataset . ', ' . $reponseMembersXGroup["theNumber"];
		}
		if ($chartLabels=="")
		{
			$chartLabels="'" . $reponseMembersXGroup["member_group"];
		}
		else
		{
			$chartLabels=$chartLabels . "', '" . $reponseMembersXGroup["member_group"];
		}
	}
	$chartLabels=$chartLabels . "'";

	$exec_requete = mysqli_query($db,$requeteCouleursGraph);
	while($reponseCouleursGraph = mysqli_fetch_array($exec_requete))
	{
		if ($chartColorCodes=="") {
			$chartColorCodes="'" . $reponseCouleursGraph["color_code_html"];
		}
		else {
			$chartColorCodes=$chartColorCodes . "', '" . $reponseCouleursGraph["color_code_html"];
		}
	}
	$chartColorCodes=$chartColorCodes . "'";

?>

<!doctype html>
<html>
	<?php require_once __DIR__ . '/head.php'; ?>
	<body>
		<div class="wrapper">
			<!-- Charge le menu latéral et la bare d'entête -->
			<?php
			require_once __DIR__ . '/header.php';
			require_once __DIR__ . '/sidebar.php';
			?>
			<div class="main-panel">
				<div class="content">
					<div class="page-inner">
						<!-- page-header -->
						<div class="page-header">
							<h4 class="page-title">Liste des membres</h4>
							<ul class="breadcrumbs">
								<li class="nav-home">
									<a href="index.php">
										<i class="fas fa-home"></i>
									</a>
								</li>
								<li class="separator">
									<i class="fas fa-angle-right"></i>
								</li>
								<li class="nav-item">
									<a href="#">Membres</a>
								</li>
								<li class="separator">
									<i class="fas fa-angle-right"></i>
								</li>
								<li class="nav-item">
									<a href="#">Liste des membres</a>
								</li>
							</ul>
						</div>
						<!-- page-header -->
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

								<div class="card card-stats card-round">
									<div class="card-body ">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-warning bubble-shadow-small">
													<i class="fa fa-euro-sign"></i>
												</div>
											</div>
											<div class="col col-stats ml-3 ml-sm-0">
												<div class="numbers">
													<p class="card-category">Cotisation non payées</p>
													<h4 class="card-title"><?php echo $reponseNbreInpayes[0] ?></h4>
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
													<i class="fa fa-euro-sign"></i>
												</div>
											</div>
											<div class="col col-stats ml-3 ml-sm-0">
												<div class="numbers">
													<p class="card-category">Cotisation non payées</p>
													<h4 class="card-title"><?php echo $reponseNbreInpayes[0] ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Nombre de membres par groupe</div>
									</div>
									<div class="card-body">
										<div class="chart-container">
											<canvas id="doughnutChart" style="width: 50%; height: 50%"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
											<table id="basic-datatables" class="display table table-striped table-hover" >
												<thead>
													<tr>
														<th>Identifiant</th>
														<th>Nom</th>
														<th>Prénom</th>
														<th>Date de naissance</th>
														<th>Groupe</th>
														<th>Niveau plongee</th>
														<th>Niveau apnée</th>
														<th>Niveau nage</th>
														<th>Certificat</th>
														<th>Cotisation</th>
														<th>Role site</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$exec_requete = mysqli_query($db,$requeteListeMembre);
														while($reponseListeMembre = mysqli_fetch_array($exec_requete)) {
													?>
													<tr>
														<td class="dtr-control sorting_1" tabindex="0" ><?php echo $reponseListeMembre["member_id"]; ?></td>
														<td><?php echo $reponseListeMembre["member_name"];?></td>
														<td><?php echo $reponseListeMembre["member_firstname"];?></td>
														<td><?php echo $reponseListeMembre["member_birthdate"];?></td>
														<td><?php echo $reponseListeMembre["member_group"];?></td>
														<td><?php echo $reponseListeMembre["member_scuba_certification"];?></td>
														<td><?php echo $reponseListeMembre["member_freedive_certification"];?></td>
														<td><?php echo $reponseListeMembre["member_swim_certification"];?></td>
														<td>
															<?php
															if ($reponseListeMembre["member_medical_certification_ok"] == "0") { ?>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="checkbox" value="">
																		<span class="form-check-sign"></span>
																	</label>
																</div>
															<?php }
															else { ?>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="checkbox" value="" checked>
																		<span class="form-check-sign"></span>
																	</label>
																</div>
															<?php }
															?>
														</td>
														<td>
															<?php
															if ($reponseListeMembre["member_contribution_payed"] == "0") { ?>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="checkbox" value="">
																		<span class="form-check-sign"></span>
																	</label>
																</div>
															<?php }
															else { ?>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="checkbox" value="" checked>
																		<span class="form-check-sign"></span>
																	</label>
																</div>
															<?php }
															?>
														</td>
														<td><?php echo $reponseListeMembre["member_website_role"]; }?></td>
													</tr>
												</tbody>
											</table>
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
		<script src="assets/js/plugin/datatables/datatables.cca.js"></script>
		<!-- Chart JS -->
		<script src="assets/js/plugin/chart.js/chart.min.js"></script>
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
		<script>
			var myDoughnutChart = new Chart(doughnutChart, {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [<?php echo $chartDataset;?>],
						backgroundColor: [<?php echo $chartColorCodes;?>]
					}],

					labels: [<?php echo $chartLabels;?>]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend : {
						position: 'bottom'
					},
					layout: {
						padding: {
							left: 20,
							right: 20,
							top: 20,
							bottom: 20
						}
					}
				}
			});

		</script>
	</body>
</html>
