<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 16/03/14
 * Time: 23:07
 */

?>
<!doctype html>
<html lang="fr" ng-app="MainApp">
<head>
    <meta charset="UTF-8">
    <title>Nouveau Projet | Php - Apache - Mysql</title>

    <link rel="stylesheet" href="./css/master.css"/>
    <link rel="stylesheet" href="./css/font-awesome.min.css"/>

    <script src="./js/vendor/angular/angular.min.js"></script>

</head>
<body>

<div id="topbar">
    PHP - Apache - MySQL
</div>
<div id="sidebar">

</div>

<div id="main">


</div>


<script src="./js/apps/newProject.js"></script>

<script>
    function setHeight(id){
        var windowHeight = window.innerHeight;
        var bodyHeight = document.body.offsetHeight;
        var element = document.querySelector(id);
        if(bodyHeight > windowHeight){
            element.style.height = bodyHeight + "px";
        } else {
            element.style.height = windowHeight + "px";
        }
    }

    document.addEventListener("DOMContentLoaded", function(){}, false);

    window.addEventListener("load", function(){
        var id = "#sidebar";
        setHeight(id);

        window.onresize = function(){
            setHeight(id);
        };
    }, false);
</script>
</body>
</html>
