getXmlHttpRequest = function() {
    var req = null;
    try {
        // Firefox, Safari, IE7
        req = new XMLHttpRequest();

    } catch (e) {
        try {
            // IE
            req = new ActiveXObject('MSXML2.XMLHTTP');
        } catch (e) {
            try {
                // IE
                req = new ActiveXObject('Microsoft.XMLHTTP');
            }
            catch (e) {
                alert("Your browser does not support AJAX!");
                return null;
            }
        }
    }
    return req;
}
/**
 *
 *
 * @author Irlei
 */
function login(){

    var form = document.getElementById("form_login");
    var count=0;
    if(form.username.value == ''){
        set_msg_erro('login_username', 'Nome de usuario ou email')
        count++;
        form.username.focus();
    }else if(form.senha.value == ''){
        set_msg_erro('login_senha','Digite a senha');
        count++;
        form.senha.focus();
    }
    if(count==0){
        var hash = CryptoJS.SHA512(form.senha.value);
        var pw = document.createElement("input");
        pw.name = "c_pw";
        pw.type="hidden";
        pw.value = hash;
        form.senha.value='';
        form.appendChild(pw);
        form.submit();
    }
}

function login_erro(){
    var form = document.getElementById("form_login");
    msg_form_empty(0);
}

function mascara(){
    var mask = document.getElementById('mascara');
    //colocando o fundo escuro
    var alturaTela = $(document).height();
    var larguraTela = $(window).width();
    $(mask).css({
        'width':larguraTela,
        'height':alturaTela
    });
    $(mask).fadeIn(1000);
    $(mask).fadeTo("slow",0.9);
    $(document).ready(function(){
        $("a[rel=modal]").click( function(ev){
            ev.preventDefault();
            var id = $(this).attr("href");
            var alturaTela = $(document).height();
            var larguraTela = $(window).width();

            //colocando o fundo preto
            $(mask).css({
                'width':larguraTela,
                'height':alturaTela
            });
            $(mask).fadeIn(1000);
            $(mask).fadeTo("slow",0.8);

            var left = ($(window).width() /2) - ( $(id).width() / 2 );
            var top = ($(window).height() / 2) - ( $(id).height() / 2 );

            $(id).css({
                'top':top,
                'left':left
            });
            $(id).show();
        });

        $('.fechar').click(function(ev){
            ev.preventDefault();
            $("#mascara").hide();
            $(window).hide();
        });

    });

}

function editarinfoLogin(req_id){
    mascara();
    var div = document.getElementById('popup_confirme');

    var func ='redirect(\''+req_id+'\')';
    div.innerHTML ='<div class="popup">'+
    '<form  id="form_popup_msg_cofirme_senha" method="post">'+
    '<h4 class="popup_msg_cofirme" >Autentica&ccedil;&atilde;o</h4>'+
    '<div class="conteudo_popup_msg_cofirme">'+
    '<label class="conteudo_popup_msg_cofirme_label">'+
    '<span>Senha:<input class="input_text" id="edit_info_login_senha" type="password"  name="cofirme_senha" /></span></label><br>'+
     '<input onclick="'+func+'" class="lnk_control_popup" type="button" value="Continuar"/>'+
    '<input  class="lnk_control_popup" onclick="ocultarMascara();" type="button" value="Cancelar"/>'+
    '</div></form></div>';

    $(div).css({
        'width':'300px',
        'top':'200px',
        'height':'200px',
        'letf':'-300px;',
        'font-size': '20px',
        'background': '#E8E8E8'


    });
    $(div).slideDown("hide",0)
    $(div).fadeIn(5000);

}

function redirect(req_id){
    /**
     * req_id  tratase da id do usuario que solicitou a autenticação
     **/
    var form = document.getElementById('form_popup_msg_cofirme_senha');

    /**
     * criptografando a os dados
     **/
    var hash = CryptoJS.SHA512(form.cofirme_senha.value);
    var url= '../page_controle/confirme_autentic.php'
    if(hash!=null){

        /**
     * adicinando  campos hidden ao formulario #form_popup_msg_cofirme_senha
     **/
        var pw = document.createElement("input");
        form.appendChild(pw);
        pw.name = "c_pw";
        pw.type = "hidden";
        pw.value = hash;

        var req = document.createElement("input");
        form.appendChild(req);
        req.name = "req";
        req.type = "hidden";
        req.value = req_id;

        form.setAttribute('method','post');
        form.setAttribute('action', url);

        form.cofirme_senha.value='';
        form.submit();
        /**
        * eliminado os dados
        **/
        form.reset();
        document.removeChild(req);
        document.removeChild(pw);
    }
}

