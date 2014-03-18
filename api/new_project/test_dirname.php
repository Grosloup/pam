<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 17/03/14
 * Time: 17:38
 */
defined("ROOT") || define("ROOT", realpath(dirname(dirname(__DIR__))));
if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest" && isset($_GET["pjt_dirname"]) ){

    $return = [];
    if(isset($_GET["pjt_abs_path"]) && $_GET["pjt_abs_path"] != ""){
        $return["error"] = false;
    } else {
        $dirname = "H:/virtualhosts/".$_GET["pjt_dirname"];
        if(is_dir($dirname)){
            $return["error"] = true;
        } else {
            $return["error"] = false;
        }
    }

    header("Content-Type: application/json");
    echo json_encode($return);
    die();

} else {
    die("Vous n'avez rien à faire ici");
}
