
// Aguarda o carregamento da página.
document.addEventListener('DOMContentLoaded', function () {

    // Localiza o campo CEP.
    const campoCep = document.getElementById('cep');

    // blur - Quando o usuário sair do campo CEP, realiza a consulta.
    campoCep.addEventListener('blur', function () {

        // Remove espaços e caracteres que não sejam números.
        const cep = campoCep.value.replace(/\D/g, '');

        // CEP possui 8 números.
        if (cep.length !== 8) {
            return;
        }

        // fetch - Consulta o CEP no ViaCEP.
        fetch(`https://viacep.com.br/ws/${cep}/json/`)

            // Transforma a resposta em JSON (Uma string/texto estruturado).
            .then(resposta => resposta.json())

            // Recebe os dados do endereço.
            .then(dados => {

                // Verifica se o CEP foi encontrado, se nao, da erro com alerta e retorna.
                if (dados.erro) {
                    alert('CEP não encontrado!');
                    return;
                }

                // retorna os campos preenchidos automaticamente.
                document.getElementById('rua').value = dados.logradouro;
                document.getElementById('bairro').value = dados.bairro;
                document.getElementById('cidade').value = dados.localidade;
                document.getElementById('estado').value = dados.uf;

            })

            // Trata possíveis erros na consulta.
            .catch(() => {
                alert('Não foi possível consultar o CEP. Tente novamente.');
            });

    });

});
