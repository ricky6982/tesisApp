var app = angular.module('app', ['restangular', 'ts-parameters']);

app.config(function(RestangularProvider, Parameters){
    RestangularProvider.setBaseUrl(Parameters.urlApi);
});

app.controller('AppCtrl', [
    '$scope',
    function($scope){

    }
]);

app.controller('ServiciosCtrl', [
    '$scope', 'Restangular',
    function($scope, Restangular){

        // Funciones del Controlador
        $scope.actualizarListado = function(){
            Restangular.all('servicios').getList().then(function(data){
                $scope.servicios = data;
            });
        };

        $scope.crearServicio = function(){
            Restangular.all('servicios').post({tesis_servicio: { nombre: "nuevo", descripcion: "nuevo"}}).then(function(){
                $scope.actualizarListado();
            });
        };

        // Inicializacion de funciones
        $scope.actualizarListado();

    }
]);