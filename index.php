<!doctype html>
<html lang="pt-br">
   <head>
      <meta charset="utf-8">
      <meta property="description" content="Interface que permita selecionar um partido político em uma caixa de seleção e, ao selecioná-lo, exibe os dados de todos os seus parlamentares da atual legislatura">
      <meta property="title" content="Partido Político">
      <meta property="og:type" content="website">
      <meta property="og:site_name" content="Partido Político">
      <meta property="og:title" content="Partido Político">
      <meta property="og:description" content="Interface que permita selecionar um partido político em uma caixa de seleção e, ao selecioná-lo, exibe os dados de todos os seus parlamentares da atual legislatura">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="stylesheet" href="assets/css/style.css">
      <title>Partido Político</title>
      <link rel="icon" type="image/png" href="assets/img/favicon.png">

        <script>
            "use strict";
            var listaPartidos = new Array();

            /* buscarListaPartidos
                Carrega 'listaPartidos' com os dados obtidos do recurso paginado,
                em chamadas sucessivas
            */
            function buscarListaPartidos (urlInicio) {
                var corpoResposta;
                var req = new XMLHttpRequest();
                var dados;

                req.open ("GET", urlInicio);
                req.onreadystatechange = function (evt) {
                   if (req.readyState === req.DONE &&
                        req.status >= 200 && req.status < 300) {
                        // A requisição foi respondida com sucesso.
                        corpoResposta = JSON.parse(req.responseText);

                        listaPartidos = listaPartidos.concat(corpoResposta.dados);

                        // Se houver um link de rel="next" na resposta, chamar a função de busca
                        // outra vez usando esse link
                        // VERSÃO COM LOOP FOR
                        for (var i = 0; i < corpoResposta.links.length; i++) {

                            if (corpoResposta.links[i].rel === "next") {
                                buscarListaPartidos(corpoResposta.links[i].href);
                                return;
                            }
                        }

                        menuCarregarOpcoes();

                    } // FIM DO "IF"
                } // FIM DE onreadystatechange
                req.setRequestHeader ("Accept", "application/json");
                req.send();
            }

            buscarListaPartidos("https://dadosabertos.camara.leg.br/api/v2/partidos?itens=100");



            /* menuCarregarOpcoes
                 Configura as opções de nomes de deputados no menu
             */
            function menuCarregarOpcoes() {
                var i=0;
                var menuwdg = document.getElementById("menupartidos");
                var opt;

                // Criar o primeiro item sem o nome de um partido...
                opt = document.createElement("option");
                opt.text = "Escolha um partido..."
                menuwdg.add(opt);

                while (listaPartidos[i]) {

                    opt = document.createElement("option");
                    opt.text = listaPartidos[i].nome;
                    menuwdg.add(opt);
                    i++;
                }
            }

            /* menuOpcaoEscolhida
                Chamada quando o usuário escolhe outro nome no menu
            */
            function menuOpcaoEscolhida() {
                var escolhido;
                var menuwdg = document.getElementById("menupartidos");

                escolhido = menuwdg.value;
                for (var i = 0; i < listaPartidos.length; i++) {
                    if (listaPartidos[i].nome === escolhido) {
                         mostrarParlamentares (listaPartidos[i]);
                    }
                }
            }

            /*
            mostrarParlamentares - recebe um item da lista de Partidos,
              contendo os dados de um partido, e os insere na
              exibição do HTML no navegador
            */
            function mostrarParlamentares (part) {

                var wdgNome = document.getElementById("nomepartido");
                var wdgPartTitle = document.getElementById("part-title");

                // O nome é inserido como conteúdo do elemento com id "nome"
                wdgNome.innerHTML = part.nome;
                wdgPartTitle.innerHTML = "Lista dos Parlamentares aqui...";

                //Incluir lista de parlamentar aqui...

            }
        </script>


   </head>
   <body>
    
    <!-- Aqui é criado o controle de menu -->
    <select id="menupartidos" onchange="menuOpcaoEscolhida()"></select><br/>

    <!-- Nome e estado -->
    <h3 id="nomepartido"></h3>
    <h4 id="part-title"></h4>
    <div id="listparlamentar"></div>
</body>
</html>