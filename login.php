<?php
    require 'configDB.php';

    session_start(); // start a session

    $data = $_POST;
    if( isset($data["do_login"]) )
    {

        $errors = array();
        if( trim($data["login"]) == '' )
        {
            $errors[] = 'Enter your login!';
        }

        if( $data["password"] == '' )
        {
            $errors[] = 'Enter your password!';
        }

        if( empty($errors) )
        {
            // check if login and password match a record in the database
            $query = "SELECT * FROM users WHERE user_login = :login AND user_password = :password";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':login', $data["login"]);
            $stmt->bindValue(':password', $data["password"]);
            $stmt->execute();
            $user = $stmt->fetch();

            if($user) {
                $_SESSION['logged_user'] = $user; // store user information in a session
                header('Location: /index.php'); // redirect to the index page
            } else {
                $errors[] = 'Invalid login or password.';
            }
        }

        if( !empty($errors))
        {
            echo '<div style="color: rgba(255,0,0,0.78);">' .array_shift($errors).'</div><hr>';
        }

    }
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