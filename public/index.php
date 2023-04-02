<?php require '../vendor/autoload.php'; ?>


<?php $uri = $_SERVER['REQUEST_URI'];

//tail -f error.log


if (isset($_GET['path_'])) {

    $path_to_file = "/home/damien/" .$_GET['path_'];

    // $path_to_file = str_replace("%2F","/",$path_to_file);

    // $path_to_file = str_replace("?path_=","/",$path_to_file);

    if (is_dir($path_to_file) || is_file($path_to_file)) {
        if (is_file($path_to_file)) {
            require '../elements/header.php';
            $content = displayFileContent($path_to_file);
            echo '<div class="container py-2">
            <div class="row m-0">
                <div class="col-xl-12">';
            echo "<pre class='code'><code class='code'>$content</code></pre>";
            echo '</div>
            </div>
            </div>';
            require '../elements/footer.php';
        }

        if (is_dir($path_to_file)) {
            require '../elements/header.php';
            echo '<div class="container py-2">
            <div class="row m-0">
                <div class="col-md-5">';
            // $arrRed = explode("/", $path_to_file);

        
            $file = listFolderFiles($path_to_file);

            // dump($file);

            echo '</div>
            </div>
            </div>';
            require '../elements/footer.php';
        }
    } else {

        require '../elements/header.php';
        echo '<div class="container py-2">
        <div class="row m-0">
            <div class="col-md-5">';
        echo $_GET['path_'];
        echo $path_to_file;

        echo '</div>
        </div>
        </div>';
        require '../templates/error404.php';
        require '../elements/footer.php';
    }

}

if (isset($_POST['submit'])) {
    // $BASE_PATH = $_POST['dir'];
    // $dir = [];
    // $dir = getDirContentsRec($_POST['dir'], $BASE_PATH);
    // echo "$BASE_PATH\n";

    require '../elements/header.php';

    // renderView($dir,$BASE_PATH);
    $arr = explode("/", $_POST["dir"]);

    foreach ($arr as $key => $value) {
        $BASE_PATH = $value;
    }

    $dir_path = str_replace($BASE_PATH, "", $_POST["dir"]);


    // echo $BASE_PATH;


    // define('SITE_URL', $BASE_PATH);
    echo '<div class="container py-2">
    <div class="row m-0">
        <div class="col-md-5">';
    $result = listFolderFiles($_POST["dir"], $BASE_PATH);
    echo '</div>
    </div>
    </div>';


    
    
    
    require '../elements/footer.php';
} else {
    if ($uri === '/') {
        require '../elements/header.php';
        echo '<div class="container py-2">
        <div class="row m-0">
            <div class="col-md-5">';
        require '../templates/home.php';
        echo '</div>
        </div>
        </div>';
        require '../elements/footer.php';
    }
}


function listFolderFiles($dir, $base="")
{

    $allowed = array('php', 'html', 'css', 'js', 'txt','pptx','png','jpg','jpeg','md','scss','sh','bash');
    $fileFolderList = scandir($dir);
    echo '<ul class="drop" id="menu">';
    foreach ($fileFolderList as $fileFolder) {
        if ($fileFolder != '.' && $fileFolder != '..') {
            if (!is_dir($dir . '/' . $fileFolder)) {
                $ext = pathinfo($fileFolder, PATHINFO_EXTENSION);
                if (in_array($ext, $allowed)) {
                    if ($base!="") {                        
                        $arr = explode($base, $dir);
                        echo '<li><form method="GET" action="./"> <input type="hidden" name="path_" value="'.$base . $arr[1] . '/' . $fileFolder . '"><img src="./png/unknown_file.png" class="unknown_file" alt="folder image"><button type="submit" name"open" class="btn-nostyle">' . $fileFolder . '</button></form>';
                    }else{
                        echo '<li><form method="GET" action="./"> <input type="hidden" name="path_" value="'. str_replace("/home/damien/","",$dir) .  '/' . $fileFolder . '"><img src="./png/unknown_file.png" class="unknown_file" alt="folder image"><button type="submit" name"open" class="btn-nostyle">' . $fileFolder . '</button></form>';
                    }
                }
            } else {
                if ($base!="") {
                    $arr2 = explode($base, $dir);
                    echo '<li><form method="GET" action="./"> <input type="hidden" name="path_" value="'.$base . $arr2[1] . '/' . $fileFolder . '"><img src="./png/folder.png" class="folder" alt="folder image"><button type="submit" name"open"  class="btn-nostyle">' . $fileFolder . '</button></form>';
                }else{
                    echo '<li><form method="GET" action="./"> <input type="hidden" name="path_" value="'. str_replace("/home/damien/","",$dir) . '/' . $fileFolder . '"><img src="./png/folder.png" class="folder" alt="folder image"><button type="submit" name"open"  class="btn-nostyle">' . $fileFolder . '</button></form>';
                }
            }
            if (is_dir($dir . '/' . $fileFolder)&&$base!="") {
                listFolderFiles($dir . '/' . $fileFolder, $base);
            }
            echo '</li>';
        }
    }
    echo '</ul>';

    return $fileFolderList;
}

function displayFileContent($path_to_file){
    return htmlspecialchars(file_get_contents($path_to_file));
}





?>