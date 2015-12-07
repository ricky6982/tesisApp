app.controller('NetworkCtrl',[
    '$scope', '$timeout', '$http',
    function($scope, $timeout, $http){
        // =============================
        // Definición de Grafo con VisJs
        // =============================
        $scope.nodes = new vis.DataSet([]);
        $scope.edges = new vis.DataSet([]);
        $scope.options = {};
        $scope.container = document.getElementById('network');
        $scope.data = {
            nodes: $scope.nodes,
            edges: $scope.edges
        };
        network = new vis.Network($scope.container, $scope.data, $scope.options);

        // =======================================
        // Definición de Variables para Depuracion
        // =======================================
        dbg = {
            nodes: $scope.nodes,
            edges: $scope.edges,
            data: $scope.data,
            options: $scope.options,
            container: $scope.container
        };

        $scope.getCurrentMap = function(){
            $http({
                url: datos.urlGetCurrenMap,
                method: 'GET',
                })
                .success(function(data, status){
                    angular.forEach(data[0].mapaJson.nodes._data, function(n){
                        $scope.nodes.add(n);
                    });
                    angular.forEach(data[0].mapaJson.edges._data, function(e){
                        $scope.edges.add(e);
                    });
                    network.setOptions({nodes: {physics: false }});
                    $scope.comportamiento.restorePositions();
                    network.fit();
                })
                .error(function(data){
                    console.log('no hay mapa');
                })
            ;
        };

        $scope.getCurrentMap();


        // Detección de Selección de Arco
        network.on('selectEdge', function(){
            $timeout(function(){
                var arco = $scope.$parent.arcoEdit = $scope.edges.get(network.getSelectedEdges()[0]);
                if (typeof arco.lugares === 'undefined') {
                    arco.lugares = {izq: [], der: []};
                    $scope.$parent.guiLugares.izq = [];
                    $scope.$parent.guiLugares.der = [];
                    console.log('Se creo la estructura de datos en el arco seleccionado.');
                }else{
                    $scope.$parent.guiLugares.izq = angular.copy(arco.lugares.izq);
                    $scope.$parent.guiLugares.der = angular.copy(arco.lugares.der);
                    console.log('Tiene lugares establecidos');
                }
            },0);
        });

        // ========================
        // Comportamiento del Grafo
        // ========================
        $scope.comportamiento = {
            movimiento: false,
            
            toggleMovimiento: function(){
                if ($scope.comportamiento.movimiento) {
                    network.setOptions({nodes: {physics: false }});
                }else{
                    network.setOptions({nodes: {physics: true }});
                }
            },

            savePositions: function(){
                network.storePositions();
                window.alert('Se guardaron las posiciones de los nodos');
            },

            restorePositions: function(){
                angular.forEach($scope.nodes.getIds(), function(value, key){
                    if ($scope.nodes.get(value).x) {
                        network.moveNode($scope.nodes.get(value).id, $scope.nodes.get(value).x, $scope.nodes.get(value).y);
                    }
                });
            }
        };
    }
]);