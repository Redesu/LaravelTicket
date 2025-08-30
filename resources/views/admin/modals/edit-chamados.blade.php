<div class="modal fade" id="editChamadosModal" tabindex="-1" aria-labelledby="editChamadosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editChamadosForm">
                @csrf
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="editChamadosModalLabel">
                        <i class="fas fa-edit"></i> Editar Chamado
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editChamadosTitulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="editChamadosTitulo" name="titulo" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editChamadosStatus" class="form-label">Status</label>
                                <select class="form-control" id="editChamadosStatus" name="status">
                                    <option value="Aberto">Aberto</option>
                                    <option value="Em andamento">Em andamento</option>
                                    <option value="Finalizado">Finalizado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="editChamadosDescricao" class="form-label">Descrição</label>
                                <input type="text" class="form-control" id="editChamadosDescricao" name="descricao"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editChamadosPrioridade" class="form-label">Prioridade</label>
                                <select class="form-control" id="editChamadosPrioridade" name="prioridade" required>
                                    <option value="" disabled selected>Selecione a prioridade</option>
                                    <option value="Baixa">Baixa</option>
                                    <option value="Média">Média</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Urgente">Urgente</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editChamadosDepartamento" class="form-label">Departamento</label>
                                <select class="form-control" id="editChamadosDepartamento" name="departamento_id"
                                    required>
                                    <option value="" disabled selected>Selecione o departamento</option>
                                    <option value="1">SUPORTE</option>
                                    <option value="2">DESENVOLVIMENTO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editChamadosCategoria" class="form-label">Categoria</label>
                                <select class="form-control" id="editChamadosCategoria" name="categoria_id" required>
                                    <option value="" disabled selected>Selecione a categoria</option>
                                    <option value="1">SUPORTE</option>
                                    <option value="2">CORREÇÃO</option>
                                    <option value="3">DUVIDAS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editChamadosUsuario" class="form-label">Usuário Responsável</label>
                                <select class="form-control" id="editChamadosUsuario" name="user_id" required>
                                    <option value="" disabled selected>Selecione o usuário responsável</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="editSpinner"></span>
                        <i class="fas fa-save"></i> Atualizar Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>