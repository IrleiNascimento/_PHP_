<?php
/**
 * Description of UsuarioControle
 *
 * @author Irlei
 */
require_once '../model/Usuario.php';
require_once '../model/Login.php';
require_once '../model_controle/LoginControle.php';

class UsuarioControle {

    const ADMIN = 1;
    const CLIENTE = 2;
    const FUNCIONARIO = 3;

    private $user;
    private $erros = array();

    public function getUser($array_post = null) {
        if (!$array_post) {
            return $this->user;
        } else {


            $id = 0;
            $url_img = null;

            $login = null;
            if (isset($array_post['id'])) {
                $id = (int) $array_post['id'];
            }

            if (isset($array_post['img_perfil'])) {
                $url_img = $array_post['img_perfil'];
            }
            if (
                    isset($array_post['c_pw']) &&
                    isset($array_post['username']) &&
                    isset($array_post['email'])) {

                $login = new Login(
                                $array_post['username'],
                                $array_post['email'],
                                $array_post['c_pw'],
                                $id);
            }

            if(isset($array_post['id']))
              $id = $array_post['id'];

            $this->user = new Usuario($id,
                            trim($array_post['nome']),
                            trim($array_post['sobrenome']),
                           Util::formatarData( trim($array_post['dt_nascimento'])),
                            trim($array_post['status']),
                         $array_post['uf'],
                           $array_post['cidade'],
                            trim($array_post['endereco']),
                            trim($array_post['telefone']),
                            $url_img);

            if ($login)
                $this->user->setLogin($login);

            return $this->user;
        }
        $this->erros[] = new Error('Evento', 'Erro ao criar Objeto a parti do $_POST');
        return null;
    }

    public function getArray($id, $info_login = false) {
        if ($id) {

                $login = LoginControle::getArray($id, $info_login);

            $query = "SELECT * FROM usuario WHERE id=" . $id;
            $result = ControlDb::select($query);
            if ($result != null) {
                $array = array('status' => $result['status_id'],
                    'id' => $result['id'],
                    'img_perfil' => $result['img_perfil'],
                    'nome' => $result['nome'],
                    'sobrenome' => $result['sobrenome'],
                    'telefone' => $result['telefone'],
                    'dt_nascimento' =>  $result['dt_nascimento'],
                    'uf' => $result['uf'],
                    'cidade' => $result['cidade'],
                    'endereco' => $result['endereco'],
                    'email' => $login['email'],
                    'username' => $login['username']);
                    if ($info_login && $login!=null ) {
                    $array['senha'] = key_exists('senha', $login) ? $login['senha'] : '';
                }
                return $array;
            }
            $this->erros[] = new Error('Pesquisa:', 'Informações de usuario não foram encontradas para o usuario com id  ' . $id);
        }
        return null;
    }

    public function setUser(Usuario $user) {
        $this->user = $user;
    }

    public function getErros() {
        return $this->erros;
    }

    public function getArrayErros() {
        $array = array();
        for ($i = 0; $i < sizeof($this->erros); $i++) {
            $array[$this->erros[$i]->getSource()] = $this->erros[$i]->getMessage();
        }
        return $array;
    }

