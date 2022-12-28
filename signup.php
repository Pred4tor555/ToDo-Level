<?php
    require 'configDB.php';

    $data = $_POST;
    if( isset($data["do_signup"]) )
    {

        $errors = array();
        if( trim($data["login"]) == '' )
        {
            $errors[] = 'Введите логин!';
        }

        if( trim($data["email"]) == '' )
        {
            $errors[] = 'Введите Email!';
        }

        if( $data["password"] == '' )
        {
            $errors[] = 'Введите пароль!';
        }

        if( $data["password_2"] != $data["password"] )
        {
            $errors[] = 'Повторный пароль введён не верно!';
        }

        if( empty($errors) )
        {
            $sql = 'INSERT INTO users( user_login, user_email, user_password ) VALUES( :login, :email, :password )';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue(':login', $data["login"]);
            $stmt -> bindValue(':email', $data["email"]);
            $stmt -> bindValue(':password', $data["password"]);
            $stmt -> execute();
            echo 'Регистрация прошла успешно';
            // Должна быть произведена регестрация пользователя в базу данных(insert, $_GET)
        } else
        {
            echo '<div style="color: rgba(255,0,0,0.78);">' .array_shift($errors).'</div><hr>';
        }

    }

?>

<form action="/signup.php" method="POST">

    <p>
        <p><strong>Ваш логин:</strong></p>
        <input type="text" name="login" value="<?php echo @$data['login']; ?>">
    </p>

    <p>
        <p><strong>Ваш Email</strong>:</p>
        <input type="email" name="email" value="<?php echo @$data['email']; ?>">
    </p>

    <p>
        <p><strong>Ваш пароль</strong>:</p>
        <input type="password" name="password" value="<?php echo @$data['password']; ?>">
    </p>

    <p>
        <p><strong>Введите ваш  пароль ещё раз</strong>:</p>
        <input type="password" name="password_2" value="<?php echo @$data['password_2']; ?>">
    </p>

    <p>
        <button type="submit" name="do_signup">Зарегестрироваться</button>
    </p>

</form>