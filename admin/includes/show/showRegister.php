<h2>Регистрация</h2>
<form action="includes/user/register.php" method="post">
    <label for="fio">ФИО</label>
    <input type="text" name="fio" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="login">Логин:</label>
    <input type="text" name="login" required>

    <label for="password">Пароль:</label>
    <input type="password" name="password" required>

    <button type="submit">Зарегистрироваться</button>
</form>