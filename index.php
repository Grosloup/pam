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

</div>

<div id="main">

    <h4>Création d'un nouveau projet</h4>

    <div id="new-project-form" ng-controller="MainCtrl">
        <div class="field error" ng-bind="appError" ng-show=" appError != '' "></div>

        <div class="field">
            <label for="pjt_name">Nom du projet qui sera le nom de serveur ServerName</label>
            <input type="text" name="pjt_name" id="pjt_name" placeholder="Mon_nouveau_projet" ng-model="pjt.name.value" ng-blur="onNameBlur()" ng-focus="pjt.name.error.is = false"/>
            <div class="error-dialogue" data-target="#pjt_name" ng-show="pjt.name.error.is" ng-cloak="">
                <div class="squarre"></div>
                <div class="front">
                    {{pjt.name.error.message}}
                </div>
            </div>
        </div>

        <div class="field">
            <label for="pjt_abs_path">Si le projet ne réside pas dans le répertoire des hôtes virtuels, indiquer ici son chemin absolu</label>
            <input type="text" name="pjt_abs_path" id="pjt_abs_path" ng-model="pjt.abspath.value"/>
        </div>

        <div class="field">
            <label for="pjt_dirname">Nom du répertoire qui contiendra le projet, si celui-ci est différent du nom du projet</label>
            <input type="text" name="pjt_dirname" id="pjt_dirname" ng-model="pjt.dirname.value" ng-blur="onDirnameBlur()" ng-focus="pjt.dirname.error.is = false"/>
            <div class="error-dialogue" data-target="#pjt_dirname" ng-show="pjt.dirname.error.is" ng-cloak="">
                <div class="squarre"></div>
                <div class="front">
                    {{pjt.dirname.error.message}}
                </div>
            </div>
        </div>

        <div class="field">
            <label for="pjt_rel_doc_root">Chemin relatif du DocumentRoot par rapport au réperoire du projet</label>
            <input type="text" name="pjt_rel_doc_root" id="pjt_rel_doc_root" placeholder="/public" ng-model="pjt.reldocroot.value"/>
        </div>

        <div class="field">
            <label for="pjt_dir_list">Liste des répertoires à créer, en chemin relatif par rapport au répertoire projet, séparés par des points virgules</label>
            <input type="text" name="pjt_dir_list" id="pjt_dir_list" placeholder="/css;/js/vendor;..." ng-model="pjt.dirlist.value"/>
        </div>

        <div class="field">
            <label for="pjt_dbname">Nom de la base de données associée au projet</label>
            <input type="text" name="pjt_dbname" id="pjt_dbname" placeholder="ma_base_de_donnees" ng-model="pjt.dbname.value"/>
        </div>

        <div class="field checkbox">
            <label for="pjt_git"><input type="checkbox" name="pjt_git" id="pjt_git" ng-model="pjt.git.value"/> Initialiser git ?</label>
        </div>

        <div class="field">
            <label for="pjt_desc">Associer une description au projet</label>
            <textarea name="pjt_desc" id="pjt_desc" ng-model="pjt.desc.value"></textarea>
        </div>

        <div class="field">
            <button class="btn btn-light" ng-disabled="cannotSubmit" ng-click="submit()">Créer</button> <button class="btn btn-light">Annuler</button>
        </div>

        <div class="field loading" ng-show="waitingForResponse" ng-cloak="">
            enregistrement en cours ...
        </div>

        <div class="field result"ng-cloak="" ng-show="appResult != ''">{{appResult}}</div>

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
