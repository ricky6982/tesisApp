var app = angular.module('app', []);
// CONTROLADOR PRINCIPAL
app.controller('AppCtrl', [
    '$scope', '$http',
    function($scope, $http){

        // Datos que estarán compartidos entre los dos controladores
        // ServicioCtrl y LocalizacionCtrl, cuando se implemente RESTful.
        // Mientras los servicios seran creados con formularios Symfony2,
        // lo cual impide que los cambios realizados por el usuario se 
        // mantengan cada vez que se van agregando servicios.

        $scope.listadoServicios = [];

        $scope.guiLugares = {};

        // Ejemplo con Datos Cargados
        // $scope.listadoServicios = [
        //     {
        //         id: 1,
        //         nombre: 'Boleterias',
        //         items: [
        //             {
        //                 id: 10,
        //                 nombre: 'El Quiaqueño',
        //                 descripcion: 'Viajes al interior de la Provincia.'
        //             },
        //             {
        //                 id: 11,
        //                 nombre: 'Balut',
        //                 descripcion: 'Viajes a todo el Pais.'
        //             }
        //         ]
        //     }
        // ];

        $http({
            method: 'GET',
            url: datos.urlGetServicios
        })
            .success(function(data, status){
                $scope.listadoServicios = data;
            })
            .error(function(data){
                console.log('no se pudo conseguir el listado de servicios.');
            })
        ;

    }
]);