function popup_confirme(id,tipo){

    if(id!=null && tipo!=null){
        mascara();
        var div = document.getElementById('popup_confirme');


        if(tipo=='usuario'){
            tp = 3;
            var nome = document.getElementById('nome');
            var email= document.getElementById('email');
            var tel= document.getElementById('telefone');
            var img_perfil= document.getElementById('img_perfil');

            url_img_ =img_perfil.getAttribute('src')==""? getDirImagens(1):img_perfil.getAttribute('src');
            var func ='excluirItem('+id+','+tp+')';
            $(div).html('<div class="popup">'+
                '<img  class="img_popup_confirme"  src="'+ url_img_+'"/>'+
                '<h3 class="popup_msg_cofirme" >Excluir Usu&aacute;rio</h3>'+
                '<ul style="text-align:left;"><li>'+
                '<label>'+nome.textContent+'</label>'+
                '</li><li>'+
                '<label> '+email.textContent+'</label>'+
                ' </li><li>'+
                '<label> '+tel.textContent+'</label>'+
                '</li></ul>'+
                '</div>'+
                '<div class="conteudo_popup_msg_cofirme_label"><input onclick="'+func+'" class="lnk_control_popup" type="button" value="Sim">'+
                '<input  class="lnk_control_popup" onclick="ocultarMascara();" type="button" value="N&atilde;o">'+
                '</div>');


            $(div).fadeIn(200);
        }else{

            var titulo = document.getElementById('titulo');
            var descri= document.getElementById('descricao');
            var url_img= document.getElementById('url_img');

            var tp;
            if(tipo=='evento'){
                tp = 1
            }else{
                tp = 2;
            }

            url_img_ =url_img.getAttribute('src')==""? getDirImagens(1):url_img.getAttribute('src');
            var func ='excluirItem('+id+','+tp+')';
            $(div).html('<div class="popup">'+
                '<img  class="img_popup_confirme"  src="'+ url_img_+'"/>'+
                '<h3 class="popup_msg_cofirme" >Excluir Item</h3>'+
                '<ul><li>'+
                '<label><output class="output_text">'+titulo.textContent+'</label>'+
                '</li><li>'+
                '<label><output class="output_text">'+descri.textContent+'</output></label>'+
                '</li></ul></div>'+
                '<div class="op_item"><input onclick="'+func+'" class="lnk_control" type="button" value="Sim">'+
                '<input  class="lnk_control" onclick="ocultarMascara();" type="button" value="N&atilde;o">'+
                '</div>');

            $(div).fadeIn(200);
        }
        $(div).css({
            'top':'200px',
            'display':'block',
            'width':'600px',
            'background': '#E8E8E8',
            'margin-left':'30%'
        });
    }
}

function ocultarMascara(){
    $('#popup_confirme').hide();
    $('#mascara').hide();

}

function excluirItem(id,tipo){
    var uri = "../page_controle/deletar_item.php?id="+id+"&tp="+tipo;
    window.location=uri;
}

function getArrayUF(){
    var estados = new Array("----",
        "Acre",
        "Alagoas",
        "Amazonas",
        "Amap&aacute;",
        "Bahia",
        "Cear&aacute;",
        "Distrito Federal",
        "Esp&iacute;rito Santo",
        "Goi&agrave;s",
        "Maranh&atilde;o",
        "Minas Gerais",
        "Mato Grosso do Sul",
        "Mato Grosso",
        "Par&aacute",
        "Para&iacuteba",
        "Pernambuco",
        "Piau&iacute;",
        "Paran&aacute;",
        "Rio de Janeiro",
        "Rio Grande do Norte",
        "Rond&ocirc;nia",
        "Roraima",
        "Rio Grande do Sul",
        "Santa Catarina",
        "Sergipe",
        "S&atilde;o Paulo",
        "Tocantins");
    return estados;
}

function criarOpcaoUF(c) {
    var ufs ='<option id="uf_0" value="0">----</option>';
    var estados = getArrayUF();
    for(i=1;i<estados.length;i++){
        ufs +='<option id="uf_'+i+'" value="'+i+'">'+estados[i]+'</option>"';
        $("#uf").html(ufs);


    }
}

function selectLocalidade(uf){
    document.getElementById("uf").selectedIndex = uf;
    obterCidades();
}

function atualizalocalidade(cid){
    $('#h_cidade').val(cid);
}

function obterCidades(cid) {
    var param=$('#uf').val();
    $("#cidade").html('<option value="0">Carregando...</option>');
    $.get("../page_controle/obter_localidade.php", {
        uf:param
    },
    function(valor){
        $("#cidade").html(valor);
        if (typeof(cid) !="undefined" && cid!=0)
            $("#cidade").val(cid);

    });

}

