app.controller('SaveCtrl', [
    '$scope', '$http',
    function($scope, $http){
        $scope.btnSave = function(){
            $http({
                method: 'POST',
                url: 'http://localhost/tesisApp/web/app_dev.php/Admin/MapaDeRecorridos/save',
                headers: {
                    'Content-Type': 'application/json'
                },
                data: {
                    mapa: $scope.data,
                    algo: [3,3,3,3]
                }
            });
        };
    }
]);
