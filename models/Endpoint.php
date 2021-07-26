<?php


class Endpoint
{
    private $x, $y, $N,
    $map;

    // Методы, объявленные абстрактными, несут, по существу, лишь описательный смысл и не могут включать реализацию.
    public function __construct($x, $y, $N)
    {
        $this->x = $x;
        $this->y = $y;
        $this->N = $N;
    }

    public function buildFiend()
    {
        $map = array($this->x, $this->y);
        echo 'Построена карта с размерами, x: ' . $this->x . ', y: ' . $this->y;
    }


    /**
     * Показать параметры класса
     * @return void
     */
    public function showParam()
    {
        echo $this->x . '<br>' .
            $this->y . '<br>' .
            $this->N .  '<br>';
    }
}

$endpoint =  new Endpoint(5, 5, 5);
$endpoint->showParam(); // для отладки
$endpoint->buildFiend();