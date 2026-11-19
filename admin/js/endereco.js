document.addEventListener('DOMContentLoaded', function () {

    // Localiza o modal.
    const modalElemento = document.getElementById('modalEndereco');

    // Cria o modal do Bootstrap.
    const modalEndereco = new bootstrap.Modal(modalElemento);

    // Localiza o botão Novo cadastro.
    const btnNovo = document.getElementById('btn-novo-endereco');

    // Abre o modal ao clicar em Novo cadastro.
    btnNovo.addEventListener('click', function () {
        modalEndereco.show();
    });

    // Verifica se existe um endereço selecionado para edição.
    if (modalElemento.dataset.editando === '1') {
        modalEndereco.show();
    }


        // Localiza os elementos da pesquisa.
        const busca = document.getElementById('busca-endereco');
        const btnPesquisar = document.getElementById('btn-pesquisar-endereco');
        const contador = document.getElementById('contador-endereco');
        const linhas = document.querySelectorAll('.linha-endereco');
        const linhaSemResultado = document.getElementById('linha-sem-resultado-endereco');

        // Função responsável por pesquisar os endereços.
        function filtrarEnderecos() {

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
                (encontrados === 1 ? ' endereço encontrado.' : ' endereços encontrados.');

            // Mostra ou esconde a mensagem de nenhum resultado.
            linhaSemResultado.classList.toggle('d-none', encontrados > 0);
        }

        // Pesquisa ao clicar no botão.
        btnPesquisar.addEventListener('click', filtrarEnderecos);

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
                filtrarEnderecos();
            }
    });

});