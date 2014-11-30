<?php
/**
 * Description of ServicoControle
 *
 * @author Irlei
 */
require_once '../model/servico.php';
require_once 'AbstractControle.php';
require_once '../model/contato.php';
require_once '../model_controle/ContatoControle.php';

class ServicoControle extends AbstractControle {

    private $servico;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    function setItem(Item $servico) {
        $this->servico = $servico;
    }

    function getErros() {
        return $this->erros;
    }

    function getArrayErros() {
        $array = array();
        for ($i = 0; $i < sizeof($this->erros); $i++) {
            $array[$this->erros[$i]->getSource()] = $this->erros[$i]->getMessage();
        }

        return $array;
    }

    //cria um Evento a partir de um array
    function getItem($array_post = null) {
        if (!$array_post) {
            return $this->servico;
        } else {
            //var_dump($array_post);

            $id = 0;
            $url_img = null;

            if (isset($array_post['id'])) {
                $id = (int) $array_post['id'];
            }

            if (isset($array_post['url_img'])) {
                $url_img = $array_post['url_img'];
            }

            //recupera os telefones ser for uma edição
            $contato = null;
            if ($id) {
                $contato = ContatoControle::getArrayContato('telefones', 'servicoid', $id);

            }
            $this->servico = new Servico($id,
                            trim($array_post['titulo']),
                            trim($array_post['descricao']),
                            Util::formatarData($array_post['dt_entrada']),
                            $array_post['uf'],
                            $array_post['cidade'],
                            trim($array_post['endereco']),
                            trim($array_post['latitude']),
                            trim($array_post['longitude']),
                            $url_img,
                            trim($array_post['link']),
                            trim($array_post['rede_social']),
                            trim($array_post['email']),
                            $array_post['categoria'],
                            $array_post['periodo'],
                            $contato);
            return $this->servico;
        }
        $this->erros[] = new Error('Servico', 'Erro ao criar Objeto a parti do $_POST');
        return null;
    }

    function salvar() {
        $this->erros = self::validarItem();
        if (empty($this->erros)) {
           
            $result = ControlDb::Insert(self::prepareSQLquery(ControlDb::INSERT), true);
            if (!$result) {
                $this->erros[] = new Error('ERRO INSERT', $result);
                return false;
            }
            $this->servico->setId($result);
            foreach ($this->servico->getContato() as $c):
                ContatoControle::insertContato('telefones', 'servicoid', $c, $result);
            endforeach;
            return true;
        }

        return false;
    }

 function delete($id) {
        if ($id != null) {
            $query = "DELETE FROM servicos WHERE id=" . $id;
            $url_img = ControlDb::getResultFron('servicos', 'url_img', 'id', $id);
            $result = ControlDb::delete($query);
            if ($result && file_exists(Util::DIR_IMAGEM_SERVICO . $url_img)) {
                unlink(Util::DIR_IMAGEM_SERVICO . $url_img);
            }
        }

        return $result;
    }

    function editar() {
        $this->erros = self::validarItem();
        if (empty($this->erros)) {
            $result = ControlDb::update(self::prepareSQLquery(ControlDb::UPDATE));
            //caso o relacionamento entre as tabelas servico e telefones_servico tenha sido alterado ou nao exista
            if (!is_string($result)) {
                if ($this->servico->getContato() != null) {
                    $id = $this->servico->getId();
                    // faz o update de cada telefone com id igual ao do servico
                    foreach ($this->servico->getContato() as $c):
                        $result = ContatoControle::updateContato('telefones', $c, $id, 'servicoid');
                    endforeach;
                }
            }else {
                $this->erros[] = new Error("Erro   function update()  >>", $result);
            }

            return $result;
        }
        return false;
    }

    /* recupera um servico e retorna um  array com os atributos do memso */

    function getArrayItem($id) {
        $query = "SELECT * FROM servicos WHERE id=" . $id;
        $result = ControlDb::select($query);
        //var_dump($result);
        if ($result != null) {
            return Array('categoria' => $result['categoria_id'],
                'id' => $result['id'],
                'periodo' => $result['periodo_id'],
                'titulo' => $result['titulo'],
                'url_img' => $result['url_img'],
                'descricao' =>$result['descricao'],
                'dt_entrada' =>  $result['dt_entrada'],
                'uf' => $result['uf'],
                'cidade' => $result['cidade'],
                'endereco' => $result['endereco'],
                'latitude' => $result['latitude'],
                'longitude' => $result['longitude'],
                'link' => $result['site'],
                'telefone' => ControlDb::getAllContatos('telefones', 'servicoid', $id),
                'email' => $result['email'],
                'rede_social' => $result['perfil_fb']);
        }
    }

    function pesquisa($id) {

        if ($id != null) {
            $query = "SELECT * FROM servicos WHERE id=" . $id;

            $array = ControlDb::select($query);

            if ($array != null) {
                $servico = new servico(
                                $array['id'],
                                $array['titulo'],
                                $array['descricao'],
                                $array['dt_entrada'],
                                $array['uf'],
                                $array['cidade'],
                                $array['endereco'],
                                $array['latitude'],
                                $array['longitude'],
                                $array['url_img'],
                                $array['site'],
                                $array['perfil_fb'],
                                $array['email'],
                                $array['categoria_id'],
                                $array['periodo_id']);

                $servico->setContato(ContatoControle::getArrayContato('telefones', 'servicoid', $id));
                return $servico;
            }
        }
        return null;
    }

