<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $count = "";
$name_err = $count_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate address
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter an name.";     
    } else{
        $name = $input_name;
    }
    
    // Validate salary
    $input_count = trim($_POST["count"]);
    if(empty($input_count)){
        $count_error = "Please enter an count.";     
    } else{
        $count = $input_count;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($count_error)){
        // Prepare an insert statement
        $sql = "INSERT INTO tools (name, count) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_name, $param_count);
            
            // Set parameters
            $param_name = $name;
            $param_count = $count;
            
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
                    <h2 class="mt-5">Add Tools</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Count</label>
                            <input type="text" name="count" class="form-control <?php echo (!empty($count)) ? 'is-invalid' : ''; ?>" value="<?php echo $count; ?>">
                            <span class="invalid-feedback"><?php echo $count_err;?></span>
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