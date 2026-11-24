<?php
session_start();
include('inc/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: entrarConta.php');
    exit;
}

$idUsuario = $_SESSION['usuario_id'];
$mensagemSucesso = "";
$mensagemErro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoNome = trim($_POST['nomeUsuario'] ?? '');
    $novoEmail = trim($_POST['emailUsuario'] ?? '');
    $senhaAntiga = $_POST['senhaAntiga'] ?? '';
    $novaSenha = $_POST['novaSenha'] ?? '';

    try {
        $stmtBusca = $pdo->prepare("SELECT * FROM tb_usuarios WHERE cd_usuario = ?");
        $stmtBusca->execute([$idUsuario]);
        $usuarioAtual = $stmtBusca->fetch();

        if ($usuarioAtual) {
            $caminhoFotoFinal = $usuarioAtual['ds_foto_usuario'] ?? null;

            // 1. Processamento do Upload da Foto de Perfil
            if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
                $arquivoTmp = $_FILES['fotoPerfil']['tmp_name'];
                $nomeOriginal = $_FILES['fotoPerfil']['name'];
                $tamanhoArquivo = $_FILES['fotoPerfil']['size'];
                $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

                $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($extensao, $extensoesPermitidas)) {
                    $mensagemErro = "Formato inválido! Envie apenas JPG, PNG ou WEBP.";
                } elseif ($tamanhoArquivo > 2 * 1024 * 1024) { // Limite de 2MB
                    $mensagemErro = "A imagem deve ter no máximo 2MB.";
                } else {
                    if (!is_dir('uploads')) {
                        mkdir('uploads', 0777, true);
                    }

                    // Gera um nome único para evitar conflito de arquivos
                    $novoNomeArquivo = 'perfil_' . $idUsuario . '_' . time() . '.' . $extensao;
                    $destino = 'uploads/' . $novoNomeArquivo;

                    if (move_uploaded_file($arquivoTmp, $destino)) {
                        // Apaga a foto antiga do servidor se existir
                        if (!empty($caminhoFotoFinal) && file_exists($caminhoFotoFinal)) {
                            unlink($caminhoFotoFinal);
                        }
                        $caminhoFotoFinal = $destino;
                    } else {
                        $mensagemErro = "Falha ao salvar a imagem na pasta uploads.";
                    }
                }
            }

            // 2. Atualização dos dados no banco (apenas se não houve erro no upload)
            if (empty($mensagemErro)) {
                if (!empty($senhaAntiga) || !empty($novaSenha)) {
                    if (password_verify($senhaAntiga, $usuarioAtual['ds_senha_usuario'])) {
                        if (strlen($novaSenha) >= 6) {
                            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
                            
                            $stmtUpdate = $pdo->prepare("UPDATE tb_usuarios SET nm_usuario = :nome, ds_email_usuario = :email, ds_senha_usuario = :senha, ds_foto_usuario = :foto WHERE cd_usuario = :id");
                            $stmtUpdate->execute([
                                ':nome' => $novoNome,
                                ':email' => $novoEmail,
                                ':senha' => $novaSenhaHash,
                                ':foto' => $caminhoFotoFinal,
                                ':id' => $idUsuario
                            ]);

                            $_SESSION['usuario_nome'] = $novoNome;
                            $_SESSION['usuario_email'] = $novoEmail;
                            $_SESSION['usuario_foto'] = $caminhoFotoFinal;
                            $mensagemSucesso = "Dados, foto e senha atualizados com sucesso!";
                        } else {
                            $mensagemErro = "A nova senha deve ter pelo menos 6 caracteres.";
                        }
                    } else {
                        $mensagemErro = "A senha atual está incorreta.";
                    }
                } else {
                    $stmtUpdate = $pdo->prepare("UPDATE tb_usuarios SET nm_usuario = :nome, ds_email_usuario = :email, ds_foto_usuario = :foto WHERE cd_usuario = :id");
                    $stmtUpdate->execute([
                        ':nome' => $novoNome,
                        ':email' => $novoEmail,
                        ':foto' => $caminhoFotoFinal,
                        ':id' => $idUsuario
                    ]);

                    $_SESSION['usuario_nome'] = $novoNome;
                    $_SESSION['usuario_email'] = $novoEmail;
                    $_SESSION['usuario_foto'] = $caminhoFotoFinal;
                    $mensagemSucesso = "Dados atualizados com sucesso!";
                }
            }
        }
    } catch (PDOException $e) {
        $mensagemErro = "Erro ao atualizar dados: " . $e->getMessage();
    }
}

$stmtInfo = $pdo->prepare("SELECT * FROM tb_usuarios WHERE cd_usuario = ?");
$stmtInfo->execute([$idUsuario]);
$dadosUsuario = $stmtInfo->fetch();

$nomeUsuarioLogado = $dadosUsuario['nm_usuario'] ?? $_SESSION['usuario_nome'];
$emailUsuarioLogado = $dadosUsuario['ds_email_usuario'] ?? $_SESSION['usuario_email'];
$cpfUsuarioLogado = $dadosUsuario['ds_cpf_usuario'] ?? '';
$dataNascUsuario = $dadosUsuario['dt_nascimento_usuario'] ?? '';
$fotoUsuarioLogado = $dadosUsuario['ds_foto_usuario'] ?? '';

