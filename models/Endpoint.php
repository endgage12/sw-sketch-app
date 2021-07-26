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

        $this->buildFiend();
        $this->showMap();
    }

    private function buildFiend()
    {
        $this->map = array_fill(0, $this->x, array_fill(0, $this->y, 1));
        echo 'Построена карта с размерами, x: ' . $this->x . ', y: ' . $this->y . '<br>';
    }

    private function showMap()
    {
        echo '<br>';
        for ($i = 0; $i < $this->x; $i++)
        {
            for ($k = 0; $k < $this->y; $k++)
            {
                print_r($this->map[$i][$k]);
            }
            echo '<br>';
        }
    }

    public function addAnimal($coordinateX, $coordinateY, $type)
    {
        if ($type === 'Заяц')
        {
            $this->map[$coordinateX][$coordinateY] = 0;
        }

        if ($type === 'Волк')
        {
            $this->map[$coordinateX][$coordinateY] = 'X';
        }
        echo 'Карта после изменений' . $this->showMap();
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

$endpoint =  new Endpoint(15, 15, 5);
$endpoint->addAnimal(0,0,'Заяц');
$endpoint->addAnimal(3,6,'Волк');