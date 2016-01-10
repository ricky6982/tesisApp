(function(){
    angular.module('ts-servicios', ['restangular', 'ts-parameters'])
        .config(function(RestangularProvider, Parameters){
            RestangularProvider.setBaseUrl(Parameters.urlApi);
        })

        .factory('serviciosFactory', [
            'Parameters', 'Restangular',
            function(Parameters, Restangular){

                function getServicio(id){
                    $http({
                        method: "GET",
                        url: Parameters.urlApi
                    })
                    ;
                }

                return {
                    list: Restangular.all('servicios').getList()
                };
            }
        ])
    ;
}());