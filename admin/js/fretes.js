document.addEventListener('DOMContentLoaded', function () {

    // Localiza o modal.
    const modalElemento = document.getElementById('modalFrete');

    // Cria o modal do Bootstrap, caso ele exista na página.
    if (modalElemento) {
        const modalFrete = new bootstrap.Modal(modalElemento);

        // Localiza o botão Novo frete.
        const btnNovo = document.getElementById('btn-novo-frete');

        // Abre o modal ao clicar no botão.
        if (btnNovo) {
            btnNovo.addEventListener('click', function () {
                modalFrete.show();
            });
        }

        // Abre o modal automaticamente durante a edição.
        if (modalElemento.dataset.editando === '1') {
            modalFrete.show();
        }
    }


        // Localiza os elementos da pesquisa.
        const busca = document.getElementById('busca-frete');
        const btnPesquisar = document.getElementById('btn-pesquisar-frete');
        const contador = document.getElementById('contador-frete');
        const linhas = document.querySelectorAll('.linha-frete');
        const linhaSemResultado = document.getElementById('linha-sem-resultado-frete');

        // Encerra a pesquisa caso os elementos ainda não existam.
        if (!busca || !btnPesquisar || !contador) {
            return;
        }

        // Filtra os fretes conforme o texto digitado.
        function filtrarFrete() {

            const texto = busca.value.trim().toLowerCase();
            let encontrados = 0;

            linhas.forEach(function (linha) {

                const conteudo = linha.textContent.toLowerCase();

                if (conteudo.includes(texto)) {
                    linha.classList.remove('d-none');
                    encontrados++;
                } else {
                    linha.classList.add('d-none');
                }

            });

            // Atualiza o contador.
            contador.textContent = encontrados + 
                (encontrados === 1 ? ' frete encontrado.' : ' fretes encontrados.');

            // Mostra ou esconde a mensagem de nenhum resultado.
            if (linhaSemResultado) {
                linhaSemResultado.classList.toggle('d-none', encontrados > 0);
            }
        }

        // Pesquisa ao clicar no botão.
        btnPesquisar.addEventListener('click', filtrarFretes);

        // Atualiza a lista automaticamente ao apagar a pesquisa.
        busca.addEventListener('input', function () {
            if (busca.value === '') {
                filtrarEnderecos();
            }
        });

         // Pesquisa ao pressionar Enter.
        busca.addEventListener('keydown', function (event) {

            if (event.key === 'Enter') {
                event.preventDefault();
                filtrarFretes();
            }

        });

        // Atualiza a pesquisa enquanto o usuário digita.
        busca.addEventListener('input', filtrarFretes);

});