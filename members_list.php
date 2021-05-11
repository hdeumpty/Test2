<?php

	/* load init file */
	require_once __DIR__ . '/init.php';

	/* Define this page info */
	$current_page = 'membres';
	$page_title = 'Liste des membres';

	/* Get the list of members */
	$sql="SELECT * FROM hdc_members";
	if (($membersList = $mysqli->query($sql))===false ) {
		printf("members_list.php - Requête invalide: %s\nWhole query: %s\n", $mysqli->error, $sql);
		exit();
	}

	/* Get the total number of members */
	$sql="SELECT COUNT(1) AS 'theNumber' FROM hdc_members";
	if (($result1 = $mysqli->query($sql))===false ) {
		printf("members_list.php - Requête invalide: %s\nWhole query: %s\n", $mysqli->error, $sql);
		exit();
	}
	else {
		$row = $result1->fetch_array();
		$nbMembres = $row["theNumber"];
	}

	/* Get the number of unpaid contributions */
	$sql="SELECT COUNT(1) AS 'theNumber' FROM hdc_members WHERE member_contribution_payed=0";
	if (($result2 = $mysqli->query($sql))===false ) {
		printf("members_list.php - Requête invalide: %s\nWhole query: %s\n", $mysqli->error, $sql);
		exit();
	}
	else {
		$row = $result2->fetch_array();
		$unpaidContribution = $row["theNumber"];
	}

	/* Get the number of member by group */
	/* and create strings to be used as data array in js */
	$chartDataset="";
	$chartLabels="";
	$sql="SELECT * FROM (SELECT member_group, COUNT(member_id) AS 'theNumber' FROM hdc_members GROUP BY member_group) S ORDER BY theNumber desc";
	if (($membersXgroups = $mysqli->query($sql))===false ) {
	  printf("members_list.php - Requête invalide: %s\nWhole query: %s\n", $mysqli->error, $sql);
	  exit();
	}
	else {
	  while ($row = $membersXgroups->fetch_array()) {
	    if ($chartDataset=="") {
	      $chartDataset=$row["theNumber"];
	    }
	    else {
	      $chartDataset=$chartDataset . ', ' . $row["theNumber"];
	    }
	    if ($chartLabels=="") {
	      $chartLabels="'" . $row["member_group"];
	    }
	    else {
	      $chartLabels=$chartLabels . "', '" . $row["member_group"];
	    }
	  }
	  $chartLabels=$chartLabels . "'";
	}
	/* Get configured colors for charts */
	$chartColorCodes="";
	$sql="SELECT * FROM hdc_chart_colors";
	if (($membersXgroups = $mysqli->query($sql))===false ) {
	  printf("members_list.php - Requête invalide: %s\nWhole query: %s\n", $mysqli->error, $sql);
	  exit();
	}
	else {
	  while ($row = $membersXgroups->fetch_array()) {
			if ($chartColorCodes=="") {
	      $chartColorCodes="'" . $row["color_code_html"];
	    }
	    else {
	      $chartColorCodes=$chartColorCodes . "', '" . $row["color_code_html"];
	    }
	  }
	  $chartColorCodes=$chartColorCodes . "'";
	}
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
							<h4 class="page-title"><?php echo $page_title ?></h4>
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
									<a href="#"><?php echo $page_title ?></a>
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
													<h4 class="card-title"><?php echo $nbMembres ?></h4>
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
													<h4 class="card-title"><?php echo $unpaidContribution ?></h4>
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
													<h4 class="card-title"><?php echo $unpaidContribution ?></h4>
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
													<?php while ($row = $membersList->fetch_array()) { ?>
													<tr>
														<td class="dtr-control sorting_1" tabindex="0" ><?php echo $row["member_id"]; ?></td>
														<td><?php echo $row["member_name"];?></td>
														<td><?php echo $row["member_firstname"];?></td>
														<td><?php echo $row["member_birthdate"];?></td>
														<td><?php echo $row["member_group"];?></td>
														<td><?php echo $row["member_scuba_certification"];?></td>
														<td><?php echo $row["member_freedive_certification"];?></td>
														<td><?php echo $row["member_swim_certification"];?></td>
														<td>
															<?php
															if ($row["member_medical_certification_ok"] == "0") { ?>
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
															if ($row["member_contribution_payed"] == "0") { ?>
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
														<td><?php echo $row["member_website_role"]; }?></td>
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
