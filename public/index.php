<?php require '../vendor/autoload.php'; ?>


<?php $uri = $_SERVER['REQUEST_URI'];

$router = new AltoRouter();

$router->map('POST', '/', function () {
    echo 'Salut';
});


if (isset($_POST['submit'])) {
    $dir=[];
    echo "<pre>";
    $dir=getDirRec($_POST['dir']);
    echo "</pre>";
    foreach ($dir as $key => $value) {
        echo "<pre>";
        echo "$value\n";
        print_r(getDirContentsRec($value));
        echo "</pre>";
    }

} else {
    require '../elements/header.php';
    if ($uri === '/') {
        require '../templates/home.php';
    } else {
        require '../templates/error404.php';
    }
    require '../elements/footer.php';
}

function getDirContentsRec($from){

    $files = array();
    $dir = array();
    if (is_dir($from)) {
        $dh = opendir($from);

        while (($file = readdir($dh)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $path = $from . '/' . $file;
            if (is_dir($path)) {
                $files = array_merge($files, getDirContentsRec($path));
            } else {
                $files[] = $path;
            }
        }

        closedir($dh);
    } else {
        $files[] = $from;
    }

    return $files;
}

function getDirRec($from){
    $files = array();
    $dir = array();
    if (is_dir($from)) {
        $dh = opendir($from);

        while (($file = readdir($dh)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $path = $from . '/' . $file;
            if (is_dir($path)) {
                array_push($dir,$path);
                $files = array_merge($files, getDirContentsRec($path));
            } else {
                $files[] = $path;
            }
        }

        closedir($dh);
    } else {
        array_push($dir,$from);
        $files[] = $from;
    }

    return $dir;
}








?>