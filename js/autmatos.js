var executar_automato = function() {
    $("#executar_caracter").html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' + 
        'Loading...'
    );
    $.post('ajax.php', {
        string: $("#string").val()
    }, function (dados) { 
        if (dados.erro) {
            alert(dados.mensagem);
            $("#executar_compilador").html('Executar');
            return false;
        }
        
        $("#erros").text("");
        $("#texto_resultado").text(dados.resultado);
        $("#executar_compilador").html('Executar');
        $("#mostrar_fonte").css("display", "none");
        $("#resultado").css("display", "block");
        $("#mostrar_fonte").css("display", "none");
    }, "json");
}
