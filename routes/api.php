<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvidprojetosPorCategoriaer within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get(
    '/qualiquiz/quiz/{codQuiz}',
    '\App\Domains\QualiQuiz\Controllers\BuscarQuizController@buscarQuiz'
);
Route::post(
    '/qualiquiz/respostas',
    '\App\Domains\QualiQuiz\Controllers\RespostasQuizController@registrar'
);

Route::namespace('Api')->group(function () {
    Route::get('/synchronize', 'SynchronizeController@index');

    Route::get('/buscaPorProjetos', 'WordpressController@buscaPorProjetos');
    Route::get('/projetosPorCategoria/{categoriaid}', 'WordpressController@projetosPorCategoria');
    Route::get('/projeto/{id}', 'WordpressController@projetoPorId');
    Route::get('/categoriasArquitetura', 'WordpressController@categoriasArquitetura');
    Route::post('/feedback', 'FeedbackController@enviarEmail');
    Route::post('/alertaDeEpi', 'AlertaDeEpiController@enviarEmail');
    Route::post('/demanda-educacao', 'DemandaEducacaoController@enviarEmail');

    // cadastro profissional
    Route::get('/estados', 'EstadoController@index');
    Route::get('/estados/{estadoId}/municipios', 'MunicipioController@index');
    Route::get('/categorias-profissionais', 'CategoriaProfissionalController@index');
    Route::get('/tipos-contratacoes', 'TipoContratacaoController@index');
    Route::get('/titulacoes-academica', 'TitulacaoAcademicaController@index');
    Route::get('/instituicoes', 'InstituicaoController@index');
    Route::get('/unidades-servico', 'UnidadeServicoController@index');
    Route::get('/categorias-profissionais/{categoriaProfissionalId}/especialidades', 'CategoriaProfissionalController@especialidades');

    Route::post('/user', 'UserController@save');
    Route::get('/user/cpf-cadastrado/{cpf}', 'UserController@cpfCadastrado');
    Route::get('/user/email-cadastrado/{email}', 'UserController@emailCadastrado');
    Route::post('/auth', 'AuthController@auth');

    Route::post('/refresh-token', 'AuthController@refreshToken');

    Route::group(['middleware' => ['ApiProtectedRoute']], function () {
        Route::post('/logout', 'AuthController@logout');
        Route::get('/projetos-por-profissional', 'UserController@projetosPorProfissional');
        Route::get('/perfil', 'UserController@perfil');
        Route::put('/user', 'UserController@update');
        Route::delete('/user', 'UserController@delete');
    });
});


Route::get('/delay-textit/{segundos?}', function ($segundos = 1) {
    sleep($segundos);
});
