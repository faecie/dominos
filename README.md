# Dominoes Game

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/faecie/dominos/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/faecie/dominos/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/faecie/dominos/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Build Status](https://scrutinizer-ci.com/g/faecie/dominos/badges/build.png?b=master)](https://scrutinizer-ci.com/g/faecie/dominos/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/faecie/dominos/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/faecie/dominos/?branch=master)

Dominoes is a family of games played with rectangular tiles. Each tile is divided into two square ends. Each end is marked with a number (one to
six) of spots or is blank. There are 28 tiles, one for each combination of spots and blanks (see image).
Write a program which allows two players to play Dominoes against each other:
 - The 28 tiles are shuffled face down and form the stock. Each player draws seven tiles
 - Pick a random tile to start the line of play
 - The players alternately extend the line of play with one tile at one of its two ends
 - A tile may only be placed next to another tile, if their respective values on the
connecting ends are identical
 - If a player is unable to place a valid tile, they must keep on pulling tiles from the stock
until they can
 - The game ends when one player wins by playing their last tile
 - You're not supposed to create an interactive application. Just write a program that will
follow the rules above

## Quick start
1. To see how this programm is working firstly you need to download it.
    if you have Git - you can just clone it:

    `git clone git@github.com:faecie/dominos.git`

    If not you need to download and unpack it 

    `https://github.com/faecie/dominos/archive/master.zip`
2. This program needs either PHP 7.4 with Composer or Docker to be installed on your local machine to run the game. 
If you have it then just go to the project folder and execute a helper make command:

    `make run`

    It will either install composer dependencies and run program on your local machine or build a Docker image and execute
    the program inside a container.  It will also remove the image and container afterwards
    
    Sorry to say - there is no other way around. Install Docker or PHP 7.4 with Composer and run make comand

## Implementation:

The initial "white boarding" of a problem available as photos in the [resource](../blob/master/resource/) folder

