<?php
// Define admin and company usernames (replace with your own usernames)
$adminUsernames = array("admin", "superadmin");
$companyUsernames = array("company1", "company2");

// Get user input from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Validate username and password
if (in_array($username, $adminUsernames)) {
    // User is an admin
    if ($password == "adminpassword") {
        // Password is correct, do further processing or redirect to admin page
        header('Location: home.html');
    exit();
    } else {
        // Password is incorrect
        echo "Invalid password";
    }
} elseif (in_array($username, $companyUsernames)) {
    // User is a company
    if ($password == "companypassword") {
        // Password is correct, do further processing or redirect to company page
        header('Location: company.html');
    exit();
    } else {
        // Password is incorrect
        header('Location: login.html');
    exit();
    }
} else {
    // Username not found
    header('Location: login.html');
    exit();
}
?>

