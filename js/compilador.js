var compilar_codigo = function () {
    $("#executar_compilador").html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' + 
        'Loading...');
    $.post('ajax.php', {
        acao: "compilar",
        codigo_fonte: $("#codigo_fonte").val()
    }, function (dados) { 
        if (dados.erro) {
            $("#erros").text(dados.mensagem);
            $("#texto_resultado").text("");
            $("#executar_compilador").html('Compilar');
            return false;
        }
        console.log(dados);
        $("#erros").text("");
        $("#texto_resultado").text(dados.resultado);
        $("#executar_compilador").html('Compilar');
        $("#mostrar_fonte").css("display", "none");
        $("#resultado").css("display", "block");
        $("#mostrar_fonte").css("display", "none");
    }, "json");
}

var mostrar_fonte = function() {
    if ($("#mostrar_fonte").css("display", "none")) {
        $("#mostrar_fonte").css("display", "block");
        $("#resultado").css("display", "none");
    } else {
        $("#mostrar_fonte").css("display", "none");
        $("#resultado").css("display", "block");
    }
    
}

var mostrar_resultado = function() {
    if ($("#resultado").css("display", "none")) {
        if ($("#texto_resultado").text() === "") {
            $("#texto_resultado").text("Nenhum codigo executado.");
        }
        $("#mostrar_fonte").css("display", "none");
        $("#resultado").css("display", "block");
    } else {
        $("#resultado").css("display", "none");
        $("#mostrar_fonte").css("display", "block");
    }
}


