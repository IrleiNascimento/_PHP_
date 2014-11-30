<?php

/* Desenvolvido por Marco Antoni <marquinho9.10@gmail.com> */

/**
 * Adaptado
 *
 * @author Irlei
 */
class upload {

    private $arquivo;
    private $altura;
    private $largura;
    private $dir;

    function __construct($arquivo, $altura, $largura, $dir) {
        $this->arquivo = $arquivo;
        $this->altura = $altura;
        $this->largura = $largura;
        $this->dir = $dir;
    }

    private function getExtensao() {
//retorna a extensao da imagem
        $str = explode('.', strrev($this->arquivo['name']));
        return strrev($str[0]);
    }

    private function ehImagem($extensao) {
        $extensoes = array('gif', 'jpeg', 'jpg', 'png');
// extensoes permitidas

        if (in_array($extensao, $extensoes))
            return true;
    }

    //largura, altura, tipo, localizacao da imagem original
    private function redimensionar($imgLarg, $imgAlt, $tipo, $img_localizacao) {
   //descobrir novo tamanho sem perder a proporcao
        $novaLarg = $imgLarg;
        $novaAlt = $imgAlt;
        if ($imgLarg > $imgAlt) {
            $novaLarg = $this->largura;
            $novaAlt = round(($novaLarg / $imgLarg) * $imgAlt);
        } elseif ($imgAlt > $imgLarg) {
            $novaAlt = $this->altura;
            $novaLarg = round(($novaAlt / $imgAlt) * $imgLarg);
        }
        else
            $novaAltura = $novaLargura = max($this->largura, $this->altura);
        //redimencionar a imagem //cria uma nova imagem com o novo tamanho
        $novaimagem = imagecreatetruecolor($novaLarg, $novaAlt);
        switch ($tipo) {
            case 1:
                $origem = imagecreatefromgif($img_localizacao);
                imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0, $novaLarg, $novaAlt, $imgLarg, $imgAlt);
                imagegif($novaimagem, $img_localizacao);
                break;
            case 2:
                $origem = imagecreatefromjpeg($img_localizacao);
                imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0, $novaLarg, $novaAlt, $imgLarg, $imgAlt);
                imagejpeg($novaimagem, $img_localizacao);
                break;
            case 3:
// png
                $origem = imagecreatefrompng($img_localizacao);
                imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0, $novaLarg, $novaAlt, $imgLarg, $imgAlt);
                imagepng($novaimagem, $img_localizacao);
                break;
        }
////destroi as imagens criadas
        imagedestroy($novaimagem);
        imagedestroy($origem);
    }

    public function salvar() {
        $extensao = $this->getExtensao();
        //gera um nome unico para a imagem em funcao do tempo
                   $novo_nome =  'img_' . time() . '.' . $extensao;
        //localizacao do arquivo
        $destino = $this->dir . $novo_nome;
        //move o arquivo
        if (!move_uploaded_file($this->arquivo['tmp_name'], $destino)) {
            return null;
        }
        if ($this->ehImagem($extensao)) {
            //pega a largura, altura, tipo e atributo da imagem
            list($largura, $altura, $tipo, $atributo) = getimagesize($destino);
            // testa se é preciso redimensionar a imagem
            if (($largura > $this->largura) || ($altura > $this->altura)) {
                $this->redimensionar($largura, $altura, $tipo, $destino);
            }
            return $novo_nome;
        }
    }

    }
?>
