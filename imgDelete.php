<?php
$folder_path = "public/uploads";

if (isset($_GET['img'])) {
    if ($_GET['img'] !== "all") {
        $files = glob($folder_path . '/' . $_GET['img']);
    } else {
        $files = glob($folder_path . '/*');
    }
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
}

header("location: form.php");
