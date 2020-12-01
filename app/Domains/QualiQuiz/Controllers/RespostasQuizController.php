<?php

declare(strict_types=1);

namespace App\Domains\QualiQuiz\Controllers;

use App\Domains\QualiQuiz\Service\RespostaQuizService;
use App\Domains\QualiQuiz\Utils\JWTDecoder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Classe controller para rotas de salvar as respostas do quiz.
 *
 * @category Qualiquiz
 *
 * @author   Chicão Thiago <fthiagogv@gmail.com>
 * @license  GPL-3 http://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * @link     https://github.com/EscolaDeSaudePublica/isus-api/issues/121
 */
class RespostasQuizController extends Controller
{
    /**
     * Recebe um json com o formato:
     * {
     *    "respostas": [
     *      {
     *        "quizId": "number",
     *        "questaoId": "number",
     *        "alternativaId": "number",
     *      }
     *    ]
     * }.
     *
     * @param $request        Request
     * @param $jwtDecoder     JWTDecoder
     * @param $respostaQuizService RespostaQuizService
     *
     * @return mix
     */
    public function registrar(
        Request $request,
        JWTDecoder $jwtDecoder,
        RespostaQuizService $respostaQuizService
    ) {
        if (!$request->header('Authorization')) {
            return response()->json(['message' => 'Token não enviado'], 403);
        }

        $autenticacao = $jwtDecoder->decoderPayload(
            $request->header('Authorization')
        );

        if (Validator::make($autenticacao->toArray(), ['email' => 'required'])->fails()) {
            return response()->json(['message' => 'Token inválido'], 403);
        }

        $validacao = Validator::make(
            $request->all(),
            [
                'respostas' => 'required',
                'respostas.*.quizId' => 'required',
                'respostas.*.questaoId' => 'required',
                'respostas.*.alternativaId' => 'required',
            ]
        );

        if ($validacao->fails()) {
            return response()->json(
                ['message' => $validacao->errors()->toArray()],
                400
            );
        }

        $resultado = $respostaQuizService->registrarRespostas(
            collect($request->respostas),
            $autenticacao
        );

        return response()->json(
            ['message' => $resultado['msg']],
            $resultado['status']
        );
    }
}
