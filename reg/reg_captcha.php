<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/08/14
 * Time: 20:41
 */


 session_start();
 $width = 100;                  // ширина
 $height = 50;                  // высота
 $font_size = 17.5;   			// размер шрифта
 $let_amount = 4;               // количество символов капчи
 $fon_let_amount = 30;          // количество символов в фоне
 $path_fonts = 'fonts/';        // путь до шрифта

 // массив - из каких символов будет состоять капча
 $letters = array('a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','9');
 // массив цветов
 $colors = array('10','30','50','70','90','110','130','150','170','190','210');

 // создаем изображение
 $src = imagecreatetruecolor($width,$height);
 // создаем фон
 $fon = imagecolorallocate($src,255,255,255);
 // заполняем этим фоном изображение
 imagefill($src,0,0,$fon);

 // массив шрифтов
 $fonts = array();
 // открываем директорию с шрифтами
 $dir=opendir($path_fonts);
 // считываем ширфты
 while($fontName = readdir($dir)) {
     // если это не текущий каталог и не дочерний
     if($fontName != "." && $fontName != "..") {
         // добавляем шрифт к массиву
         $fonts[] = $fontName;
     }
 }
 // закрываем директорию
 closedir($dir);

 // проходим в цикле от 0 - 30
 for($i=0;$i<$fon_let_amount;$i++) {
     // получаем случайный цвет символов
     $color = imagecolorallocatealpha($src,rand(0,255),rand(0,255),rand(0,255),100);
     // случайным образом выбираем шрифт
     $font = $path_fonts.$fonts[rand(0,sizeof($fonts)-1)];
     // случайным образом выбираем символ из массива
     $letter = $letters[rand(0,sizeof($letters)-1)];
     // случайным образом выбираем размер от 17.5 - 2 до 17.5 + 2
     $size = rand($font_size-2,$font_size+2);
     // рисуем символы
     imagettftext($src,$size,rand(0,45),rand($width*0.1,$width-$width*0.1),rand($height*0.2,$height),$color,$font,$letter);
 }

 // проходим в цикле от 0 - 4
 for($i=0;$i<$let_amount;$i++) {
     // получаем случайный цвет
     $color = imagecolorallocatealpha($src,$colors[rand(0,sizeof($colors)-1)],$colors[rand(0,sizeof($colors)-1)],$colors[rand(0,sizeof($colors)-1)],rand(20,40));
     // получаем случайный шрифт
     $font = $path_fonts.$fonts[rand(0,sizeof($fonts)-1)];
     // получаем случайный символ
     $letter = $letters[rand(0,sizeof($letters)-1)];
     // получаем случайный размер
     $size = rand($font_size*2.1-2,$font_size*2.1+2);
     // расположение символа по оси х
     $x = ($i+1)*$font_size + rand(4,7);
     // расположение символа по оси y
     $y = (($height*2)/3) + rand(0,5);
     // добавляем к массиву символ
     $cod[] = $letter;
     // рисуем символ
     imagettftext($src,$size,rand(0,15),$x,$y,$color,$font,$letter);
 }

 // сохраняем в сессии код из 4-х символов
 $_SESSION['img_captcha'] = implode('',$cod);

 // отправляем заголовок, что будет изображение gif
 header ("Content-type: image/gif");
 // создаем изображение gif
 imagegif($src);

?>