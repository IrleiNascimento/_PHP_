<?php

/**
 * Description of EventoControle
 *
 * @author Irlei
 */
require_once '../model/evento.php';
require_once '../model/upload_img.php';
require_once '../model/ControlDb.php';
require_once '../util/Util.php';
require_once '../model/Error.php';
require_once '../model/contato.php';
require_once '../model_controle/ContatoControle.php';
require_once 'AbstractControle.php';

class EventoControle extends AbstractControle {

    private $evento;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    function setItem(Item $evento) {
        $this->evento = $evento;
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
        //var_dump($array_post);
        if (!$array_post) {
            return $this->evento;
        } else {

            $dat = null;
            if (isset($array_post['data']) && isset($array_post['hora']))
                $dat = $array_post['data'] . " " . $array_post['hora'];
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
                $contato = ContatoControle::getArrayContato('telefones_eventos', 'evento_id', $id);
            }
            $this->evento = new Evento($id,
                            trim($array_post['titulo']),
                            trim($array_post['descricao']),
                            $dat,
                            $array_post['uf'],
                            trim($array_post['cidade']),
                            trim($array_post['endereco']),
                            trim($array_post['latitude']),
                            trim($array_post['longitude']),
                            $url_img,
                            trim($array_post['link']),
                            trim($array_post['rede_social']),
                            trim($array_post['email']),
                            (int) $array_post['tipo'],
                            $contato);

            self::setContatoItem($array_post['telefone']);
            return $this->evento;
        }
        $this->erros[] = new Error('Evento', 'Erro ao criar Objeto a parti do $_POST');
        return null;
    }

    function salvar() {
        $this->erros = self::validarItem();
        if (empty($this->erros)) {
            $result = ControlDb::Insert(self::prepareSQLquery(ControlDb::INSERT), true);
            if (!$result) {
                $this->erros[] = new Error('ERRO INSERT', "kjfdfkfjdk");
                return false;
            }
            $this->evento->setId($result);
            if ($this->evento->getContato() != null)
                foreach ($this->evento->getContato() as $c):
                    ContatoControle::insertContato('telefones_eventos', 'evento_id', $c, $result);
                endforeach;
            return true;
        }

        return false;
    }

    function delete($id) {
        if ($id != null) {
            $query = "DELETE FROM evento WHERE id=" . $id;
            $url_img = ControlDb::getResultFron('evento', 'url_img', 'id', $id);
            $result = ControlDb::delete($query);

            if ($result && file_exists(Util::DIR_IMAGEM_EVENTOS.$url_img))
                unlink(Util::DIR_IMAGEM_EVENTOS.$url_img);
        }

        return $result;
    }

    function editar() {
        $this->erros = self::validarItem();
        if (empty($this->erros)) {
            $result = ControlDb::update(self::prepareSQLquery(ControlDb::UPDATE));
            //caso o relacionamento entre as tabelas evento e telefones_evento tenha sido alterado ou nao exista
            if (!is_string($result)) {
                if ($this->evento->getContato() != null) {
                    $id = $this->evento->getId();
                    // faz o update de cada telefone com id igual ao do evento
                    foreach ($this->evento->getContato() as $c):
                        $result = ContatoControle::updateContato('telefones_eventos', $c, $id, 'evento_id');
                    endforeach;
                }
            }else {
                $this->erros[] = new Error("Erro   function update()  >>", $result);
            }

            return $result;
        }
        return false;
    }

    // recupera um evento e retorna um  array com os atributos do memso
    function getArrayItem($id) {
        $query = "SELECT * FROM evento WHERE id=" . $id;
        $result = ControlDb::select($query);
        if ($result != null) {

                $data_hora = explode(" ",$result['data_evento']);

            return Array('tipo' => $result['tipo_ev'],
                'id' => $result['id'],
                'titulo' => $result['titulo'],
                'url_img' => $result['url_img'],
                'descricao' => $result['descricao'],
                'data' => $data_hora[0],
                'hora' => substr($data_hora[1], 0, 5),
                'uf' => $result['uf'],
                'cidade' => $result['cidade'],
                'endereco' => $result['endereco'],
                'latitude' => $result['latitude'],
                'longitude' => $result['longitude'],
                'link' => $result['link'],
                'telefone' => ControlDb::getAllContatos('telefones_eventos', 'evento_id', $id),
                'email' => $result['email'],
                'rede_social' => $result['rede_social']);
        }
        return null;
    }

    function pesquisa($id) {

        if ($id != null) {
            $query = "SELECT * FROM evento WHERE id=" . $id;
            $array = ControlDb::select($query);
            if ($array != null) {
                $evento = new Evento(
                                $array['id'],
                                $array['titulo'],
                                $array['descricao'],
                                $array['data_evento'],
                                $array['uf'],
                                $array['cidade'],
                                $array['endereco'],
                                $array['latitude'],
                                $array['longitude'],
                                $array['url_img'],
                                $array['link'],
                                $array['rede_social'],
                                $array['email'],
                                $array['tipo_ev']);

                $evento->setContato(ContatoControle::getArrayContato('telefones_eventos', 'evento_id', $id));
                return $evento;
            }
        }
        return null;
    }

    function prepareSQLquery($tipo) {
        switch ($tipo) {
            case ControlDb::INSERT;
                $execute = " INSERT INTO evento (titulo, descricao, data_evento, uf,cidade, endereco, latitude, longitude, url_img, link, email,rede_social,tipo_ev,user_id)
                VALUES (
                       '" . Util::safe($this->evento->getTitulo()) .
                        "','" . Util::safe($this->evento->getDescricao()) .
                        "','" . date("Y-m-d H:s:i",strtotime($this->evento->getDataTime()))  .
                        "','" . $this->evento->getUf() .
                        "','" . $this->evento->getCidade() .
                        "','" . Util::safe($this->evento->getEndereco()) .
                        "','" . Util::safe($this->evento->getLatitude()) .
                        "','" . Util::safe($this->evento->getLogitude()) .
                        "','" . Util::safe($this->evento->getImg_url()) .
                        "','" . Util::safe($this->evento->getLink()) .
                        "','" . Util::safe($this->evento->getEmail()) .
                        "','" . Util::safe($this->evento->getRede_social()) .
                        "'," . $this->evento->getTipo() .
                        //o id do usuario logado deve
                        "," . $this->getId() . ")";
  //print_r($execute);

                return $execute;
                break;
            case ControlDb::UPDATE:
                $execute = "UPDATE  evento SET
           titulo='" . Util::safe($this->evento->getTitulo()) . "',
           descricao='" . Util::safe($this->evento->getDescricao()) . "',
           data_evento='" . date("Y-m-d H:s:i",strtotime($this->evento->getDataTime()))  . "',
           uf='" . $this->evento->getUf() . "',
           cidade='" . $this->evento->getCidade() . "',
           endereco='" . Util::safe($this->evento->getEndereco()) . "',
           latitude='" . Util::safe($this->evento->getLatitude()) . "',
           longitude='" . Util::safe($this->evento->getLogitude()) . "',
           url_img='" . Util::safe($this->evento->getImg_url()) . "',
           link='" . Util::safe($this->evento->getLink()) . "',
           email='" . Util::safe($this->evento->getEmail()) . "',
           rede_social='" . Util::safe($this->evento->getRede_social()) . "',
           tipo_ev=" . $this->evento->getTipo() . ",
           user_id=" . $this->getId() . "
           WHERE id=" . $this->evento->getId();
                // print_r($execute);

                return $execute;
                break;
        }
    }

    function validarItem() {
        $erros = array();
        if ($this->evento != null) {
            if ($this->evento->getTipo() == 0)
                $erros[] = new Error("Tipo", "Escolha um tipo");
            if (!trim($this->evento->getTitulo()))
                $erros[] = new Error("Titulo", "O Título é obrigatório");
            if (!trim($this->evento->getDescricao()))
                $erros[] = new Error("Descrição", "A descrição é obrigatória");
            if (!trim($this->evento->getDataTime()))
                $erros[] = new Error("Data", "Data inválida");
            if (!trim($this->evento->getCidade()))
                $erros[] = new Error("Cidade", "A cidade é obrigatória");
            if (!trim($this->evento->getEndereco()))
                $erros[] = new Error("Endereco", "O endereço é obrigatório");
        }
        return $erros;
    }

    public function setContatoItem($telefone_s, $id = 0) {
        if ($telefone_s != null) {
            $tels = explode(",", $telefone_s);
            $array = $this->evento->getContato();
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
                    $array[] = new Contato($tels[$i], $id);
                    $add++;
                } else {
                    // se um telefone for apagado
                    $array[$i]->setTelefone('');
                }
            }
            // se o array foi alterado entao atualiza
            if ($add)
                $this->evento->setContato($array);
        }
    }

static function getTipoFrom($int_value) {
        switch ($int_value) {
            case Evento::CULTURA:
                return 'Cultura';
                break;
            case Evento::FESTA:
                return 'Festa';
                break;
            default :
                return'';
        }
    }
}

?>