    public function salvar() {

        $this->erros = self::validarUser();
        if (empty($this->erros)) {
            if ($this->user->getLogin() == null) {
                $this->erros[] = new Error('ERRO>>>', 'Informações de login ausente');
                return false;
            }
            $result = null;
            $result = ControlDb::Insert(self::prepareSQLquery(ControlDb::INSERT), true);
            if (!$result) {
                $this->erros[] = new Error('ERRO', $result);
                return false;
            }

            if ($this->user->getLogin() != null) {
                require_once 'LoginControle.php';
                $this->user->setId($result);
                $this->user->getLogin()->setId($result);
                $erros = LoginControle::insertLogin($this->user->getLogin());
                if (!empty($erros)) {
                    $this->erros[] = new Error('ERRO>>>', $erros);
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function editar() {
        $this->erros = self::validarUser();
//var_dump($this->user);
        if (empty($this->erros)) {
            $result = null;
            $result = ControlDb::update(self::prepareSQLquery(ControlDb::UPDATE));
            if (is_string($result)) {
                $this->erros[] = new Error('ERRO update()', $result);
                return false;
            } elseif ($this->user->getLogin() != null) {
                require_once 'LoginControle.php';
                $erros = LoginControle::updateLogin($this->user->getLogin());
                if (!empty($erros)) {
                    $this->erros[] = new Error('ERRO::>>  updateLogin()', $erros);
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    public function pesquisa($id, $info_login = false) {
        if ($id) {
            $query = "SELECT * FROM usuario WHERE id=" . $id;

            $login = null;

                $login = LoginControle::getLogin($id, $info_login);


            $array = ControlDb::select($query);
            if ($array) {

               $this->user = new Usuario(
                                $array['id'],
                                $array['nome'],
                                $array['sobrenome'],
                                 Util::formatarData($array['dt_nascimento']),
                                $array['status_id'],
                                $array['uf'],
                                $array['cidade'],
                                $array['endereco'],
                                $array['telefone'],
                                $array['img_perfil']);

                if ($login)
                     $this->user->setLogin($login);

                return  $this->user;
            } else {
                $this->erros[] = new Error('Pesquisa:', ' Sem resultados para o usuario com id : ' . $id);
                return null;
            }
        }
    }

   public function delete($id) {
        if ($id != null) {
            $query = "DELETE FROM usuario WHERE id=" . $id;
            $url_img = ControlDb::getResultFron('usuario', 'img_perfil', 'id', $id);
            $result = ControlDb::delete($query);
            if ($result && file_exists(Util::DIR_IMAGEM_USUARIO . $url_img)) {
                unlink(Util::DIR_IMAGEM_USUARIO . $url_img);
            }

            return $result;
        }
    }

    function prepareSQLquery($tipo) {
        switch ($tipo) {
            case ControlDb::INSERT;
                return  " INSERT INTO usuario (
                    status_id,
                    nome,
                    sobrenome,
                    telefone,
                    dt_nascimento,
                    uf,
                    cidade,
                    endereco,
                    img_perfil)
                VALUES (
                       '" . Util::safe($this->user->getStatus()) .
                        "','" . Util::safe($this->user->getNome()) .
                        "','" . Util::safe($this->user->getSobrenome()) .
                        "','" . Util::safe($this->user->getTelefone()) .
                        "','" . Util::safe($this->user->getDt_nascimento()) .
                        "','" . Util::safe($this->user->getUf()) .
                        "','" . Util::safe($this->user->getCidade()) .
                        "','" . Util::safe($this->user->getEndereco()) .
                        "','" . $this->user->getImg_perfil().
                        "')";
                break;
            case ControlDb::UPDATE:
                return "UPDATE  usuario SET
           status_id='" . Util::safe($this->user->getStatus()) . "',
           nome='" . Util::safe($this->user->getNome()) . "',
           sobrenome='" . Util::safe($this->user->getSobrenome()) . "',
           telefone='" . Util::safe($this->user->getTelefone()) . "',
           dt_nascimento='" .Util::formatarData( Util::safe($this->user->getDt_nascimento()))  . "',
           uf='" . Util::safe($this->user->getUf()) . "',
           cidade='" . Util::safe($this->user->getCidade()) . "',
           endereco='" . Util::safe($this->user->getEndereco()) . "',
           img_perfil='" .$this->user->getImg_perfil() . "'
           WHERE id=" . Util::safe($this->user->getId());
                break;
        }
    }

    public function validarUser() {
        $erros = array();
        if ($this->user != null) {
            if ($this->user->getStatus() == 0)
                $erros[] = new Error("Status", "Escolha um Status ");
            if (!trim($this->user->getNome()))
                $erros[] = new Error("Nome", "O Nome é obrigatório");
            if (!trim($this->user->getSobrenome()))
                $erros[] = new Error("Sobrenome", "O sobrenome é obrigatório");
            if (!trim($this->user->getDt_nascimento()))
                $erros[] = new Error("Nascimento", "A data de nascimento é obrigatório!");
            if ($this->user->getLogin() != null) {
                $e = Util::validarEmail($this->user->getLogin()->getEmail(), $this->user->getId());
                if (is_string($e)) {
                    $erros[] = new Error("Email",  $e);
                }
            }
        }
        return $erros;
    }

    public function upload_imagem($file_img, $atr_name, $dir) {
        require_once'../model/upload_img.php';
        if (!empty($file_img[$atr_name]) && strlen($file_img[$atr_name]['name']) > 0) {
            $upload = new Upload($file_img[$atr_name], 180, 140, $dir);
            return $upload->salvar();
        }
    }

    public static function getStatusFrom($id) {
        $tabela = 'status_usuario';
        $coluna = 'status';
        $coluna_id = 'id';
        return ControlDb::getResultFron($tabela, $coluna, $coluna_id, $id);
    }

    public static function getUserNameFrom($id) {
        $tabela = 'login';
        $coluna = 'username';
        $coluna_id = 'usuario_id';
        return ControlDb::getResultFron($tabela, $coluna, $coluna_id, $id);
    }
}?>