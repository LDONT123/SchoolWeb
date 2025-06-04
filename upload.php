<?php
header('Content-Type: application/json');

// Define the upload directory
$uploadDir = 'uploads/';

// Create the uploads directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$response = ['success' => false, 'error' => 'Unknown error occurred.'];

if (isset($_FILES['imageFile'])) {
    $file = $_FILES['imageFile'];

    // Check for errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];

        // Generate a unique name for the file to prevent overwriting
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $uniqueFileName = uniqid('img_', true) . '.' . $fileExtension;
        $destination = $uploadDir . $uniqueFileName;

        // Allowed image types
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedTypes)) {
            // Move the file to the uploads directory
            if (move_uploaded_file($fileTmpName, $destination)) {
                // Get the current server protocol (http or https)
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                // Get the host name
                $host = $_SERVER['HTTP_HOST'];
                // Construct the full URL
                $url = $protocol . $host . '/' . $destination;

                $response = ['success' => true, 'url' => $url];
            } else {
                $response = ['success' => false, 'error' => 'Failed to move uploaded file. Check permissions.'];
            }
        } else {
            $response = ['success' => false, 'error' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.'];
        }
    } elseif ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        $response = ['success' => false, 'error' => 'File is too large.'];
    } else {
        $response = ['success' => false, 'error' => 'File upload error: ' . $file['error']];
    }
} else {
    $response = ['success' => false, 'error' => 'No file uploaded.'];
}

echo json_encode($response);
?>
