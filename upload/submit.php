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
        $response['success'] = "内容上传成功！文件已保存为：{$idStr}";
    } else {
        $response['error'] = '文件保存失败，请检查目录权限';
    }
}

echo json_encode($response);
?>
