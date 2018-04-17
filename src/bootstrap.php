<?php

chdir(__DIR__);

use ImageSegmentation\{Filters,Image,Rounded};

$img = '01';
if (is_numeric($_GET['i'] ?? null)) {
    $img = $_GET['i'];
}

$type = 'img';
if (!empty($_GET['type'] ?? null)) {
    $type = $_GET['type'];
}

$content = Image::get_by_pixel_array("resources/imagens/fatia{$img}.txt");

$content = Filters::median_cube($content, 3);
//Image::show_gray($content);

$histogram = Filters::median_linear(Rounded::create_histogram($content, 300), 9);

$marks = Filters::climbing_marks($histogram);

$content = Filters::colorize_rgb($content, ...$marks);


if ($type == 'hist') {
    Image::show_histogram($histogram, 255 * 3, 300, $marks);
}
else {
    Image::show_colorized($content);
}