function simpleEmail(email_v){
    var form = document.getElementById('form');
    var el=document.getElementById('validar_email');
    var valor =form.email.value;
    if(valor == null || valor == "" || email_v == valor){
        return;
    }
    if(!getRegx(valor)){
        $('.msg_erro_input_email').html('O email parece ser invalido...');
        animeEl($('.msg_erro_input_email'),0.7);
        return;
    }
    $('.msg_erro_input_email').fadeTo('hide',0.0);
}

function getRegx(str){
    var re = /^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/;
    return re.test(str);

}

function validarEmail(email_v,id){
    if(email_v!=null){
        var form = document.getElementById('form');
        var el=document.getElementById('validar_email');
        var valor =form.email.value;
        if(valor == null || valor == '' || email_v == valor){
            return;
        }
         $('.msg_erro_input_email').html('<img src="../imagens/sistem/load.gif">');
        if(getRegx(valor)){
            var req = getXmlHttpRequest();
            var method = 'GET';
            var uri = '../page_controle/validar_email.php?email='+valor+'&id='+id;
            req.open(method, uri, true);
            req.onreadystatechange = function(){
                if (req.readyState == 4) {
                    if(req.responseText !=true){
                        $('.msg_erro_input_email').html( req.responseText);
                        animeEl($('.msg_erro_input_email'),0.7)
                        $(el).val('false');

                    }else{
                        $('.msg_erro_input_email').html('OK');
                       $(el).val('true');
                        animeEl($('.msg_erro_input_email'),0.0);

                    }
                }
            }
            req.send(null);

        }else{
            $(el).attr('value','false');
            $('.msg_erro_input_email').html('O email parece ser invalido...');
            animeEl($('.msg_erro_input_email'),0.7);
            return;
        }

    }
     animeEl($('.msg_erro_input_email'),0.7);
}

function animeEl(e,_float){
    if(e!=null){
        $(e).fadeTo("slow",_float);
        $(e).fadeIn(5000);
        $(e).focus();
    }
}

function dicaDeSenha(){
    var form = document.getElementById('form');
    var re = /(?=.*\d)(?=.*[0-9].[a-zA-Z]).{6,}/;
    if(form.senha.value.length<6){
        set_msg_erro('senha', 'A senha precisa ter no m&iacute;nimo 6 caracteres!');
    }else if (!re.test(form.senha.value)){
        set_msg_erro('senha', 'A senha precisa conter letra e numeros!');
    }else{
        $('.msg_erro_input_senha').html('');
        $('.msg_erro_input_senha').hide();
    }
}

function validarSenha(){
    var form = document.getElementById('form');
    if(form.senha.value!="" && form.c_senha.value!=""){
        if(form.senha.value!=form.c_senha.value){
            set_msg_erro('c_senha', 'Senhas  diferentes!');
        }else{
            $('.msg_erro_input_c_senha').hide();
        }
    }
}

function set_msg_erro(campo,msg){
    if(msg!=null && campo!=null){
        msg_erro=msg;
    } else{
        msg_erro='Este campo &eacute; obrigat&oacute;rio'
    }

    var el='.msg_erro_input_'+campo;
    $(el).html(msg_erro);
    $(el).fadeTo("slow",0.6);
    $(el).fadeIn(10000);



}

function erroClean(){
    $('.msg_erro_form').hide();
    $('#form').focus();
}

function validarCadastroUser(){
    var form = document.getElementById('form');
    var div_info_login = document.getElementById('info_login');
    var count=0;
    var hash=null;
    var erros = new Array();

    //informacões de login podem estar ocultas em uma edcão
    if(div_info_login!=null){
        hash =CryptoJS.SHA512(form.senha.value);
    }

    if(hash!=null){
        var pw = document.createElement("input");
        form.appendChild(pw);
        $(pw).hide();
        pw.name = "c_pw";
        pw.value = hash;
        form.senha.value='';
        form.c_senha.value='';
    }
     if(form.dt_nascimento.value!=''){
        form.submit();
        form.reset();
    }else{
        msg_form_empty(4);
    }
}




// array para adicionar exeções de campos de formularios
var execao_form_servico =['img','link','rede_social','longitude','latitude','dt_entrada'];
var execao_form_evento = ['telefone','email','hora','img','link','rede_social','longitude','latitude'];
var execao_form_user =['telefone','img'];

function getExecaoInput(name,tipo){
    if(tipo=='servico')
        return execao_form_servico.indexOf(name);
    if(tipo=='evento')
        return execao_form_evento.indexOf(name);
         if(tipo=='user')
        return execao_form_user.indexOf(name);
    return undefined;
}

