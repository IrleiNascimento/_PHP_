<?php
require_once '../page_controle/is_sessao.php';
require_once '../model_controle/ServicoControle.php';

require_once '../model/enum.php';

 $item = $_SESSION['item'];

  $current_dir=Util::DIR_IMAGEM_SERVICO;
   $img =Util::DIR_IMAGEM_ITEM_PADRAO;
    if(!isset($item['tp']) && $_GET['item']){
        $item['tp']=$_GET['item'];
    }
    if(  $item['tp']==enum::EVENTO){
        require_once '../model_controle/EventoControle.php';
        $current_dir=Util::DIR_IMAGEM_EVENTOS;
    }


if(file_exists($current_dir.$item['url_img']))
         $img=$current_dir.$item['url_img'];
$is_ev=true;

//var_dump( $item);
    ?>
    <div class="conteudo_item">
        <div id="img_detalhes_item">
            <img id="url_img" src="<?php echo  $img; ?>" />
        </div>
        <div id="detalhes_item">

            <?php if ($item ['tp'] && $item['tp'] == enum::EVENTO) { ?>
                <label class="detalhes_item">
                    <span id="tipo">Tipo:</span><output class="output_text" ><?php echo EventoControle::getTipoFrom($item['tipo']) ?></output>
                </label>
            <?php } elseif (isset($item['tp']) && $item['tp'] == enum::SERVICO) { $is_ev=false;?>
                <label class="detalhes_item">
                    <span id="tipo">Categoria:</span>
                    <output class="output_text"  ><?php echo ServicoControle::getCategoriaFrom($item['categoria']) ?></output>
                </label>
   <label class="detalhes_item">
                <span id="data">Data de início:</span> <output  class="output_text" ><?php echo Util::formatarData($item['dt_entrada'],0) ?></output>
            </label>
                <label class="detalhes_item">
                    <span id="periodo">Periodo:</span>
                    <output class="output_text"  ><?php echo ServicoControle::getPeriodoFrom($item['periodo']) ?></output>
                </label>

            <?php } ?>
            <label class="detalhes_item">
                <span >Titulo:</span>
                <output class="output_text"  id="titulo"><?php echo $item['titulo'] ?></output>
            </label>
            <label class="detalhes_item">
                <span >Descrição:</span>
                <textarea readonly class="output_textarea" id="descricao" ><?php echo $item['descricao'] ?></textarea>
            </label>
            <div class="info_localidade_correspondecia">
                <label class="detalhes_item"><span>UF:</span><output class="output_text" ><?php echo Util::getUF($item['uf']); ?></output>
               </label>
               <label class="detalhes_item">
               <span>Cidade: </span>
               <output class="output_text" ><?php echo Util::getCidadeFromID($item['cidade']); ?></output>
                </label>
              </label>
                <label class="detalhes_item">
                    <span id="endereco">Endereço:</span>
                    <output class="output_text"  ><?php echo $item['endereco'] ?></output>
                </label>
            </div>
              <label class="detalhes_item">
                    <span id="endereco">Telefone:</span>
                    <output class="output_text"  ><?php echo $item['telefone'] ?></output>
                </label>
            <?php if($is_ev){?>
            <label class="detalhes_item">
                <span id="data">Data:
                <output style="width:140px;" class="output_text" ><?php echo Util::formatarData($item['data'],0) ?></output></span> <span id="hora">Hora: <output style="width:120px;"class="output_text" ><?php echo $item['hora'] ?></output></span>
            </label> <?php } ?>
            <label class="detalhes_item">
                <span>Site:</span>
                <output class="output_text"><a href="<?php echo $item['link'] ?>"><?php echo $item['link'] ?></a></output>
            </label>
            <label class="detalhes_item">
                <span>Rede Social:</span>
                <output class="output_text"><a href="<?php echo $item['rede_social']; ?>">
                        <?php echo $item['rede_social'] ?></a></output>
            </label>
            <div  id="info_localizacao">
                <h3>Geo  lacalização:</h3>
                    <span>Latitude: <output style="width:160px;" class="output_text"><?php echo $item['latitude'] ?></output></span><span>longitude:<output class="output_text"><?php echo $item['longitude'] ?></output></span>

            </div>
        </div>
    </div>