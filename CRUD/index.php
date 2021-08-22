<?php
 require_once ('inc/functions.php');
 $info = '';
$task = $_GET['task'] ?? 'report';
$error = $_GET['error'] ?? '0';

if ('delete' == $task) {
	$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_STRING);
	if ($id>0) {
		deleteStudent($id);
		header("Location: /index.php?task=report");
	}
}

if('seed' == $task){
	seed();
	$info = "Sedding is Complete";
}

// Adding New Student 
$fname 	= '';
$lname 	= '';
$age 	= '';
$roll 	= '';


if (isset($_POST['submit'])) {
	$fname = filter_input(INPUT_POST, 'fname',FILTER_SANITIZE_STRING);
	$lname = filter_input(INPUT_POST, 'lname',FILTER_SANITIZE_STRING);
	$age = filter_input(INPUT_POST, 'age',FILTER_SANITIZE_STRING);
	$roll = filter_input(INPUT_POST, 'roll',FILTER_SANITIZE_STRING);
	$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_STRING);


	if ( $id ) {
		if ($fname != '' && $lname != '' && $age != '' && $roll != '') {
			$resualt = updateStudent($fname,$lname,$roll,$age,$id);
			if ($resualt) {
				header("Location: /index.php?task=report");
			}else{
				header("Location: /index.php?task=report&error=1");
				$error = 1;
			}
		}
	}else{

		if ($fname != '' && $lname != '' && $age != '' && $roll != '') {
			$resualt = addStudent($fname,$lname,$age,$roll);
			if ($resualt) {
				header("Location: /index.php?task=report");
			}else{
				header("Location: /index.php?task=report&error=1");
				$error = 1;
			}
		}
	}

}

// editing Student data



 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

	<title>Document</title>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="column column-60 column-offset-20"><br>
				<h1>Project 2 | CRUD </h1>
				<p>a simple project to perform CRUD operations using plain files and PHP</p>
				<?php include_once  "inc/templates/nav.php"; ?>
				<hr/>
				<?php if ($info != ''){
					echo "<p>{$info}</p>";
				} ?>
			</div>
		</div>
		<?php if ('1' == $error): ?>
			<div class="row">
			<div class="column column-60 column-offset-20">
				<blockquote>
					Duplicate Roll Number.
				</blockquote>
			</div>
		</div>
		<?php endif ?>
		<?php if ('report' == $task): ?>
		<div class="row">
			<div class="column column-60 column-offset-20">
				<?php generateReport(); ?>
			</div>
		</div>
		<?php endif;
		 if ('add' == $task): ?>
		<div class="row">
			<div class="column column-60 column-offset-20">
				<form action="/index.php?task=add" method="post">
					<label for="fname">First Name</label>
					<input type="text" name="fname" id="fname" value="<?php echo $fname; ?>">
					<label for="lname">Last Name</label>
					<input type="text" name="lname" id="lname" value="<?php echo $lname; ?>">
					<label for="age">Age</label>
					<input type="number" name="age" id="age" value="<?php echo $age; ?>">
					<label for="roll">Roll</label>
					<input type="number" name="roll" id="roll" value="<?php echo $roll; ?>">
					<button type="submit" class="button button-primary" name="submit">Submit</button>
				</form>
			</div>
		</div>
		<?php endif; ?>
		<?php if ('edit' == $task):
		 	$id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_STRING);
			$student = getStudent( $id );
		 	if ($student):
		  ?>
		<div class="row">
			<div class="column column-60 column-offset-20">
				<form method="post">
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<label for="fname">First Name</label>
					<input type="text" name="fname" id="fname" value="<?php echo $student['fname']; ?>">
					<label for="lname">Last Name</label>
					<input type="text" name="lname" id="lname" value="<?php echo $student['lname']; ?>">
					<label for="age">Age</label>
					<input type="number" name="age" id="age" value="<?php echo $student['age']; ?>">
					<label for="roll">Roll</label>
					<input type="number" name="roll" id="roll" value="<?php echo $student['roll']; ?>">
					<button type="submit" class="button button-primary" name="submit">Update</button>
				</form>
			</div>
		</div>
		<?php 
			endif;
		endif;
		 ?>
	</div>

	<script type="text/javascript" src="assects/js/scripts.js"></script>
</body>
</html>