function observeForm(op,tipo){


    if(op!=null && tipo!=null){

        var form = document.getElementById('form');
        if(op=="Novo"){
            $(document).ready(function(){

                $("#btn_salvar").click(function(){
                    var count=0;

                    $(".input_text").each(function(){
                         if(getExecaoInput($(this).attr('name'),tipo)<0){
                            if($(this).val() == 0 && $(this).val() == "")  {
                                count++;
                            }
                        }
                    });

   if($('#validar_email').val()=='false'){
                        msg_form_empty(3);

                    }else{


                        if(count==0 && tipo=='user'){

                            validarCadastroUser();

                        }else if(count==0&&tipo=='servico'||count==0&&tipo=='evento'){

                            validarCadastroItem();


                        }else{
                            msg_form_empty(2);
                        }

}
                });
            });

        }else if(op=="Editar"){
            var array=new Array();
            var index=0;
            $(".input_text").each(function(){
                array[index]=new Array($(this).attr('id'),$(this).val());
                index++;

            });
                $('#validar_email').val()=='true';
                 $('#remove_img').css({
        'visibility': 'visible'
    });

            $(document).ready(function(){
                var cont=0;
                var v=null;
                $("#btn_salvar").click(function(){
                    for (i in array){
                        var id=array[i][0];
                        var val =array[i][1]
                        var el = document.getElementById(id);
                      if(val!=0 && $(el).val()!= val|| val==''&& $(el).val()!= val ){
                            cont++;
                        }else{
                        var ex=getExecaoInput($(el).attr('name'),tipo);
                        if( $(el).val() != val  &&  $(el).val()!=''&&ex >= 0)
                         cont++;
                        }
                   }



 if($('#validar_email').val()=='false'){
                    msg_form_empty(3);
                   restun;
               }else{

                    if(cont!=0){
                        if(tipo=='user'){

                                validarCadastroUser();

                        }else if(tipo=='servico'||tipo=='evento'){
                            validarCadastroItem();
                        }
                    }else{
                        msg_form_empty(1);
                    }}
                });
            });

        }
    }
}

function msg_form_empty(op){
    var msg ;
    switch (op){
        case 0:
            msg='Nome de usuário ou senha incorreto';
            break;
        case 1:
            msg='<i class="erro">Você  não fez nehuma alteração</i>';
            break;
        case 2:
            msg='<i class="erro">Verifique os campos obrigatórios </i> ';
            break;
        case 3:
            msg='<i class="erro">O email informado já exite </i>';
            break;
            case 4:
            msg='<i class="erro">A data é obrigatária</i>';
            break;
    }
    msg_box(msg);
}
function msg_box(msg){
    var div = document.getElementById('msg_box');

    $(div).html('<div class="popup">'+
        '<h5 class="popup_msg_cofirme" >'+msg+'</h5>'+
        '</div>');
    $(div).css({
        'width':'350px',
        'top':'5px',
        'height':'auto',
        'left':'350px',
        'font-size': '20px',
        'background':'#E8E8E8'
    });

    $(div).slideDown('speed',0);
    $('.input_text').click(function(){
     $(div).slideUp('slow',0);
    });
     $(div).click(function(){
     $(div).slideUp('slow',0);
    });
  $(window).scroll(function() {

     $(div).slideUp('slow',0);

   });
    $(div).focus();
}

function validarCadastroItem(tipo){

    var form = document.getElementById('form');


    var count=0;
    var erros = new Array();
    if(tipo=='servico'){
        if(form.periodo.value == 0){
            set_msg_erro('periodo',null);
            erros.push(form.periodo);
        }
        if(form.categoria.value == 0){
            set_msg_erro('categoria',null);
            erros.push(form.categoria);
        }
    }

    if(form.titulo.value == ''){
        set_msg_erro('titulo',null);
        erros.push(form.titulo);
    }
    if(form.descricao.value == ''){
        set_msg_erro('descricao',null);
        erros.push(form.titulo);
    }
    if(form.endereco.value==''){
        set_msg_erro('endereco',null);
        erros.push(form.endereco);
    }
    if(tipo=='evento'){
    if(form.data.value==''){
        set_msg_erro('data',null);
        erros.push(form.endereco);
    }}
    /*
    if(form.email.value==''){
        set_msg_erro('email',null);
        erros.push(form.email);
    }
    */
    if(form.uf.value==0){
        set_msg_erro('uf');
        erros.push(form.uf);
    }


    if(form.cidade.value==0){
        set_msg_erro('cidade',null);
        erros.push(form.cidade);
    }


    if(erros.length==0){
        form.submit();
    }else{
        erros[0].focus();
    }

}