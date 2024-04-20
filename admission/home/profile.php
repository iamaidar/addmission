<?php
    session_start();

	if(isset($_SESSION['email']))
	{
        //function this
        $email = $_SESSION['email'];
        include 'variables.php';
        $mysqli = new mysqli($databaseHost,$databaseUsername,$databasePassword,$databaseName);
        //
        $query = "SELECT * FROM student_data WHERE email = '{$_SESSION['email']}'"; 
        $result = $mysqli->query($query) or die($mysqli->error);
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                foreach($row as $val) {
                    $details[] = $val;
                }
            }   
        }
?>
<?php include 'home-menu.php'; ?>
    <?php include 'user-side-menu.php'; ?>
        <div class="container">
            <div class="col-lg-9">
                <div class="panel panel-default">
                    <div class="panel-heading main-color-bg"> <h3 class="panel-title"><b>Basic Information</b></h3> </div>
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Fullname</label>
                                            <input type="text" name="fullname" class="form-control" value= "<?php echo $details[1]; ?> " disabled>
                                        </div>
                                        <div style="overflow-y:hiden; height: 150px; position: absolute; right:0; top: 0; class="col-sm-6 form-group">
                                            <label>CV</label> <br>
                                            <img id="cvImage" style="padding-right: 16px; object-fit: cover; weight: 100%; height: 130px; overflow-y: hidden;" src="<?php echo '../' . $details[11];?>" height="200" width="200">
                                        </div>
                              
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Gender</label>
                                            <input list="gender" name="gender" class="form-control" value= "<?php echo $details[2]; ?> " disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" rows="3" cols="4" class="form-control"  disabled> <?php echo $details[3]; ?> </textarea>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-3 form-group">
                                            <label>Date of Birth</label>
                                            <input type="text" name="pnumber" class="form-control" value= "<?php echo $details[10]; ?> " disabled>
                                        </div>
                                     
                                        <div class="col-sm-3 form-group">
                                            <label>City</label>
                                            <input type="text" name="city" class="form-control" value= "<?php echo $details[4]; ?> " disabled>
                                        </div>

                                        <div class="col-sm-3 form-group">
                                            <label>State</label>
                                            <input type="text" name="state" class="form-control" value= "<?php echo $details[5]; ?> " disabled>
                                        </div>
                                        <div class="col-sm-3 form-group">
                                            <label>Status</label>
                                            <input type="text" name="state" class="form-control" value= "<?php echo $details[13]; ?> " disabled>
                                        </div>

                                    </div>                               
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="pnumber" class="form-control" value= "<?php echo $details[6]; ?> " disabled>
                                    </div>

                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" name="email" class="form-control" value= "<?php echo $details[7]; ?> " disabled>
                                    </div>

                                    <div class="form-group">
                                        <label>Registration Date</label>
                                        <input type="text" name="reg_date" class="form-control" value= "<?php echo $details[9]; ?> " disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
    
<?php } else { ?>
<?php  					echo "<script language='javascript'>alert('You are not logged in');
			window.location.href='/Admission/';
			 </script>";; } ?>

<script>
    $(document).ready(function() {
        // Назначаем обработчик события клика на изображение
        $("#cvImage").click(function() {
            // Получаем ссылку на изображение
            var imageUrl = $(this).attr("src");
            // Создаем модальное окно для увеличенного изображения
            var modal = '<div id="imageModal" class="modal fade" role="dialog"> \
                            <div class="modal-dialog"> \
                                <div class="modal-content"> \
                                    <div class="modal-body"> \
                                        <img src="' + imageUrl + '" class="img-responsive"> \
                                    </div> \
                                </div> \
                            </div> \
                        </div>';
            // Добавляем модальное окно в конец тела документа
            $("body").append(modal);
            // Показываем модальное окно
            $("#imageModal").modal("show");
        });
    });
</script>

<style>
    .modal-dialog {
        max-width: 90%; /* Максимальная ширина модального окна */
        margin: auto; /* Центрирование модального окна */
    }

    .modal-body {
        text-align: center; /* Выравнивание содержимого по центру */
    }

    .modal-body img {
        max-width: 100%; /* Максимальная ширина изображения в модальном окне */
        height: auto; /* Автоматическое вычисление высоты изображения */
    }
</style>


