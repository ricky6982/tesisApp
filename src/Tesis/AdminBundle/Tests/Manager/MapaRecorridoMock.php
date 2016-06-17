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
                            )
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
}