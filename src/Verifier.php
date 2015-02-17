<?php
class Verifier
{
    public function verify(Sudoku $sd)
    {
        $values = 0;
        for ($row = 0; $row <= 8; $row++) {
            for ($col = 0; $col <= 8; $col++) {
                if ($sd->data[$row][$col] != '') {
                    ++$values;
                }
            }
        }
        if ($values < 81) {
            throw new Exception(
                'Not fully filled - ' . $values . '/81'
            );
        }

        //rows
        foreach ($sd->data as $lnum => $arLine) {
            $sum = array_sum($arLine);
            if ($sum != 45) {
                throw new Exception(
                    'Sum of line ' . $lnum . ' is not 45 but ' . $sum
                );
            }
        }

        //cols
        for ($n = 0; $n < 9; $n++) {
            $sum = array_sum(array_column($sd->data, $n));
            if ($sum != 45) {
                throw new Exception(
                    'Sum of column ' . $lnum . ' is not 45 but ' . $sum
                );
            }
        }

        //squares
        $squares = array(
            array(0, 0), array(0, 3), array(0, 6),
            array(3, 0), array(3, 3), array(3, 6),
            array(6, 0), array(6, 3), array(6, 6),
        );
        foreach ($squares as $sqpos) {
            list($row, $col) = $sqpos;
            $sum = 0;
            for ($nr = $row; $nr < $row + 3; $nr++) {
                for ($nc = $col; $nc < $col + 3; $nc++) {
                    $sum += $sd->data[$nr][$nc];
                }
            }
            if ($sum != 45) {
                throw new Exception(
                    'Sum of square ' . $row . ',' . $col
                    . ' is not 45 but ' . $sum
                );
            }
        }
    }
}
?>
