<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $uploadDir = 'public/uploads/';
    $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);

    /* File type check */
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $authorizedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if ((!in_array($extension, $authorizedExtensions))) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg, Jpeg, Png, Gif ou Webp !';
    }

    /* Size check */
    $maxFileSize = 1000000;
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
        $errors[] = "Votre fichier doit faire moins de 1Mo !";
    }

    /* Exists check */
    if (file_exists('public/uploads/' . $_FILES['avatar']['name'])) {
        $errors[] = "Votre fichier existe déjà.";
    }

    /* Uploader name check */
    if (empty($_POST['name'])) {
        $errors[] = "Vous devez rentrer un nom !";
    }

    /* Uploader age check */
    if ($_POST['age'] <= 0) {
        $errors[] = "L'âge est invalide !";
    }

    /* Upload function */
    if (empty($errors)) {
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profile pic uploader</title>
</head>

<body>
    <div class="App">
        <?php if ($_SERVER['REQUEST_METHOD'] === "GET") {
            echo '<h1>Vos photos</h1>' . PHP_EOL;
        } ?>
        <?php if ($_SERVER['REQUEST_METHOD'] === "POST" && empty($errors)) {
            echo '<h1>Photos de ' . $_POST['name'] . ', ' . $_POST['age'] . ' ans.</h1>' . PHP_EOL;
        } ?>
        <div class="containerTop">
            <?php
            function lister_images($repertoire)
            {
                if (is_dir($repertoire)) {
                    if ($iteration = opendir($repertoire)) {
                        while (($fichier = readdir($iteration)) !== false) {
                            if ($fichier != "." && $fichier != "..") {
                                $fichier_info = finfo_open(FILEINFO_MIME_TYPE);
                                $mime_type = finfo_file($fichier_info, $repertoire . $fichier);
                                if (strpos($mime_type, 'image/') === 0) {
                                    echo '<div class="containerBot"> <img class="useruploadimg" src="' . $repertoire . $fichier . '" alt=""> <a href="imgDelete.php?img=' . $fichier . '">Delete</a> </div>';
                                }
                            }
                        }
                        closedir($iteration);
                    }
                }
            }
            lister_images("public/uploads/");
            ?>
        </div>
        <div class="errors">
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($errors)) : ?>
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach ?>
            <?php endif ?>
        </div>
        <form class="imgForm" action="" method="post" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="John Doe" />
            <label for="age">Age</label>
            <input type="number" name="age" id="age" />
            <label for="imageUpload" class="uploadStyle">Choisir un fichier<input class="invis" type="file" name="avatar" id="imageUpload" /></label>
            <button name="send">Send</button>
        </form>
    </div>
</body>

</html>