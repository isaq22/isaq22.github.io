<!DOCTYPE html>
<html>
<head>
<?php if (isset($_POST["comment"])) {
    echo "<meta http-equiv='refresh' content='0; url=${_SERVER["PHP_SELF"]}'/>";
} ?>
    <title>isaqchan</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<hr />
<h1 style="text-align: center;">isaqchan</h1>

<hr />
<p style="text-align:center">&nbsp;&nbsp;<a href="http://tabulaymwtljjmqxpvcckvaqomjurldtgqwxznxume2sicm6wq6ztyad.onion/" target="_blank"><img alt="" src="https://cdn.discordapp.com/attachments/1080856527637848065/1091422826638102668/final.gif" style="height:25px; width:130px" /></a>&nbsp;&nbsp;<a href="https://isaqathe.org" target="_blank"><img alt="" src="https://cdn.discordapp.com/attachments/1080856527637848065/1091431724233281586/mygif.gif" style="height:25px; width:130px" /></a>&nbsp;</p>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" required>
    <input type="text" name="comment" placeholder="enter a comment" required>
    <input type="text" name="username" placeholder="enter a username">
    <input type="submit" name="submit" value="upload">
</form>
<?php
//check if data.txt file exists create it if it doesn't
$file = "data.txt";
if (!file_exists($file)) {
    ($handle = fopen($file, "w")) or die("Cannot create file: " . $file);
    fclose($handle);
}

// function to generate a unique hash for each uploaded image
function generate_hash($file_path)
{
    return md5_file($file_path);
}

//read data from text file
$data = file("data.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//store hashes of all uploaded images in a separate file
$hash_file = "hashes.txt";
if (!file_exists($hash_file)) {
    ($handle = fopen($hash_file, "w")) or
        die("Cannot create file: " . $hash_file);
    fclose($handle);
}
$hashes = file($hash_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//check if form was submitted
if (isset($_POST["submit"])) {
    //get the uploaded image and move it to the images directory
    $image_name = $_FILES["image"]["name"];
    $image_tmp_name = $_FILES["image"]["tmp_name"];
    move_uploaded_file($image_tmp_name, "images/" . $image_name);

    //get the comment timestamp and username and sanitize them
    $comment = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);
    $timestamp = date("F j, Y, g:i a");
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "anon!";

    //generate a hash for the uploaded image and check if it already exists
    $image_hash = generate_hash("images/" . $image_name);
    if (!in_array($image_hash, $hashes)) {
        //add the image and comment to the data.txt file
        $data_line =
            $image_name . ";" . $comment . ";" . $timestamp . ";" . $username;
        file_put_contents("data.txt", PHP_EOL . $data_line, FILE_APPEND);

        //Add the image hash to the separate "hashes.txt" file
        file_put_contents($hash_file, PHP_EOL . $image_hash, FILE_APPEND);
        exit();
    } else {
        echo "<p></p>";
    }
}
//display images and comments in reverse order (newest first)
foreach (array_reverse($data) as $line) {
    $values = explode(";", $line);
    $image_file = trim($values[0]);
    $comment = trim($values[1]);
    $timestamp = trim($values[2]);
    $username = trim($values[3]);

    //get the full URL of the image
    $image_url =
        "http://" .
        $_SERVER["HTTP_HOST"] .
        dirname($_SERVER["PHP_SELF"]) .
        "/images/" .
        $image_file;

    echo '<div class="post">';
    echo "<blockquote>";
    if (
        strpos($image_file, ".mp4") !== false ||
        strpos($image_file, ".webm") !== false
    ) {
        // if the uploaded file is a video, display it as a video element
        echo '<div class="video-container">';
        echo '<video controls width="200" height="200">';
        echo '<source src="' . $image_url . '" type="video/mp4">';
        echo '<source src="' . $image_url . '" type="video/webm">';
        echo "</video>";
        echo "</div>";
    } else {
        // if the uploaded file is an image, display it as an image element
        echo '<div class="image-container">';
        echo '<a href="images/' .
            $image_file .
            '" target="_blank"><img src="' .
            $image_url .
            '" alt="' .
            $comment .
            '" width="200" height="200"></a>';
        echo "</div>";
    }
    echo '<div class="comment">';
    echo '<span class="username" style="color:cyan;">' .
        $username .
        "!</span> " .
        $comment;
    echo '<div class="timestamp">' . $timestamp . "</div>";
    echo '<div class="original-link"><a href="images/' .
        $image_file .
        '" target="_blank">Open original</a></div>';
    echo "</div>";
    echo "</blockquote>";
    echo "</div>";
}
// define an array of allowed file extensions
$allowed_extensions = ["jpg", "jpeg", "png", "gif", "mp4", "webm"];

if (isset($_POST["submit"])) {
    //get the uploaded image and move it to the "images" directory
    $image_name = $_FILES["image"]["name"];
    $image_tmp_name = $_FILES["image"]["tmp_name"];
    $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    // check if the uploaded file has a valid image extension
    if (!in_array($image_extension, $allowed_extensions)) {
        echo "illegal extension!";
        exit();
    }
}
?>
<style>/* CSS code */
.post blockquote a *,
.post blockquote a video {
    max-width: 200px;
    height: 200px;
    object-fit: cover;
    border: 1px solid #fff;
}
img {
    width: 270px;
    border: 1px solid white;
}
</style>
<style type="text/css">body {
            width: 800px;
            height: 600px;
            margin: 0 auto;
        }</style>
        
<script>img[src="https://cdn.000webhost.com/000webhost/logo/footer-powered-by-000webhost-white2.png"]{ display: none; }</script>
<style>img[alt="www.000webhost.com"]{display:none};</style>
</body>
</html>
