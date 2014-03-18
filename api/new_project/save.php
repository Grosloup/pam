<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 17/03/14
 * Time: 23:46
 */

if(!isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) != "xmlhttprequest"){
    die("Vous n'avez rien à faire ici");
}

$rawDatas =  file_get_contents("php://input");
$datas = json_decode($rawDatas, true);

if(false !== $rawDatas  && null !== $datas ){
    $date = new DateTime("now", new DateTimeZone("Europe/Paris"));
    $dateStr = $date->format("d/m/Y H:i:s");
    $dir = "";

    if($datas["abspath"]["value"] != ""){
        $absPath = str_replace("\\","/", $datas["abspath"]["value"]);
        if($datas["dirname"]["value"] != ""){
            $dir = ltrim($absPath, "/") . "/" . ltrim($datas["dirname"]["value"], "/") . "/";
        } else {
            $dir = ltrim($absPath, "/") . "/" . ltrim($datas["name"]["value"], "/") . "/";
        }
    } else {
        if($datas["dirname"]["value"] != ""){
            $dir .= "H:/virtualshosts/" . ltrim($datas["dirname"]["value"], "/") . "/";
        } else {
            $dir .= "H:/virtualshosts/" . ltrim($datas["name"]["value"], "/") . "/";
        }
    }
    $baseDir = $dir;

    if($datas["reldocroot"]["value"]){
        $dir .= ltrim($datas["reldocroot"]["value"], "/");
    }

    $dir = rtrim($dir, "/");

    if(is_dir($dir) ){
        $diff = array_diff($dir, [".",".."]);
        if($diff != null){
            header("HTTP/1.1 409 Conflict");
            header("Content-Type: text/html");
            die("Le réperoire à créer existe déjà et n'est pas vide.");
        }
    } else {
        $isDirCreated = mkdir($dir, 0777, true);
        if(!$isDirCreated){
            header("HTTP/1.1 500 Internal Server Error");
            die();
        }
    }
    $dirListErrors = "";
    if($datas["dirlist"]["value"] != ""){
        $dirs = explode(";", $datas["dirlist"]["value"]);

        foreach($dirs as $d){
            $d = rtrim($d, "/");
            if(false === mkdir($dir . "/" . $d, 0777, true)){
                $dirListErrors .= "'" . $d . "' n'a pas pu être créé." . PHP_EOL;
            }
        }
    }

    if($datas["git"]["value"] == true){
         $origin = __DIR__;
        chdir($baseDir);
        $tmp = exec("git init");
        chdir($origin);
    }

    $dbErrors = "";
    if($datas["dbname"]["value"] != ""){
        try{
            $pdo = new PDO("mysql:host=localhost;port=3306;", "root", "root");
        } catch (PDOException $e){
            $dbErrors .= $e->getMessage() . PHP_EOL;
        }

        if(false === $pdo->exec("CREATE DATABASE IF NOT  EXISTS `" . $datas["dbname"]["value"] . "`;")){
            $dbErrors .="un problème est survenu lors de la création de la base de données" . PHP_EOL;
        }
    }

    $hosts = "C:/Windows/System32/drivers/etc/hosts";
    $hostsErrors = "";
    if(chmod($hosts, 0777)){
        $str = "#################################### crée le $dateStr ##". PHP_EOL;
        $str .= "127.0.0.1    " . $datas["name"]["value"] . PHP_EOL;
        $str .= "########################################################". PHP_EOL;
        $content = file_get_contents($hosts);
        if( false === file_put_contents($hosts, $str, FILE_APPEND)){
            $hostsErrors .= "Le fichier hosts n'a pas pu être modifié." . PHP_EOL;
        }
        chmod($hosts, 0755);
    } else {
        $hostsErrors .= "Le fichier hosts n'a pas pu être modifié. Permission refusée." . PHP_EOL;
    }

    $vhosts = "H:/Apache24/conf/extra/httpd-vhosts.conf";
    $newVHost = PHP_EOL;
    $newVHost .= <<<EOD
#################################### crée le $dateStr ##
<VirtualHost *:80>
    ServerName {$datas['name']['value']}
    DocumentRoot "{$dir}"
    <Directory "{$dir}" >
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
    </Directory>
</VirtualHost>
#########################################################
EOD;
    $newVHost .= PHP_EOL.PHP_EOL;
    $vhostError = "";
    if(false === file_put_contents($vhosts, $newVHost, FILE_APPEND)){
        $vhostError = "Le fichier httpd-vhosts.conf n'a pas été modifié" . PHP_EOL;
    }



    $errors = $dirListErrors . $dbErrors . $hostsErrors . $vhostError;

    $projectsArr = json_decode(file_get_contents("H:/Apache24/htdocs/pam/datas/projects.json"), true);
    $projectsArr[$datas['name']['value']] = [
        "name"=>$datas['name']['value'],
        "dir"=>$baseDir,
        "document_root"=>$dir,
        "description"=>$datas['desc']['value'],
    ];

    if(false === file_put_contents("H:/Apache24/htdocs/pam/datas/projects.json",json_encode($projectsArr, JSON_PRETTY_PRINT))){
        $errors .= "Le fichier project.json n'a pas été modifié";
    }

    header("Content-Type: application/json");
    echo json_encode(["errors"=>$errors]);

} else {
    header("HTTP/1.1 500 Internal Server Error");
    die();
}
