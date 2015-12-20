var app = angular.module('app', []);

app.controller('CartaMenuCtrl', [
    '$scope',
    function ($scope) {
        $scope.categoria = {
            quitar: function(id){
                if (confirm("Esta seguro de que quiere quitar la categoria, junto a todos sus productos")){
                    console.log('se elimino la categoria ' + id);
                    window.location.href = dataUrl.quitar_categoria.replace('__id__', id);
                }
            },
            editar: function(id){
                // console.log('editando la categoria ' + id);
            }
        };

        $scope.producto = {
            quitar: function(id){
                if (confirm("Esta seguro de que quiere quitar el producto")){
                    console.log('se elimino la producto ' + id);
                    window.location.href = dataUrl.quitar_producto.replace('__id__', id);
                }
            }
        };
    }
]);