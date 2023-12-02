<?php
    require_once 'includes/config_db.php';
    $schema = getSchema();
?>

<div class="admin_menu">
    <div class="admin_menu__logo">
        <a href="/" target="_blank">ЦМИТ</a>
    </div>
    <div class="admin_menu__items">
        <ul>
            <?php foreach ($schema as $sectionName => $sectionData) { ?>
                <a href="/admin/?tab_name=<?php echo $sectionName; ?>" data_info="<?php print_r($sectionName); ?>">
                    <?php print_r($sectionData['menuName']); ?>
                </a>
            <?php } ?>
        </ul>
        <ul>
            <a href="includes/user/logout.php">Выход</a>
        </ul>
    </div>
</div>