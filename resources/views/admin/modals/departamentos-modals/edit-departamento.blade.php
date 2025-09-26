<div class="modal fade" id="editDepartamentoModal" tabindex="-1" aria-labelledby="editDepartamentoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editDepartamentoForm">
                @csrf
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="editDepartamentoModalLabel">
                        <i class="fas fa-edit"></i> Editar Departamento
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" id="editDepartamentoId" name="id">
                                <label for="DepartamentoNome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="DepartamentoNome" name="nome" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editDepartamentoDescricao" class="form-label">Descrição</label>
                        <textarea class="form-control form-control-lg" id="editDepartamentoDescricao" name="descricao"
                            rows="5"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="editSpinner"></span>
                        <i class="fas fa-save"></i> Atualizar Departamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
