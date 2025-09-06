<div class="modal fade" id="createCategoriaModal" tabindex="-1" aria-labelledby="createCategoriaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="createCategoriaForm">
                @csrf
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="createCategoriaModalLabel">
                        <i class="fas fa-plus-circle"></i> Criar uma nova categoria
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Fechar
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none" id="spinner"></span>
                            <i class="fas fa-plus-circle"></i> Criar categoria
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>