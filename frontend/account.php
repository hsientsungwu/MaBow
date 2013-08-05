<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$currentUser = $fb->getUser();

if ($currentUser) {
	$account = $db->fetchRow("SELECT id, name FROM Users WHERE facebook_id = ?", array($currentUser));

	$params = array(
		'next' => 'http://' . $_SERVER['HTTP_HOST']
	);

	$logoutUrl = $fb->getLogoutUrl($params);
} else {
	header("Location: /account/login/");
}

$title = 'MaBow - Account';

include $_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php';
?>

<div class="row">
	<div class="large-12 large-centered columns">
		<div class="row">
			<div class="large-5 large-centered columns">
				<h4>Welcome to MaBow - <?php echo $account['name']; ?></h4>
				<a href="<?php echo $logoutUrl; ?>">Logout</a>
			</div>
		</div>
	</div>
</div>

<?php
$scripts = array(
  '<script src="/js/admin.js" ></script>',
);

include $_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php';
?>
