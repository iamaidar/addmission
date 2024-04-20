<?php
session_start();
$sid = 0;
if (isset($_SESSION["email"])) {
    $id = $_GET["id"];
    include "variables.php";
    $mysqli = new mysqli(
        $databaseHost,
        $databaseUsername,
        $databasePassword,
        $databaseName
    );

    $query = "SELECT * FROM student_data WHERE id =$id";
    ($result = $mysqli->query($query)) or die($mysqli->error);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($row as $val) {
                $details[] = $val;
            }
            $sid = $details[0];
        }
    }
    if ($id == $sid) {
?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Jquery -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <title>Basic Profile</title>

        <body>
            <div class="container">
                <h1 class="well" style="text-align: center;">Student Detail</h1>
                <div class="col-lg-12 well">
                    <div class="row">
                        <div class="col-sm-12">
                            <div style="width: 100%;" class="row">
                                <div style="width: 75%; padding: 0 30px;" class="col-sm-6">
                                    <div class="row">
                                        <label>Fullname</label>
                                        <input type="text" class="form-control" value="<?php echo $details[1]; ?>" readonly>
                                    </div>
                                    <form style="padding: 0; margin-top: 70px; width: 100%; display: flex; align-items: center;" method="POST">
                                        <div style="margin-right: 10px; display: flex; align-items: center; width: 50%;" class="col-sm-6 form-group">
                                            <label for="exam_grade ">Exam Grade</label>
                                            <input type="number" class="form-control" id="exam_grade" name="exam_grade" min="0" max="100" required>
                                        </div>
                                        <div style="margin-right: 10px; display: flex; align-items: center; width: 50%;" class="col-sm-6 form-group">
                                            <label>Interview Grade</label>
                                            <input type="number" class="form-control" id="interview_grade" name="interview_grade" min="0" max="100" required>
                                        </div>
                                        <div style="margin-right: 10px; display: flex; align-items: center; width: 50%;" class="col-sm-6 form-group">
                                            <label for="approved">Status</label>
                                            <select class="form-control" id="approved" name="approved" required>
                                                <option value="In Process">In Process</option>
                                                <option value="Approved">Approved</option>
                                            </select>
                                        </div>
                                        <div style="margin-right: 10px; display: flex; align-items: center;">
                                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div style="min-width: 200px; max-width: 250px; justify-self: flex-end; display: flex; flex-direction: column; align-items: flex-end;" class="col-sm-6 form-group">
                                    <label >CV</label>
                                    <img src="<?php echo "../" . $details[11]; ?>" height="200" width="200">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Gender</label>
                                    <input type="text" class="form-control" value="<?php echo $details[2]; ?> " readonly>
                                </div>
<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, были ли отправлены данные для обработки
    if (isset($_POST["submit"])) {
        // Обработка данных для кнопки "Submit"
        $approved = $_POST["approved"]; // Получаем статус из формы

        // Проверяем, были ли введены новые оценки
        $exam_grade = isset($_POST["exam_grade"]) && $_POST["exam_grade"] !== "" ? $_POST["exam_grade"] : null;
        $interview_grade = isset($_POST["interview_grade"]) && $_POST["interview_grade"] !== "" ? $_POST["interview_grade"] : null;

        // Если были введены новые оценки, обновляем их и статус
        if ($exam_grade !== null && $interview_grade !== null) {
            // Получаем предыдущие оценки из базы данных
            $query = "SELECT exam_grade, interview_grade FROM grades WHERE student_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $sid);
            $stmt->execute();
            $stmt->bind_result($prev_exam_grade, $prev_interview_grade);
            $stmt->fetch();
            $stmt->close();

            // Обновляем оценки в базе данных
            $sql = "INSERT INTO grades (student_id, exam_grade, interview_grade) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE exam_grade = VALUES(exam_grade), interview_grade = VALUES(interview_grade)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iii", $sid, $exam_grade, $interview_grade);
            if ($stmt->execute()) {
                // Обновляем статус в таблице student_data
                $updateStudentDataQuery = "UPDATE student_data SET approved = ? WHERE id = ?";
                $updateStmt = $mysqli->prepare($updateStudentDataQuery);
                $updateStmt->bind_param("si", $approved, $sid);
                if ($updateStmt->execute()) {
                    echo "<script>alert('Data saved successfully.');</script>"; // Выводим алерт после успешного сохранения
                    echo "<script>window.location.href = 'http://localhost/admission/admin/records.php';</script>"; // Перенаправляем на указанную страницу
                    exit;
                } else {
                    echo "Error updating status: " . $mysqli->error;
                }
            } else {
                echo "Error adding data: " . $mysqli->error;
            }
        } elseif ($approved !== "") {
            // Если только статус был изменен, обновляем только статус
            $updateStudentDataQuery = "UPDATE student_data SET approved = ? WHERE id = ?";
            $updateStmt = $mysqli->prepare($updateStudentDataQuery);
            $updateStmt->bind_param("si", $approved, $sid);
            if ($updateStmt->execute()) {
                echo "<script>alert('Status updated successfully.');</script>"; // Выводим алерт после успешного обновления
                echo "<script>window.location.href = 'http://localhost/admission/admin/records.php';</script>"; // Перенаправляем на указанную страницу
                exit;
            } else {
                echo "Error updating status: " . $mysqli->error;
            }
        } else {
            echo "Error: not all data has been entered.";
        }
    }
}

?>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" rows="3" class="form-control" readonly> <?php echo $details[3]; ?> </textarea>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Date of Birth</label>
                                    <input type="text" class="form-control" value="<?php echo $details[10]; ?> " readonly>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" value="<?php echo $details[4]; ?> " readonly>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control" value="<?php echo $details[5]; ?> " readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" value="<?php echo $details[6]; ?> " readonly>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>Email Address</label>
                                    <input type="text" class="form-control" value="<?php echo $details[7]; ?> " readonly>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>Registration Date</label>
                                    <input type="text" name="reg_date" class="form-control" value="<?php echo $details[9]; ?> " readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
<?php
    } else {
        echo '<title> Error </title>
		<div class="container" style="margin:150px;">
		<h1 class="well" style="text-align: center;color: red !important;">Error : Invalid Student ID</h1>
		<h3 class="well" style="text-align: center;"><a href="view.php?id=1" style="color: #000080 !important;">Click here to return ID 1</a></h3>';
    }
} else {
    echo "<script language='javascript'>alert('Login to continue');
	window.location.href='/admission/admin';
	</script>";
}

?>
