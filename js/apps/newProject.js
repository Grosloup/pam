/**
 * Created by Nicolas on 17/03/14.
 */

var app = angular.module("MainApp",[])
    .config(["$httpProvider", function($httpProvider){
        $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
    }]);

app.value('baseUrl', "http://localhost/pam/api/new_project/");

app.factory("NewProjectFct", ["$http", "baseUrl", function($http, baseUrl){

    return {

        testName: function(name){
            return $http.get(baseUrl + "test_name.php", {params:{"pjt_name":name}});

        },
        testDirname: function(dirname, abspath){
            return $http.get(baseUrl + "test_dirname.php", {params:{"pjt_dirname":dirname, "pjt_abs_path": abspath}});
        },
        submit: function(datas){
            return $http.post(baseUrl + "save.php", datas);
        }
    };
}]);

app.controller("MainCtrl", ['$scope', 'NewProjectFct', function($scope, NewProjectFct){

    $scope.pjt = {
        name:{value:"", error:{}},
        abspath:{value:""},
        dirname:{value:"", error:{is: false}},
        reldocroot:{value:""},
        dirlist:{value:""},
        dbname:{value:""},
        git:{value:false},
        desc:{value:""}
    };
    $scope.appError = "";
    $scope.appResult = "";
    $scope.waitingForResponse = false;
    $scope.cannotSubmit = true;

    var httpError = function(datas, status, headers, config){
        $scope.appError = status + " : Un problème est survenu !";
    };

    $scope.onNameBlur = function () {
        if($scope.pjt.name.value == ""){
            $scope.pjt.name.error.is = true;
            $scope.pjt.name.error.message = "Ce champs est obligatoire ! Vous devez donner un nom à votre projet.";
        } else {
            $scope.pjt.name.error.is = false;
            NewProjectFct.testName($scope.pjt.name.value)
                .success(function(datas){
                    if(datas.error){
                        $scope.pjt.name.error.is = true;
                        $scope.pjt.name.error.message = "Le nom " + $scope.pjt.name.value + " est déjà utilisé, choisissez en un autre.";

                    } else {
                        $scope.pjt.name.error.is = false;
                        $scope.cannotSubmit = false;
                    }
                })
                .error(httpError);
        }
    };

    $scope.onDirnameBlur = function(){
        if($scope.pjt.dirname.value != ""){
            NewProjectFct.testDirname($scope.pjt.dirname.value, $scope.pjt.abspath.value)
                .success(function(datas){
                    if(datas.error){
                        $scope.pjt.dirname.error.is = true;
                        $scope.pjt.dirname.error.message = "Le nom " + $scope.pjt.dirname.value + " est déjà utilisé, choisissez en un autre.";
                    } else {
                        $scope.pjt.dirname.error.is = false;
                    }
                })
                .error(httpError);
        }
    }

    $scope.submit = function(){
        $scope.waitingForResponse = true;
        NewProjectFct.submit($scope.pjt).success(function(datas){
            var errors = datas.errors;
            if(errors != ""){
                $scope.appResult = datas.errors;
            } else {
                $scope.appResult = "Tout c'est bien passé. Relancez Apache !";
            }
            $scope.waitingForResponse = false;

        }).error(httpError);
    }

}]);
