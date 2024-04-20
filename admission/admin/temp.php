<?php 
	session_start();
	$d="";
	$msg=" ";
	$email = $_SESSION['email'];
	if(isset($_SESSION['email'])){		
	include 'variables.php';

	$conn = new PDO("mysql:host=$databaseHost;dbname=$databaseName;", $databaseUsername, $databasePassword);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT id,coursename FROM courses";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$users = $stmt->fetchAll();
	
	//id
	$mysqli = new mysqli($databaseHost,$databaseUsername,$databasePassword,$databaseName);
	//
	$query = "SELECT id FROM student_data WHERE email = '{$_SESSION['email']}'"; 
	
	$result = $mysqli->query($query) or die($mysqli->error);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			foreach($row as $val) {
				$details[] = $val;
			}
		}   
		
	}


if(isset($_POST['applyCourse'])){
    // Проверяем наличие информации об образовании
    $eQuery = "SELECT ID FROM education_information WHERE ID = $details[0]";			
    $eResult = $mysqli->query($eQuery) or die($mysqli->error);
    if(!$eResult->num_rows > 0) {
        // Если информации об образовании нет, перенаправляем пользователя на страницу для заполнения
        header("Location: ../home/educational_details.php");			
    } else {
        // Иначе продолжаем выполнение кода
        $selectedCourse = $_POST['selectedCourse'];
        $id = $details[0];		
        $query = $conn->prepare("SELECT id,coursename from selected_courses where id=? AND coursename=?");			
        $query->bindValue(1, $id );
        $query->bindValue(2, $selectedCourse);
        $query->execute();

        if($query->rowCount() > 0 ) {
            // Если курс уже выбран, выводим сообщение об ошибке
            $msg = "<p style='text-align:center; color:red;'>Course Already Selected</p>";
        } else {
            // Иначе добавляем курс в базу данных
            $insertQuery = "INSERT INTO selected_courses (id, coursename, isAvailable) VALUES ('$id', '$selectedCourse', 1)";
            if ($conn->query($insertQuery)){
                // Если успешно добавлено, выводим сообщение об успешном подтверждении
                $msg = "<p style='text-align:center; color:green;'>Application Successful</p>";
            } else {
                // Если произошла ошибка, выводим сообщение об ошибке
                $msg = "<p style='text-align:center; color:red;'>An Error Occurred. Contact SysAdmin</p>";
            }
        }
        // Добавляем запись в таблицу student_data со значением 'In Process' в столбец 'approved'
		$updateStudentDataQuery = "UPDATE student_data SET approved = 'In Process' WHERE id = '$id'";
		$mysqli->query($updateStudentDataQuery) or die($mysqli->error);
    }
}
?>

<html lang="en">
<?php include 'home-menu.php';?>
<?php include 'user-side-menu.php'; ?>
<link rel="stylesheet" href="css/apply.css" type="text/css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script src="js/apply.js"></script>
<div class="container">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading main-color-bg">
                <h3 class="panel-title"> <b>Apply For Course</b> </h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-10">
                            <form action="apply.php" method="post">
                                <div class="col-sm-12 form-group">
                                    <label>Courses</label>
                                    <select class="form-control" name="selectedCourse">
										<?php foreach($users as $user): ?>
        								<option value="<?= $user['coursename']; ?>"><?= $user['coursename'];?></option>
    									<?php endforeach; ?>
										</select>
                                </div>
                                <input class="btn btn-info" type="submit" name="applyCourse" value="Submit">
                            </form>
                            <p>
                                <?php echo $msg;?>
                            </p>
                        </div>
                        <div class="col-sm-12 form-group">
                        </div>
                    </div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
	}
		else
		{
		?>
<?php
			echo "<script language='javascript'>alert('You are not logged in');
			window.location.href='/Admission/';
			 </script>";
		}
		?>
</html>