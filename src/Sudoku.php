<?php
class Sudoku
{
    public $data;

    public function __toString()
    {
        //return $this->renderMinimal();
        //return $this->renderSmall();
        return $this->renderPretty();
    }

    public function renderPretty()
    {
        $str = '';
        $str .= "╔═══╤═══╤═══╗\n";
        foreach ($this->data as $row => $arLine) {
            if ($row > 0 && $row % 3 == 0) {
                $str .= "╟───┼───┼───╢\n";
            }
            $str .= '║';
            foreach ($arLine as $col => $val) {
                if ($col > 0 && $col % 3 == 0) {
                    $str .= '│';
                }
                if (is_int($val)) {
                    $str .= $val;
                } else {
                    $str .= ' ';
                }
            }
            $str .= "║\n";
        }
        $str .= "╚═══╧═══╧═══╝\n";
        return $str;
    }

    public function renderSmall()
    {
        $str = '';
        foreach ($this->data as $row => $arLine) {
            if ($row > 0 && $row % 3 == 0) {
                $str .= "---+---+---\n";
            }
            foreach ($arLine as $col => $val) {
                if ($col > 0 && $col % 3 == 0) {
                    $str .= '|';
                }
                if (is_int($val)) {
                    $str .= $val;
                } else {
                    $str .= ' ';
                }
            }
            $str .= "\n";
        }
        return $str;
    }

    public function renderMinimal()
    {
        $str = '';
        foreach ($this->data as $lnum => $arLine) {
            foreach ($arLine as $cnum => $val) {
                if (is_int($val)) {
                    $str .= $val;
                } else {
                    $str .= ' ';
                }
            }
            $str .= "\n";
        }
        return $str;
    }
}
?>
