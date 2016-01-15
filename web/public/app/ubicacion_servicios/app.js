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

        $scope.editServicio = function(index){
            $scope.editarServicio = angular.copy($scope.servicios[index]);
            $('#editServicio').modal('show');
        };

        $scope.updateServicio = function(){
            var obj = angular.copy($scope.editarServicio);
            $('#editServicio').modal('hide');
            obj.customPUT({tesis_servicio: {nombre: obj.nombre, descripcion: obj.descripcion}}).then(function(){
                $scope.actualizarListado();
            }, function(){
                console.log('no se modificó la categoria del servicio');
            });
        };

        $scope.deleteServicio = function(index){
            $scope.eliminarServicio = angular.copy($scope.servicios[index]);
            $('#deleteServicio').modal('show');
        };

        $scope.removeServicio = function(){
            $('#deleteServicio').modal('hide');
            $scope.eliminarServicio.remove().then(function(){
                $scope.actualizarListado();
            }, function(){
                console.log('No se pudo eliminar el servicio');
            });

        };

        $scope.newServicioItem = function(idServicio){
            $('#crearServicioItem').modal('show');
            $scope.nuevoServicioItem = {};
            $scope.nuevoServicioItem.servicio = idServicio;
        };

        $scope.saveServicioItem = function(){
            $('#crearServicioItem').modal('hide');
            var obj = { tesis_servicioitem: angular.copy($scope.nuevoServicioItem)};
            Restangular.one('servicios', $scope.nuevoServicioItem.servicio).customPOST(obj, 'items').then(function(){
                $scope.actualizarListado();
                console.log('Se creo el servicio');
            }, function(){
                console.log('no se pudo crear el sercicio');
            });
        };

        $scope.deleteServicioItem = function(idServicio, idItem){
            $scope.eliminarServicioItem = Restangular.one('servicios', idServicio).one('items', idItem);
            $('#deleteServicioItem').modal('show');
        };

        $scope.removeServicioItem = function(){
            $('#deleteServicioItem').modal('hide');
            $scope.eliminarServicioItem.remove().then(function(){
                $scope.actualizarListado();
            }, function(){
                console.log('no se pudo eliminar el servicio');
            });
        };

        $scope.editarServicioItem = function(indexServicio, idItem){
            $('#editarServicioItemModal').modal('show');
            $scope.edicionServicioItem = angular.copy($scope.servicios.plain()[indexServicio].items[idItem]);
            $scope.edicionServicioItem.servicio_id = $scope.servicios.plain()[indexServicio].id;
        };

        $scope.updateServicioItem = function(){
            $('#editarServicioItemModal').modal('hide');
            Restangular.one('servicios', $scope.edicionServicioItem.servicio_id).one('items', $scope.edicionServicioItem.id).get().then(function(item){
                var edicion = {
                    nombre: $scope.edicionServicioItem.nombre,
                    descripcion: $scope.edicionServicioItem.descripcion,
                    servicio: $scope.edicionServicioItem.servicio_id,
                };
                item.customPUT({tesis_servicioitem: edicion}).then(function(){
                    $scope.actualizarListado();
                }, function(){
                    console.log('no se pudo actualizar el servicio');
                });
            });

            
        };

        // Inicializacion de funciones
        $scope.actualizarListado();

    }
]);