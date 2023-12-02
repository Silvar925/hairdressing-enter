<?php
require '../include.php';
require '../config_db.php';
require 'universalModel.php';

$schema = getSchema();
$tableName = formatstr($_GET['add']);

$data = $_POST;
$files = $_FILES;
$uploadedFiles = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $files['fileInput']['name'][0] != '') {
    $fileInputs = $_FILES['fileInput'];
    $maxFileSize = 16 * 1024 * 1024; // 2 MB in bytes

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

            $uploadPath = '../../img/' . $uniqueFilename;

            // Check if the file is an image
            $isImage = getimagesize($fileInputs['tmp_name'][$key]);

            if ($isImage !== false) {
                // If it's an image, convert to WebP
                $image = imagecreatefromstring(file_get_contents($fileInputs['tmp_name'][$key]));
                if ($image !== false) {
                    $uniqueFilenameWEBP = uniqid('file_') . '.webp';
                    $webpPath = '../../img/' . $uniqueFilenameWEBP;
                    imagewebp($image, $webpPath, 80); // 80 is the quality (0-100)
                    imagedestroy($image);

                    // Update the database entry with the WebP path
                    $uploadedFiles[] = $uniqueFilenameWEBP;
                }
            } else {
                // If it's not an image, keep the original extension
                $uploadPath = '../../img/' . $uniqueFilename;
                move_uploaded_file($fileInputs['tmp_name'][$key], $uploadPath);
                $uploadedFiles[] = $uniqueFilename;
            }
        } else {
            echo 'Ошибка при загрузке файла ' . $fileInputs['name'][$key] . '.<br>';
        }
    }
}

$addData = [];

$universalModel = new UniversalModel($schema[$tableName]['fields']);

// Используем цикл для установки значений полей
foreach ($data as $fieldName => $fieldValue) {
    $universalModel->setField($fieldName, $fieldValue);
}

// Устанавливаем значение для поля 'img'
if ($uploadedFiles != []) {
    $universalModel->setField('img', implode(',', $uploadedFiles));
}

$dataArray = $universalModel->getData();

if ($dataArray['img'] && $files['fileInput']['name'][0] == '') {
    session_start();
    $_SESSION['error_add'] = 'Ошибка: Размер файла ' . $originalFilename . ' превышает 1 МБ.<br>';
    $_SESSION['error_add__changeData'] = $dataArray;

    header('Location: /admin/?tab_name=' . $tableName);
    exit();
}

// Выполняем валидацию

try {
    $universalModel->validate();

    $bean = R::dispense($tableName);

    foreach ($dataArray as $fieldName => $fieldValue) {
        $bean->{$fieldName} = $universalModel->getField($fieldName);
    }

    try {
        $bean = R::store($bean);

        session_start();
        $_SESSION['success_add'] = 'Данные успешно сохранены в БД';
        header('Location: /admin/?tab_name=' . $tableName);
        exit();
    } catch (Exception $e) {
        session_start();
        $_SESSION['error_add'] = "Ошибка при сохранении данных в БД: " . $e->getMessage();
        header('Location: /admin/?tab_name=' . $tableName);
        exit();
    }
} catch (\InvalidArgumentException $e) {
    session_start();
    $_SESSION['error_add'] = "Ошибка при сохранении данных в БД: " . $e->getMessage();
    header('Location: /admin/?tab_name=' . $tableName);
    exit();
}
