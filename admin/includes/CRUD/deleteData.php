<?php
require '../include.php';

// Получение id и названия таблицы
$id = $_POST['id'];  // предположим, что id передается POST-запросом
$tableName = $_POST['tableName'];  // предположим, что tableName передается POST-запросом

if ($id && $tableName) {
    $bean = R::load($tableName, $id);
    R::trash($bean);

    // Получение и разбор массива в поле img
    $imgArray = explode(',', $bean->img);
    
    // Удаление фотографий из папки img
    foreach ($imgArray as $imgFileName) {
        $imgPath = '../../img/' . $imgFileName;

        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }

    session_start();
    $_SESSION['success_add'] = 'Данные успешно удалены из БД';
    exit();
} else {
    session_start();
    $_SESSION['error_add'] = 'Не удалось удалить запись. Пожалуйста, проверьте переданные параметры.';
    exit();
}
?>