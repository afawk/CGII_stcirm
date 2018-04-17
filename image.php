<?php

require 'vendor/autoload.php';
require 'src/bootstrap.php';

use ImageSegmentation\{Filters,Image,Rounded};

if (!isset($_GET['i'])) {
    return;
}

$img = $_GET['i'];
$type = 'rgb';

if (!empty($_GET['type'] ?? null)) {
    $type = $_GET['type'];
}

$content = Image::get_by_pixel_array("resources/imagens/fatia{$img}.txt");

if ($type != 'grey') {
    $content = Filters::median_cube($content, 3);

    $histogram = Filters::median_linear(Rounded::create_histogram($content, 300), 9);

    $marks = Filters::climbing_marks($histogram);
}

switch ($type) {
    case 'hist':
        Image::show_histogram($histogram, 255 * 3, 300, $marks);
    break;

    case 'rgb':
        $content = Filters::colorize_rgb($content, ...$marks);
        Image::show_colorized($content, function ($img, $colors) {
            return imagecolorallocate($img,
                $colors['red'],
                $colors['green'],
                $colors['blue']
            );
        });
    break;

    case 'r':
        $content = Filters::colorize_rgb($content, ...$marks);
        Image::show_colorized($content, function ($img, $colors) {
            return imagecolorallocate($img,
                $colors['red'] ? : $colors['intensity'],
                $colors['red'] ? 0 : $colors['intensity'],
                $colors['red'] ? 0 : $colors['intensity']
            );
        });
    break;

    case 'g':
        $content = Filters::colorize_rgb($content, ...$marks);
        Image::show_colorized($content, function ($img, $colors) {
            return imagecolorallocate($img,
                $colors['green'] ? 0 : $colors['intensity'],
                $colors['green'] ? : $colors['intensity'],
                $colors['green'] ? 0 : $colors['intensity']
            );
        });
    break;

    case 'b':
        $content = Filters::colorize_rgb($content, ...$marks);
        Image::show_colorized($content, function ($img, $colors) {
            return imagecolorallocate($img,
                $colors['blue'] ? 0 : $colors['intensity'],
                $colors['blue'] ? 0 : $colors['intensity'],
                $colors['blue'] ?: $colors['intensity']
            );
        });
    break;

    case 'grey':
        Image::show_gray($content);
    break;
}