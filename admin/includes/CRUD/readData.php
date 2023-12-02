<?php
$tableName = $_GET['tab_name'];
$records = R::findAll($tableName);
?>
<div class="admin_info__item___content">
    <?php foreach ($records as $record) { ?>
        <div class="admin_info__item___content____element">
            <div class="admin_info__item___content____element_____title">
                <img src="img/<?php echo explode(",", $record['img'])[0]; ?>" alt="">
                <?php echo $record['title'] ?>
            </div>
            <div class="admin_info__item___content____element_____btnHover">
                <a href="editData.php?id=<?php echo $record['id'] ?>&tab_name=<?php echo $tableName ?>" class="admin_info__item___content____element_____btnHover______title <?php echo $tableName ?>Edit" idtoedit="<?php echo $record['id'] ?>">Изменить</a>
                <div class="admin_info__item___content____element_____btnHover______title <?php echo $tableName ?>Delete delete" idtodel="<?php echo $record['id'] ?>">Удалить</div>
            </div>
        </div>
    <?php } ?>
</div>