<?php require 'includes/include.php'; ?>
<?php require 'includes/config_db.php'; ?>

<?php
    $schema = getSchema();
    
    foreach($schema as $tables => $table){
        $tableName = $tables;
        break;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = formatstr($_POST['login']);
        $password = formatstr($_POST['password']);
    
        $user = R::findOne('user', 'login = ?', [$login]);
    
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
        } else {
            $_SESSION['error'] = "* Неправильный логин или пароль";
        }
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

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
    if (isset($_SESSION['user_id'])) { 
        $user = R::load('user', $_SESSION['user_id']);
        if ($user->id) { ?>
            <div class="admin">
                <?php require_once 'includes/show/showMenu.php'; ?>
                <?php require_once 'includes/show/showInfo.php'; ?>
            </div>
        <?php }
    } else { 
        ?>
        <div class="autentificationForm">
            <div class="autentificationForm_title">Вход в админ панель</div>
            <div class="autentificationForm_block">
                <form action="/admin/?tab_name=<?php echo $tableName; ?>" method="post">
                    <label for="login">Логин:</label>
                    <input type="text" name="login" placeholder="Введите логин" required>
        
                    <label for="password">Пароль:</label>
                    <input type="password" name="password" placeholder="Введите пароль" required>
        
                    <button type="submit">Войти</button>
        
                    <?php
                    if ($_SESSION['error']) { ?>
                        <div class="autentificationForm_block__message">
                            <?php
                            print_r($_SESSION['error']);
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    <?php } ?>

    <script src="js/main.js"></script>
</body>

</html>