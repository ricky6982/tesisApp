<?php

namespace Tesis\FrontendBundle\Twig;

class AppExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
                new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
                new \Twig_SimpleFilter('wordPrice', array($this, 'wordPriceFilter')),
            );
    }
    
    public function priceFilter($number, $decimals = 0, $decPoint = ",", $thousandsSep = ".")
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);

        return '$ '.$price;
    }

    public function wordPriceFilter($number)
    {
        $n = $number;
        $whole = floor($n);
        $fraction = ($n - $whole) * 100;
        $price = $whole . ' pesos';

        if($fraction != 0){
            $price = $price . ' con '.$fraction.' centavos.';
        }

        return $price;
    }

    public function getName()
    {
        return 'app_extension';
    }
}