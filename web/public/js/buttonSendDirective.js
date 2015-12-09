buttonSendTemplate = ""+
    "<button class='{{ xclass }}' ng-disabled='flag' ng-click='submit()'>"+
        "{{ label }}"+
        " <i class='glyphicon glyphicon-refresh spinning' ng-show='flag'></i> "+
    "</button>"+


app.directive('btnSend', [
    function(){
        return {
            restrict: 'E',
            replace: true,
            scope: {
                label: "@label",
                xclass: "@xclass",
                url: "@url",
                method: "@method",
                data: "=data"
            },
            template: buttonSendTemplate,
            controller: ['$scope', '$http',
                function($scope, $http){
                    $scope.flag = false;
                    $scope.submit = function(){
                        network.storePositions();
                        $scope.flag = true;
                        $http({
                            method: $scope.method,
                            url: $scope.url,
                            data: $scope.data
                            })
                            .success(function(data, status){
                                $scope.flag = false;
                            })
                            .error(function(data, status){
                                $scope.flag = false;
                                console.log('Ocurrio un error al enviar los datos para ser guardados.');
                            })
                        ;
                    };
                }
            ]
        };
    }
]);