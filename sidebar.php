<?php ?>
<!-- Sidebar -->
		<div class="sidebar sidebar-style-2">

			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<!-- User start -->
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="images/HD_LOGO_48.png" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									<?php echo $_SESSION['username'];?>
									<span class="user-level"><?php echo $_SESSION['user_role'];?></span>
									<span class="caret"></span>
								</span>
							</a>
							<div class="clearfix"></div>

							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									<li>
										<a href="#profile">
											<span class="link-collapse">My Profile</span>
										</a>
									</li>
									<li>
										<a href="#edit">
											<span class="link-collapse">Edit Profile</span>
										</a>
									</li>
									<li>
										<a href="#settings">
											<span class="link-collapse">Settings</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- User end -->

					<!-- Menu -->
					<ul class="nav nav-primary">
						<?php
						if ($thisPage[0] == "index") { ?>
							<li class="nav-item active">
						<?php
						}
						else { ?>
							<li class="nav-item">
						<?php
						}
						?>
							<a href="index.php">
								<i class="fas fa-home"></i>
								<p>Accueil</p>
							</a>
						</li>
						<?php
						if ($thisPage[0] == "membres") { ?>
							<li class="nav-item active submenu">
								<a data-toggle="collapse" href="#base">
									<i class="fas fa-users"></i>
									<p>Membres</p>
									<span class="caret"></span>
								</a>
								<div class="collapse show" id="base">
						<?php
						}
						else { ?>
							<li class="nav-item">
								<a data-toggle="collapse" href="#base">
									<i class="fas fa-users"></i>
									<p>Membres</p>
									<span class="caret"></span>
								</a>
								<div class="collapse" id="base">
						<?php
						}
						?>
								<ul class="nav nav-collapse">
									<li>
										<a href="members_list.php">
											<i class="fas fa-address-book"></i>
											<span>Liste des membres</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="fas fa-user-plus"></i>
											<span>Nouveau membre</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="fas fa-file-export"></i>
											<span>Exporter vers WP</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#sidebarLayouts">
								<i class="fas fa-cog"></i>
								<p>Paramètres</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="sidebarLayouts">
								<ul class="nav nav-collapse">
									<li>
										<a href="../sidebar-style-1.html">
											<i class="fas fa-user-friends"></i>
											<span>Groupes</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
