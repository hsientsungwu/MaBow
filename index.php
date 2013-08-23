<?php require $_SERVER['DOCUMENT_ROOT'] . '/config.php'; ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php'); ?>

<div class="cl-device-body">
    <div class="cl-page">
        <div class="cl-bar-title">
            <h1 class="cl-title">媽寶 - 線上影音</h1>
        </div>
        <div class="cl-content">
            <div class="cl-table">
                <?php
                $categories = $db->fetchRows("SELECT id, name FROM Category ORDER BY weight ASC");

                foreach ($categories as $category) {
                  echo '<div class="cl-table-cell"><a href="category.php?id=' . $category['id'] . '"><span class="label">' . $category['name'] . '</span</a></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>