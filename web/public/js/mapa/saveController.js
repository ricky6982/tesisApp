app.controller('SaveCtrl', [
    '$scope', '$http',
    function($scope, $http){
        $scope.btnSave = function(){
            $http({
                method: 'POST',
                url: datos.urlSaveMap,
                headers: {
                    'Content-Type': 'application/json'
                },
                data: {
                    mapa: network,
                }
            });
        };
    }
]);
