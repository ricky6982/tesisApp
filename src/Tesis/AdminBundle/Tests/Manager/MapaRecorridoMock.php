<?php

namespace Tesis\AdminBundle\Tests\Manager;

class MapaRecorridoMock
{
    public static $currentMap = array(
        0 => array(
            'id' => 1,
            'mapaJson' => array(
                'nodes' => array(
                    '_data' => array(
                        1 => array(
                            'id' => 1,
                            'label' => 'N-1',
                            'conexiones' => array(
                                2 => 'izq',
                                4 => 'abj',
                                5 => 'der',
                            ),
                        ),
                        2 => array(
                            'id' => 2,
                            'label' => 'N-2',
                            'conexiones' => array(
                                1 => 'der',
                                3 => 'abj',
                            ),
                        ),
                        3 => array(
                            'id' => 3,
                            'label' => 'N-3',
                            'conexiones' => array(
                                2 => 'arr',
                                4 => 'der',
                            ),
                        ),
                        4 => array(
                            'id' => 4,
                            'label' => 'N-4',
                            'conexiones' => array(
                                1 => 'arr',
                                3 => 'izq',
                                6 => 'der',
                            ),
                        ),
                        5 => array(
                            'id' => 5,
                            'label' => 'N-5',
                            'conexiones' => array(
                                1 => 'izq',
                                6 => 'abj',
                            ),
                        ),
                        6 => array(
                            'id' => 6,
                            'label' => 'N-6',
                            'conexiones' => array(
                                4 => 'izq',
                                5 => 'abj',
                            ),
                        ),
                    ),
                ),
                'edges' => array(
                    '_data' => array(
                        'd72801a9-c237-4d32-a186-ddf1342a9adc' => array(
                            'from' => 2,
                            'to' => 1,
                            'id' => 'd72801a9-c237-4d32-a186-ddf1342a9adc',
                            'distancia' => 10,
                            'infRef' => 'Arco Horizontal 1',
                        ),
                        '5ba415af-71a3-4459-a52a-51c6fb8334be' => array(
                            'from' => 2,
                            'to' => 3,
                            'id' => '5ba415af-71a3-4459-a52a-51c6fb8334be',
                            'distancia' => 5,
                            'infRef' => 'Arco Vertical 1',
                        ),
                        '7f3088a0-d58b-41f4-a4aa-aec074abf13c' => array(
                            'from' => 3,
                            'to' => 4,
                            'id' => '7f3088a0-d58b-41f4-a4aa-aec074abf13c',
                            'distancia' => 10,
                            'infRef' => 'Arco horizontal 3',
                        ),
                        'f71ecbab-d2cc-41b8-8770-beb8644ffc8e' => array(
                            'from' => 1,
                            'to' => 4,
                            'id' => 'f71ecbab-d2cc-41b8-8770-beb8644ffc8e',
                            'distancia' => 5,
                            'infRef' => 'Arco Vertical 2',
                        ),
                        '582fca7c-f66b-41f6-aa22-d1113a34587c' => array(
                            'from' => 1,
                            'to' => 5,
                            'id' => '582fca7c-f66b-41f6-aa22-d1113a34587c',
                            'distancia' => 10,
                            'infRef' => 'Arco Vertical 1',
                        ),
                        '0bcc0655-1734-429b-9ce7-d439b08a66ce' => array(
                            'from' => 5,
                            'to' => 6,
                            'id' => '0bcc0655-1734-429b-9ce7-d439b08a66ce',
                            'distancia' => 5,
                            'infRef' => 'Arco Vertical 3',
                        ),
                        '434d2354-4773-4c71-8437-381b14023388' => array(
                            'from' => 4,
                            'to' => 6,
                            'id' => '434d2354-4773-4c71-8437-381b14023388',
                            'distancia' => 10,
                            'infRef' => 'Arco horizontal 4',
                        ),
                    ),
                ),
            ),
        ),
    );

//    TODO: Crear mock de mapa de recorrido con servicios
    /**
     * Representación Grafica:
     *                     s2
     *      (1)------(3)------(2)
     *           s1   |
     *                |
     *                |
     *                |
     *               (4)---------(5)--------(6)
     *                                       |
     *                                       |
     *                                       |
     *                                       |
     *                                      (7)
     *                                       ^ Punto de Partida
     */
    public static $currentMapWithServices = array(
        0 => array(
            'id' => 1,
            'mapaJson' => array(
                'nodes' => array(
                    '_data' => array(
                        1 => array(
                            'id' => 1,
                            'label' => 'N-1',
                            'conexiones' => array(
                                3 => 'der',
                            ),
                        ),
                        2 => array(
                            'id' => 2,
                            'label' => 'N-2',
                            'conexiones' => array(
                                3 => 'izq',
                            ),
                        ),
                        3 => array(
                            'id' => 3,
                            'label' => 'N-3',
                            'conexiones' => array(
                                1 => 'izq',
                                2 => 'der',
                                4 => 'abj',
                            ),
                        ),
                        4 => array(
                            'id' => 4,
                            'label' => 'N-4',
                            'conexiones' => array(
                                3 => 'arr',
                                5 => 'der',
                            ),
                        ),
                        5 => array(
                            'id' => 5,
                            'label' => 'N-4',
                            'conexiones' => array(
                                4 => 'izq',
                                6 => 'der',
                            ),
                        ),
                        6 => array(
                            'id' => 6,
                            'label' => 'N-4',
                            'conexiones' => array(
                                5 => 'izq',
                                7 => 'abj',
                            ),
                        ),
                        7 => array(
                            'id' => 7,
                            'label' => 'N-4',
                            'conexiones' => array(
                                6 => 'arr',
                            ),
                        ),
                    ),
                ),
                'edges' => array(
                    '_data' => array(
                        'd72801a9-c237-4d32-a186-ddf1342a9adc' => array(
                            'from' => 1,
                            'to' => 3,
                            'id' => 'd72801a9-c237-4d32-a186-ddf1342a9adc',
                            'distancia' => 10,
                            'infRef' => 'Arco Horizontal 1',
                            'lugares' => array(
                                'izq' => array(),
                                'der' => array(
                                    array(
                                        'idCategoria' => '1',
                                        'idServicio' => '1',
                                        'categoria' => 'Boleteria',
                                        'servicio' => 'Flecha Bus',
                                        'distancia' => '2',
                                    ),
                                ),
                            ),
                        ),
                        '5ba415af-71a3-4459-a52a-51c6fb8334be' => array(
                            'from' => 3,
                            'to' => 2,
                            'id' => '5ba415af-71a3-4459-a52a-51c6fb8334be',
                            'distancia' => 15,
                            'infRef' => 'Arco Horizontal 2',
                            'lugares' => array(
                                'izq' => array(
                                    array(
                                        'idCategoria' => '1',
                                        'idServicio' => '2',
                                        'categoria' => 'Boleteria',
                                        'servicio' => 'Balut',
                                        'distancia' => '5',
                                    ),
                                ),
                                'der' => array(),
                            ),
                        ),
                        '7f3088a0-d58b-41f4-a4aa-aec074abf13c' => array(
                            'from' => 3,
                            'to' => 4,
                            'id' => '7f3088a0-d58b-41f4-a4aa-aec074abf13c',
                            'distancia' => 20,
                            'infRef' => 'Arco Vertical',
                            'lugares' => array(
                                'izq' => array(
                                    array(
                                        'idCategoria' => '2',
                                        'idServicio' => '3',
                                        'categoria' => 'Baños',
                                        'servicio' => 'Baño Damas',
                                        'distancia' => '5',
                                    ),
                                ),
                                'der' => array(
                                    array(
                                        'idCategoria' => '2',
                                        'idServicio' => '4',
                                        'categoria' => 'Baños',
                                        'servicio' => 'Baño Caballeros',
                                        'distancia' => '5',
                                    ),
                                ),
                            ),
                        ),
                        '15af-71a3-4459-a52a-51c6fb8334be5ba4' => array(
                            'from' => 4,
                            'to' => 5,
                            'id' => '15af-71a3-4459-a52a-51c6fb8334be5ba4',
                            'distancia' => 15,
                            'infRef' => 'Arco Horizontal 3',
                        ),
                        '71a3-4459-a52a-51c6fb8334be-5ba415af' => array(
                            'from' => 6,
                            'to' => 5,
                            'id' => '71a3-4459-a52a-51c6fb8334be-5ba415af',
                            'distancia' => 15,
                            'infRef' => 'Arco Horizontal 4',
                        ),
                        '888415af-71a3-4459-a52a-51c6fb8334be' => array(
                            'from' => 7,
                            'to' => 6,
                            'id' => '888415af-71a3-4459-a52a-51c6fb8334be',
                            'distancia' => 15,
                            'infRef' => 'Arco Vertical 2',
                        ),
                    ),
                ),
            ),
        ),
    );
}