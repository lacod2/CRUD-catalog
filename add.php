<?php
	require_once('dbConfig.php');
	$upload_dir = '/xampp/htdocs/www/upload/';

	if(isset($_POST['btnSave'])){
		$name = $_POST['name'];
		$position = $_POST['position'];

		$imgName = $_FILES['myfile']['name'];
		$imgTmp = $_FILES['myfile']['tmp_name'];
		$imgSize = $_FILES['myfile']['size'];

		if(empty($name)){
			$errorMsg = 'Kérjük, adja meg a címét';
		}elseif(empty($position)){
			$errorMsg = 'Kérjük, adja meg a műfaját';
		}elseif(empty($imgName)){
			$errorMsg = 'Kérjük, válaszon egy képet';
		}else{
			//get image extension
			$imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
			//allow extenstion
			$allowExt  = array('jpeg', 'jpg', 'png', 'gif');
			//random new name for photo
			$userPic = time().'_'.rand(1000,9999).'.'.$imgExt;
			//check a valid image
			if(in_array($imgExt, $allowExt)){
				//check image size less than 5MB
				if($imgSize < 5000000){
					move_uploaded_file($imgTmp ,$upload_dir.$userPic);
				}else{
					$errorMsg = 'Kép mérete túl nagy';
				}
			}else{
				$errorMsg = 'Kérjük, válasszon egy érvényes képet';
			}
		}

		//check upload file not error than insert data to database
		if(!isset($errorMsg)){
			$sql = "insert into tbl_users(name, position, photo)
					values('".$name."', '".$position."', '".$userPic."')";
			$result = mysqli_query($conn, $sql);
			if($result){
				$successMsg = 'Sikeresen hozzáadva';
				header('refresh:5;index.php');
			}else{
				$errorMsg = 'Error '.mysqli_error($conn);
			}
		}

	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Könyv katalógus</title>
	<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./bootstrap/css/bootstrap-theme.min.css">
</head>
<body>

<div class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<h3 class="navbar-brand">Könyv katalógus</h3>
		</div>
	</div>
</div>
<div class="container">
	<div class="page-header">
		<h3>Hozzáadás
			<a class="btn btn-default" href="index.php">
				<span class="glyphicon glyphicon-arrow-left"></span> &nbsp; Vissza
			</a>
		</h3>
	</div>

	<?php
		if(isset($errorMsg)){
	?>
		<div class="alert alert-danger">
			<span class="glyphicon glyphicon-info">
				<strong><?php echo $errorMsg; ?></strong>
			</span>
		</div>
	<?php
		}
	?>

	<?php
		if(isset($successMsg)){
	?>
		<div class="alert alert-success">
			<span class="glyphicon glyphicon-info">
				<strong><?php echo $successMsg; ?> - automatikus átirányítás</strong>
			</span>
		</div>
	<?php
		}
	?>

	<form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
		<div class="form-group">
			<label for="name" class="col-md-2">Cím</label>
			<div class="col-md-10">
				<input type="text" name="name" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label for="position" class="col-md-2">Műfaj</label>
			<div class="col-md-10">
				<input type="text" name="position" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label for="photo" class="col-md-2">Borítókép</label>
			<div class="col-md-10">
				<input type="file" name="myfile">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2"></label>
			<div class="col-md-10">
				<button type="submit" class="btn btn-success" name="btnSave">
					<span class="glyphicon glyphicon-save"></span>Mentés
				</button>
			</div>
		</div>
	</form>
</div>

</body>
</html>
