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

    /**
     * Построение поля размеров x, y
     */
    private function buildFiend()
    {
        $this->map = array_fill(0, $this->x, array_fill(0, $this->y, 1));
        echo 'Построена карта с размерами, x: ' . $this->x . ', y: ' . $this->y . '<br>';
    }

    /**
     * Показать карту на текущий момент
     */
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

    /**
     * Добавить животное в конкретную точку
     * @param $coordinateX
     * @param $coordinateY
     * @param $type
     */
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
     * Добавление нескольких животных в случайные координаты
     * @param $amount
     * @param $type
     */
    public function randAddAnimal($amount, $type)
    {
        while ($amount > 0)
        {
            $coordinateX = rand(0, $this->x);
            $coordinateY = rand(0, $this->y);

            if ($type === 'Заяц')
            {
                $this->map[$coordinateX][$coordinateY] = 0;
            }

            if ($type === 'Волк')
            {
                $this->map[$coordinateX][$coordinateY] = 'X';
            }
            echo 'Карта после изменений' . $this->showMap();
            $amount--;
        }
    }

    public function moveAtRand()
    {
        foreach ($this->map as $vertical => $arr_y)
        {
            foreach ($arr_y as $horizontal => $value) {
                if($value === 'X')
                {
                    $ch = rand(0, 3);
                    switch ($ch)
                    {
                        case 0: // Вверх
                            $this->map[$vertical][$horizontal] = '1';
                            if ($vertical != 0)
                            {
                                $this->map[$vertical-1][$horizontal] = 'X';
                            } else $this->map[$vertical+1][$horizontal] = 'X';
                            break;
                        case 1: // Вниз
                            $this->map[$vertical][$horizontal] = '1';
                            $this->map[$vertical+1][$horizontal] = 'X';
                            break;
                        case 2: // Влево
                            $this->map[$vertical][$horizontal] = '1';
                            if ($horizontal != 0)
                            {
                                $this->map[$vertical][$horizontal-1] = 'X';
                            } else $this->map[$vertical][$horizontal+1] = 'X';
                            break;
                        case 3: // Вправо
                            $this->map[$vertical][$horizontal] = '1';
                            $this->map[$vertical][$horizontal+1] = 'X';
                            break;
                    }
                }
            }
        }
        echo 'Карта после хода' . $this->showMap();
    }
}

$endpoint =  new Endpoint(5, 5, 5);
//$endpoint->addAnimal(0,0,'Заяц');
//$endpoint->addAnimal(3,6,'Волк');
$endpoint->randAddAnimal(6, 'Волк');
$endpoint->moveAtRand();
