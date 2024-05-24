<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "../includes/config.php";

$name = $description = $price = "";
$name_err = $description_err = $price_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";
    } else{
        $name = trim($_POST["name"]);
    }
    
    if(empty(trim($_POST["description"]))){
        $description_err = "Please enter a description.";     
    } else{
        $description = trim($_POST["description"]);
    }
    
    if(empty(trim($_POST["price"]))){
        $price_err = "Please enter a price.";     
    } else{
        $price = trim($_POST["price"]);
    }
    
    if(empty($name_err) && empty($description_err) && empty($price_err)){
        
        $sql = "INSERT INTO products_table (name, description, price) VALUES (?, ?, ?)";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssd", $param_name, $param_description, $param_price);
            
            $param_name = $name;
            $param_description = $description;
            $param_price = $price;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: dashboard.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
