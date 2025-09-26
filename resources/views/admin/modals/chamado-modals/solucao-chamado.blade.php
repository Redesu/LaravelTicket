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

                    <div class="col-12">
                        <div class="form-group file-upload-component">
                            <label>Anexo (Opcional - Múltiplos arquivos permitidos)</label>
                            <div class="drop-zone">
                                <span class="drop-zone-text">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    Arraste e solte os arquivos aqui ou clique para selecionar
                                </span>
                                <input type="file" class="d-none file-input" name="anexos[]" multiple
                                    accept=".jpg,.jpeg,.png,.pdf,.zip,.rar,.mp4">
                            </div>
                            <div class="invalid-feedback d-block file-feedback" style="display: none;"></div>
                            <div class="selected-files-container mt-2" style="display: none;">
                                <small class="text-muted">Arquivos selecionados:</small>
                                <div class="selected-files-list"></div>
                            </div>
                        </div>
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
