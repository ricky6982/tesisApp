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

        $scope.saveServicio = function(){
            var obj = {tesis_servicio: angular.copy($scope.newServicio)};
            var modal = $('#crearServicio');
            modal.modal('hide');
            Restangular.all('servicios').post(obj).then(function(){
                $scope.actualizarListado();
                $scope.newServicio.nombre = "";
                $scope.newServicio.descripcion = "";
            }, function(){
                console.log('no se pudo guardar la nueva categoría de servicio.');
                modal.modal('show');
            });
        };

        $scope.deleteServicio = function(index){
            $scope.eliminarServicio = angular.copy($scope.servicios[index]);
            var modal = $('#deleteServicio');
            modal.modal('show');
        };

        $scope.removeServicio = function(){
            $('#deleteServicio').modal('hide');
            $scope.eliminarServicio.remove().then(function(){
                $scope.actualizarListado();
            }, function(){
                console.log('No se pudo eliminar el servicio');
            });

        };

        // Inicializacion de funciones
        $scope.actualizarListado();

    }
]);