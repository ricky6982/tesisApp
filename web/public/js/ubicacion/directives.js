// ========================
// Definición de Directivas
// ========================

// Directiva ts-panel-servicio
// Parametros: 
//      - listado-servicios
//      - lugar
//      - direccion
//      - remove: funcion para remover el elemento.
app.directive('tsPanelServicio', ['$filter', function ($filter) {
    return {
        restrict: 'AE',
        replace: true,
        templateUrl: "panel-servicio.html",
        scope: {
            listadoServicios: '=listadoServicios',
            lugar: '=lugar',
            direccion: '@direccion',
            index: '=index',
            remove: '&remove'
        },
        controller: ['$scope',
            function($scope){
                $scope.updateSublistaServicios = function(){
                    var result = $filter('filter')($scope.listadoServicios, {id: $scope.lugar.idCategoria });
                    if (result.length > 0) {
                        $scope.sublistaServicios = result[0].items;
                    }
                };

                $scope.updatePropiedades = function(){
                    $scope.lugar.categoria = $filter('filter')($scope.listadoServicios, {id: $scope.lugar.idCategoria })[0].nombre;
                    $scope.lugar.servicio = $filter('filter')($scope.sublistaServicios, {id: $scope.lugar.idServicio })[0].nombre;
                };

                if ($scope.lugar) {
                    $scope.updateSublistaServicios();
                }
            }
        ]
    };
}]);