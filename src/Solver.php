<?php
class Solver
{
    public $sd;
    public $possibilities;
    public $exclusions;

    public function solve(Sudoku $sd)
    {
        $this->sd = $sd;
        $this->possibilities = array_fill(
            0, 9,
            array_fill(
                0, 9,
                array_combine(range(1, 9), range(1, 9))
            )
        );

        $arValueCoords = $this->getCellsWithValues();
        foreach ($arValueCoords as $arCoords) {
            list($row, $col) = $arCoords;
            $this->possibilities[$row][$col] = array();
        }
        foreach ($arValueCoords as $arCoords) {
            list($row, $col) = $arCoords;
            $this->reducePossibilities($row, $col);
        }
        echo $sd . "\n";

        do {
            $bChanged = false;
            do {
                //echo "--calc single\n";
                $nReduced = $this->calculateSingles();
                //$nReduced = 0;
                if ($nReduced > 0) {
                    $bChanged = true;
                    //echo $sd . "\n";
                }
            } while ($nReduced > 0);

            do {
                //echo "--calc excl\n";
                $nReduced = $this->calculateExclusions();
                if ($nReduced > 0) {
                    $bChanged = true;
                    //echo $sd . "\n";
                }
            } while ($nReduced > 0);
        } while ($bChanged);
    }

    public function calculateSingles()
    {
        $nReduced = 0;
        $arSingleCoords = $this->getSinglePossibilityCoords();
        foreach ($arSingleCoords as $arCoords) {
            list($row, $col) = $arCoords;
            $val = reset($this->possibilities[$row][$col]);
            $nReduced += $this->setValue($row, $col, $val);
        }
        return $nReduced;
    }

    public function reducePossibilities($row, $col)
    {
        $nReduced = 0;
        $val = $this->sd->data[$row][$col];

        //all in that row
        foreach ($this->possibilities[$row] as $pCol => &$arPoss) {
            if (isset($arPoss[$val])) {
                unset($arPoss[$val]);
                ++$nReduced;
            }
        }

        //all in that col
        for ($pRow = 0; $pRow < 9; $pRow++) {
            if (isset($this->possibilities[$pRow][$col][$val])) {
                unset($this->possibilities[$pRow][$col][$val]);
                ++$nReduced;
            }
        }

        //all in that square
        foreach ($this->getSquareCoords($row, $col) as $arCoords) {
            list($pRow, $pCol) = $arCoords;
            if (isset($this->possibilities[$pRow][$pCol][$val])) {
                unset($this->possibilities[$pRow][$pCol][$val]);
                ++$nReduced;
            }
        }
        return $nReduced;
    }

    public function calculateExclusions()
    {
        // calculate numbers only possible in one place in a row/col/square
        $nReduced = 0;

        //1. rows
        for ($row = 0; $row < 9; $row++) {
            $arNumbers = array();
            for ($col = 0; $col < 9; $col++) {
                if ($this->sd->data[$row][$col] != '') {
                    continue;
                }
                foreach ($this->possibilities[$row][$col] as $val) {
                    $arNumbers[$val][] = array($row, $col);
                }
            }
            foreach ($arNumbers as $val => $arValues) {
                if (count($arValues) == 1) {
                    list($row, $col) = reset($arValues);
                    $nReduced += $this->setValue($row, $col, $val);
                }
            }
        }

        //2. cols
        for ($col = 0; $col < 9; $col++) {
            $arNumbers = array();
            for ($row = 0; $row < 9; $row++) {
                if ($this->sd->data[$row][$col] != '') {
                    continue;
                }
                foreach ($this->possibilities[$row][$col] as $val) {
                    $arNumbers[$val][] = array($row, $col);
                }
            }
            foreach ($arNumbers as $val => $arValues) {
                if (count($arValues) == 1) {
                    list($row, $col) = reset($arValues);
                    $nReduced += $this->setValue($row, $col, $val);
                }
            }
        }

        //3. squares
        foreach ($this->getSquareRoots() as $arSquareRootCoords) {
            list($rRow, $rCol) = $arSquareRootCoords;
            $arNumbers = array();
            foreach ($this->getSquareCoords($rRow, $rCol) as $arCoords) {
                list($row, $col) = $arCoords;
                if ($this->sd->data[$row][$col] != '') {
                    continue;
                }
                foreach ($this->possibilities[$row][$col] as $val) {
                    $arNumbers[$val][] = array($row, $col);
                }
            }
            foreach ($arNumbers as $val => $arValues) {
                if (count($arValues) == 1) {
                    list($row, $col) = reset($arValues);
                    $nReduced += $this->setValue($row, $col, $val);
                }
            }
        }

        return $nReduced;
    }

    public function getCellsWithValues()
    {
        $arValueCoords = array();
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($this->sd->data[$row][$col] != '') {
                    $arValueCoords[] = array($row, $col);
                }
            }
        }
        return $arValueCoords;
    }

    public function getSinglePossibilityCoords()
    {
        $arCoords = array();
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if (count($this->possibilities[$row][$col]) == 1) {
                    $arCoords[] = array($row, $col);
                }
            }
        }
        return $arCoords;
    }

    public function getSquareCoords($row, $col)
    {
        $sRow = $row - $row % 3;
        $sCol = $col - $col % 3;

        return array(
            array($sRow + 0, $sCol + 0),
            array($sRow + 0, $sCol + 1),
            array($sRow + 0, $sCol + 2),
            array($sRow + 1, $sCol + 0),
            array($sRow + 1, $sCol + 1),
            array($sRow + 1, $sCol + 2),
            array($sRow + 2, $sCol + 0),
            array($sRow + 2, $sCol + 1),
            array($sRow + 2, $sCol + 2),
        );
    }

    public function getSquareRoots()
    {
        return array(
            array(0, 0), array(0, 3), array(0, 6),
            array(3, 0), array(3, 3), array(3, 6),
            array(6, 0), array(6, 3), array(6, 6),
        );
    }

    public function setValue($row, $col, $val)
    {
        if ($this->sd->data[$row][$col] != '') {
            throw new Exception(
                'Trying to overwrite existing value: '
                . $row . ',' . $col 
                . ' - ' . $this->sd->data[$row][$col]
                . ' with ' . $val
            );
        }
        $this->sd->data[$row][$col] = $val;
        $this->possibilities[$row][$col] = array();
        return $this->reducePossibilities($row, $col);
    }
}
?>
