<div class="modal fade" id="solucaoChamadoModal" tabindex="-1" aria-labelledby="solucaoChamadoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="solucaoChamadoForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="solucaoChamadoModalLabel">Adicionar Solução</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="solucaoDescricao" class="form-label">Descrição da nota</label>
                        <textarea class="form-control form-control-lg" id="solucaoDescricao" name="descricao"
                            rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="solucaoSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="solucaoSpinner"></span>
                        Finalizar chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>