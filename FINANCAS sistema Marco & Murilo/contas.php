<?php
session_start();
include_once('inc/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: entrarConta.php');
    exit;
}

$idUsuarioLogado = $_SESSION['usuario_id'];
$nomeUsuarioLogado = $_SESSION['usuario_nome'] ?? 'Usuário';
$emailUsuarioLogado = $_SESSION['usuario_email'] ?? '';
$fotoUsuarioLogado = $_SESSION['usuario_foto'] ?? '';

$partesNome = explode(' ', trim($nomeUsuarioLogado));
$iniciaisUsuario = mb_strtoupper(substr($partesNome[0], 0, 1));
if (count($partesNome) > 1) {
    $iniciaisUsuario .= mb_strtoupper(substr(end($partesNome), 0, 1));
}

$mensagemSucesso = "";
$mensagemErro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar_banco') {
    $nomeBanco = trim($_POST['nomeBanco'] ?? '');
    $idCategoria = (int) ($_POST['idCategoria'] ?? 0);
    $saldoInicial = (float) ($_POST['saldoInicial'] ?? 0.00);

    if (!empty($nomeBanco) && $idCategoria > 0) {
        try {
            $stmtInsert = $pdo->prepare("INSERT INTO tb_bancos (nm_banco, ds_dinheiro_banco, id_usuario, id_categoria) VALUES (?, ?, ?, ?)");
            $stmtInsert->execute([$nomeBanco, $saldoInicial, $idUsuarioLogado, $idCategoria]);
            $mensagemSucesso = "Banco cadastrado com sucesso!";
        } catch (PDOException $e) {
            $mensagemErro = "Erro ao cadastrar banco: " . $e->getMessage();
        }
    } else {
        $mensagemErro = "Preencha todos os campos obrigatórios do banco.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'excluir_banco') {
    $idBanco = (int) ($_POST['id_banco'] ?? 0);

    try {
        $stmtCheckDep = $pdo->prepare("SELECT COUNT(*) FROM tb_depositos WHERE id_banco = ?");
        $stmtCheckDep->execute([$idBanco]);
        $temDepositos = $stmtCheckDep->fetchColumn();

        $stmtCheckSaq = $pdo->prepare("SELECT COUNT(*) FROM tb_saques WHERE id_banco = ?");
        $stmtCheckSaq->execute([$idBanco]);
        $temSaques = $stmtCheckSaq->fetchColumn();

        if ($temDepositos > 0 || $temSaques > 0) {
            $mensagemErro = "Não é possível excluir este banco pois existem movimentações vinculadas a ele.";
        } else {
            $stmtDel = $pdo->prepare("DELETE FROM tb_bancos WHERE cd_banco = ? AND id_usuario = ?");
            $stmtDel->execute([$idBanco, $idUsuarioLogado]);
            $mensagemSucesso = "Banco excluído com sucesso!";
        }
    } catch (PDOException $e) {
        $mensagemErro = "Erro ao excluir banco: " . $e->getMessage();
    }
}

