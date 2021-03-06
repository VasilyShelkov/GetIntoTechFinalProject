<?php

class User {
    protected $ID;
    public $first_name;
    public $last_name;
    protected $user_type;
                
    function __construct($ID, $first_name, $last_name, $user_type) {
        $this->ID = $ID;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->user_type = $user_type;
    }

    public function loginUser($emailinput, $passwordinput) {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT u.ID, u.first_name, u.last_name, t.type FROM blog_user as u INNER JOIN user_type as t ON u.user_type_id=t.ID WHERE u.email= :email AND u.password= :password");
        $req->bindParam(':email', $email);
        $req->bindParam(':password', $password);
        try {
            if(isset($_POST['email'])&& $_POST['email']!=""){
                 $filteredEmail = filter_input(INPUT_POST,'email', FILTER_SANITIZE_SPECIAL_CHARS);
            }    
            if(isset($_POST['password'])&& $_POST['password']!=""){
                 $filteredPassword = filter_input(INPUT_POST,'password', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $email = $filteredEmail;
            $password = $filteredPassword;
            $req->execute();
            $user=$req->fetch();
            if ($user){
                return new User($user['ID'],$user['first_name'], $user['last_name'], $user['type']);
            }

        } catch (PDOException $e) {
            die("Could not login ....." . $e->getMessage());
        }
        unset($stmt);
    }
    
     public function register($emailinput, $passwordinput, $firstnameinput, $lastnameinput, $dobinput) {
        $db = Db::getInstance();
        // Insert new user
        $req = $db->prepare("INSERT INTO blog_user (dob, email, first_name, last_name, password, user_type_id) VALUES (:dob, :email, :first_name, :last_name, :password, 1)");
        $req->bindParam(':email', $email);
        $req->bindParam(':password', $password);
        $req->bindParam(':first_name', $first_name);
        $req->bindParam(':last_name', $last_name);
        $req->bindParam(':dob', $dob);
        try {
            if($emailinput && $emailinput !=""){
                 $filteredEmail = filter_input(INPUT_POST,'email', FILTER_SANITIZE_SPECIAL_CHARS);
            }    
            if($passwordinput && $passwordinput !=""){
                 $filteredPassword = filter_input(INPUT_POST,'password', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if($firstnameinput && $firstnameinput !=""){
                 $filteredfirst_name = filter_input(INPUT_POST,'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
            }    
            if($lastnameinput && $lastnameinput !=""){
                 $filteredlast_name = filter_input(INPUT_POST,'last_name', FILTER_SANITIZE_SPECIAL_CHARS);               
            }   
            $email = $filteredEmail;
            $password = $filteredPassword;
            $first_name = $filteredfirst_name;
            $last_name = $filteredlast_name;
            $dob = $dobinput; 
            $req->execute();
            
            // Get new user after creating it
            $new_user_req = $db->prepare("SELECT u.ID, u.first_name, u.last_name, t.type FROM blog_user as u INNER JOIN user_type as t ON u.user_type_id=t.ID WHERE u.email= :email");
            $new_user_req->bindParam(':email', $email);
            $new_user_req->execute();
            $user=$new_user_req->fetch();
            return new User($user['ID'],$user['first_name'], $user['last_name'], $user['type']);

        } catch (PDOException $e) {
            die("Could not signup ....." . $e->getMessage());
        }
        unset($stmt);
    }
    
            
//    { 
//        //replace with a more meaningful exception 
//        throw new Exception('A real exception should go here'); 
//    } 
//    } 
}
