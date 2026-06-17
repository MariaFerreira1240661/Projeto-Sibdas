<?php
$pagina_atual = 'localizacoes';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Editar Localização</h1>
                <p>Atualização dos dados de uma área hospitalar associada ao inventário.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/index.php">
                            <i class="bi bi-box-arrow-up-right"></i>
                            Sair para o site público
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            Terminar sessão
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <section class="backend-box">
            <div class="backend-section-header">
                <div>
                    <h2>Dados da Localização</h2>
                    <p>Altere os campos necessários e guarde as alterações.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formEditarLocalizacao">

                <div class="form-grid">
                    <div>
                        <label for="editCodigoLocalizacao">Código interno *</label>
                        <input type="text" id="editCodigoLocalizacao" value="L001" required>
                    </div>

                    <div>
                        <label for="editEdificioLocalizacao">Edifício *</label>
                        <input type="text" id="editEdificioLocalizacao" value="Edifício Principal" required>
                    </div>

                    <div>
                        <label for="editPisoLocalizacao">Piso *</label>
                        <input type="text" id="editPisoLocalizacao" value="2" inputmode="numeric" pattern="[0-9]*" maxlength="3" required>
                    </div>

                    <div>
                        <label for="editServicoLocalizacao">Serviço / Localização *</label>
                        <select id="editServicoLocalizacao" required>
                            <option selected>UCI</option>
                            <option>Bloco Operatório</option>
                            <option>Medicina Interna</option>
                            <option>Urgência</option>
                            <option>Imagiologia</option>
                            <option>Laboratório</option>
                        </select>
                    </div>

                    <div>
                        <label for="editSalaLocalizacao">Sala / Unidade *</label>
                        <input type="text" id="editSalaLocalizacao" value="UCI Geral" required>
                    </div>

                    <div>
                        <label for="editEstadoLocalizacao">Estado da localização *</label>
                        <select id="editEstadoLocalizacao">
                            <option selected>Ativa</option>
                            <option>Em manutenção</option>
                            <option>Inativa</option>
                            <option>Temporariamente indisponível</option>
                        </select>
                    </div>


                    <div>
                        <label for="editResponsavelLocalizacao">Responsável da localização *</label>
                        <input type="text" id="editResponsavelLocalizacao" value="Enfermeiro responsável" required>
                    </div>

                    <div>
                        <label for="editContactoInternoLocalizacao">Contacto interno *</label>
                        <input type="text" id="editContactoInternoLocalizacao" value="2201" inputmode="numeric" pattern="[0-9]*" maxlength="6" required>
                    </div>
                </div>

                <div class="form-full">
                    <label for="editObservacoesLocalizacao">Observações</label>
                    <textarea id="editObservacoesLocalizacao" rows="4">Localização associada a equipamentos de elevada criticidade clínica.</textarea>
                </div>

                <p id="mensagemEditarLocalizacao" class="mensagem-login"></p>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
                        <i class="bi bi-check-circle"></i>
                        Guardar alterações
                    </button>

                    <a href="detalhes.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>