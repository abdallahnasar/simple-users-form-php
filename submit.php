<?php
session_start();

$errors = [];
unset($_SESSION['errors']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    die('Bad Request');

}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die("CSRF token validation failed!");
}


$firstName = trim($_POST['first_name']);

if(!validateName($firstName))
{
    $errors ['first_name'] = 'Error validating firstName';
}
$lastName = trim($_POST['last_name']);

if(!validateName($lastName))
{
    $errors ['last_name'] = 'Error validating lastName';
}

if (empty($_FILES['image']['tmp_name'])) {
    $errors ['image'] = 'empty image uploaded';
}
// Validate and process the uploaded image
$uploadedImage = $_FILES['image'];
$imageName = $uploadedImage['name'];
$imageTmpPath = $uploadedImage['tmp_name'];

// Check if file is an image
$check = getimagesize($uploadedImage['tmp_name']);
if ($check === false) {
    $errors ['image'] = 'File is not an image';
}


// Check file size
if ($uploadedImage['size'] > 2 * 1024 * 1024) {
    $errors ['image'] = 'Image size exceeds 2MB';
}

if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
}

$filename = uniqid('image_') . '.' . pathinfo($uploadedImage['name'], PATHINFO_EXTENSION);

$targetPath = 'uploads/' . $imageName;
move_uploaded_file($imageTmpPath, $targetPath);


$dbHost = '127.0.0.1';
$dbName = 'test';
$dbUser = 'root';
$dbPass = '';

try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $db->prepare("INSERT INTO users (first_name, last_name, image) VALUES (?, ?, ?)");
    $query->execute([$firstName, $lastName, $filename]);
    $db = null;

    echo 'added successfully';
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

unset($_SESSION['csrf_token']);



function validateName(string $string) : bool
{
    if (empty($string)) {
        return false;
    }

    $sanitizedString = htmlspecialchars($string);
    // Validate the string format (alphanumeric characters only)
    if (!preg_match('/^[a-zA-Z0-9]+$/', $sanitizedString)) {
        return false;
    }
    return true;
}
