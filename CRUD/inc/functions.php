<?php
	define('DB_NAME', 'd:/code/php-mastering/CRUD/data/db.txt');

function seed(){
	$data = array(
	    array(
	    	'id'	=>1,
	        'fname' => 'Samim',
	        'lname' => 'Ahamed',
	        'age'   => 12,
	        'roll'  =>11
	    ),
	    array(
	    	'id'	=>2,
	        'fname' => 'Rahim',
	        'lname' => 'Ahamed',
	        'age'   => 16,
	        'roll'  =>9
	    ),
	    array(
	    	'id'	=>3,
	        'fname' => 'Karim',
	        'lname' => 'Ali',
	        'age'   => 45,
	        'roll'  =>33
	    )
	);
	$serializedData = serialize($data);
	file_put_contents(DB_NAME, $serializedData,LOCK_EX);


}

// View All Student
function generateReport(){
	$serializedData = file_get_contents( DB_NAME );
	$students = unserialize($serializedData);
	?>
	<table>
		<tr>
			<th>Name</th>
			<th>Age</th>
			<th>Roll</th>
			<th>Action</th>
		</tr>
		<?php 
		foreach($students as $student){ ?>
			<tr>
				<td><?php printf("%s %s",$student['fname'],$student['lname']); ?></td>
				<td><?php printf("%s",$student['age']); ?></td>
				<td><?php printf("%s",$student['roll']); ?></td>
				<td><?php printf("<a href='/index.php?task=edit&id=%s'>Edit</a> | <a class='delete' href='/index.php?task=delete&id=%s'>Delete</a>",$student['id'],$student['id']); ?></td>
			</tr>
		<?php } ?>
		
	</table>
	<?php
}

// Add New Student

function addStudent($fname,$lname,$age,$roll){
	$found = false;
	$serializedData = file_get_contents( DB_NAME );
	$students = unserialize($serializedData);
	foreach ($students as $_student) {
		if ($_student['roll'] == $roll) {
			$found = true;
			break;
		}
	}
	if ( ! $found) {
		$newId = getNewId($students);
		$student = array(
			'id' 	=> $newId,
			'fname' => $fname,
			'lname' => $lname,
			'age' 	=> $age,
			'roll' 	=> $roll
		);
		array_push($students, $student);
		$serializedData = serialize($students);
		file_put_contents(DB_NAME, $serializedData,LOCK_EX);
		return true;
	}
	return false;
}

// Editing Student Data

function getStudent($id){
	$serializedData = file_get_contents( DB_NAME );
	$students = unserialize($serializedData);
	foreach ($students as $student) {
		if ( $student['id'] == $id) {
			return $student;
		}
		
	}
	return false;
}


function updateStudent($fname,$lname,$roll,$age,$id){
	$found = false;
	$serializedData = file_get_contents( DB_NAME );
	$students = unserialize($serializedData);
	foreach ($students as $_student) {
		if ($_student['roll'] == $roll && $_student['id'] != $id) {
			$found = true;
			break;
		}
	}
	if ( ! $found) {
		$students[$id-1]['fname'] = $fname;
		$students[$id-1]['lname'] = $lname;
		$students[$id-1]['age'] = $age;
		$students[$id-1]['roll'] = $roll;

		$serializedData = serialize($students);
		file_put_contents(DB_NAME, $serializedData,LOCK_EX);
		return true;
	}

	return false;
}

function deleteStudent($id){
	$serializedData = file_get_contents( DB_NAME );
	$students = unserialize($serializedData);

	foreach ($students as $key => $student) {
		if ($student['id'] == $id) {
			unset($students[$key]);
		}
	}
	$serializedData 			= serialize($students);
	file_put_contents(DB_NAME, $serializedData,LOCK_EX);
}

function getNewId($students){
	$maxId = max(array_column($students, 'id'));
	return $maxId+1;
}