<div class="modal fade" id="editCategoriaModal" tabindex="-1" aria-labelledby="editCategoriaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editCategoriaForm">
                @csrf
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="editCategoriaModalLabel">
                        <i class="fas fa-edit"></i> Editar Categoria
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" id="editCategoriaId" name="id">
                                <label for="categoriaNome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="categoriaNome" name="nome" required>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="editSpinner"></span>
                        <i class="fas fa-save"></i> Atualizar Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>