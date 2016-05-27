<?php

namespace App\Http\Controllers\Blog\Notes;

use Auth;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Model\Note;
use DB;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;
use Intervention\Image\ImageManagerStatic as Image;

class NotesController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     *
     * Показ формы добавления Заметки
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createShow()
    {
        $notes = Note::select()
          ->orderBy('created_at', 'desc')
          ->paginate(20);

        return view('blog.notes.addNote',[
            'notes' => $notes
        ]);
    }

    /**
     * Форма добавления заметки
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdd(Request $request)
    {
        //Рабаота с загрузкой файла
        //Получаем файл
        $file = $request->file('files');
        //Проверяем что он загрузился
        if ( $request->hasFile('files') )
        {
            //Получаем текющую дату
            $today = date("d.m.y");
            //Устанавливаем папуку куда скопируем файл
            $destinationPath = base_path() . '/public/files/notes/' . $today;
            //Получаем имя файла
            $fileName = time() . '-' . $file->getClientOriginalName();
            //Перемещаяем файл
            $file->move($destinationPath, $fileName);

            //ОБрезаем так же копируемый файл чтоб показывать его в тизере до размера 400х280
            //http://image.intervention.io/getting_started/installation
            //https://laracasts.com/discuss/channels/general-discussion/laravel-5-image-upload-and-resize?page=1
            $destinationPath300 = base_path() . '/public/files/notes/!300x300/' . $today;
            //Создаем новую директорию если ее нет
            if ( !file_exists($destinationPath300)){
                mkdir($destinationPath300);
            }
            //Копируем туда файл
            copy( $destinationPath . '/' . $fileName, $destinationPath300 . '/' . $fileName);
            //Обрезаем до нужного размера
            Image::make( $destinationPath300 . '/' . $fileName )->fit(400, 280)->save($destinationPath300 . '/' . $fileName);
        }

        //Работа с оставшимися атрибутами
        $title = $request->input(['title']);
        $image = $request->hasFile('files') ? '/public/files/notes/' . $today . '/' .  $fileName: null;
        $body = $request->input(['body']);
        $show = $request->input(['visible']) === 'on' ? true : false;

        //Запись в БД
        DB::beginTransaction();
        try{
            $note = new Note();
            $note->title = $title;
            $note->image = $image;
            $note->body = $body;
            $note->user = Auth::id();
            $note->show = $show;
            $note->save();

            DB::commit();
        } catch (Exception $e) {
            if ( $request->hasFile('files') ){
                File::delete( base_path() . '/public/files/notes/' . $today . '/' . $fileName);
                File::delete( base_path() . '/public/files/notes/!300x300/' . $today . '/' . $fileName);
            }
            DB::rolLback();
        }

        return redirect()->back();
    }

    /**
     * Возвращает все заметки с пагинацией
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAll()
    {
        $notes = Note::where('show', true)
          ->orderBy('created_at', 'desc')
          ->paginate(10);

        return view('blog.notes.showAllNotes',[
            'notes' => $notes
        ]);
    }

    /**
     * Возвращает единственную заметку по $id
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showOne($id)
    {
        $note = Note::findOrFail($id);

        if ( $note->show ){

            return view('blog.notes.showOneNote',[
              'note' => $note
            ]);

        } else {
            return view('errors.404',[
                'error' => 'Заметка скрыта от паказа'
            ]);
        }
    }
}
