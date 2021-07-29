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
    }

    /**
     * Построение поля размеров x, y
     */
    private function buildFiend()
    {
        $this->map = array_fill(0, $this->x, array_fill(0, $this->y, '*'));
        echo 'Построена карта с размерами, x: ' . $this->x . ', y: ' . $this->y . '<br>';
    }

    /**
     * Показать карту на текущий момент
     */
    public function showMap()
    {
        echo '<br>';
        for ($i = 0; $i < $this->x; $i++) {
            for ($k = 0; $k < $this->y; $k++) {
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
        if ($type === 'Заяц') {
            $this->map[$coordinateX][$coordinateY] = '0';
        }

        if ($type === 'Волк') {
            $this->map[$coordinateX][$coordinateY] = 'X';
        }
    }

    /**
     * Добавление нескольких животных в случайные координаты
     * @param $amount
     * @param $type
     */
    public function randAddAnimal($amount, $type)
    {
        while ($amount > 0) {
            $coordinateX = rand(0, $this->x);
            $coordinateY = rand(0, $this->y);

            if ($type === 'Заяц') {
                $this->map[$coordinateX][$coordinateY] = '0';
            }

            if ($type === 'Волк') {
                $this->map[$coordinateX][$coordinateY] = 'X';
            }

            $amount--;
        }
    }

    private function eatHareIfPossible($x, $y)
    {
        $sum = 0;

        if ($this->lookHareLeft($x, $y)) $sum++;
        if ($this->lookHareRight($x, $y)) $sum++;
        if ($this->lookHareTop($x, $y)) $sum++;
        if ($this->lookHareBottom($x, $y)) $sum++;
        if ($this->lookHareTopLeft($x, $y)) $sum++;
        if ($this->lookHareTopRight($x, $y)) $sum++;
        if ($this->lookHareBottomLeft($x, $y)) $sum++;
        if ($this->lookHareBottomRight($x, $y)) $sum++;

        if ($sum > 0 && $sum < 2) $this->eatHare($x, $y);
        if ($sum > 2) echo "За $sum-мя зайцами погонишься, ни одного не поймаешь";
    }

    private function lookHareLeft($x, $y)
    {
        if (!isset($this->map[$x][$y-1])) return 0;
        elseif ($this->map[$x][$y-1] === '0') return 1;
        else return 0;
    }

    private function lookHareRight($x, $y)
    {
        if (!isset($this->map[$x][$y+1])) return 0;
        elseif ($this->map[$x][$y+1] === '0') return 1;
        else return 0;
    }

    private function lookHareTop($x, $y)
    {
        if (!isset($this->map[$x-1][$y])) return 0;
        elseif ($this->map[$x-1][$y] === '0') return 1;
        else return 0;
    }

    private function lookHareBottom($x, $y)
    {
        if (!isset($this->map[$x+1][$y])) return 0;
        elseif ($this->map[$x+1][$y] === '0') return 1;
        else return 0;
    }

    private function lookHareTopLeft($x, $y)
    {
        if (!isset($this->map[$x-1][$y-1])) return 0;
        elseif ($this->map[$x-1][$y-1] === '0') return 1;
        else return 0;
    }

    private function lookHareTopRight($x, $y)
    {
        if (!isset($this->map[$x-1][$y+1])) return 0;
        elseif ($this->map[$x-1][$y+1] === '0') return 1;
        else return 0;
    }

    private function lookHareBottomLeft($x, $y)
    {
        if (!isset($this->map[$x+1][$y-1])) return 0;
        elseif ($this->map[$x+1][$y-1] === '0') return 1;
        else return 0;
    }

    private function lookHareBottomRight($x, $y)
    {
        if (!isset($this->map[$x+1][$y+1])) return 0;
        elseif ($this->map[$x+1][$y+1] === '0') return 1;
        else return 0;
    }

    private function eatHare($x, $y)
    {
        $sign = '*';
        if ($this->lookHareLeft($x, $y) == 1) $this->map[$x][$y-1] = $sign;
        if ($this->lookHareRight($x, $y)) $this->map[$x][$y+1] = $sign;
        if ($this->lookHareTop($x, $y)) $this->map[$x-1][$y] = $sign;
        if ($this->lookHareBottom($x, $y)) $this->map[$x+1][$y] = $sign;
        if ($this->lookHareTopLeft($x, $y)) $this->map[$x-1][$y-1] = $sign;
        if ($this->lookHareTopRight($x, $y)) $this->map[$x-1][$y+1] = $sign;
        if ($this->lookHareBottomLeft($x, $y)) $this->map[$x+1][$y-1] = $sign;
        if ($this->lookHareBottomRight($x, $y)) $this->map[$x+1][$y+1] = $sign;
    }

    public function makeMove()
    {
        foreach ($this->map as $vertical => $arr_y)
        {
            foreach ($arr_y as $horizontal => $value)
            {
                if ($value === 'X' || $value === '0') {

                    if ($value === 'X') $this->eatHareIfPossible($vertical, $horizontal);
                    $this->checkAvailableDirections($vertical, $horizontal);

                    //На данный момент перемещает каждое животное



                    $this->showMap(); //Для пошагового разбора движений каждого животного
                }
            }
        }
    }

    private function moveAnimalTo($left, $right, $top, $bottom, $x, $y)
    {
        $listDirection = [
            'left' => $left,
            'right' => $right,
            'top' => $top,
            'bottom' => $bottom,
        ];

        foreach ($listDirection as $key => $direction)
        {
            if ($direction == 0) unset($listDirection[$key]);
        }

        $moveDirection = array_rand($listDirection); // string с названием ключа
        if ($moveDirection === 'left') $this->moveAnimalToLeft($x, $y);
        if ($moveDirection === 'right') $this->moveAnimalToRight($x, $y);
        if ($moveDirection === 'top') $this->moveAnimalToTop($x, $y);
        if ($moveDirection === 'bottom') $this->moveAnimalToBottom($x, $y);
    }

    private function checkAvailableDirections($x, $y)
    {
        $availableLeft = $availableRight = $availableTop = $availableBottom = 0;

        $availableLeft = $this->lookAvailableLeftDirection($x, $y);
        $availableRight = $this->lookAvailableRightDirection($x, $y);
        $availableTop = $this->lookAvailableTopDirection($x, $y);
        $availableBottom = $this->lookAvailableBottomDirection($x, $y);

        $this->moveAnimalTo($availableLeft, $availableRight, $availableTop, $availableBottom, $x, $y);
    }

    private function lookAvailableLeftDirection($x, $y)
    {
        if (!isset($this->map[$x][$y-1])) return 0;
        elseif ($this->map[$x][$y] === 'X' && $this->map[$x][$y-1] !== 'X') return 1;
        elseif ($this->map[$x][$y-1] !== 'X' && $this->map[$x][$y-1] !== '0') return 1;
        else return 0;
    }

    private function lookAvailableRightDirection($x, $y)
    {
        if (!isset($this->map[$x][$y+1])) return 0;
        elseif ($this->map[$x][$y] === 'X' && $this->map[$x][$y+1] !== 'X') return 1;
        elseif ($this->map[$x][$y+1] !== 'X' && $this->map[$x][$y+1] !== '0') return 1;
        else return 0;
    }

    private function lookAvailableTopDirection($x, $y)
    {
        if (!isset($this->map[$x-1][$y])) return 0;
        elseif ($this->map[$x][$y] === 'X' && $this->map[$x-1][$y] !== 'X') return 1;
        elseif ($this->map[$x-1][$y] !== 'X' && $this->map[$x-1][$y] !== '0') return 1;
        else return 0;
    }

    private function lookAvailableBottomDirection($x, $y)
    {
        if (!isset($this->map[$x+1][$y])) return 0;
        elseif ($this->map[$x][$y] === 'X' && $this->map[$x+1][$y] !== 'X') return 1;
        elseif ($this->map[$x+1][$y] !== 'X' && $this->map[$x+1][$y] !== '0') return 1;
        else return 0;
    }

    private function moveAnimalToLeft($x, $y)
    {
        $temp = $this->map[$x][$y];
        $this->map[$x][$y] = '*';
        $this->map[$x][$y-1] = $temp;
    }

    private function moveAnimalToRight($x, $y)
    {
        $temp = $this->map[$x][$y];
        $this->map[$x][$y] = '*';
        $this->map[$x][$y+1] = $temp;
    }

    private function moveAnimalToTop($x, $y)
    {
        $temp = $this->map[$x][$y];
        $this->map[$x][$y] = '*';
        $this->map[$x-1][$y] = $temp;
    }

    private function moveAnimalToBottom($x, $y)
    {
        $temp = $this->map[$x][$y];
        $this->map[$x][$y] = '*';
        $this->map[$x+1][$y] = $temp;
    }

}

$endpoint =  new Endpoint(6, 6, 5);
//$endpoint->addAnimal(2,1,'Заяц');
//$endpoint->addAnimal(2,2,'Заяц');
//$endpoint->addAnimal(2,3,'Заяц');
//$endpoint->addAnimal(3,3,'Заяц');
//$endpoint->addAnimal(3,1,'Заяц');
//$endpoint->addAnimal(4,1,'Заяц');
//$endpoint->addAnimal(4,2,'Заяц');
$endpoint->addAnimal(4,3,'Заяц');
$endpoint->addAnimal(3,2,'Волк');
$endpoint->showMap();
//$endpoint->randAddAnimal(6, 'Заяц');
//$endpoint->randAddAnimal(6, 'Волк');
//$endpoint->moveAtRand();
$endpoint->makeMove();
//$endpoint->showMap();