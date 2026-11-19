

const modalEl = document.getElementById('modalProduto');
const modal = new bootstrap.Modal(modalEl);

const inputId = document.getElementById('produto-id');
const inputNome = document.getElementById('nome');
const titulo = document.getElementById('tituloModal');
const btnSalvar = document.getElementById('btn-salvar');

const busca = document.getElementById('busca');
const contador = document.getElementById('contador');
const linhas = document.querySelectorAll('.linha-produto');
const linhaSemResultado = document.getElementById('linha-sem-resultado');

// "1" quando a página foi carregada com um produto escolhido para edição
let editando = modalEl.dataset.editando === '1';

/* ---------- Pesquisa ---------- */
// Tira acentos e maiúsculas: "Açúcar" casa com "acucar"
function normalizar(texto) {
  return texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function filtrar() {
  const termo = normalizar(busca.value.trim());
  let visiveis = 0;

  linhas.forEach((linha) => {
    const aparece = normalizar(linha.textContent).includes(termo);
    linha.classList.toggle('d-none', !aparece);
    if (aparece) visiveis++;
  });

  if (linhaSemResultado) {
    linhaSemResultado.classList.toggle('d-none', visiveis > 0);
  }
  if (linhas.length > 0) {
    contador.textContent = visiveis + ' de ' + linhas.length + ' produto(s)';
  }
}

// Pesquisa ao clicar no botão
document.getElementById('btn-pesquisar').addEventListener('click', filtrar);

// Atualiza a lista automaticamente ao apagar a pesquisa
busca.addEventListener('input', function() {
    if (busca.value === '') {
        filtrar();
    }
});

// Pesquisa ao apertar Enter
busca.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        filtrar();
    }
});


/* ---------- Janela do formulário ---------- */
function formularioNovo() {
  inputId.value = '';
  inputNome.value = '';
  titulo.textContent = 'Cadastrar Produto';
  btnSalvar.textContent = 'Cadastrar Produto';
}

document.getElementById('btn-novo').addEventListener('click', () => {
  formularioNovo();
  modal.show();
});

// Se a página abriu em modo edição, já mostra a janela preenchida
if (editando) {
  modal.show();
}

modalEl.addEventListener('shown.bs.modal', () => inputNome.focus());

// Ao fechar sem salvar, esquece o produto que estava em edição
modalEl.addEventListener('hidden.bs.modal', () => {
  if (editando) {
    fetch('../php/cancelarEdicao.php');
    editando = false;
  }
  formularioNovo();
});

