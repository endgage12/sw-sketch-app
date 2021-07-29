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
                $this->map[$coordinateX][$coordinateY] = 0;
            }

            if ($type === 'Волк') {
                $this->map[$coordinateX][$coordinateY] = 'X';
            }

            $amount--;
        }
    }

    /**
     * Случайное перемещение действующего лица на 1 в любую сторону, приоритет хода за волком
     * Не проверяет нижние границы
     */
    public function moveAtRand()
    {
        $this->checkIsAte();
        foreach ($this->map as $vertical => $arr_y) {
            foreach ($arr_y as $horizontal => $value) {
                if ($value === 'X' || $value === '0') {
                    $ch = rand(0, 3);
                    switch ($ch) {
                        case 0: // Вверх
                            $this->map[$vertical][$horizontal] = '1';
                            if ($vertical > 0) {
                                if ($value === 'X' && $this->map[$vertical - 1][$horizontal] !== 'X') { $this->map[$vertical - 1][$horizontal] = 'X'; $this->logMoves(0); }
                                    else { $this->map[$vertical + 1][$horizontal] = 'X'; $this->logMoves(1);} // если сверху занято другим, то пойти вниз
                                if ($value === '0' && $this->map[$vertical - 1][$horizontal] !== '0') { $this->map[$vertical - 1][$horizontal] = '0'; $this->logMoves(0);}
                                    else { $this->map[$vertical + 1][$horizontal] = '0'; $this->logMoves(1);}// если сверху занято другим, то пойти вниз
                            } elseif ($value === 'X') { $this->map[$vertical + 1][$horizontal] = 'X'; $this->logMoves(1);}
                            elseif ($value === '0') { $this->map[$vertical + 1][$horizontal] = '0'; $this->logMoves(1);}
                            break;
                        case 1: // Вниз
                            $this->map[$vertical][$horizontal] = '1';
                            if ($value === 'X' && $this->map[$vertical + 1][$horizontal] !== 'X') $this->map[$vertical + 1][$horizontal] = 'X';
                                else
                            if ($value === '0') $this->map[$vertical + 1][$horizontal] = '0';
                            break;
                        case 2: // Влево
                            $this->map[$vertical][$horizontal] = '1';
                            if ($horizontal != 0) {
                                if ($value === 'X') $this->map[$vertical][$horizontal - 1] = 'X';
                                if ($value === '0') $this->map[$vertical][$horizontal - 1] = '0';
                            } elseif ($value === 'X') $this->map[$vertical][$horizontal + 1] = 'X';
                            elseif ($value === '0') $this->map[$vertical][$horizontal + 1] = '0';
                            break;
                        case 3: // Вправо
                            $this->map[$vertical][$horizontal] = '1';
                            if ($value === 'X') $this->map[$vertical][$horizontal + 1] = 'X';
                            if ($value === '0' && $this->map[$vertical][$horizontal + 1] !== '0') $this->map[$vertical][$horizontal + 1] = '0';
                                else $this->map[$vertical][$horizontal - 1] = '0'; // если справа занято, пойти влево
                            break;
                    }
                }
            }
        }
        $this->checkIsAte();
    }

    public function makeMove()
    {
        foreach ($this->map as $vertical => $arr_y)
        {
            foreach ($arr_y as $horizontal => $value)
            {
                if ($value === 'X' || $value === '0') {
                    $this->checkAvailableDirections($vertical, $horizontal, $value);
//                    $this->showMap(); Для пошагового разбора движений каждого животного
                }
            }
        }
    }

    private function moveAnimalTo($left, $right, $top, $bottom, $x, $y, $animal)
    {
        $listDirection = [
            'left' => $left,
            'right' => $right,
            'top' => $top,
            'bottom' => $bottom,
        ];

        foreach ($listDirection as $direction)
        {
            if ($direction === 0) unset($direction);
        }

        $moveDirection = array_rand($listDirection); // string с названием ключа
        if ($moveDirection === 'left') $this->moveAnimalToLeft($x, $y, $animal);
        if ($moveDirection === 'right') $this->moveAnimalToRight($x, $y, $animal);
        if ($moveDirection === 'top') $this->moveAnimalToTop($x, $y, $animal);
        if ($moveDirection === 'bottom') $this->moveAnimalToBottom($x, $y, $animal);
    }

    private function checkAvailableDirections($x, $y, $animal)
    {
        $availableLeft = $availableRight = $availableTop = $availableBottom = 0;

        $availableLeft = $this->lookAvailableLeftDirection($x, $y);
        $availableRight = $this->lookAvailableRightDirection($x, $y);
        $availableTop = $this->lookAvailableTopDirection($x, $y);
        $availableBottom = $this->lookAvailableBottomDirection($x, $y);

        $this->moveAnimalTo($availableLeft, $availableRight, $availableTop, $availableBottom, $x, $y, $animal);
    }

    private function lookAvailableLeftDirection($x, $y)
    {
        if ($this->map[$x][$y-1] !== 'X' && $this->map[$x][$y-1] !== '0') return 1;
        else return 0;
    }

    private function lookAvailableRightDirection($x, $y)
    {
        if ($this->map[$x][$y+1] !== 'X' && $this->map[$x][$y+1] !== '0') return 1;
        else return 0;
    }

    private function lookAvailableTopDirection($x, $y)
    {
        if ($this->map[$x-1][$y] !== 'X' && $this->map[$x-1][$y] !== '0') return 1;
        else return 0;
    }

    private function lookAvailableBottomDirection($x, $y)
    {
        if (!isset($this->map[$x+1][$y])) return 0;
        if ($this->map[$x+1][$y] !== 'X' && $this->map[$x+1][$y] !== '0') return 1;
        else return 0;
    }

    private function moveAnimalToLeft($x, $y, $animal)
    {
        $this->map[$x][$y] = '*';
        $this->map[$x][$y-1] = $animal;
    }

    private function moveAnimalToRight($x, $y, $animal)
    {
        $this->map[$x][$y] = '*';
        $this->map[$x][$y+1] = $animal;
    }

    private function moveAnimalToTop($x, $y, $animal)
    {
        $this->map[$x][$y] = '*';
        $this->map[$x-1][$y] = $animal;
    }

    private function moveAnimalToBottom($x, $y, $animal)
    {
        $this->map[$x][$y] = '*';
        $this->map[$x+1][$y] = $animal;
    }

    private function checkIsAte()
    {
        foreach ($this->map as $vertical => $arr_y)
        {
            foreach ($arr_y as $horizontal => $value) {
                if ($value === '0'
                    && isset($this->map[$vertical-1][$horizontal])
                && isset($this->map[$vertical+1][$horizontal])
                && isset($this->map[$vertical][$horizontal-1])
                && isset($this->map[$vertical][$horizontal+1]) )
                {
                    if ($vertical != 0)
                    {
                        if ($this->map[$vertical-1][$horizontal] === 'X') $this->map[$vertical][$horizontal] = 'e'; //Сверху
                        if ($this->map[$vertical-1][$horizontal-1] === 'X') $this->map[$vertical][$horizontal] = 'e'; //Диагональ вверх-влево
                        if ($this->map[$vertical-1][$horizontal+1] === 'X') $this->map[$vertical][$horizontal] = 'e'; //Диагональ вверх-вправо
                    }

                    if ($horizontal != 0)
                    {
                        if ($this->map[$vertical][$horizontal-1] === 'X') $this->map[$vertical][$horizontal] = 'e'; //Влево
                        if ($this->map[$vertical+1][$horizontal-1] === 'X') $this->map[$vertical][$horizontal] = 'e'; //Диагональ вниз-влево
                    }

                    if ($this->map[$vertical+1][$horizontal] === 'X') $this->map[$vertical][$horizontal] = 'e'; //Снизу

                    if ($this->map[$vertical][$horizontal+1] === 'X') $this->map[$vertical][$horizontal] = 'e'; //Вправо

                    if ($this->map[$vertical+1][$horizontal+1] === 'X') $this->map[$vertical][$horizontal] = 'e'; //Диагональ вниз-вправо
                }
            }
        }
    }

    private function logMoves($direction)
    {
        switch ($direction)
        {
            case 0:
                echo 'Ушел вверх';
                break;
            case 1:
                echo 'Ушел вниз';
                break;
            case 2:
                echo 'Ушел влево';
                break;
            case 3:
                echo 'Ушел вправо';
                break;
        }
    }
}

$endpoint =  new Endpoint(6, 6, 5);
$endpoint->addAnimal(3,1,'Заяц');
$endpoint->addAnimal(3,2,'Заяц');
$endpoint->addAnimal(5,3,'Волк');
//$endpoint->randAddAnimal(6, 'Заяц');
//$endpoint->randAddAnimal(6, 'Волк');
//$endpoint->moveAtRand();
$endpoint->makeMove();