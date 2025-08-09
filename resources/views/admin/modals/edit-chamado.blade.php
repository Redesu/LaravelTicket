<div class="modal fade" id="editChamadoModal" tabindex="-1" aria-labelledby="editChamadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editChamadoForm">
                @csrf
                <input type="hidden" id="editChamadoId" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editChamadoModalLabel">Editar Chamado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editModalErrors" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label for="editTitulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="editTitulo" name="titulo" required>
                    </div>

                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-select" id="editStatus" name="status" disabled>
                            <option value="Aberto">Aberto</option>
                            <option value="Em Andamento">Em Andamento</option>
                            <option value="Finalizado">Finalizado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editDescricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="editDescricao" name="descricao" required>
                    </div>

                    <div class="mb-3">
                        <label for="editPrioridade" class="form-label">Prioridade</label>
                        <select class="form-select" id="editPrioridade" name="prioridade" required>
                            <option value="" disabled selected>Selecione a prioridade</option>
                            <option value="Baixa">Baixa</option>
                            <option value="Média">Média</option>
                            <option value="Alta">Alta</option>
                            <option value="Urgente">Urgente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editDepartamento" class="form-label">Departamento</label>
                        <select class="form-select" id="editDepartamento" name="departamento" required>
                            <option value="" disabled selected>Selecione o departamento</option>
                            <option value="SUPORTE">SUPORTE</option>
                            <option value="DESENVOLVIMENTO">DESENVOLVIMENTO</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editCategoria" class="form-label">Categoria</label>
                        <select class="form-select" id="editCategoria" name="categoria" required>
                            <option value="" disabled selected>Selecione a categoria</option>
                            <option value="SUPORTE">SUPORTE</option>
                            <option value="CORREÇÃO">CORREÇÃO</option>
                            <option value="DUVIDAS">DUVIDAS</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="editSpinner"></span>
                        Atualizar Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>