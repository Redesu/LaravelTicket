<div class="modal fade" id="editChamadosModal" tabindex="-1" aria-labelledby="editChamadosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editChamadosForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editChamadosModalLabel">Editar Chamado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editChamadosModalErrors" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label for="editChamadosTitulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="editChamadosTitulo" name="titulo" required>
                    </div>

                    <div class="mb-3">
                        <label for="editChamadosStatus" class="form-label">Status</label>
                        <select class="form-select" id="editChamadosStatus" name="status">
                            <option value="Aberto">Aberto</option>
                            <option value="Em andamento">Em andamento</option>
                            <option value="Finalizado">Finalizado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editChamadosDescricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="editChamadosDescricao" name="descricao" required>
                    </div>

                    <div class="mb-3">
                        <label for="editChamadosPrioridade" class="form-label">Prioridade</label>
                        <select class="form-select" id="editChamadosPrioridade" name="prioridade" required>
                            <option value="" disabled selected>Selecione a prioridade</option>
                            <option value="Baixa">Baixa</option>
                            <option value="Média">Média</option>
                            <option value="Alta">Alta</option>
                            <option value="Urgente">Urgente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editChamadosDepartamento" class="form-label">Departamento</label>
                        <select class="form-select" id="editChamadosDepartamento" name="departamento" required>
                            <option value="" disabled selected>Selecione o departamento</option>
                            <option value="SUPORTE">SUPORTE</option>
                            <option value="DESENVOLVIMENTO">DESENVOLVIMENTO</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editChamadosCategoria" class="form-label">Categoria</label>
                        <select class="form-select" id="editChamadosCategoria" name="categoria" required>
                            <option value="" disabled selected>Selecione a categoria</option>
                            <option value="SUPORTE">SUPORTE</option>
                            <option value="CORREÇÃO">CORREÇÃO</option>
                            <option value="DUVIDAS">DUVIDAS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editChamadosUsuario" class="form-label">Usuário Responsável</label>
                        <select class="form-select" id="editChamadosUsuario" name="user_id" required>
                            <option value="" disabled selected>Selecione o usuário responsável</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="editSpinner"></span>
                        Atualizar Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>