try {
    $stmtBancos = $pdo->prepare("
        SELECT 
            b.cd_banco,
            b.nm_banco,
            b.ds_dinheiro_banco,
            c.nm_categoria
        FROM tb_bancos b
        INNER JOIN tb_categoria_contas c ON b.id_categoria = c.cd_categoria
        WHERE b.id_usuario = ?
        ORDER BY b.cd_banco DESC
    ");
    $stmtBancos->execute([$idUsuarioLogado]);
    $bancos = $stmtBancos->fetchAll(PDO::FETCH_ASSOC);

    $stmtCat = $pdo->query("SELECT cd_categoria, nm_categoria FROM tb_categoria_contas ORDER BY nm_categoria ASC");
    $categoriasBanco = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $bancos = [];
    $categoriasBanco = [];
    $mensagemErro = "Erro ao carregar contas: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contas | GERENCI MONEY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
    <style>
        .bank-card {
            background-color: #14161d;
            border: 1px solid #222630;
            border-radius: 12px;
            padding: 20px;
            transition: 0.2s;
        }

        .bank-card:hover {
            border-color: #8fe028;
        }

        .badge-categoria {
            background-color: rgba(143, 224, 40, 0.1);
            color: #8fe028;
            border: 1px solid rgba(143, 224, 40, 0.2);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .btn-excluir {
            background: transparent;
            border: 1px solid #2d3342;
            color: #fc8181;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.8rem;
            transition: 0.2s;
        }

        .btn-excluir:hover {
            background-color: #e53e3e;
            color: #fff;
            border-color: #e53e3e;
        }
    </style>
</head>

<body>
    <div class="page-glow">
        <main class="dashboard">
            <aside class="sidebar">
                <div class="brand">
                    <div class="brand-mark">₼</div>
                    <span>GERENCI MONEY</span>
                </div>


                <p class="menu-label">PAINEL</p>
                <nav class="menu">
                    <a class="menu-link" href="inicial.php"><span>▦</span> Visão geral</a>
                    <a class="menu-link" href="transacoes.php"><span>◫</span> Transações</a>
                    <a class="menu-link active" href="contas.php"><span>♙</span> Contas</a>
                </nav>

                <p class="menu-label settings-label">CONFIGURAÇÕES</p>
                <nav class="menu">
                    <a class="menu-link" href="configuracoes.php"><span>⚙</span> Perfil</a>
                    <a class="menu-link text-danger" href="logout.php">Sair da conta</a>
                </nav>
            </aside>

            <section class="main-content">
                <header class="topbar">
                    <div class="breadcrumbs">
                        <span>▱</span><span>☆</span>
                        <span class="muted">/</span><span class="muted">Painel</span>
                        <span class="muted">/</span><strong>Contas</strong>
                    </div>

                    <div class="top-actions d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm fw-bold px-3 py-2" data-bs-toggle="modal"
                            data-bs-target="#modalNovoBanco"
                            style="background-color: #8fe028; color: #0d0e12; border: none; border-radius: 8px;">
                            + Novo Banco
                        </button>

                        <div class="dropdown">
                            <div class="avatar user-avatar dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" style="cursor: pointer; overflow: hidden; padding: 0;">
                                <?php if (!empty($fotoUsuarioLogado) && file_exists($fotoUsuarioLogado)): ?>
                                    <img src="<?= htmlspecialchars($fotoUsuarioLogado) ?>" alt="Perfil"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <?= htmlspecialchars($iniciaisUsuario) ?>
                                <?php endif; ?>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow"
                                style="background-color: #14161d; border: 1px solid #222630;">
                                <li>
                                    <h6 class="dropdown-header text-light"><?= htmlspecialchars($nomeUsuarioLogado) ?>
                                    </h6>
                                </li>
                                <li><small class="dropdown-item-text text-muted text-truncate"
                                        style="max-width: 200px;"><?= htmlspecialchars($emailUsuarioLogado) ?></small>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-secondary">
                                </li>
                                <li><a class="dropdown-item text-light" href="configuracoes.php">⚙ Minhas
                                        Configurações</a></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Sair da Conta</a></li>
                            </ul>
                        </div>
                    </div>
                </header>

                <div class="heading-row">
                    <div>
                        <p class="eyebrow">INSTITUIÇÕES E CARTEIRAS</p>
                        <h1>Gerenciar Contas e Bancos</h1>
                    </div>
                </div>

                <?php if (!empty($mensagemSucesso)): ?>
                    <div class="alert alert-success py-2 text-center" role="alert"><?= htmlspecialchars($mensagemSucesso) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensagemErro)): ?>
                    <div class="alert alert-danger py-2 text-center" role="alert"><?= htmlspecialchars($mensagemErro) ?>
                    </div>
                <?php endif; ?>

                <!-- Lista de Bancos em Grid -->
                <div class="row g-4 mt-2">
                    <?php if (!empty($bancos)): ?>
                        <?php foreach ($bancos as $banco): ?>
                            <div class="col-md-4">
                                <article class="bank-card d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h3 class="h5 text-white mb-0"><?= htmlspecialchars($banco['nm_banco']) ?></h3>
                                            <span class="badge-categoria"><?= htmlspecialchars($banco['nm_categoria']) ?></span>
                                        </div>
                                        <p class="text-muted small mb-1">Saldo Inicial Cadastrado:</p>
                                        <h4 class="text-success fw-bold mb-3">R$
                                            <?= number_format($banco['ds_dinheiro_banco'], 2, ',', '.') ?></h4>
                                    </div>
                                    <div
                                        class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary">
                                        <small class="text-muted">ID Conta: #<?= $banco['cd_banco'] ?></small>
                                        <form action="" method="POST"
                                            onsubmit="return confirm('Deseja realmente excluir este banco?');">
                                            <input type="hidden" name="acao" value="excluir_banco">
                                            <input type="hidden" name="id_banco" value="<?= $banco['cd_banco'] ?>">
                                            <button type="submit" class="btn-excluir">Excluir Conta</button>
                                        </form>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="panel text-center py-5">
                                <p class="text-muted mb-3">Nenhum banco ou conta cadastrada no momento.</p>
                                <button type="button" class="btn btn-sm fw-bold px-4 py-2" data-bs-toggle="modal"
                                    data-bs-target="#modalNovoBanco"
                                    style="background-color: #8fe028; color: #0d0e12; border-radius: 8px;">
                                    + Cadastrar Primeiro Banco
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <footer class="footer mt-5">© <?= date('Y') ?> GERENCI MONEY. Todos os direitos reservados.</footer>
            </section>
        </main>
    </div>

    <!-- MODAL DE NOVO BANCO -->
    <div class="modal fade" id="modalNovoBanco" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="background-color: #14161d; color: #fff; border: 1px solid #222630; border-radius: 12px;">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title">Cadastrar Novo Banco / Conta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="acao" value="cadastrar_banco">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-white">Nome do Banco / Instituição:</label>
                            <select name="nomeBanco" class="form-control bg-black text-white border-secondary" required>
                                <option value="">Selecione a instituição...</option>
                                <option value="Nubank">Nubank</option>
                                <option value="Mercado Pago">Mercado Pago</option>
                                <option value="Bradesco">Bradesco</option>
                                <option value="Itaú">Itaú</option>
                                <option value="Banco do Brasil">Banco do Brasil</option>
                                <option value="Caixa Econômica">Caixa Econômica</option>
                                <option value="Santander">Santander</option>
                                <option value="Inter">Inter</option>
                                <option value="Carteira / Dinheiro Físico">Carteira / Dinheiro Físico</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Tipo de Conta (Categoria):</label>
                            <select name="idCategoria" class="form-control bg-black text-white border-secondary"
                                required>
                                <option value="">Selecione o tipo de conta...</option>
                                <?php foreach ($categoriasBanco as $cat): ?>
                                    <option value="<?= $cat['cd_categoria']; ?>">
                                        <?= htmlspecialchars($cat['nm_categoria']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Saldo Inicial (R$):</label>
                            <input type="number" step="0.01" name="saldoInicial"
                                class="form-control bg-black text-white border-secondary" value="0.00" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm fw-bold px-4"
                            style="background-color: #8fe028; color: #0d0e12;">Salvar Banco</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>