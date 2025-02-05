<?php
// Initialize database connection
$con = new mysqli("localhost", "user", "password", "database");

if ($con->connect_error) {
   // die("Connection failed: " . $con->connect_error);
}

// Function to execute allowed commands securely
function execSafeCommand($cmd) {
    $allowedCommands = ['ls', 'whoami'];
    if (in_array($cmd, $allowedCommands)) {
        exec(escapeshellcmd($cmd), $output);
        foreach ($output as $line) {
            echo htmlspecialchars($line) . "<br>";
        }
    } else {
        echo "Command not allowed<br>";
    }
}

// Function to search files in the database
function searchFiles($con, $search) {
    if (!$con->connect_error) {
        $stmt = $con->prepare("SELECT * FROM files WHERE name LIKE ?");
        if ($stmt) {
            $searchParam = "%$search%";
            $stmt->bind_param("s", $searchParam);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo htmlspecialchars($row['name']) . "<br>";
                }
            } else {
                // Log error, continue with script
                error_log("Execute failed: " . $stmt->error);
            }
        } else {
            // Log error, continue with script
            error_log("Prepare failed: " . $con->error);
        }
    }
}

function ls($dir) {
    $folder = opendir($dir);
    while ($file = readdir($folder)) {
        if ($file != "." && $file != "..") {
            echo htmlspecialchars($file) . "<br>";
        }
    }
    closedir($folder);
}

function handle_file_upload($dir) {
    if (isset($_POST['submit'])) {
        $filename = basename($_FILES['file']['name']);
        $target = rtrim($dir, '/') . '/' . $filename;

        // Validate the file upload
        $fileType = pathinfo($target, PATHINFO_EXTENSION);
        if (in_array($fileType, ['jpg', 'png', 'txt'])) { // Allow only certain file types
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target)) {
                echo 'File ' . htmlspecialchars($filename) . ' uploaded successfully.';
            } else {
                echo 'File upload failed.';
            }
        } else {
            echo 'Invalid file type.';
        }
    }
}

// Handling command execution
$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';
if (!empty($cmd)) {
    execSafeCommand($cmd);
}

// Handling file search
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sagar SSW</title>
</head>
<body>
    <h1>SSW - Sagar's Secure Webshell</h1>
    <pre><?=htmlspecialchars(getcwd())?></pre>
    <pre><?php if (isset($_POST['submit'])) { handle_file_upload(getcwd()); } ?></pre>

    <!-- Search functionality -->
    <?php if (!empty($search)): ?>
    <div>You searched for: <?=htmlspecialchars($search)?></div>
    <div><?php searchFiles($con, $search); ?></div>
    <?php endif; ?>

    <!-- Directory listing -->
    <?php ls(getcwd()); ?>
    <br>
    <form method="GET">
        <input type="text" placeholder="Search files..." name="search">
        <input type="submit" value="Search">
    </form>

    <form method="POST" enctype="multipart/form-data">
        <input type="file" id="file" name="file">
        <input type="submit" id="submit" name="submit" value="Upload file">
    </form>

    <form>
        <input type="text" placeholder="Enter shell commands..." id="cmd" name="cmd" autofocus><br>
    </form>

</body>
</html>
