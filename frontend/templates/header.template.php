<html>
	<head>
		<title><?php echo ($title ? $title : 'MaBow - 媽寶'); ?></title>
		<!-- basic stylesheets and scripts -->
		<meta name="viewport" content="width=device-width" />
		<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="/css/normalize.css" />
		<link rel="stylesheet" href="/css/foundation.css" />
		<link rel="stylesheet" href="/css/frontend.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>
		<script src="/js/foundation.min.js"></script>
	</head>
	<body>
		<nav class="top-bar">
		  	<ul class="title-area">
		    	<li class="name"><h1><a href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>">媽寶 MaBow</a></h1></li>
		  	</ul>
		  	<section class="top-bar-section">
		  		<ul class="right">
		  			<?php
		  				$currentUser = $fb->getUser();

		  				if ($currentUser) {
							$isExisted = $db->fetchCell("SELECT id FROM Users WHERE facebook_id = ?", array($currentUser));
							$params = array(
								'next' => 'http://' . $_SERVER['HTTP_HOST']
							);

							$logoutUrl = $fb->getLogoutUrl($params);

							if (!$isExisted) {
								$user_profile = $fb->api("/{$currentUser}");

								print_r($user_profile);

								$newUser = array(
									'name' => $user_profile['name'],
									'fname' => $user_profile['first_name'],
									'lname' => $user_profile['last_name'],
									'facebook_id' => $user_profile['id'],
									'registered_time' => date("Y-m-d H:i:s"),
									'lastlogin_time' => date("Y-m-d H:i:s"),
								);

								$affected = $db->insert($newUser, 'Users');
							} else {
								$updatedUser = array(
									'lastlogin_time' => date("Y-m-d H:i:s"),
								);

								$affected = $db->update($updatedUser, 'Users', 'id = ?', array($isExisted));
							}
							?>
								<li class="has-dropdown not-click"><a href="/account/">Account</a>
					              	<ul class="dropdown">
					                	<li class=""><a href="/account/favorite/">Favorites</a></li>
					                	<li class=""><a href="<?php echo $logoutUrl; ?>">Logout</a></li>
					              	</ul>
			            		</li>
							<?php
						} else {
							?>
							<li class=""><a href="/account/login/">Facebook Sign In</a></li>
							<?php
						}
		  			?>
				  			
		  		</ul>
		  	</section>
		</nav>
		