<?php 
    require_once("DBConnection.php");
?>

<?php

    function encryption ($password){
        $BlowFishFormate = "$2y$10$";
        $salt = generateSalt(22);
        $BlowFish_Plus_Salt = $BlowFishFormate . $salt;
        $Hash = crypt($password, $BlowFish_Plus_Salt);

        return $Hash;
    }

    function generateSalt($length){
        $uniqueRandomString = md5(uniqid(mt_rand(), true));
        $base64String = base64_encode($uniqueRandomString);
        $modifiedBase64String = str_replace('+','.',$base64String);
        $salt = substr($modifiedBase64String,0,$length);

        return $salt;
    }

    function passwordCheck($password, $existingHash){
        $Hash = crypt($password, $existingHash);
        if($Hash === $existingHash)
            return true;
        else
            return false;
    }

    function login($empID, $password, $conn){
            $query = mysqli_query($conn, "SELECT * FROM users WHERE empID='".$empID."'");
			$numrows = mysqli_num_rows($query);
			if($numrows !=0)
			{
				while($row = mysqli_fetch_assoc($query))
				{
					$dbusername=$row['empID'];
					$dbpassword=$row['password'];
					$type=$row['type'];
					$id=$row['id'];
				}
				if($empID == $dbusername && passwordCheck($password, $dbpassword))
				{
					
					$_SESSION['sess_user']=$empID;
					$_SESSION['sess_eid']=$id;
					//Redirect Browser
					if($type=="admin"){
						header("Location:admin.php");
					}
					else{
					header("Location:leaveAplicationForm.php");
					}
                    return true;
				}
			}
			else{
	 			//echo "Invalid Username or Password";
                 return false;
                 
	 		}
    }

    function signup($fullname,$empID,$email,$password,$phone,$repassword,$gender,$dept,$type,$conn){
        $hashedPassword = encryption($password);

        $query = mysqli_query($conn,"INSERT INTO users(fullname, empID, email, phone, password, gender, department, type) VALUES('$fullname','$empID','$email','$phone','$hashedPassword','$gender','$dept','$type')");
        $query1 = mysqli_query($conn,"SELECT id from users WHERE empID='".$empID."'");
        $eid = mysqli_fetch_assoc($query1);

        if($query){


            echo 'Registration successful!!';
            
            $_SESSION['sess_user'] = $empID;
            $_SESSION['sess_eid'] = $eid['id'];

            header("Location:leaveAplicationForm.php");
            exit;
        }
        else{
            echo "Query Error : " . "INSERT INTO users(fullname, empID, email, phone, password, gender, type) VALUES('$fullname','$empID','$email','$phone','$hashedPassword','$gender','$type')" . "<br>" . mysqli_error($conn);
            echo "<br>";
            echo "Query Error : " . "SELECT id from users WHERE name='".$empID."'" . "<br>" . mysqli_error($conn);
        }

    }

?>