    function prepareSQLquery($tipo) {
        switch ($tipo) {
            case ControlDb::INSERT;
               $execute = " INSERT INTO servicos (categoria_id, titulo,
                    descricao,
                    dt_entrada,
                    periodo_id,
                    endereco,
                    uf,
                    cidade,
                    email,
                    site,
                    latitude,
                    longitude,
                    url_img,
                    perfil_fb,
                    user_id)
                    VALUES ('" . Util::safe($this->servico->getTipo()) .
                        "','" . Util::safe($this->servico->getTitulo()) .
                        "','" . Util::safe($this->servico->getDescricao()) .
                        "','" . Util::safe($this->servico->getDataTime()) .
                        "','" . Util::safe($this->servico->getPeriodo()) .
                        "','" . Util::safe($this->servico->getEndereco()) .
                        "','" . $this->servico->getUf() .
                        "','" . $this->servico->getCidade() .
                        "','" . Util::safe($this->servico->getEmail()) .
                        "','" . Util::safe($this->servico->getLink()) .
                        "','" . Util::safe($this->servico->getLatitude()) .
                        "','" . Util::safe($this->servico->getLogitude()) .
                        "','" . Util::safe($this->servico->getImg_url()) .
                        "','" . Util::safe($this->servico->getRede_social()) .
                        "'," . $this->getId() .
                        ")";
           return $execute ;
                break;
            case ControlDb::UPDATE:

               $execute = "UPDATE  servicos SET
           categoria_id=" . $this->servico->getTipo() . ",
           titulo='" . Util::safe($this->servico->getTitulo()) . "',
           descricao='" . Util::safe($this->servico->getDescricao()) . "',
           periodo_id='" . $this->servico->getPeriodo() . "',
           endereco='" . Util::safe($this->servico->getEndereco()) . "',
           uf='" . $this->servico->getUf() . "',
           cidade='" . $this->servico->getCidade() . "',
           email='" . Util::safe($this->servico->getEmail()) . "',
           site='" . Util::safe($this->servico->getLink()) . "',
           latitude='" . Util::safe($this->servico->getLatitude()) . "',
           longitude='" . Util::safe($this->servico->getLogitude()) . "',
           url_img='" . Util::safe($this->servico->getImg_url()) . "',
           perfil_fb='" . Util::safe($this->servico->getRede_social()) . "',
           user_id='" . $this->getId() . "'
           WHERE id=" . Util::safe($this->servico->getId());
           //print_r($execute);
           return $execute ;
                break;
        }
    }

    function validarItem() {
        $erros = array();
        if ($this->servico != null) {
            if ($this->servico->getTipo() == 0)
                $erros[] = new Error("Tipo", "Escolha uma categoria");
            if (!trim($this->servico->getTitulo()))
                $erros[] = new Error("Titulo", "O Título é obrigatório");
            if (!trim($this->servico->getDescricao()))
                $erros[] = new Error("Descrição", "A descrição é obrigatória");
            if (!trim($this->servico->getCidade()))
                $erros[] = new Error("Cidade", "A cidade é obrigatória");
            if (!trim($this->servico->getEndereco()))
                $erros[] = new Error("Endereco", "O endereço é obrigatório");
        }
        return $erros;
    }

    public function setContatoItem($telefone_s, $id = 0) {
        if ($telefone_s != null) {
            $tels = explode(",", $telefone_s);
            $array = $this->servico->getContato();
            $add = 0;
            $t1 = sizeof($array);
            $t2 = sizeof($tels);
            //pegua o valor do maior array
            $tam = $t1 < $t2 ? $t2 : $t1;
            for ($i = 0; $i < $tam; $i++) {
                if (isset($tels[$i]) && isset($array[$i])) {
                    $array[$i]->setTelefone($tels[$i]);
                } elseif (isset($tels[$i]) && !isset($array[$i])) {
                    //se existir um novo telefone ele sera inserido no array
                    $array[] = new Contato($tels[$i]);
                    $add++;
                } else {
                    // se um telefone for apagado
                    $array[$i]->setTelefone('');
                }
            }
            // se o array foi alterado entao atualiza
            if ($add) {
                $this->servico->setContato($array);
            }
        }
    }

    public static function getCategoriaFrom($id) {
        $tabela = 'categoria_servico';
        $coluna = 'descri';
        $coluna_id = 'id';
        return ControlDb::getResultFron($tabela, $coluna, $coluna_id, $id);
    }

    public static function getPeriodoFrom($id) {
        $tabela = 'periodo_anuncio';
        $coluna = 'descri';
        $coluna_id = 'id';
        return ControlDb::getResultFron($tabela, $coluna, $coluna_id, $id);
    }}

?>