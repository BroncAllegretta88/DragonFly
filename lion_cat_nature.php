<?php 

// Startup Code 
ini_set('magic_quotes_gpc', false);
error_reporting(E_ALL);

// Establish a connection to the database 
$db_host = "localhost";
$db_name = "webdev_agency";
$db_user = "db_admin";
$db_pass = "db_pass";

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($db->connect_error) {
	trigger_error('Database connection failed: ' . $db->connect_error, E_USER_ERROR);
}

// Create a content table 
$sql = "CREATE TABLE IF NOT EXISTS content (
	id INT PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(255) NOT NULL,
	body TEXT NOT NULL,
	dateCreated timestamp NOT NULL
) ENGINE=InnoDB";

$db->query($sql) or trigger_error('Error: ' . $db->error, E_USER_ERROR);
$db->set_charset('utf8');

// Insert content into database 
$sql = "INSERT INTO content(title, body, dateCreated) VALUES (?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param('sss', $title, $body, $dateCreated);

$title = "Welcome to our Agency";
$body = "A web design and development agency that helps clients create memorable online experiences through cutting-edge design and technology";
$dateCreated = date("Y-m-d H:i:s");
$stmt->execute();

// Get all contents 
$sql = "SELECT * FROM content";
$result = $db->query($sql);

if($result->num_rows > 0) {
	
	// Echo content from the database 
	while($row = $result->fetch_assoc()) {
		echo "<h1>".$row['title']."</h1>";
		echo "<p>".$row['body']."</p>";
		echo "<p>Posted on ".$row['date_created']."</p>";
	}

} else {
	// No content found in the database
	echo "<p>No content found.</p>";
}

// Update Content
$sql = "UPDATE content SET body = ? WHERE title = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('ss', $body, $title);

$title = "Welcome to our Agency";
$body = "A web design and development agency that helps clients create memorable online experiences through cutting-edge design and innovative technology";
$stmt->execute();

// Delete Content
$sql = "DELETE FROM content WHERE title = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('s', $title);

$title = "Welcome to our Agency";
$stmt->execute();

// Close the connection 
$db->close();

?>