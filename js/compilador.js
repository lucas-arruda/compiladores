var compilar_codigo = function () {
    $("#executar_compilador").html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' + 
        'Loading...'
    );
    if ($("#codigo_fonte").val() == "") {
        alert("Nenhum registro encontrado.");
        $("#executar_compilador").html('Compilar');
        return false;
    }
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
        $("#erros").text("");
        $("#valores_variaveis").val(dados.dados_variaveis);
        $("#texto_resultado").text(dados.resultado);
        $("#executar_compilador").html('Compilar');
        $("#mostrar_fonte").css("display", "none");
        $("#resultado").css("display", "block");
    }, "json");
}

var mostrar_fonte = function() {
    if ($("#mostrar_fonte").css("display", "none")) {
        $("#mostrar_fonte").css("display", "block");
        $("#resultado").css("display", "none");
        $("#mostrar_tabela").css("display", "none");
    }
    if ($("#mostrar_erros").css("display", "none")) {
        $("#mostrar_erros").css("display", "block");
    }
}

var mostrar_resultado = function() {
    if ($("#resultado").css("display", "none")) {
        if ($("#texto_resultado").text() === "") {
            $("#texto_resultado").text("Nenhum codigo executado.");
        }
        $("#mostrar_fonte").css("display", "none");
        $("#mostrar_tabela").css("display", "none");
        $("#resultado").css("display", "block");
    }
}

var mostrar_tabela = function() {
    if ($("#mostrar_tabela").css("display", "none")) {
        $("#mostrar_tabela").css("display", "block");
        $("#mostrar_fonte").css("display", "none");
        $("#mostrar_erros").css("display", "none");
    }
}

var abrir_documentacao = function() {
    nova_aba = window.open("documentacao/documentacao.pdf");
};

var abrir_automato = function() {
    nova_aba = window.open("imagem/automato.jpeg");
};

var analisador_lexico = function() {
    $("#analisador_lexico").html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' + 
        'Loading...'
    );
    if ($("#codigo_fonte").val() == "") {
        alert("Nenhum registro encontrado.");
        $("#analisador_lexico").html('Tabela de simbolos');
        return false;
    }
    $.post('ajax.php', {
        acao: "analisador_lexico",
        codigo_fonte: $("#codigo_fonte").val(),
        dados_variaveis: $("#valores_variaveis").val()
    }, function (dados) {
        if (dados.erro) {
            $("#erros").text(dados.mensagem);
            $("#analisador_lexico").html('Compilar');
            return false;
        }
        $("#tabela_simbolos td").remove();

        Object.keys(dados.tabela_simbolos).map(function(key){
            $("#tabela_simbolos tbody").append(
                '<tr>' +
                '<td>' + dados.tabela_simbolos[key].cadeia + '</td>' +
                '<td>' + dados.tabela_simbolos[key].token + '</td>' +
                '<td>' + dados.tabela_simbolos[key].categoria + '</td>' +
                '<td>' + dados.tabela_simbolos[key].tipo + '</td>' +
                '<tr>'
            );;
        });
        $("#analisador_lexico").html('Tabela de simbolo');
        $("#mostrar_tabela").css("display", "block");
        $("#mostrar_fonte").css("display", "none");
        $("#mostrar_erros").css("display", "none");
    }, "json");
}

