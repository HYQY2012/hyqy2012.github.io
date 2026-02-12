<?php
header('Content-Type: application/json');
$response = [
    'error' => '',
    'success' => ''
];

$username = trim($_POST['username'] ?? '');
$content = trim($_POST['content'] ?? '');

if (empty($username)) {
    $response['error'] = '请输入用户名';
} elseif (empty($content)) {
    $response['error'] = '请输入内容';
} else {
    // 定位到父文件夹下的articles目录
    $articlesDir = dirname(__DIR__) . '/articles/';
    
    // 创建articles目录（如果不存在）
    if (!is_dir($articlesDir)) {
        mkdir($articlesDir, 0755, true);
    }
    
    // 验证目录可写性
    if (!is_writable($articlesDir)) {
        $response['error'] = 'articles目录无写入权限，请检查目录权限';
    } else {
        // 获取目录下所有id_*.md文件
        $files = glob($articlesDir . 'id_*.md');
        $maxId = 0;
        
        // 提取最大ID
        foreach ($files as $file) {
            preg_match('/id_(\d+)\.md/', $file, $matches);
            if (isset($matches[1])) {
                $id = (int)$matches[1];
                if ($id > $maxId) {
                    $maxId = $id;
                }
            }
        }
        
        // 生成新ID和文件路径
        $newId = $maxId + 1;
        $idStr = sprintf('id_%05d.md', $newId);
        $filePath = $articlesDir . $idStr;
        
        // 构建Markdown内容（包含元信息）
        $markdownContent = "---\n";
        $markdownContent .= "username: {$username}\n";
        $markdownContent .= "created_at: " . date('Y-m-d H:i:s') . "\n";
        $markdownContent .= "---\n\n";
        $markdownContent .= $content;
        
        // 保存文件
        if (file_put_contents($filePath, $markdownContent)) {
            $response['success'] = "内容上传成功！文件已保存为：articles/{$idStr}";
        } else {
            $response['error'] = '文件保存失败，请重试';
        }
    }
}

// 输出JSON响应
echo json_encode($response);
?>
