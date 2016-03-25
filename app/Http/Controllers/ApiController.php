<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Mexitek\PHPColors\Color;

class ApiController extends Controller
{
    public function front(Request $request, $dimensions)
    {
        list($width, $height) = explode('x', $dimensions, 2);
        $width = intval($width);
        $height = intval($height);
        if (!(0 < $width && $width <= 2000)) {
            $width = 1;
        }
        if (!(0 < $height && $height <= 2000)) {
            $height = 1;
        }
        
        $format = $request->input('format');
        if (empty($format) || !in_array($format, array('jpg', 'png', 'gif'), true)) {
            $format = 'png';
        }
        
        $bgcolor = $request->input('bgcolor');
        if(preg_match('/^[a-f0-9]{3,6}$/i', $bgcolor)) {
            $color = new Color($bgcolor);
            $norBgcolor = $color->getHex();
        } else {
            $norBgcolor = 'cccccc';
        }
        
        $txtcolor = $request->input('txtcolor');
        if(preg_match('/^[a-f0-9]{3,6}$/i', $txtcolor)) {
            $color = new Color($txtcolor);
            $norTxtcolor = $color->getHex();
        } else {
            $norTxtcolor = '808080';
        }
        
        $txtsize = intval($request->input('txtsize'));
        if (empty($txtsize)) {
            $txtsize = 32;
        }
        
        $text = $request->input('txt');
        if (!isset($text)) {
            $text = $width . 'x' . $height;
        }
        
        $image = Image::canvas($width, $height, '#' . $norBgcolor)
                ->text($text, intval($width * 0.5), intval($height * 0.5), function ($font) use ($txtsize, $norTxtcolor) {
                    $font->file(config('fonts')['file']);
                    $font->size($txtsize);
                    $font->color('#' . $norTxtcolor);
                    $font->align('center');
                    $font->valign('middle');
                });

        return $image->response($format);
    }
}
