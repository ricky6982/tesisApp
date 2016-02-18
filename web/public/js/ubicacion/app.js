var app = angular.module('app', ['ts-parameters', 'mapaRecorrido', 'restangular', 'localizacionServicio']);

app.config(function(RestangularProvider, Parameters){
    RestangularProvider.setBaseUrl(Parameters.urlApi);
});

app.controller('AppCtrl', [
    '$scope', 'Parameters', 'mapaService', '$timeout', '$http',
    function($scope, Parameters, Mapa, $timeout, $http){
        Mapa.init(document.getElementById('network'));
        Mapa.remote.setUrlMap(Parameters.urlGetCurrentMap);
        Mapa.remote.setUrlSave(Parameters.urlSaveMap);
        Mapa.remote.getMap();
        $scope.data = Mapa.data;

        $scope.listadoServicios = $http({
            method: 'GET',
            url: Parameters.urlGetServicios
        });

        $scope.nodoEdit = null;
        $scope.arcoEdit = null;

        Mapa.events.nodoSeleccionado.suscribe($scope, function(){
            $timeout(function(){
                var nodo = Mapa.node.getSelected()[0];
                $scope.nodoEdit = Mapa.node.get(nodo);
                Mapa.node.updateOrientacion($scope.nodoEdit);
            },0);
        });

        Mapa.events.arcoSeleccionado.suscribe($scope, function(){
            $timeout(function(){
                var arco = Mapa.edge.getSelected()[0];
                $scope.arcoEdit = Mapa.edge.get(arco);
            },0);
        });

        Mapa.events.clickCanvas.suscribe($scope, function(){
            if (Mapa.node.getSelected().length === 0) {
                $timeout(function(){
                    $scope.nodoEdit = null;
                },0);
            }
            if (Mapa.edge.getSelected().length === 0) {
                $timeout(function(){
                    $scope.arcoEdit = null;
                },0);
            }
        });
    }
]);
