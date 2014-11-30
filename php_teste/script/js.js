/**
 *
 *
 * @author Irlei
 */

function selectTipo(op){
    document.getElementById("tipo_ev").selectedIndex =op;

}

function selectCategoria(op){
    document.getElementById("categoria").selectedIndex =op;

}

function selectPeriodo(op){
    document.getElementById("periodo").selectedIndex =op;

}

function selectStatus(op){
    document.getElementById("status").selectedIndex =op;

}

function cancelar(url){
    var form = document.getElementById('form');
    form.reset();
    window.location.href=url;
}

function validarPost(){
    var form =  document.getElementById("form");
    titulo = document.getElementById("titulo").valueOf().value;


}

function previewImagem(){
    el=document.getElementById('img_file');
    el.addEventListener('change', handleFileS, false);


}

function btn_remove_img(op){
    document.getElementById('img_item')
    .setAttribute('src',getDirImagens(op));
$('#img_file').val('')
}

function mak_btn_img(){

    $('#img_file').click(
        previewImagem() );

    $('#remove_img').css({
        'visibility': 'visible'
    });


}

function handleFileS(evt) {
    var files = evt.target.files; // FileList object
    // Loop through the FileList and render image files as thumbnails.
    var reader = new FileReader();
    // Closure to capture the file information.
    reader.onload = (function(theFile) {
        return function(e) {
            // Render thumbnail.
            var img = document.getElementById('img_item');
            img.setAttribute('src',  e.target.result);
        };

    })(files[0]);

    // Read in the image file as a data URL.
    reader.readAsDataURL(files[0]);
}
function getDirImagens(op){
    switch(op){
        case 1:
            return"../imagens/sistem/padrao_perfil.jpg";
            break;
        case 2:
            return"../imagens/sistem/padrao_img_evento.jpg";
            break;

    }

}