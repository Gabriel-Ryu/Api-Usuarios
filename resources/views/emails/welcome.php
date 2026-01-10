<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;">
        <h1 style="color: #2d3748;">Olá, <?php echo htmlspecialchars($name); ?>!</h1>
        
        <p>Seu usuário foi criado com sucesso</p>
        
        <p>Informações do seu cadastro:</p>
        <ul>
            <li><strong>E-mail:</strong> <?php echo htmlspecialchars($email); ?></li>
            <li><strong>Data:</strong> <?php echo date('d/m/Y'); ?></li>
        </ul>

        <div style="margin-top: 30px; padding: 15px; background-color: #f7fafc; border-left: 4px solid #4a5568;">
            <p style="margin: 0;">Obrigado por se juntar a nós!</p>
        </div>
    </div>
</body>
</html>