$partesNome = explode(' ', trim($nomeUsuarioLogado));
$iniciaisUsuario = mb_strtoupper(substr($partesNome[0], 0, 1));
if (count($partesNome) > 1) {
    $iniciaisUsuario .= mb_strtoupper(substr(end($partesNome), 0, 1));
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configurações | GERENCI MONEY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
    <style>
        .settings-card {
            background-color: #14161d;
            border: 1px solid #222630;
            border-radius: 16px;
            padding: 30px;
            max-width: 700px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background-color: #000;
            color: white;
            border: 1px solid #2d3342;
        }
        .form-control:focus {
            background-color: #000;
            color: white;
            border-color: #8fe028;
            box-shadow: none;
        }
        .form-control::placeholder {
            color: white;
            opacity: 0.3;
        }
        .btn-salvar {
            background-color: #8fe028;
            color: black;
            font-weight: 600;
            border: none;
        }
        .btn-salvar:hover {
            background-color: #a3e635;
        }
        label, .form-label {
            color: #ffffff !important;
        }
        .txtparaPreto {
            color: black;
        }
        .perfil-preview {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #8fe028;
            background-color: #1e222d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
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
                <a class="menu-link" href="contas.php"><span>♙</span> Contas</a>
            </nav>

            <p class="menu-label settings-label">CONFIGURAÇÕES</p>
            <nav class="menu">
                <a class="menu-link active" href="configuracoes.php"><span>⚙</span>Perfil</a>
                <a class="menu-link text-danger" href="logout.php">Sair da conta</a>
            </nav>
        </aside>

        <section class="main-content">
            <header class="topbar">
                <div class="breadcrumbs"><span>▱</span><span>☆</span><span class="muted">/</span><span class="muted">Painel</span><span class="muted">/</span><strong>Configurações</strong></div>
                
                <div class="top-actions">
                    <div class="dropdown">
                        <div class="avatar user-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer; overflow: hidden; padding: 0;">
                            <?php if (!empty($fotoUsuarioLogado) && file_exists($fotoUsuarioLogado)): ?>
                                <img src="<?= htmlspecialchars($fotoUsuarioLogado) ?>" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <?= htmlspecialchars($iniciaisUsuario) ?>
                            <?php endif; ?>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" style="background-color: #14161d; border: 1px solid #222630;">
                            <li><h6 class="dropdown-header text-light"><?= htmlspecialchars($nomeUsuarioLogado) ?></h6></li>
                            <li><small class="dropdown-item-text text-muted text-truncate" style="max-width: 200px;"><?= htmlspecialchars($emailUsuarioLogado) ?></small></li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><a class="dropdown-item text-light active" href="configuracoes.php">⚙ Minhas Configurações</a></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Sair da Conta</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="heading-row mb-4">
                <div><p class="eyebrow">GERENCIE SEU PERFIL</p><h1>Configurações da Conta</h1></div>
            </div>

            <div class="settings-card">
                <?php if (!empty($mensagemSucesso)): ?>
                    <div class="alert alert-success text-center" role="alert"><?= $mensagemSucesso; ?></div>
                <?php endif; ?>

                <?php if (!empty($mensagemErro)): ?>
                    <div class="alert alert-danger text-center" role="alert"><?= $mensagemErro; ?></div>
                <?php endif; ?>

                <!-- enctype="multipart/form-data" adicionado para permitir upload de arquivo -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <h4 class="mb-3 text-light" style="font-size: 1.1rem; border-bottom: 1px solid #222630; padding-bottom: 10px;">Foto de Perfil</h4>

                    <div class="d-flex align-items-center gap-4 mb-4">
                        <?php if (!empty($fotoUsuarioLogado) && file_exists($fotoUsuarioLogado)): ?>
                            <img src="<?= htmlspecialchars($fotoUsuarioLogado) ?>" alt="Foto atual" class="perfil-preview">
                        <?php else: ?>
                            <div class="perfil-preview"><?= htmlspecialchars($iniciaisUsuario) ?></div>
                        <?php endif; ?>

                        <div class="w-100">
                            <label class="form-label text-muted">Escolher nova foto (JPG, PNG ou WEBP - Máx. 2MB)</label>
                            <input type="file" name="fotoPerfil" class="form-control" accept="image/jpeg, image/png, image/webp">
                        </div>
                    </div>

                    <h4 class="mb-3 text-light" style="font-size: 1.1rem; border-bottom: 1px solid #222630; padding-bottom: 10px;">Informações Pessoais</h4>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Nome de Usuário</label>
                        <input type="text" name="nomeUsuario" class="form-control" value="<?= htmlspecialchars($nomeUsuarioLogado); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">E-mail</label>
                        <input type="email" name="emailUsuario" class="form-control" value="<?= htmlspecialchars($emailUsuarioLogado); ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">CPF (Não editável)</label>
                            <input type="text" class="txtparaPreto form-control" value="<?= htmlspecialchars($cpfUsuarioLogado); ?>" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Data de Nascimento</label>
                            <input type="date" class="txtparaPreto form-control" value="<?= htmlspecialchars($dataNascUsuario); ?>" disabled>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-light" style="font-size: 1.1rem; border-bottom: 1px solid #222630; padding-bottom: 10px;">Alterar Senha (Opcional)</h4>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Senha Atual</label>
                        <input type="password" name="senhaAntiga" class="form-control" placeholder="Digite sua senha atual caso queira alterar">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Nova Senha</label>
                        <input type="password" name="novaSenha" class="form-control" placeholder="Mínimo de 6 caracteres">
                    </div>

                    <button type="submit" class="btn btn-salvar w-100 mt-3 py-2">Salvar Alterações</button>
                </form>
            </div>

            <footer class="footer mt-5">© <?= date('Y') ?> GERENCI MONEY. Todos os direitos reservados.</footer>
        </section>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>