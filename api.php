<?php
/*Include config file with databse connection data and to import classes*/
include_once("includes/config.php");

// Access controll to allow all users to use the REST service
header('Access-Control-Allow-Origin: *');

//Tells that the service uses JSON
header('Content-Type: application/json; charset=UTF-8');

//Add which commands that is allowed besides GET.
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

//Which headers who is allowed from the client-side.
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Check whick method the client has sent.
$method = $_SERVER['REQUEST_METHOD'];

//If the parameter set set in the request.
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

// Create a new course variable.
$course = new course();

switch($method) {
    case 'GET':

        if(isset($id)) {
            //Function to get info from an id
            $response = $course->getCourse($id);
        } else {
            //Function go gett all data from database
            $response = $course->getCourses();
        }
    
        //If any rows was returned.
        if(sizeof($response) > 0) {
            http_response_code(200); // Rows was returned
        } else {
            http_response_code(404); // No rows was returned
            $response = array("message" => "No courses found");
        }

        break;
    case 'POST':
        //Läser in JSON-data skickad med anropet och omvandlar till ett objekt.
        $data = json_decode(file_get_contents("php://input"));
        
        //Run class createCourse to add new data to table. Output response code if succeed or not and with a message
        if($course->createCourse($data->code,$data->name,$data->progression,$data->syllabus)) {
            $response = array("message" => "Course created");
            http_response_code(201); //Created
        } else {
            $response = array("message" => "Error when creating course");
            http_response_code(500); //Error code
        }
      
        break;
    case 'PUT':
        //Check if there is an ID in the rest request.
        if(!isset($id)) {
            http_response_code(400); //Bad Request - The server could not understand the request due to invalid syntax.
            $response = array("message" => "No id, add id and try again.");
        //Om id är skickad   
        } else {
            $data = json_decode(file_get_contents("php://input"));
            //Check if there the ID exists in the DB. And output errorcode with an message
            if($response === false) {
                http_response_code(404);
                $response = array("message" => "There is now Course with that ID.");
            } else {
                //Run class updateCourse to update data in the table. Output response code if succeed or not and with a message
                if($course->updateCourse($id,$data->code,$data->name,$data->progression,$data->syllabus)) {
                    $response = array("message" => "Course with id=$id is updated");
                    http_response_code(201); //Created
                } else {
                    $response = array("message" => "Error when updating course");
                    http_response_code(500); //Error code
                }
            }
        }
        break;
        
    case 'DELETE':
        //Check if there is an ID in the rest request.
        if(!isset($id)) {
            http_response_code(400);
            $response = array("message" => "No id, add id and try again.");  
        } else {
            // Run the deleteCourse class
            $response = $course->deleteCourse($id);
            
            //if return false the ID did not exist in the databse otherwise return a positive responmse code
            if($response === false) {
                http_response_code(404);
                $response = array("message" => "There is now Course with that ID.");
            } else {
                http_response_code(200);
                $response = array("message" => "Course with id=$id is deleted");
            }
            
        }
        break;
        
}

//Send the resonse to the user
echo json_encode($response);