var compilar_codigo = function () {
    $("#executar_compilador").html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' + 
        'Loading...');
    $.post('ajax.php', {
        acao: "compilar",
        codigo_fonte: $("#codigo_fonte").val()
    }, function (dados) {
        if(dados.mensagem != "") {
            $("#erros").text(dados.mensagem);
            $("#codigo_fonte").css("display", "block");
            $("#executar_compilador").html('Compilar');
        }

        if (dados.erro) {
            $("#erros").text(dados.mensagem);
            $("#codigo_fonte").css("display", "block");
            $("#executar_compilador").html('Compilar');
        }
        console.log(dados);
        cont = 0.
        tabela = new Array();
        for (var i = 0; i < dados.write.length; i++) {
            tabela.push(
                '<tr>' +
                '<label>' + dados.write[cont] + '</label>' +
                '<input type="text" id="valor_' 
            );
        }


    }, "json");
}

var mostrar_fonte = function () {
    $("#codigo_fonte").css("display", "block");
}


