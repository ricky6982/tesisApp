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
                            'infRef' => 'Arco Vertical 1',
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

    /**
     * Representación Grafica:
     *                     s2
     *      (1)------(2)------(3)
     *           s1            |
     *                         |
     *                         |s3
     *                         |
     *                         |                           s6
     *                        (4)---------(5)       (10)--------(9)
     *                               s4    |                     |
     *                                     |                     |
     *                                     |                     |
     *                                     |               s5    |
     *                                    (6)--------(7)--------(8)
     */
    public static $currentMapWithServicesInEachEdges = array(
        0 => array(
            'id' => 1,
            'mapaJson' => array(
                'nodes' => array(
                    '_data' => array(
                        1 => array(
                            'id' => 1,
                            'label' => 'N-1',
                            'conexiones' => array(
                                2 => 'der',
                            ),
                        ),
                        2 => array(
                            'id' => 2,
                            'label' => 'N-2',
                            'conexiones' => array(
                                1 => 'izq',
                                3 => 'der',
                            ),
                        ),
                        3 => array(
                            'id' => 3,
                            'label' => 'N-3',
                            'conexiones' => array(
                                2 => 'izq',
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
                            'label' => 'N-5',
                            'conexiones' => array(
                                4 => 'izq',
                                6 => 'abj',
                            ),
                        ),
                        6 => array(
                            'id' => 6,
                            'label' => 'N-6',
                            'conexiones' => array(
                                5 => 'arr',
                                7 => 'der',
                            ),
                        ),
                        7 => array(
                            'id' => 7,
                            'label' => 'N-7',
                            'conexiones' => array(
                                6 => 'izq',
                                8 => 'der',
                            ),
                        ),
                        8 => array(
                            'id' => 8,
                            'label' => 'N-8',
                            'conexiones' => array(
                                7 => 'izq',
                                9 => 'arr',
                            ),
                        ),
                        9 => array(
                            'id' => 9,
                            'label' => 'N-9',
                            'conexiones' => array(
                                8 => 'abj',
                                10 => 'izq',
                            ),
                        ),
                        10 => array(
                            'id' => 10,
                            'label' => 'N-10',
                            'conexiones' => array(
                                9 => 'der',
                            ),
                        ),
                    ),
                ),
                'edges' => array(
                    '_data' => array(
                        'arco01' => array(
                            'from' => 1,
                            'to' => 2,
                            'id' => 'arco01',
                            'distancia' => 10,
                            'infRef' => 'Arco Horizontal 1',
                            'lugares' => array(
                                'izq' => array(),
                                'der' => array(
                                    array(
                                        'idCategoria' => '1',
                                        'idServicio' => '1',
                                        'categoria' => 'Boleteria',
                                        'servicio' => 'Servicio S1',
                                        'distancia' => '1',
                                    ),
                                ),
                            ),
                        ),
                        'arco02' => array(
                            'from' => 2,
                            'to' => 3,
                            'id' => 'arco02',
                            'distancia' => 10,
                            'infRef' => 'Arco Horizontal 1',
                            'lugares' => array(
                                'izq' => array(
                                    array(
                                        'idCategoria' => '1',
                                        'idServicio' => '2',
                                        'categoria' => 'Boleteria',
                                        'servicio' => 'Servicio S2',
                                        'distancia' => '2',
                                    ),
                                ),
                                'der' => array(),
                            ),
                        ),
                        'arco03' => array(
                            'from' => 3,
                            'to' => 4,
                            'id' => 'arco03',
                            'distancia' => 15,
                            'infRef' => 'Arco Vertical 1',
                            'lugares' => array(
                                'izq' => array(
                                    array(
                                        'idCategoria' => '1',
                                        'idServicio' => '3',
                                        'categoria' => 'Boleteria',
                                        'servicio' => 'Servicio S3',
                                        'distancia' => '3',
                                    ),
                                ),
                                'der' => array(),
                            ),
                        ),
                        'arco04' => array(
                            'from' => 4,
                            'to' => 5,
                            'id' => 'arco04',
                            'distancia' => 10,
                            'infRef' => 'Arco Horizontal 2',
                            'lugares' => array(
                                'izq' => array(),
                                'der' => array(
                                    array(
                                        'idCategoria' => '1',
                                        'idServicio' => '4',
                                        'categoria' => 'Boleteria',
                                        'servicio' => 'Servicio S4',
                                        'distancia' => '4',
                                    ),
                                ),
                            ),
                        ),
                        'arco05' => array(
                            'from' => 5,
                            'to' => 6,
                            'id' => 'arco05',
                            'distancia' => 15,
                            'infRef' => 'Arco Vertical 2',
                            'lugares' => array(
                                'izq' => array(),
                                'der' => array(),
                            ),
                        ),
                        'arco06' => array(
                            'from' => 6,
                            'to' => 7,
                            'id' => 'arco06',
                            'distancia' => 10,
                            'infRef' => 'Arco Horizontal 3',
                        ),
                        'arco07' => array(
                            'from' => 7,
                            'to' => 8,
                            'id' => 'arco07',
                            'distancia' => 10,
                            'infRef' => 'Arco Horizontal 3',
                            'lugares' => array(
                                'izq' => array(
                                    array(
                                        'idCategoria' => '1',
                                        'idServicio' => '5',
                                        'categoria' => 'Boleteria',
                                        'servicio' => 'Servicio S5',
                                        'distancia' => '7',
                                    ),
                                ),
                                'der' => array(),
                            ),
                        ),
                        'arco08' => array(
                            'from' => 8,
                            'to' => 9,
                            'id' => 'arco08',
                            'distancia' => 15,
                            'infRef' => 'Arco Vertical 3',
                            'lugares' => array(
                                'izq' => array(),
                                'der' => array(),
                            ),
                        ),
                        'arco09' => array(
                            'from' => 9,
                            'to' => 10,
                            'id' => 'arco09',
                            'distancia' => 10,
                            'infRef' => 'Arco Horizontal 4',
                            'lugares' => array(
                                'izq' => array(),
                                'der' => array(
                                    array(
                                        'idCategoria' => '1',
                                        'idServicio' => '6',
                                        'categoria' => 'Boleteria',
                                        'servicio' => 'Servicio S6',
                                        'distancia' => '9',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );
}