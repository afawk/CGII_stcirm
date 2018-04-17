<?php

namespace ImageSegmentation;

class Filters
{
    public static function median_linear(array $content, int $neigh = 9) : array
    {
        if ($neigh % 2 === 0) {
            $neigh++;
        }

        $filtered = [];

        foreach ($content as $k => $pixel) {
            $median_in = [];

            for ($i = max($k - $neigh, 0); $i < min($k + $neigh, 255); $i++) {
                $median_in[] = $content[$i];
            }

            $filtered[$k] = Rounded::get_center_val($median_in);
        }

        return $filtered;
    }


    public static function median_cube(array $content, int $cube = 3) : array
    {
        $changed = [];

        $size    = sqrt(count($content));
        $chunked = array_chunk($content, $size);

        $window = floor($cube / 2);

        // Não pego as bordas...
        for ($x = $window; $x < ($size - $window); $x++) {
            for ($y = $window; $y < ($size - $window); $y++) {
                $cube_arr = [];

                for ($j = ($x - $window); $j <= ($x + $window); $j++) {
                    for ($k = $y - $window; $k <= ($y + $window); $k++) {
                        $cube_arr[] = $chunked[$j][$k];
                    }
                }

                $changed[] = self::calc_median_cube($cube_arr);
            }
        }

        // preciso arrumar aqui para adicionar as bordas de novo.
        // tá retornando 510 ao invés de 512
        return $changed;
    }

    public static function calc_median_cube(array $content, int $window = 1) : int
    {
        array_push($content, $content[count($content) - 1]);
        array_unshift($content, $content[0]);

        $result = [];
        $count  = count($content) - 1;
        for ($i = $window; $i < $count; $i++) {
            $median = [];

            for ($j = ($i - $window); $j <= ($i + $window); $j++) {
                $median[] = $content[$j];
            }

            $result[$i] = Rounded::get_center_val($median);
        }

        return Rounded::get_center_val($result);
    }

    public static function climbing_marks(array $histogram) : array
    {
        // Localiza plateu para $red_to_green
        // Segmentação por limiar
        $red_to_green = self::search_plateu($histogram);

        // Localiza subida ingreme para $blue_to_red
        $neigh = 50;

        do {
            $blue_to_red = self::search_climbing($histogram, $neigh);
            $neigh -= 10;
        }
        while ($blue_to_red < 10 and $neigh > 10);

        return [$blue_to_red, $red_to_green];
    }

    public static function search_climbing(array $histogram, int $neigh) : int
    {
        $blue_to_red = 0;

        // Julgo que, antes de um terço da imagem, não terá nada...
        $init  = ceil(count($histogram) / 5);
        foreach ($histogram as $k => $intensity) {
            if ($k < $init) {
                continue;
            }

            // Defindo que a taxa de busca anterior é $neigh * 2
            $tax_bef = $neigh * 3;

            // Pego as ultimas $tax_bef casas
            $sum_bef = array_sum(array_slice($histogram, max(0, $k - $tax_bef), $tax_bef));
            // Pego as próximas $neigh casas
            $sum_aft = array_sum(array_slice($histogram, $k, $neigh));

            // Começou a ficar ingreme? Parou!
            if (($sum_aft * 0.9) > $sum_bef) {
                $blue_to_red = $k;
                break;
            }
        }

        return $blue_to_red;
    }

    public static function search_plateu(array $histogram) : int
    {
        $red_to_green = 255;

        $last_down = null;
        for ($is_up = $next_is_stop = false, $k = 253; $k >= 0; $k--) {

            // Subindo...
            if ($histogram[$k] > $histogram[$k + 1]) {
                $is_up = true;
            }
            // Desceu!
            else if ($histogram[$k] < $histogram[$k + 1]) {
                $next_is_stop = true;
                $last_down = $k;
            }

            // Depois da descida, começou a subir? Parou. Achamos o plateu
            if ($next_is_stop && $is_up) {
                // Tento pegar o meio do plateu :)
                $red_to_green = ceil(abs($k + $last_down) / 2);
                break;
            }

            $is_up = false;
        }

        return $red_to_green;
    }

    public static function colorize_rgb(
        array $content, int $blue_to_red, int $red_to_green
    ) : array
    {
        $colorized = [];
        $lines = count($content);

        for ($i = 0; $i < $lines; $i++) {
            $blue = $red = $green = 0;

            if ($content[$i] > 0) {
                $blue = 255;
            }

            if ($content[$i] >= $blue_to_red) {
                $blue = 0;
                $red = 255;
            }

            if ($content[$i] >= $red_to_green) {
                $red = 0;
                $green = 255;
            }

            $intensity = $content[$i];
            $colorized[$i] = compact('red', 'green', 'blue', 'intensity');
        }

        return $colorized;
    }
};