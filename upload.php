<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; 

use Aws\S3\S3Client; 
use Aws\Exception\AwsException; 

// Instantiate S3 client 
$s3Client = new S3Client([ 
    'version' => 'latest', 
    'region'  => 'us-east-1', 
    'credentials' => [ 
        'key'    => 'YOUR_ACCESS_KEY', 
        'secret' => 'YOUR_SECRET_KEY' 
    ] 
]); 

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if (isset($_FILES["anyfile"]) && $_FILES["anyfile"]["error"] == 0) { 
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"]; 
        $filename = basename($_FILES["anyfile"]["name"]); 
        $filetype = $_FILES["anyfile"]["type"]; 
        $filesize = $_FILES["anyfile"]["size"]; 

        $ext = pathinfo($filename, PATHINFO_EXTENSION); 
        if (!array_key_exists($ext, $allowed)) {
            die("Error: Invalid file format.");
        } 

        if ($filesize > 10 * 1024 * 1024) { 
            die("Error: File too large.");
        } 

        if (!in_array($filetype, $allowed)) {
            die("Error: File type mismatch.");
        }

        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetFile = $uploadDir . $filename;
        if (file_exists($targetFile)) {
            die("Error: File already exists.");
        } 

        if (!move_uploaded_file($_FILES["anyfile"]["tmp_name"], $targetFile)) { 
            die("Error: Upload failed.");
        } 

        $bucket = 'navanava'; 
        try { 
            $result = $s3Client->putObject([ 
                'Bucket' => $bucket, 
                'Key'    => $filename, 
                'Body'   => fopen($targetFile, 'rb'), 
                'ACL'    => 'public-read', 
            ]); 
            $urls3 = $result->get('ObjectURL'); 
            $cfurl = str_replace("https://navanava.s3.ap-south-1.amazonaws.com", "https://d1g04a21wg9rz.cloudfront.net", $urls3); 

            $conn = new mysqli("database-1.cx0iyacc8tzg.ap-south-1.rds.amazonaws.com", "root", "Pass1234", "facebook"); 
            if ($conn->connect_error) {
                die("DB connection failed: " . $conn->connect_error);
            }

            $name = $conn->real_escape_string($_POST["name"]);
            $stmt = $conn->prepare("INSERT INTO posts (name, s3url, cfurl) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $urls3, $cfurl);
            if ($stmt->execute()) {
                echo "✅ Image uploaded.<br>S3: $urls3 <br>CloudFront: $cfurl<br>";
            } else {
                echo "DB Insert Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } catch (AwsException $e) { 
            echo "AWS Error: " . $e->getMessage();
        } 
    } else { 
        echo "Upload error: " . $_FILES["anyfile"]["error"];
    } 
}
?>
