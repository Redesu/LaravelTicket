<div class="modal fade" id="filtrarChamadosModal" tabindex="-1" aria-labelledby="filtrarChamadosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="filtrarChamadosForm">
                @csrf
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="filtrarChamadosModalLabel">
                        <i class="fas fa-filter"></i> Filtrar Chamados
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Todos os Status</option>
                                    <option value="Aberto">Aberto</option>
                                    <option value="Em andamento">Em andamento</option>
                                    <option value="Finalizado">Finalizado</option>
                                </select>
                            </div>
                        </div>

                        <!-- Prioridade -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filtrarChamadosPrioridade" class="form-label">Prioridade</label>
                                <select class="form-control" id="filtrarChamadosPrioridade" name="prioridade">
                                    <option value="">Todas as Prioridades</option>
                                    <option value="Baixa">Baixa</option>
                                    <option value="Média">Média</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filtrarChamadosUsuario" class="form-label">Usuário Responsável</label>
                                <select class="form-control" id="filtrarChamadosUsuario" name="user_id">
                                    <option value="">Todos os Usuários</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filtrarChamadosDepartamento" class="form-label">Departamento</label>
                                <select class="form-control" id="filtrarChamadosDepartamento" name="departamento">
                                    <option value="">Todos os Departamentos</option>
                                    <option value="SUPORTE">
                                        SUPORTE</option>
                                    <option value="DESENVOLVIMENTO">DESENVOLVIMENTO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filtrarChamadosCategoria" class="form-label">Categoria</label>
                                <select class="form-control" id="filtrarChamadosCategoria" name="categoria">
                                    <option value="">Todas as Categorias</option>
                                    <option value="SUPORTE">
                                        SUPORTE</option>
                                    <option value="DUVIDA">
                                        DÚVIDAS</option>
                                    <option value="CORREÇÃO">
                                        CORREÇÃO</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6"></div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-calendar-alt"></i> Período de Criação
                            </h5>
                        </div>

                        <!-- Data de Criação - De -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="created_at_inicio" class="form-label">
                                    <i class="far fa-calendar"></i> Criado a partir de
                                </label>
                                <input type="date" class="form-control" id="created_at_inicio" name="created_at_inicio"
                                    value="">
                            </div>
                        </div>

                        <!-- Data de Criação - Até -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="created_at_fim" class="form-label">
                                    <i class="far fa-calendar"></i> Criado até
                                </label>
                                <input type="date" class="form-control" id="created_at_fim" name="created_at_fim"
                                    value="">
                            </div>
                        </div>
                    </div>

                    <!-- Optional: Additional Date Range for Updates -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-edit"></i> Período de Atualização
                            </h5>
                        </div>

                        <!-- Data de Atualização - De -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="updated_at_inicio" class="form-label">
                                    <i class="far fa-clock"></i> Atualizado a partir de
                                </label>
                                <input type="date" class="form-control" id="updated_at_inicio" name="updated_at_inicio"
                                    value="">
                            </div>
                        </div>

                        <!-- Data de Atualização - Até -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="updated_at_fim" class="form-label">
                                    <i class="far fa-clock"></i> Atualizado até
                                </label>
                                <input type="date" class="form-control" id="updated_at_fim" name="updated_at_fim"
                                    value="">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <!-- Clear Filters Button -->
                    <button type="button" class="btn btn-secondary" id="clearFilters">
                        <i class="fas fa-eraser"></i> Limpar Filtros
                    </button>

                    <!-- Close Button -->
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fas fa-times"></i> Fechar
                    </button>

                    <!-- Filter Submit Button -->
                    <button type="submit" class="btn btn-primary" id="filtrarSubmitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="filtrarSpinner"></span>
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>