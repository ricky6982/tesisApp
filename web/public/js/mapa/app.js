var app = angular.module('app', ['mapaRecorrido', 'ts-parameters']);

var mapa_data;

app.controller('AppCtrl',[
    '$scope', '$timeout', 'mapaService', 'Parameters',
    function($scope, $timeout, Mapa, Parameters){

        Mapa.init(document.getElementById('network'));
        Mapa.remote.setUrlMap(Parameters.urlGetCurrentMap);
        Mapa.remote.setUrlSave(Parameters.urlSaveMap);
        Mapa.remote.getMap();
        $scope.data = Mapa.data;

        $scope.nodoEdit = null;
        $scope.arcoEdit = null;
        $scope.cantidadNodos = 0;
        $scope.cantidadArcos = 0;

        // ===========
        // Trayectoria
        // ===========
        $scope.trayecto = [{id:''},{id:''}];

        $scope.trayectoria = {
            addField: function(){
                $scope.trayecto.push({id: ''});
            },
            removeField: function(){
                if ($scope.trayecto.length > 2) {
                    $scope.trayecto.pop();
                }
            },
            clean: function(){
                angular.forEach($scope.trayecto, function(value, key){
                    value.id = '';
                });
            },
            reestablecer: function(){
                $scope.trayecto = [
                    {id: ''},
                    {id: ''}
                ];
            },
            crear: function(){
                console.log('creando trayecto');
                var trayecto = $scope.trayecto.map(function(obj){
                    return obj.id;
                });
                Mapa.path.add(trayecto);
            }
        };

        $scope.comportamiento = {
            movimiento: true,
            
            toggleMovimiento: function(){
                Mapa.setAnimacion(!$scope.comportamiento.movimiento);
            },

            savePositions: function(){
                Mapa.savePositions();
            },

            restorePositions: function(){
                Mapa.restorePositions();
            }
        };


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


        $scope.nodo = {
            remove: function(){
                var nodoEliminar = Mapa.node.getSelected()[0];
                if (window.confirm('¿Esta seguro de que quiere eliminar el nodo '+nodoEliminar+' ?')) {
                    console.log(nodoEliminar);
                    console.log('Eliminando Nodo');
                    Mapa.node.remove(Mapa.node.remove(nodoEliminar));
                }
            },
            update: function(){
                $scope.nodes.update($scope.nodoEdit);
            }
        };

        $scope.listadoNodos = Mapa.data.nodes._data;

        $scope.arco = {
            remove: function(){
                var arcoEliminar = Mapa.edge.getSelected()[0];
                if (window.confirm('¿Esta seguro de que quiere elimnar los arcos seleccionados?')) {
                    console.log(arcoEliminar);
                    console.log('Eliminando Arco');
                    Mapa.edge.remove(arcoEliminar);
                }
            },

            setDistancia: function(){
                $scope.arcoEdit.label = $scope.arcoEdit.distancia;
                Mapa.edge.update($scope.arcoEdit);
            }
        };

        $scope.shortestPath = function(){
            var i = $scope.nodoInicial.id.toString();
            var f = $scope.nodoFinal.id.toString();
            console.log(Mapa.path.shortest(i,f));
            $scope.camino = Mapa.path.shortest(i,f);
            $scope.distanciaTotal = Mapa.path.distancia($scope.camino);
        };

        $scope.guardarOrientacion = function(){
            Mapa.node.updateOrientacion($scope.nodoEdit);
            Mapa.node.validarOrientacion();
        };
    }
]);

