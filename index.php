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
    <title>Php - Apache - Mysql</title>

    <link rel="stylesheet" href="./css/master.css"/>
    <link rel="stylesheet" href="./css/font-awesome.min.css"/>

    <script src="./js/vendor/angular/angular.min.js"></script>

</head>
<body>

<div id="topbar">
    PHP - Apache - MySQL
</div>
<div id="sidebar">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut cupiditate dicta doloribus ipsam itaque magni nihil
        pariatur totam voluptate voluptatum!</p>
</div>

<div id="main">

    <h4>Création d'un nouveau projet</h4>

    <div class="w30" ng-controller="MainCtrl">

        <div class="field">
            <label for="pjt_name">Nom du projet qui sera le nom de serveur ServerName</label>
            <input type="text" name="pjt_name" id="pjt_name" placeholder="Mon_nouveau_projet" ng-model="pjt.name.value" ng-blur="onNameBlur()"/>
            <div class="error-dialogue" data-target="#pjt_name" ng-show="pjt.name.error.is">
                <div class="squarre"></div>
                <div class="front">
                    {{pjt.name.error.message}}
                </div>
            </div>
        </div>

        <div class="field">
            <label for="pjt_dirname">Nom du répertoire qui contiendra le projet, si celui-ci est différent du nom du projet</label>
            <input type="text" name="pjt_dirname" id="pjt_dirname"/>
        </div>

        <div class="field">
            <label for="pjt_abs_path">Si le projet ne réside pas dans le répertoire des hôtes virtuels, indiquer ici son chemin absolu</label>
            <input type="text" name="pjt_abs_path" id="pjt_abs_path"/>
        </div>

        <div class="field">
            <label for="pjt_rel_doc_root">Chemin relatif du DocumentRoot par rapport au réperoire du projet</label>
            <input type="text" name="pjt_rel_doc_root" id="pjt_rel_doc_root" placeholder="/public"/>
        </div>

        <div class="field">
            <label for="pjt_dir_list">Liste des répertoires à créer, en chemin relatif par rapport au répertoire projet, séparés par des points virgules</label>
            <input type="text" name="pjt_dir_list" id="pjt_dir_list" placeholder="/css;/js/vendor;..." />
        </div>

        <div class="field">
            <label for="pjt_dbname">Nom de la base de données associée au projet</label>
            <input type="text" name="pjt_dbname" id="pjt_dbname" placeholder="ma_base_de_donnees" />
        </div>

        <div class="field checkbox">
            <label for="pjt_git"><input type="checkbox" name="pjt_git" id="pjt_git"/> Initialiser git ?</label>
        </div>

        <div class="field">
            <label for="pjt_desc">Associer une description au projet</label>
            <textarea name="pjt_desc" id="pjt_desc"></textarea>
        </div>

        <div class="field">
            <button class="btn btn-light">Créer</button> <button class="btn btn-light">Annuler</button>
        </div>

        <div class="field errors"></div>

    </div>

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

    function errorDialoguePosition(){

    }

    var id = "#sidebar";

    setHeight(id);

    window.onresize = function(){
        setHeight(id);
    };

    document.addEventListener("DOMContentLoaded", function(){

        var iptFocus = document.querySelector("#pjt_name");
        iptFocus.focus();

        var errDials = document.querySelectorAll(".error-dialogue");

        [].slice.call(errDials).forEach(function(el){
            var target = document.querySelector(el.dataset.target);
            var pos = target.getBoundingClientRect().bottom - target.parentNode.getBoundingClientRect().top;
            el.style.top = pos + 15 + "px";
        });

    }, false);

    window.addEventListener("load", function(){

        setHeight(id);

        window.onresize = function(){
            setHeight(id);
        };



    }, false);
</script>
</body>
</html>
