<?php

require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$currentUser = $fb->getUser();

if ($_GET) {
	if ($_GET['code']) {
		// add or update user information here and redirect user to account page.

		if ($currentUser) {
			$isExisted = $db->fetchCell("SELECT id FROM Users WHERE facebook_id = ?", array($currentUser));

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
		}
	} else {
		$errors = "Something wrong with authenticating your Facebook Account, please click the connect button and try it again.";
	}
}

if ($currentUser) {
	// add user session here
	header("Location: /account/");
} else {
	$params = array(
    	'scope' => array(),
    	'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . '/account/'
  	);

	$loginUrl = $fb->getLoginUrl($params);
}

$title = 'MaBow - Sign In with Facebook';

include $_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php';
?>

<div class="row">
	<div class="large-12 large-centered columns">
		<div class="row">
			<div class="large-3 large-centered columns">
				<a href="<?php echo $loginUrl; ?>"><img src="/img/fb_connect.png" /></a>
			</div>
		</div>
	</div>
</div>

<?php
$scripts = array();

include $_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php';
?>