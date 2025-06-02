// script.js
const editarModal = document.getElementById('editarModal');
editarModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const nome = button.getAttribute('data-nome');
    const marca = button.getAttribute('data-marca');
    const tipo = button.getAttribute('data-tipo');
    const imagem = button.getAttribute('data-imagem');
    const idProduto = button.getAttribute('data-id'); // Aqui você pega o ID do produto

    // Preencher os campos com as informações atuais do produto
    document.getElementById('editarNome').value = nome;
    document.getElementById('editarMarca').value = marca;
    document.getElementById('editarTipo').value = tipo;
    document.getElementById('editarNomeOriginal').value = nome;
    document.getElementById('editarIdProduto').value = idProduto; // Preenche o campo oculto

    // Exibir imagem atual (caso exista) e permitir edição
    const imagemPreview = document.getElementById('imagemPreview');
    const imagemPreviewContainer = document.getElementById('imagemPreviewContainer');

    if (imagem) {
        imagemPreview.src = imagem;  // Exibe a imagem atual
        imagemPreviewContainer.style.display = 'block';  // Exibe a imagem (caso já exista)
    } else {
        imagemPreviewContainer.style.display = 'none';  // Esconde se não houver imagem
    }

    // Visualizar imagem selecionada pelo usuário
    document.getElementById('editarImagem').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagemPreview.src = e.target.result;  // Atualiza a imagem de pré-visualização
                imagemPreviewContainer.style.display = 'block';  // Exibe a pré-visualização
            };
            reader.readAsDataURL(file);
        }
    });
});
