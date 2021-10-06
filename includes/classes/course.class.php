<?php

class Course {

    //properties
    private $db;
    private $code;
    private $name;
    private $progression;
    private $syllabus;
    
    //constructor
    public function __construct() {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        $this->db->set_charset("utf8");
        if($this->db->connect_errno > 0) {
            die("fel vid anslutning: " . $this->db->connect_errno);
        }
    }
    
    //add new course
    public function createCourse(string $code, string $name, string $progression, string $syllabus) :bool {
        //check if the data meets the requirements
        if(!$this->setCode($code)) return false;
        if(!$this->setName($name)) return false;
        if(!$this->setProgression($progression)) return false;
        if(!$this->setSyllabus($syllabus)) return false;
        
        //Prepare statement
        $stmt = $this->db->prepare("INSERT INTO courses (code, name, progression, syllabus) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->code, $this->name, $this->progression, $this->syllabus);

        //execute statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

        //close statement
        $stmt->close();
    }
    
    //Get all courses
    public function getCourses() : array {
        $sql = "SELECT * FROM courses ORDER BY code;";
        $result = $this->db->query($sql);

        //return as associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //Get Courses by ID
    public function getCourse(int $id) : array {
        $id = intval($id);
          
        $sql ="SELECT * FROM courses WHERE id=$id;";
        $result = $this->db->query($sql);
        
        //return as associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //update course
    public function updateCourse(int $id, string $code, string $name, string $progression, string $syllabus) :bool {
        //check if the data meets the requirements
        if(!$this->setCode($code)) return false;
        if(!$this->setName($name)) return false;
        if(!$this->setProgression($progression)) return false;
        if(!$this->setSyllabus($syllabus)) return false;

        //store the input ID to the $id variable.
        $id = intval($id);

        //Check if there is something to delete
        if(!$this->checkCourseID($id)) return false;

        //prepare statement
        $stmt = $this->db->prepare("UPDATE courses SET code=?, name=?, progression=?, syllabus=? WHERE id=$id;");
        $stmt->bind_param("ssss", $this->code, $this->name, $this->progression, $this->syllabus);

        //execute statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
 
        //close statement
        $stmt->close();
    }

    //delete course
    public function deleteCourse(int $id) :bool {
        $id = intval($id);
        //Check if there is something to delete
        if(!$this->checkCourseID($id)) return false;

        $sql = "DELETE FROM courses WHERE id=$id";
        $result = $this->db->query($sql);
    
        return $result;
    
    }

    //check if the id exists in the databse
    public function checkCourseID(int $id) : bool {
        
        $id = intval($id);

        $sql = "SELECT * FROM courses WHERE id=$id";
        $result = $this->db->query($sql);

        if(mysqli_num_rows($result) === 0) {
            return false;
        } else {
            return true;
        }   
    }

    //set method to check if course code meets the number of characters
    public function setCode (string $code) : bool {
        
        if(strlen($code) === 5) {
            $this->code = $code;
            return true;
        } else {
            return false;
        }
    }

    //set method to check if name is not empty
    public function setName (string $name) : bool {
        if(strlen($name) > 1) {
            $this->name = $name;
            return true;
        } else {
            return false;
        }
    }

    //set method to check the progression is just one character.
    public function setProgression (string $progression) : bool {
        if(strlen($progression) === 1) {
            $this->progression = $progression;
            return true;
        } else {
            return false;
        }
    }

    //set method to check if syllabus is not empty
    public function setSyllabus (string $syllabus) : bool {
        if(strlen($syllabus) > 1) {
            $this->syllabus = $syllabus;
            return true;
        } else {
            return false;
        }
    }


}