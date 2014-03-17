/**
 * Created by Nicolas on 17/03/14.
 */

var app = angular.module("MainApp",[]);

app.value('baseUrl', "http://localhost/pam/api/new_project/");

app.factory("NewProjectValidationFct", ["$http", "baseUrl", function($http, baseUrl){

    return {

        testName: function(name){
            return $http.get(baseUrl + "test_name", {params:{"name":name}});

        }
    };
}]);

app.controller("MainCtrl", ['$scope', 'NewProjectValidationFct', function($scope, NewProjectValidationFct){

    $scope.pjt = {
        name:{value:"", error:{}}
    };


    $scope.onNameBlur = function () {
        if($scope.pjt.name.value == ""){
            $scope.pjt.name.error.is = true;
            $scope.pjt.name.error.message = "Ce champs est obligatoire ! Vous devez donner un nom à votre projet.";
        } else {
            $scope.pjt.name.error.is = false;
            NewProjectValidationFct.testName($scope.pjt.name.value)
                .success(function(datas){
                    if(datas.error){
                        $scope.pjt.name.error = true;
                    }
                })
                .error(function(datas, status){

                });
        }

    };

}]);
