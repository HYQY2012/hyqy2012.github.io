<?php
$error = '';
$success = '';
$username = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (empty($username)) {
        $error = '请输入用户名';
    } elseif (empty($content)) {
        $error = '请输入内容';
    } else {
        $dataDir = 'data/';
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
        
        $files = glob($dataDir . 'id_*.md');
        $maxId = 0;
        
        foreach ($files as $file) {
            preg_match('/id_(\d+)\.md/', $file, $matches);
            if (isset($matches[1])) {
                $id = (int)$matches[1];
                if ($id > $maxId) {
                    $maxId = $id;
                }
            }
        }
        
        $newId = $maxId + 1;
        $idStr = sprintf('id_%05d.md', $newId);
        $filePath = $dataDir . $idStr;
        
        $markdownContent = "---\n";
        $markdownContent .= "username: {$username}\n";
        $markdownContent .= "created_at: " . date('Y-m-d H:i:s') . "\n";
        $markdownContent .= "---\n\n";
        $markdownContent .= $content;
        
        if (file_put_contents($filePath, $markdownContent)) {
            $success = "内容上传成功！文件已保存为：{$idStr}";
            $username = '';
            $content = '';
        } else {
            $error = '文件保存失败，请检查目录权限';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube风格内容上传</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', Arial, sans-serif;
        }
        
        body {
            background-color: #f9f9f9;
            color: #333;
        }
        
        header {
            background-color: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ff0000;
            font-size: 24px;
            font-weight: bold;
        }
        
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .upload-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .upload-card h2 {
            color: #202124;
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #202124;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            min-height: 300px;
            resize: vertical;
            font-family: 'Consolas', 'Monaco', monospace;
        }
        
        .btn {
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .btn:hover {
            background-color: #cc0000;
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        .alert-error {
            background-color: #ffebee;
            color: #b71c1c;
        }
        
        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .hint {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <span>▶</span>
                <span>Content Upload</span>
            </div>
            <div>上传内容/div>
        </div>
    </header>
    
    <div class="container">
        <div class="upload-card">
            <h2>内容上传</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">用户名</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="请输入您的用户名">
                </div>
                
                <div class="form-group">
                    <label for="content">内容 (支持Markdown)</label>
                    <textarea id="content" name="content" placeholder="请输入Markdown格式的内容..."><?php echo htmlspecialchars($content); ?></textarea>
                    <div class="hint">支持Markdown语法：# 标题、**粗体**、*斜体*、[链接](url) 等</div>
                </div>
                
                <button type="submit" class="btn">上传内容</button>
            </form>
        </div>
    </div>
</body>
</html>
