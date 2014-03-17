<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 17/03/14
 * Time: 14:26
 */
defined("ROOT") || define("ROOT", realpath(dirname(dirname(__DIR__))));
if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest" && isset($_GET["pjt_name"]) ){

    $datas = json_decode(file_get_contents(ROOT."/datas/projects.json", true));
    $return = [];
    if(array_key_exists($_GET["pjt_name"], $datas)){
       $return["error"] = true;
    } else {
        $return["error"] = false;
    }

    header("Content-Type: application/json");
    echo json_encode($return);

    die();


} else {
    die("Vous n'avez rien à faire ici");
}
