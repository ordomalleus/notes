var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
//компиляция без карт кода
elixir.config.sourcemaps = false;

elixir(function(mix) {
    //включаем sass
    mix.sass('app.scss');

    //SCSS для заметок
    mix.sass(['blog/notes/notes.scss'], 'public/css/blog/notes/notes.css'); //для всех заметок
    mix.sass(['blog/notes/note.scss'], 'public/css/blog/notes/note.css');   //для одной заметки



    //включаем скрипты
    mix.scripts(
        [
            'script.js'
        ], 'public/js/main.js'
    );



    mix.styles(
        [ '../bootstrap-material/css/bootstrap-material-design.min.css' ],
        'public/css/bootstrap-material/bootstrap-material-design.min.css'
    );
    mix.styles(
        [ '../bootstrap-material/css/ripples.min.css' ],
        'public/css/bootstrap-material/ripples.min.css'
    );

    mix.copy('resources/assets/bootstrap-material/js', 'public/js/bootstrap-material');

});
