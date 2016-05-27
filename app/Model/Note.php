<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Note extends Model
{

  /**
   * Не используеться
   * Метод обрезает '/publick' в имени
   * @param $img
   * @return mixed
   */
  public static function fixImg($img)
  {
    if ( !$img->image ){
      $img->image = '/files/notes/23.05.16/Imperial_Aquila_I_by_WackyCamper.jpg';
    } else {
      $img->image = str_replace('/public', '', $img->image );
    }
    return $img;
  }

  /**
   * Не используеться
   * Возвращает картинку заглушку(если нет картинки) или обрезаный путь до картинки
   * @return mixed|string
   */
  public function getImgFixed()
  {
    if ( !$this->image ){
      return  '/files/notes/23.05.16/Imperial_Aquila_I_by_WackyCamper.jpg';
    } else {
      return str_replace('/public', '', $this->image );
    }
  }

  public function getTiserImg()
  {
    if ( !$this->image ){
      return  '/files/notes/23.05.16/Imperial_Aquila_I_by_WackyCamper.jpg';
    } else {
      //Вырезаем из строки в бд не нужный путь
      $oldPath = str_replace('/files/notes/', '', $this->image );
      //Превращаем в массив оставшуеся стсроку
      $arr = explode("/", $oldPath);
      //собераем строку
      $str = "/files/notes/!300x300/" . $arr[0] . "/" . $arr[1];
      return $str;
    }
  }

  /**
   * Аксессор для url картинок
   * @param $value
   * @return mixed|string
   */
  public function getImageAttribute($value)
  {
    if ( !$value ){
      return  '/files/notes/23.05.16/Imperial_Aquila_I_by_WackyCamper.jpg';
    } else {
      return str_replace('/public', '', $value );
    }
  }

  /**
   * Аксессор для вывода даты создание. Пример: 24-05-2016 | 10:50:17
   * @param $value
   * @return string
   */
  public function getCreatedAtAttribute($value)
  {
    return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d-m-Y | H:i:s');
    //Пример как получить тайм штамп. Пример: 1464087017
    //return Carbon::createFromFormat('Y-m-d H:i:s', $value)->getTimestamp();
  }

}
