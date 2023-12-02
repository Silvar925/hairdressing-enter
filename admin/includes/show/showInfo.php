<?php
require_once 'includes/config_db.php';
$schema = getSchema();

$tableName = formatstr($_GET['tab_name']);
?>

<div class="admin_info">
    <?php foreach ($schema as $sectionName => $sectionData) { 
        if ($sectionName == $tableName){?>
        <div class="admin_info__elem" data_info="<?php print_r($sectionName); ?>">
            <div class="admin_info__elem___btns">
                <div class="admin_info__elem___allData show_all_<?php print_r($sectionName); ?> openAll activeTabName">
                    <?php print_r("Все " . $sectionData['menuName']); ?>
                </div>
                <div class="admin_info__elem___allData add_new_<?php print_r($sectionName); ?> openForm">
                    <?php print_r("Добавить  " . $sectionData['menuName']); ?>
                </div>
            </div>

            <div class="admin_info__elem___formData">
                <form action="includes/CRUD/createData.php?add=<?php print_r($sectionName); ?>" method="post" enctype="multipart/form-data">
                    <?php foreach ($schema[$tableName]['fields'] as $fieldName => $fieldData) { ?>
                        <?php if ($fieldData['element'] == 'input') {?>
                            <?php if ($fieldData['type'] == 'file') {?>
                            <label><?php print_r($fieldData['name']); ?></label>
                            <<?php print_r($fieldData['element']); ?> 
                                type="<?php print_r($fieldData['type']); ?>" 
                                name="fileInput[]" 
                                multiple 
                                placeholder="<?php print_r($fieldData['name']); ?>" 
                                <?php if ($fieldData['required'] == true) {print_r('required = true');} ?>">
                            <?php } else if ($fieldData['type'] == 'date') {?>
                            <label><?php print_r($fieldData['name']); ?></label>
                            <<?php print_r($fieldData['element']); ?> 
                                type="<?php print_r($fieldData['type']); ?>" 
                                name="<?php print_r($fieldName); ?>" 
                                id="date_<?php print_r($fieldName); ?>__<?php print_r($tableName); ?>"
                                value="<?php print_r($_SESSION['error_add__changeData'][$fieldName]); ?>" 
                                multiple 
                                placeholder="<?php print_r($fieldData['name']); ?>" 
                                <?php if ($fieldData['required'] == true) {print_r('required = true');} ?>">
                            <?php } else {?>
                            <label><?php print_r($fieldData['name']); ?></label>
                            <<?php print_r($fieldData['element']); ?> 
                                type="<?php print_r($fieldData['type']); ?>" 
                                name="<?php print_r($fieldName); ?>" 
                                id="<?php print_r($fieldName); ?>__<?php print_r($tableName); ?>"
                                value="<?php print_r($_SESSION['error_add__changeData'][$fieldName]); ?>" 
                                multiple 
                                placeholder="<?php print_r($fieldData['name']); ?>" 
                                <?php if ($fieldData['required'] == true) {print_r('required = true');} ?>">
                            <?php }
                        } ?>    

                        <?php if ($fieldData['element'] == 'textarea') {?>
                            <label><?php print_r($fieldData['name']); ?></label>
                            <<?php print_r($fieldData['element']); ?> 
                            name="<?php print_r($fieldName); ?>" 
                            id="summernote_<?php print_r($fieldName . '_' . $index); ?>__<?php print_r($tableName); ?>"
                            placeholder="<?php print_r($fieldData['name']); ?>" 
                            <?php if ($fieldData['required'] == true) {print_r('required');} ?>><?php print_r($_SESSION['error_add__changeData'][$fieldName]); ?></<?php print_r($fieldData['element']); ?>>
                            <input id="getIDTextarea__<?php print_r($tableName); ?>" type="hidden" value="summernote_<?php print_r($fieldName . '_' . $index); ?>__<?php print_r($tableName); ?>">
                        <?php } ?>  
                        
                        <?php if ($fieldData['data']) { ?>
                            <div class="formData_choseBlocks" id="elements_<?php echo $fieldName; ?>__<?php print_r($tableName); ?>">
                            <?php foreach($fieldData['data'] as $elem){ ?>
                                <div 
                                    class="formData_choseBlocks__element" 
                                    <?php if ($fieldData['selectOne'] == 'true'){echo 'data_selectOne=true';} else {echo 'data_selectOne=false';} ?>
                                    data_blockUpdate="<?php echo $fieldName; ?>__<?php print_r($tableName); ?>"><?php print_r($elem); ?></div>
                            <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <script>
                        function selectElements(id) {
                            let massSelectOne = [];

                            // Используем уникальный класс для каждого блока, чтобы отделить их друг от друга
                            $(`.formData_choseBlocks__element[data_blockUpdate="${id}"]`).click(function () {
                                let block = $(this).attr('data_blockUpdate');
                                console.log(block);

                                if (id == block) {
                                    let selectOne = $(this).attr('data_selectOne');

                                    if (selectOne == 'true') {
                                        let data = $(this).html();

                                        // Снимаем активный класс только с элементов текущего блока
                                        $(`.formData_choseBlocks__element[data_blockUpdate="${id}"]`).removeClass('activeFormDataElement');

                                        // Обновляем значение элемента с идентификатором "block"
                                        $(`#${block}`).val(data);

                                        $(this).addClass('activeFormDataElement');
                                    } else if (selectOne == 'false') {
                                        // Переключаем активный класс только для элементов текущего блока
                                        $(this).toggleClass('activeFormDataElement');

                                        let data = $(this).html();
                                        var blockIndex = massSelectOne.indexOf(data);

                                        if (blockIndex === -1) {
                                            // Если элемент не найден в массиве, добавляем его
                                            massSelectOne.push(data);
                                        } else {
                                            // Если элемент уже в массиве, удаляем его
                                            massSelectOne.splice(blockIndex, 1);
                                        }

                                        // Обновление значения элемента с идентификатором "block" с уникальными значениями
                                        var uniqueArray = [...new Set(massSelectOne)];
                                        var myString = uniqueArray.join(', ');
                                        $(`#${block}`).val(myString);
                                    }
                                }
                            });
                        }

                        // Вызываем функцию для каждого уникального блока
                        <?php
                            foreach ($schema[$tableName]['fields'] as $fieldName => $fieldData) {
                                if ($fieldData['data']) { ?>
                                    selectElements('<?php echo $fieldName . '__' . $tableName; ?>');
                                <?php }
                            }
                        ?>
                    </script>

                    <button type="submit">Добавить в БД</button>

                    <?php
                    if ($_SESSION['error_add']) { ?>
                        <div class="autentificationForm_block__message">
                            <?php
                            print_r($_SESSION['error_add']);
                            unset($_SESSION['error_add']);
                            ?>
                        </div>
                    <?php } ?>
                    <?php
                    if ($_SESSION['success_add']) { ?>
                        <div class="autentificationForm_block__message___success">
                            <?php
                            print_r($_SESSION['success_add']);
                            unset($_SESSION['success_add']);
                            ?>
                        </div>
                    <?php } ?>
                    <?php
                        unset($_SESSION['error_add__changeData']);
                    ?>
                    
                </form>
                <script>
                    $(document).ready(function() {
                        let getIDTextarea = $(`#getIDTextarea__<?php echo $tableName; ?>`).val();
                        
                        if (getIDTextarea) {
                            $(`#${getIDTextarea}`).summernote();
                        }

                        let getIDDate = `#date_date__<?php print_r($tableName); ?>`;

                        var currentDate = new Date();
                        var formattedDate = currentDate.toISOString().split('T')[0];
                        $(getIDDate).val(formattedDate);
                    });
                </script>
            </div>

            <div class="admin_info__elem___allDataFromDB">
                <?php require 'includes/CRUD/readData.php'; ?>
            </div>
        </div>
    <?php } } ?>
</div>