// CONTROLADOR PARA LA ASIGNACIÓN DE LOS SERVICIOS EN UN ARCO
app.controller('LocalizacionCtrl',[
    '$scope', '$timeout',
    function($scope, $timeout){

        $scope.arcoEdit = {};

        // ==============================================
        // Logica para la interfaz visual para posicionar
        // un servicio sobre un arco
        // ==============================================
        $scope.lugarAux = {
            idCategoria: null,
            idServicio: null,
            categoria: "",
            servicio: "",
            distancia: ""
        };

        $scope.serviciosItem = function(){
            return [{id: 1, nombre: "test 1"},{id: 2, nombre: "test 2"},{id: 3, nombre: "test 3"}];
        };

        $scope.$parent.guiLugares = {
            izq: [],
            der: [],
            add: function(direccion){
                switch (direccion){
                    case 'izq': $scope.guiLugares.izq.push(angular.copy($scope.lugarAux));
                        break;
                    case 'der': $scope.guiLugares.der.push(angular.copy($scope.lugarAux));
                        break;
                }
                console.log('Agregregando item a la ' + direccion);
            },
            remove: function(direccion, item){
                switch (direccion){
                    case 'izq': $scope.guiLugares.izq.splice(item, 1);
                        break;
                    case 'der': $scope.guiLugares.der.splice(item, 1);
                        break;
                }
                console.log('Removiendo item ' + item + ' de ' + direccion);
            }
        };

        $scope.showForm = function(){
            if (angular.isNumber($scope.arcoEdit.from )) {
                return true;
            }else{
                return false;
            }
        };

        $scope.guardar = function(){
            $scope.loader = true;
            $timeout(function(){
                $scope.loader = false;
            },1000);
            $scope.arcoEdit.lugares.izq = angular.copy($scope.$parent.guiLugares.izq);
            $scope.arcoEdit.lugares.der = angular.copy($scope.$parent.guiLugares.der);
            dbg.edges.update($scope.arcoEdit);

            
        };

    }
]);

