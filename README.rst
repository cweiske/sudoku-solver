*************
Sudoku solver
*************
A tool that solves sudoku puzzles.
Solves easy and hard puzzles.

It uses two approaches to solve a sudoku:

1. Create an index with all possible values of a cell.
   Whenever a cell's number is determined, remove this value from the
   possibilities of all cells in the same row, same column and same 3x3 square.
   Once only one possible value is left for a cell, use that.
   Repeat.
2. In addition to point 1, calculate the following for each of the possible values:
   Check if the possible value is allowed in other cells in the same row - if
   not, use it. Do the same for the same column and the same 3x3 square.


Usage
=====
Write the sudoku into a text file::

    7    5  3
    946
     5 82
         3 5
      3 1 2
     8 7
        56 7
          591
    2  4    8

Run the solver with the file name::

     $ ./bin/sudoku-solver.php examples/hard-5.txt 
     ╔═══╤═══╤═══╗
     ║7  │  5│  3║
     ║946│   │   ║
     ║ 5 │82 │   ║
     ╟───┼───┼───╢
     ║   │  3│ 5 ║
     ║  3│ 1 │2  ║
     ║ 8 │7  │   ║
     ╟───┼───┼───╢
     ║   │ 56│ 7 ║
     ║   │   │591║
     ║2  │4  │  8║
     ╚═══╧═══╧═══╝

     ╔═══╤═══╤═══╗
     ║728│645│913║
     ║946│371│825║
     ║351│829│746║
     ╟───┼───┼───╢
     ║172│983│654║
     ║693│514│287║
     ║584│762│139║
     ╟───┼───┼───╢
     ║839│156│472║
     ║467│238│591║
     ║215│497│368║
     ╚═══╧═══╧═══╝
     ✔ solved


Dependencies
============
PHP 5.4+


Author
======
Christian Weiske, cweiske+sudoku-solver@cweiske.de


License
=======
AGPLv3 or later
