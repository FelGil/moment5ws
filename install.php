<html>
    <head>
        <title>Install.php</title>
    </head>
 <body>   
<?php

//Include config file
include("includes/config.php");

//Create a new DB instnace
$db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
if($db->connect_errno > 0){
    die('Error while connectiong [' . $db->connect_error);
}

//SQL query to drop old table and create a new empty table
$sql = "DROP TABLE IF EXISTS courses;
    CREATE TABLE courses(
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(11) NOT NULL,
    name VARCHAR(128) NOT NULL,
    progression VARCHAR(11) NOT NULL,
    syllabus VARCHAR(128) NOT NULL
    );
";

//Insert test data to table
$sql .= "
    INSERT INTO courses(code, name, progression, syllabus) VALUES('A1234','Test kurs','A','HTTP');
";

//Output the sql query
echo "<pre>$sql</pre>";

//run the query, and out put if error or not.
if($db->multi_query($sql)) {
    echo "<p>Table is installed</p>";
}else{
    echo"<p class='error'>Error when trying to install the table</p>";
}

?>
</body>
</html>