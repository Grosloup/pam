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

if(false !== $rawDatas = file_get_contents("php://input")  && null !== $datas = json_decode($rawDatas, true)){

    $dir = "";

    if($datas["abspath"]["value"] != ""){
        if($datas["dirname"]["value"] != ""){
            $dir = ltrim($datas["abspath"]["value"], "/") . "/" . ltrim($datas["dirname"]["value"], "/") . "/";
        } else {
            $dir = ltrim($datas["abspath"]["value"], "/") . "/" . ltrim($datas["name"]["value"], "/") . "/";
        }
    } else {
        if($datas["dirname"]["value"] != ""){
            $dir .= "H:/virtualshosts/" . ltrim($datas["dirname"]["value"], "/") . "/";
        } else {
            $dir .= "H:/virtualshosts/" . ltrim($datas["name"]["value"], "/") . "/";
        }
    }

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
    $dirListErrors = [];
    if($datas["dirlist"]["value"] != ""){
        $dirs = explode(";", $datas["dirlist"]["value"]);

        foreach($dirs as $d){
            $d = rtrim($d, "/");
            if(false === mkdir($dir . "/" . $d)){
                $dirListErrors[] = "'" . $d . "' n'a pas pu être créé.";
            }
        }
    }

    if($datas["git"]["value"] == true){
         $origin = __DIR__;
        chdir($dir);
        passthru("git init");
        chdir($origin);
    }

    $dbErrors = [];
    if($datas["dbname"]["value"] != ""){
        try{
            $pdo = new PDO("mysql:host=localhost;port=3306;", "root", "root");
        } catch (PDOException $e){
            $dbErrors[] = $e->getMessage();
        }

        if(false === $pdo->exec("CREATE DATABASE IF NOT  EXISTS `" . $datas["dbname"]["value"] . "`;")){
            $dbErrors[] ="un problème est survenu lors de la création de la base de données";
        }
    }

    $hosts = "C:/Windows/System32/drivers/etc/hosts";
    $hostsErrors = [];
    if(chmod($hosts, 0777)){
        $str = "127.0.0.1    " . $datas["name"]["value"];
        $content = file_get_contents($hosts);
        file_put_contents($hosts, $str, FILE_APPEND);
        chmod($hosts, 0755);
    } else {
        $hostsErrors[] = "Le fichier hosts n'a pas pu être modifié.";
    }





} else {
    header("HTTP/1.1 500 Internal Server Error");
    die();
}
