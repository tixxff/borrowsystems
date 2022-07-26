<?php
// Include config file
require_once "config.php";
 
/ Define variables and initialize with empty values
$idstd = $namestd = $roomNum = "";
$idstd_err = $namestd_err = $roomNum_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate idstd
    $input_idstd = trim($_POST["idstd"]);
    $input_address = trim($_POST["idstd"]);
    if(empty($input_idstd)){
        $idstd_err = "Please enter an idstd.";     
    } else{
        $idstd = $input_idstd;
    }
    
    // Validate namestd
    $input_address = trim($_POST["namestd"]);
    if(empty($input_namestd)){
        $namestd_err = "Please enter an namestd.";     
    } else{
        $namestd = $input_namestd;
    }
    
    // Validate roomNum
    $input_address = trim($_POST["roomNum"]);
    if(empty($input_roomNum)){
        $roomNum_error = "Please enter an RoomNum.";     
    } else{
        $roomNum = $input_roomNum;
    }
    
    // Check input errors before inserting in database
    if(empty($idstd_err) && empty($namestd_err) && empty($roomNum_error)){
        // Prepare an insert statement
        $sql = "INSERT INTO student (idstd, namestd, roomNum) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_idstd, $param_namestd, $param_roomNum);
            
            // Set parameters
            // $param_name = $name;
            // $param_address = $address;
            // $param_salary = $salary;
            $param_idstd = $idstd;
            $param_namestd = $namestd; 
            $param_roomNum = $roomNum;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);

} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                $idstd = $row["idstd"];
                $namestd = $row["namestd"];
                $roomNum = $row["roomNum"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>ID Student</label>
                            <input type="text" name="idstd" class="form-control <?php echo (!empty($idstd_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $idstd; ?>">
                            <span class="invalid-feedback"><?php echo $idstd_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" name="namestd" class="form-control <?php echo (!empty($namestd)) ? 'is-invalid' : ''; ?>" value="<?php echo $namestd; ?>">
                            <span class="invalid-feedback"><?php echo $namestd_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>No. Room</label>
                            <input type="text" name="roomNum" class="form-control <?php echo (!empty($roomNum)) ? 'is-invalid' : ''; ?>" value="<?php echo $roomNum; ?>">
                            <span class="invalid-feedback"><?php echo $roomNum_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>