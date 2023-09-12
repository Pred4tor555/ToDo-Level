<?php
// Подключение к базе данных MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo-level";
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Обработка формы создания задачи
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_task"])) {
    $task = $_POST["task"];
    $description = $_POST["description"];
    $date = $_POST["date"];
    $time = $_POST["time"];

// Запрос к базе данных для создания задачи
    $sql = "INSERT INTO tasks (task, description, date, time) VALUES ('$task', '$description', '$date', '$time')";
    if ($conn->query($sql) === TRUE) {
        echo "Задача успешно создана.<br><br>";
    } else {
        echo "Ошибка при создании задачи: " . $conn->error;
    }
}

// Обработка формы редактирования задачи
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_task"])) {
    $id = $_POST["id"];
    $task = $_POST["task"];
    $description = $_POST["description"];
    $date = $_POST["date"];
    $time = $_POST["time"];

// Запрос к базе данных для обновления задачи
    $sql = "UPDATE tasks SET task='$task', description='$description', date='$date', time='$time' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Задача успешно обновлена.<br><br>";
    } else {
        echo "Ошибка при обновлении задачи: " . $conn->error;
    }
}

// Обработка формы удаления задачи
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_task"])) {
    $id = $_POST["id"];

// Запрос к базе данных для удаления задачи
    $sql = "DELETE FROM tasks WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Задача успешно удалена.<br><br>";
    } else {
        echo "Ошибка при удалении задачи: " . $conn->error;
    }
}

// Запрос к базе данных для получения списка задач
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);
?>

    <!DOCTYPE html>
    <html>
    <head>
        <style>
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: none;
            }

            .popup {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                display: none;
            }

            .editPopup {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                display: none;
            }

            h2 {
                font-size: 24px;
                text-align: center;
                margin-bottom: 70px;
            }

            button {
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                cursor: pointer;
            }

            button:hover {
                background-color: #45a049;
            }

            input[type="submit"] {
                padding: 10px 20px;
                background-color: #f44336;
                color: white;
                border: none;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #d32f2f;
            }

            input[type="button"] {
                padding: 10px 20px;
                background-color: #2196F3;
                color: white;
                border: none;
                cursor: pointer;
            }

            input[type="button"]:hover {
                background-color: #1976D2;
            }

            input[type="text"],
            textarea,
            input[type="date"],
            input[type="time"] {
                width: 100%;
                padding: 10px;
            }

            .task-container {
                max-width: 1400px;
                margin: 0 auto;
                gap: 150px 150px;
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: flex-start;
            }

            .task-card {
                border: 2px solid gray;
                border-radius: 3px;
            }

        </style>
    </head>
    <body>
    <button onclick="showPopup()">Создать задачу</button>
    <div class="overlay"></div>
    <div class="popup">
        <h3>Новая задача</h3>
        <form method="post" action="<?php echo $_SERVER["tasks.php"]; ?>">
            <label for="task">Заголовок:</label>
            <input type="text" name="task" id="task" required><br><br>
            <label for="description">Описание:</label>
            <textarea name="description" id="description" required></textarea><br><br>
            <label for="date">Дата:</label>
            <input type="date" name="date" id="date" required><br><br>
            <label for="time">Время:</label>
            <input type="time" name="time" id="time" required><br><br>
            <input type="submit" name="create_task" value="Создать задачу">
        </form>
        <button onclick="hidePopup()">Отмена</button>
    </div>

    <h2>Список задач</h2>
    <div class="task-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='task-card'>";
                echo "<h3>" . $row["task"] . "</h3>";
                echo "<p>" . $row["description"] . "</p>";
                echo "<p>" . $row["date"] . "</p>";
                echo "<p>" . $row["time"] . "</p>";
                echo "<form method='post' action='" . $_SERVER["tasks.php"] . "'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<input type='hidden' name='task' value='" . $row["task"] . "'>";
                echo "<input type='hidden' name='description' value='" . $row["description"] . "'>";
                echo "<input type='hidden' name='date' value='" . $row["date"] . "'>";
                echo "<input type='hidden' name='time' value='" . $row["time"] . "'>";
                echo "<input type='button' onclick='showEditPopup(this)' value='Редактировать'>";
                echo "<input type='submit' name='delete_task' value='Удалить'>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "Нет задач.";
        }
        ?>
    </div>

    <div class="overlay"></div>
    <div class="editPopup">
        <h3>Редактирование задачи</h3>
        <form id="editForm" method="post" action="<?php echo $_SERVER["tasks.php"]; ?>">
            <input type="hidden" name="id" id="editId">
            <label for="editTask">Заголовок:</label>
            <input type="text" name="task" id="editTask" required><br><br>
            <label for="editDescription">Описание:</label>
            <textarea name="description" id="editDescription" required></textarea><br><br>
            <label for="editDate">Дата:</label>
            <input type="date" name="date" id="editDate" required><br><br>
            <label for="editTime">Время:</label>
            <input type="time" name="time" id="editTime" required><br><br>
            <input type="submit" name="edit_task" value="Сохранить изменения">
        </form>
        <button onclick="hideEditPopup()">Отмена</button>
    </div>

    <script>
        function showPopup() {
            document.querySelector('.overlay').style.display = 'block';
            document.querySelector('.popup').style.display = 'block';
        }

        function hidePopup() {
            document.querySelector('.overlay').style.display = 'none';
            document.querySelector('.popup').style.display = 'none';
        }

        function showEditPopup(button) {
            var form = document.getElementById('editForm');
            var id = button.parentNode.querySelector('input[name="id"]').value;
            var task = button.parentNode.querySelector('input[name="task"]').value;
            var description = button.parentNode.querySelector('input[name="description"]').value;
            var date = button.parentNode.querySelector('input[name="date"]').value;
            var time = button.parentNode.querySelector('input[name="time"]').value;

            form.querySelector('#editId').value = id;
            form.querySelector('#editTask').value = task;
            form.querySelector('#editDescription').value = description;
            form.querySelector('#editDate').value = date;
            form.querySelector('#editTime').value = time;

            document.querySelector('.overlay').style.display = 'block';
            document.querySelector('.popup').style.display = 'none';
            document.querySelector('.editPopup').style.display = 'block';
        }

        function hideEditPopup() {
            document.querySelector('.overlay').style.display = 'none';
            document.querySelector('.editPopup').style.display = 'none';
        }
    </script>
    </body>
    </html>

<?php
// Закрытие соединения с базой данных
$conn->close();
?>