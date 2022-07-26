<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$namestd = $roomNum = "";
$namestd_err = $roomNum_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate address
    $input_namestd = trim($_POST["namestd"]);
    if(empty($input_namestd)){
        $namestd_err = "Please enter an namestd.";     
    } else{
        $namestd = $input_namestd;
    }
    
    // Validate salary
    $input_roomNum = trim($_POST["roomNum"]);
    if(empty($input_roomNum)){
        $roomNum_error = "Please enter an RoomNum.";     
    } else{
        $roomNum = $input_roomNum;
    }
    
    // Check input errors before inserting in database
    if(empty($namestd_err) && empty($roomNum_error)){
        // Prepare an insert statement
        $sql = "INSERT INTO student (namestd, roomNum) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_namestd, $param_roomNum);
            
            // Set parameters
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Student</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>