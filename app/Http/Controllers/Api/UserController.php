<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\UnidadeServico;
use App\Model\UnidadesServicoCategoria;
use App\Model\User;
use App\Model\UserKeycloak;
use App\Service\KeycloakService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function save(Request $request)
    {
        $dados = $request->all();
        $validacao = $this->validarRequisicao($dados);
        if ($validacao->fails()) {
            return response()->json(['sucesso' => false, 'erros' =>  $validacao->errors()]);
        }
        $userKeycloak = new UserKeycloak($dados);
        $keyCloakService = new KeycloakService();
        $user = $keyCloakService->save($userKeycloak);

        if (!empty($user->id_keycloak)) {
            return response()->json(['sucesso' => true, 'mensagem' =>  'Usuário cadastrado com sucesso']);
        }
    }

    public function projetosPorProfissional(Request $request)
    {
        $usuario = User::where('id_keycloak', $request->usuario->sub)->first();

        if ($usuario) {
            $unidadesDoUsuario = $usuario->unidadesServicos()->get()->pluck('unidade_servico_id');
            $macroUnidadesDeSaude = UnidadeServico::pegarMacroUnidadeDeServico($unidadesDoUsuario);
            $projetosDoProfissional = [];

            if ($unidadesDoUsuario->contains(UnidadeServico::ISUS_CATEGORIA_UTI)) {
                $macroUnidadesDeSaude = $macroUnidadesDeSaude->push(UnidadeServico::find(UnidadeServico::ISUS_CATEGORIA_UTI));
            }
            foreach ($macroUnidadesDeSaude as $macroUnidadeDeSaude) {
                $projetosPorMacrounidades = $this->projetosPorMacroUnidades($macroUnidadeDeSaude);
                $projetosDoProfissional = array_merge($projetosDoProfissional, $projetosPorMacrounidades);
            }

            return response()->json([
                'sucesso' => true,
                'projetosDoProfissional' => array_unique($projetosDoProfissional, SORT_REGULAR),
            ]);
        }

        return response()->json([
            'sucesso' => false,
            'mensagem' => 'Usuário não existe',
        ]);
    }

    public function perfil(Request $request)
    {
        $usuario = User::where('id_keycloak', $request->usuario->sub)->first();
        $dadosUsuario = $usuario->dadosUsuario();

        return response()->json([
            'sucesso' => true,
            'data' =>  $dadosUsuario,
        ]);
    }

    public function update(Request $request)
    {
        $dados = $request->all();
        $validacao = $this->validarRequisicaoUpdate($dados);
        if ($validacao->fails()) {
            return response()->json(['sucesso' => false, 'erros' =>  $validacao->errors()]);
        }

        $userKeycloak = new UserKeycloak($dados);
        $keyCloakService = new KeycloakService();
        $user = $keyCloakService->update($userKeycloak, $request->usuario->sub);

        if (!empty($user->id_keycloak)) {
            return response()->json(['sucesso' => true, 'mensagem' =>  'Usuário atualizado com sucesso']);
        }
    }

    public function cpfCadastrado($cpf)
    {
        $dados = ['cpf' => $cpf];
        $validacao = Validator::make($dados, [
            'cpf' => 'required|cpf|min:11|max:11',
        ]);

        if ($validacao->fails()) {
            return response()->json(['sucesso' => true, 'mensagem' =>  $validacao->errors()]);
        }

        $keycloakService = new KeycloakService();
        $cpfCadastrado = $keycloakService->verificarSeExisteDadoNaPropriedade('CPF', $cpf);
        if ($cpfCadastrado) {
            return response()->json(['cpf_existe' => true, 'mensagem' =>  'CPF já cadastrado no ID Saúde']);
        }

        return response()->json(['cpf_existe' => false]);
    }

    public function emailCadastrado($email)
    {
        $dados = ['email' => $email];
        $validacao = Validator::make($dados, [
            'email' => 'required|email',
        ]);

        if ($validacao->fails()) {
            return response()->json(['sucesso' => true, 'mensagem' =>  $validacao->errors()]);
        }

        $keycloakService = new KeycloakService();
        $username = $email;
        $emailCadastrado = $keycloakService->keyCloakRetornaUsuarioPorUsername($username);
        if ($emailCadastrado) {
            return response()->json(['email_existe' => true, 'mensagem' =>  'EMAIL já cadastrado no ID Saúde']);
        }

        return response()->json(['email_existe' => false]);
    }

    public function delete(Request $request)
    {
        $keyCloakService = new KeycloakService();
        try {
            $idKeycloak = $request->usuario->sub;
            $keycloakService = new KeycloakService();

            if ($keycloakService->delete($idKeycloak)) {
                return response()->json(['sucesso' => true, 'mensagem' => 'Usuário excluído com sucesso']);
            }
        } catch (Exception $error) {
            return response()->json(['sucesso' => false, 'erros' =>  'Não foi possível excluir usuário'], Response::HTTP_BAD_REQUEST);
        }
    }

    private function projetosPorMacroUnidades($macroUnidadeDeSaude)
    {
        $projetosPorMacrounidades = [];
        $unidadeServicoCategoria = $macroUnidadeDeSaude->unidadesServicoCategoria()->first();
        $categoria = $unidadeServicoCategoria->categoria()->first();

        if (null !== $categoria) {
            if (in_array($categoria->term_id, UnidadesServicoCategoria::WORDPRESS_CATEGORIAS_VALIDAS)) {
                $categoriasProjetos = $categoria->categoriaProjetos()->get();

                foreach ($categoriasProjetos as $categoriaProjeto) {
                    $projeto = $categoriaProjeto->projeto()->first();
                    $projetosPorMacrounidades[] = [
                        'id' => $projeto->id,
                        'slug' => $projeto->slug,
                        'post_date' => $projeto->data,
                        'post_title' => $projeto->post_title,
                        'post_content' => $projeto->content,
                        'image' => $projeto->image,
                        'anexos' => $projeto->anexos()->get(),
                    ];
                }
            }
        }

        return $projetosPorMacrounidades;
    }

    private function validarRequisicao($dados)
    {
        return Validator::make($dados, [
            'email' => 'required|email|unique:users',
            'nomeCompleto' => 'required',
            'senha' => 'min:8|required|required_with:repetirsenha|same:repetirsenha',
            'repetirsenha' => 'min:8|required',
            'telefone' => 'required|min:9|max:11',
            'cpf' => 'required|cpf|min:11|max:11|unique:users',
            'cidadeId' => 'required',
            'cidade' => 'required',
            'termos' => 'accepted',
        ]);
    }

    private function validarRequisicaoUpdate($dados)
    {
        return Validator::make($dados, [
            'email' => 'required|email',
            'nomeCompleto' => 'required',
            'telefone' => 'required|min:9|max:11',
            'cpf' => 'required|cpf|min:11|max:11',
            'cidadeId' => 'required',
            'cidade' => 'required',
            'termos' => 'accepted',
        ]);
    }
}
