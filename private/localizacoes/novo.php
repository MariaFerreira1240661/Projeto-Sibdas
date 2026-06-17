<?php
$pagina_atual = 'localizacoes';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Nova Localização</h1>
                <p>Registo de uma área hospitalar associada aos equipamentos médicos.</p>
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
                    <p>Preencha os campos principais para simular o registo de uma localização.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formLocalizacao">

                <div class="form-grid">
                    <div>
                        <label for="codigoLocalizacao">Código interno *</label>
                        <input type="text" id="codigoLocalizacao" placeholder="Ex: L006" required>
                    </div>

                    <div>
                        <label for="edificioLocalizacao">Edifício *</label>
                        <input type="text" id="edificioLocalizacao" placeholder="Ex: Edifício Principal" required>
                    </div>

                    <div>
                        <label for="pisoLocalizacao">Piso *</label>
                        <input type="text" id="pisoLocalizacao" placeholder="Ex: 2" inputmode="numeric" pattern="[0-9]*" maxlength="3" required>
                    </div>

                    <div>
                        <label for="servicoLocalizacao">Serviço / Localização *</label>
                        <select id="servicoLocalizacao" required>
                            <option value="">Selecione o serviço</option>
                            <option>UCI</option>
                            <option>Bloco Operatório</option>
                            <option>Medicina Interna</option>
                            <option>Urgência</option>
                            <option>Imagiologia</option>
                            <option>Laboratório</option>
                        </select>
                    </div>

                    <div>
                        <label for="salaLocalizacao">Sala / Unidade *</label>
                        <input type="text" id="salaLocalizacao" placeholder="Ex: Sala 3" required>
                    </div>

                    <div>
                        <label for="estadoLocalizacao">Estado da localização *</label>
                        <select id="estadoLocalizacao" required>
                            <option value="">Selecione o estado</option>
                            <option>Ativa</option>
                            <option>Em manutenção</option>
                            <option>Inativa</option>
                            <option>Temporariamente indisponível</option>
                        </select>
                    </div>

                    <div>
                        <label for="responsavelLocalizacao">Responsável da localização *</label>
                        <input type="text" id="responsavelLocalizacao" placeholder="Ex: Enfermeiro responsável" required>
                    </div>

                    <div>
                        <label for="contactoInternoLocalizacao">Contacto interno *</label>
                        <input type="text" id="contactoInternoLocalizacao" placeholder="Ex: 2201" inputmode="numeric" pattern="[0-9]*" maxlength="6" required>
                    </div>
                </div>

                <div class="form-full">
                    <label for="observacoesLocalizacao">Observações</label>
                    <textarea id="observacoesLocalizacao" rows="4" placeholder="Observações adicionais sobre a localização"></textarea>
                </div>

                <p id="mensagemLocalizacao" class="mensagem-login"></p>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
                        <i class="bi bi-check-circle"></i>
                        Guardar localização
                    </button>

                    <a href="index.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>