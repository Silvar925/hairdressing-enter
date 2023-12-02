<?php
require 'includes/include.php';
require 'includes/config_db.php';

$id = $_GET['id'];
$table_name = $_GET['tab_name'];

$record = R::load($table_name, $id);

$schema = getSchema();

$formData = array();

function generateForm($schema, $record, $table_name, $id)
{
    global $formData; // Обращение к глобальной переменной

    echo '<a href="/admin//?tab_name=' . $table_name . ' " class="comeBack"><img src="img/icons/left-arrow.png">Вернуться назад</a>';
    echo '<form action="editData.php?id=' . $id . '&tab_name=' . $table_name . '" method="post" enctype="multipart/form-data">';
    echo '<input type="hidden" name="id" value="' . $record->id . '">';

    foreach ($schema['fields'] as $fieldName => $field) {
        echo '<label>' . $field['name'] . '</label>';

        // Проверка, является ли поле textarea
        if ($field['element'] === 'textarea') {
            print_r('<textarea name="' . $fieldName . '" id="summernote_' . $fieldName . '__' . $table_name . '"');
            if ($field['required']) {
                echo ' required';
            }
            print_r('>' . htmlspecialchars($record->$fieldName) . '</textarea>');
            print_r('<input id="getIDTextarea__' . $table_name . '" type="hidden" value="summernote_' . $fieldName . '__' . $table_name . '">');

            // Сохранение значения textarea в массиве
            $formData[$fieldName] = ($_POST[$fieldName]) ?? $record->$fieldName;
        } else if ($field['element'] === 'input' && $field['type'] === 'file') {
            $imageArray = explode(",", $record['img']);
            if (is_array($imageArray)) {
                $count = 1;
                foreach ($imageArray as $image) {
                    $file_name = $image;
                    $file_info = pathinfo($file_name);
                    $file_extension = $file_info['extension'];

                    if ($file_extension == 'webp') {
                        echo '<img class="img_' . $count . '" src="img/' . $image . '" alt="Current Image" style="max-width: 100px;">';
                        echo '<div class="delImg" oldName = "' . $image . '" classImgToDel = "img_' . $count . '">Удалить</div>';
                    } else {
                        echo '<a class="img_' . $count . '" target="_blank" href="img/' . $image . '" alt="Current Image" style="max-width: 100px;">' . $image . '</a>';
                        echo '<div class="delImg" oldName = "' . $image . '" classImgToDel = "img_' . $count . '">Удалить</div>';
                    }
                    
                    $count++;
                }
                $count = 1;
            }

            echo '<label>Добавить еще картинки</label>';
            echo '<input type="file" name="fileInput[]" multiple> ';

            echo '<input id="img_to_del" name="img_to_del" type="hidden"> '; ?>

                <script>
                    $(document).ready(function () {
                        $(".delImg").click(function () {
                            let data = $(this).attr('oldName');
                            let classToDel = $(this).attr('classImgToDel');

                            let strData = $("#img_to_del").val();
                            strData += data + ',';

                            $("#img_to_del").val(strData);

                            $(this).remove();
                            $(`.${classToDel}`).remove();
                        })
                    });
                </script>


                <?php

                $delIMG = $_POST['img_to_del'];

                $imgArray = explode(',', $delIMG);
                function removeEmpty($value)
                {
                    return !empty($value) || $value === 0 || $value === "0";
                }

                $filteredArray = array_filter($imgArray, 'removeEmpty');

                //удаление фотографий из img
                foreach ($filteredArray as $imgFileName) {
                    $imgPath = 'img/' . $imgFileName;

                    if (file_exists($imgPath)) {
                        unlink($imgPath);
                    }
                }

                $imageArrayOld = explode(",", $record['img']);

                $resultArray = array_diff($imageArrayOld, $filteredArray);
                $resultArray = array_values($resultArray);

                $files = $_FILES;

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && $files['fileInput']['name'][0] != '') {
                    $fileInputs = $_FILES['fileInput'];
                    $maxFileSize = 3 * 1024 * 1024; // 2 MB in bytes
    
                    foreach ($fileInputs['error'] as $key => $error) {
                        if ($error === UPLOAD_ERR_OK) {
                            $originalFilename = $fileInputs['name'][$key];
                            $fileSize = $fileInputs['size'][$key];

                            if ($fileSize > $maxFileSize) {
                                session_start();
                                $_SESSION['error_add'] = 'Ошибка: Размер файла ' . $originalFilename . ' превышает 2 МБ.<br>';
                                continue; // Skip processing this file
                            }

                            $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
                            $uniqueFilename = uniqid('file_') . '.' . $extension;

                            $uploadPath = 'img/' . $uniqueFilename;

                            // Check if the file is an image
                            $isImage = getimagesize($fileInputs['tmp_name'][$key]);

                            if ($isImage !== false) {
                                // If it's an image, convert to WebP
                                $image = imagecreatefromstring(file_get_contents($fileInputs['tmp_name'][$key]));
                                if ($image !== false) {
                                    $uniqueFilenameWEBP = uniqid('file_') . '.webp';
                                    $webpPath = 'img/' . $uniqueFilenameWEBP;
                                    imagewebp($image, $webpPath, 80); // 80 is the quality (0-100)
                                    imagedestroy($image);

                                    // Update the database entry with the WebP path
                                    $resultArray[] = $uniqueFilenameWEBP;
                                }
                            } else {
                                // If it's not an image, keep the original extension
                                $uploadPath = 'img/' . $uniqueFilename;
                                move_uploaded_file($fileInputs['tmp_name'][$key], $uploadPath);
                                $resultArray[] = $uniqueFilename;
                            }
                        } else {
                            echo 'Ошибка при загрузке файла ' . $fileInputs['name'][$key] . '.<br>';
                        }
                    }
                }
        } else if ($field['data']) { ?>

            <?php if ($field['data']) {
                echo '<' . $field['element'] . ' id="' . $fieldName . '__' . $table_name . '" type="' . $field['type'] . '" name="' . $fieldName . '"';

                if ($field['type'] === 'file') {
                    echo ' value="' . basename($record->$fieldName) . '"';
                } else {
                    echo ' value="' . htmlspecialchars($record->$fieldName) . '"';
                }
                if ($field['required']) {
                    echo ' required';
                }
                echo '>'; ?>

                        <div class="formData_choseBlocks" id="elements_<?php echo $fieldName; ?>__<?php print_r($table_name); ?>">
                    <?php foreach ($field['data'] as $elem) { ?>
                                <div class="formData_choseBlocks__element" <?php if ($field['selectOne'] == 'true') {
                                    echo 'data_selectOne=true';
                                } else {
                                    echo 'data_selectOne=false';
                                } ?> data_blockUpdate="<?php echo $fieldName; ?>__<?php print_r($table_name); ?>">
                            <?php print_r($elem); ?>
                                </div>
                    <?php } ?>
                        </div>

                <?php $formData[$fieldName] = ($_POST[$fieldName]) ?? $record->$fieldName; ?>
            <?php } ?>
        <?php } else {
            echo '<' . $field['element'] . ' type="' . $field['type'] . '" name="' . $fieldName . '"';
            if ($field['type'] === 'file') {
                echo ' value="' . basename($record->$fieldName) . '"';
            } else {
                echo ' value="' . htmlspecialchars($record->$fieldName) . '"';
            }
            if ($field['required']) {
                echo ' required';
            }
            echo '>';

            $formData[$fieldName] = ($_POST[$fieldName]) ?? $record->$fieldName;
        }

        echo '';
    } ?>


    <script>
        function selectElements(id) {
            let dataBlock = $(`#${id}`).val();
            let massSelectOne = dataBlock.split(', ');

            let clickBlocks = $(`.formData_choseBlocks__element[data_blockUpdate="${id}"]`);

            massSelectOne.forEach(element => {
                for (let j = 0; j < clickBlocks.length; j++) {
                    let buttonInnerText = clickBlocks[j].innerHTML.trim(); // Убедитесь, что убраны пробелы вокруг текста кнопки
                    if (element === buttonInnerText) {
                        console.log(element);
                        // Добавьте/удалите активный класс для соответствующей кнопки
                        clickBlocks[j].classList.add('activeFormDataElement');
                    }
                }
            });

            // Используем уникальный класс для каждого блока, чтобы отделить их друг от друга
            $(`.formData_choseBlocks__element[data_blockUpdate="${id}"]`).click(function () {
                let block = $(this).attr('data_blockUpdate');

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
        foreach ($schema['fields'] as $fieldName => $fieldData) {
            if ($fieldData['data']) { ?>
                selectElements('<?php echo $fieldName . '__' . $table_name; ?>');
            <?php }
        } ?>
    </script>

    <?php
    $formData['img'] = implode(",", $resultArray);

    echo '<input type="submit" class="updateDataForm" value="Обновить запись">';
    echo '</form>'; ?>


    <script>
        $(document).ready(function () {
            let getIDTextarea = $(`#getIDTextarea__<?php echo $table_name; ?>`).val();

            if (getIDTextarea) {
                $(`#${getIDTextarea}`).summernote();
            }
        });
    </script>

    <?php
    $bean = R::load($table_name, $id);

    foreach ($formData as $fieldName => $fieldValue) {
        $bean->{$fieldName} = $fieldValue;
    }

    $bean = R::store($bean);
    session_start();
    $_SESSION['success_add'] = 'Данные успешно сохранены в БД';
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Административная панель</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <!-- include libraries(jQuery, bootstrap) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        $user = R::load('user', $_SESSION['user_id']);
        if ($user->id) { ?>
            <div class="admin">
                <?php require_once 'includes/show/showMenu.php'; ?>
                <div class="admin_info">
                    <?php
                    generateForm($schema[$table_name], $record, $table_name, $id);

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['success_add'])) {
                        $redirectUrl = '/admin/?tab_name=' . $table_name;

                        echo '<script>window.location.href = "' . $redirectUrl . '";</script>';
                        exit;
                    }
                    ?>
                </div>
            </div>
        <?php }
    } else {
        require_once 'includes/show/showAutentification.php';
    }
    ?>

    <script src="js/main.js"></script>
</body>

</html>