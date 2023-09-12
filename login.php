<?php
require 'configDB.php';

session_start(); // начинаем сессию

$data = $_POST;
if (isset($data["do_login"])) {

    $errors = array();
    if (trim($data["login"]) == '') {
        $errors[] = 'Введите логин!';
    }

    if ($data["password"] == '') {
        $errors[] = 'Введите пароль!';
    }

    if (empty($errors)) {
// проверяем, совпадает ли логин и пароль с записью в базе данных
        $query = "SELECT * FROM users WHERE user_login = :login AND user_password = :password";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':login', $data["login"]);
        $stmt->bindValue(':password', $data["password"]);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['logged_user'] = $user; // сохраняем информацию о пользователе в сессии
            header('Location: /index.php'); // перенаправляем на главную страницу
            exit; // прекращаем выполнение скрипта
        } else {
            $errors[] = 'Неверный логин или пароль.';
        }
    }

    if (!empty($errors)) {
        echo '<div style="color: rgba(255,0,0,0.78);">' . array_shift($errors) . '</div><hr>';
    }
}

// проверяем, авторизован ли уже пользователь
if (isset($_SESSION['logged_user'])) {
// пользователь уже авторизован, выполните здесь необходимые действия
    echo 'Вы уже авторизованы.';
} else {
// пользователь не авторизован, отображаем форму входа
    ?>

    <form action="/login.php" method="POST">
        <p>
        <p><strong>Ваш логин:</strong></p>
        <input type="text" name="login" value="<?php echo @$data['login']; ?>">
        </p>
        <p>
        <p><strong>Ваш пароль:</strong></p>
        <input type="password" name="password" value="<?php echo @$data['password']; ?>">
        </p>
        <p>
            <button type="submit" name="do_login">Войти</button>
        </p>
    </form>

    <?php
}
?>
