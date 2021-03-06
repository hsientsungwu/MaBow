<?php require $_SERVER['DOCUMENT_ROOT'] . '/config.php'; ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php'); ?>

<div class="cl-bar-title">
    <h1 class="cl-title">媽寶 - 線上影音</h1>
</div>
<div class="cl-content">
    <div class="cl-article">
          <p class="page-logo"><img src="/img/logoist_logo_nobkg.png" /></p>
    </div>
    <div class="cl-table">
        <?php
        $categories = $db->fetchRows("SELECT id, name FROM Category ORDER BY weight ASC");

        foreach ($categories as $category) {
          echo '<div class="cl-table-cell">
                <a href="category.php?id=' . $category['id'] . '">
                    <span class="label">' . $category['name'] . '</span><i class="icon icon-ios-arrow-right"></i></a></div>';
        }
        ?>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>