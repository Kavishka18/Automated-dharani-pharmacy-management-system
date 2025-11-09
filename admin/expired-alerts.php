<?php
session_start();
include('includes/dbconnection.php');

// === PHPMailer - YOUR EXACT PATH ===
include('/Applications/XAMPP/xamppfiles/htdocs/pms/vendor/phpmailer/phpmailer/src/PHPMailer.php');
include('/Applications/XAMPP/xamppfiles/htdocs/pms/vendor/phpmailer/phpmailer/src/SMTP.php');
include('/Applications/XAMPP/xamppfiles/htdocs/pms/vendor/phpmailer/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// === 1. Login Check ===
$adminId = $_SESSION['pmsaid'] ?? 0;
if ($adminId == 0) { 
    header('location: logout.php'); 
    exit(); 
}

// === 2. TODAY'S DATE for email tracking ===
$today = date('Y-m-d');

// === 3. Fetch Critical Medicines ===
$q = mysqli_query($con, "
    SELECT 
        m.MedicineName, m.MedicineCompany, m.MedicineBatchno,
        m.ExpiryDate, m.Quantity AS total_qty,
        COALESCE(SUM(c.ProductQty), 0) AS sold_qty,
        (m.Quantity - COALESCE(SUM(c.ProductQty), 0)) AS remaining_qty
    FROM tblmedicine m
    LEFT JOIN tblcart c ON m.ID = c.ProductId AND c.IsCheckOut = '1'
    GROUP BY m.ID
    HAVING (m.ExpiryDate <= CURDATE() + INTERVAL 30 DAY)
    ORDER BY m.ExpiryDate ASC
");

$alerts = [];
$now = time();
$expired = $urgent = $warning = [];

while ($row = mysqli_fetch_assoc($q)) {
    $daysLeft = (strtotime($row['ExpiryDate']) - $now) / 86400;
    $priority = 'low';

    if ($daysLeft <= 0) {
        $priority = 'expired';
        $expired[] = $row;
    } elseif ($daysLeft <= 7) {
        $priority = 'urgent';
        $urgent[] = $row;
    } elseif ($daysLeft <= 30) {
        $priority = 'warning';
        $warning[] = $row;
    }

    $alerts[] = [
        'name' => $row['MedicineName'],
        'company' => $row['MedicineCompany'],
        'batch' => $row['MedicineBatchno'],
        'expiry' => $row['ExpiryDate'],
        'days_left' => round($daysLeft),
        'remaining' => $row['remaining_qty'],
        'priority' => $priority
    ];
}

// === 4. SEND EMAIL ALERTS (Only once per day) ===
$emailSentToday = $_SESSION['email_alert_sent'] ?? false;

if (!$emailSentToday && (count($expired) > 0 || count($urgent) > 0 || count($warning) > 0)) {
    
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aceri32005@gmail.com';  // YOUR EMAIL
        $mail->Password   = 'cnqo pqzd zxfb jijx'; // **REPLACE WITH APP PASSWORD**
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Recipients
        $mail->setFrom('acari32005@gmail.com', 'Dharani Pharmacy Alert System');
        $mail->addAddress('aceri32005@gmail.com');  // ADMIN EMAIL

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = '🚨 Pharmacy Alert: Expired & Expiring Medicines';

        // Build HTML Email Body
        $htmlBody = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; color: white;">
                <h1 style="margin: 0; font-size: 28px;">🩺 Dharani Pharmacy</h1>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Critical Medicine Alert System</p>
            </div>
            <div style="padding: 30px; background: #f8f9fa;">
                <h2 style="color: #333;">📋 Daily Expiry Report</h2>
                <p><strong>Date:</strong> ' . date('F j, Y') . '</p>';

        // EXPIRED SECTION
        if (count($expired) > 0) {
            $htmlBody .= '
                <div style="background: #ffeaea; border-left: 5px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 8px;">
                    <h3 style="color: #dc3545; margin-top: 0;">
                        <i class="fas fa-exclamation-triangle"></i> 🚨 ' . count($expired) . ' MEDICINES EXPIRED
                    </h3>';
            foreach ($expired as $item) {
                $daysAgo = abs((strtotime($item['ExpiryDate']) - $now) / 86400);
                $htmlBody .= '
                    <div style="background: white; padding: 12px; margin: 10px 0; border-radius: 6px; border-left: 4px solid #dc3545;">
                        <strong>' . htmlspecialchars($item['MedicineName']) . '</strong><br>
                        <small>' . htmlspecialchars($item['MedicineCompany']) . ' • Batch: ' . $item['MedicineBatchno'] . '</small><br>
                        <strong>⚠️ Expired ' . $daysAgo . ' days ago</strong> | Stock: ' . $item['remaining_qty'];
            }
            $htmlBody .= '</div>';
        }

        // URGENT SECTION
        if (count($urgent) > 0) {
            $htmlBody .= '
                <div style="background: #fff4e6; border-left: 5px solid #fd7e14; padding: 20px; margin: 20px 0; border-radius: 8px;">
                    <h3 style="color: #fd7e14; margin-top: 0;">
                        <i class="fas fa-clock"></i> ⚠️ ' . count($urgent) . ' MEDICINES EXPIRING SOON (≤7 days)
                    </h3>';
            foreach ($urgent as $item) {
                $daysLeft = (strtotime($item['ExpiryDate']) - $now) / 86400;
                $htmlBody .= '
                    <div style="background: white; padding: 12px; margin: 10px 0; border-radius: 6px; border-left: 4px solid #fd7e14;">
                        <strong>' . htmlspecialchars($item['MedicineName']) . '</strong><br>
                        <small>' . htmlspecialchars($item['MedicineCompany']) . ' • Batch: ' . $item['MedicineBatchno'] . '</small><br>
                        <strong>⏰ ' . round($daysLeft) . ' days left</strong> | Stock: ' . $item['remaining_qty'];
            }
            $htmlBody .= '</div>';
        }

        // WARNING SECTION
        if (count($warning) > 0) {
            $htmlBody .= '
                <div style="background: #fffbe6; border-left: 5px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 8px;">
                    <h3 style="color: #ffc107; margin-top: 0;">
                        <i class="fas fa-exclamation-circle"></i> 📅 ' . count($warning) . ' MEDICINES EXPIRING (8-30 days)
                    </h3>';
            foreach ($warning as $item) {
                $daysLeft = (strtotime($item['ExpiryDate']) - $now) / 86400;
                $htmlBody .= '
                    <div style="background: white; padding: 12px; margin: 10px 0; border-radius: 6px; border-left: 4px solid #ffc107;">
                        <strong>' . htmlspecialchars($item['MedicineName']) . '</strong><br>
                        <small>' . htmlspecialchars($item['MedicineCompany']) . ' • Batch: ' . $item['MedicineBatchno'] . '</small><br>
                        <strong>📋 ' . round($daysLeft) . ' days remaining</strong> | Stock: ' . $item['remaining_qty'];
            }
            $htmlBody .= '</div>';
        }

        $htmlBody .= '
                <div style="background: #e9ecef; padding: 20px; border-radius: 8px; text-align: center; margin-top: 30px;">
                    <p><a href="http://localhost/pms/admin/expired-alerts.php" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold;">🔗 View Full Dashboard</a></p>
                    <p style="color: #6c757d; font-size: 0.9em; margin-top: 15px;">
                        This is an automated alert from <strong>Dharani Pharmacy Management System</strong><br>
                        © ' . date('Y') . ' All rights reserved.
                    </p>
                </div>
            </div>
        </div>';

        $mail->Body = $htmlBody;

        $mail->send();
        
        // Mark as sent today
        $_SESSION['email_alert_sent'] = true;
        $_SESSION['last_email_date'] = $today;

    } catch (Exception $e) {
        // Silent fail - don't break page (log error if needed)
        error_log("Email alert failed: {$mail->ErrorInfo}");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expired Drug Alerts - PMS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet"/>
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet"/>
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet"/>
    <style>
        .alert-card { border-left: 6px solid; padding: 15px; margin-bottom: 15px; border-radius: 8px; }
        .priority-expired { border-left-color: #dc3545; background: #ffeaea; }
        .priority-urgent  { border-left-color: #fd7e14; background: #fff4e6; }
        .priority-warning { border-left-color: #ffc107; background: #fffbe6; }
        .priority-label { font-weight: bold; text-transform: uppercase; font-size: 0.8rem; }
        .email-status { 
            position: fixed; top: 20px; right: 20px; 
            padding: 12px 20px; border-radius: 6px; 
            color: white; font-weight: bold; z-index: 9999; 
        }
        .email-sent { background: #28a745; }
        .email-failed { background: #dc3545; }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- EMAIL STATUS NOTIFICATION -->
        <?php if (isset($_SESSION['email_alert_sent']) && $_SESSION['email_alert_sent']): ?>
            <div class="email-status email-sent">
                <i class="fas fa-paper-plane"></i> Alert Email Sent to Admin!
            </div>
            <?php unset($_SESSION['email_alert_sent']); ?>
        <?php endif; ?>

        <!-- Header -->
        <div class="header bg-gradient-danger pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center py-4">
                        <div class="col-lg-6">
                            <h1 class="text-white mb-0">
                                <i class="ni ni-bell-55"></i> Expired & Expiring Drug Alerts
                            </h1>
                            <p class="text-white opacity-8">Critical medicines needing immediate action</p>
                        </div>
                        <div class="col-lg-6 text-right">
                            <span class="h2 text-white"><?php echo count($alerts); ?> Critical Items</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts List -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0">Priority Alerts</h3>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Auto-email alerts sent daily to admin
                            </small>
                        </div>
                        <div class="card-body">

                            <?php if (empty($alerts)): ?>
                                <div class="text-center py-5">
                                    <i class="ni ni-check-bold text-success" style="font-size: 4rem;"></i>
                                    <h4 class="mt-3 text-success">No Critical Alerts!</h4>
                                    <p class="text-muted">All medicines are within safe expiry range.</p>
                                </div>
                            <?php else: ?>

                                <!-- Expired (Red) -->
                                <?php $expiredAlerts = array_filter($alerts, fn($a) => $a['priority'] === 'expired'); ?>
                                <?php if ($expiredAlerts): ?>
                                    <h5 class="text-danger mb-3">
                                        <i class="ni ni-fat-remove"></i> EXPIRED (Discard Now)
                                    </h5>
                                    <?php foreach ($expiredAlerts as $a): ?>
                                        <div class="alert-card priority-expired">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong><?php echo htmlspecialchars($a['name']); ?></strong>
                                                    <small class="text-muted d-block">
                                                        <?php echo htmlspecialchars($a['company']); ?> • Batch: <?php echo $a['batch']; ?>
                                                    </small>
                                                </div>
                                                <div class="text-right">
                                                    <span class="priority-label text-danger">EXPIRED</span><br>
                                                    <small>Expired <?php echo abs($a['days_left']); ?> days ago</small>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <small>
                                                <strong>Stock Left:</strong> <?php echo $a['remaining']; ?> | 
                                                <strong>Expiry:</strong> <?php echo $a['expiry']; ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Urgent (Orange) -->
                                <?php $urgentAlerts = array_filter($alerts, fn($a) => $a['priority'] === 'urgent'); ?>
                                <?php if ($urgentAlerts): ?>
                                    <h5 class="text-warning mb-3 mt-4">
                                        <i class="ni ni-time-alarm"></i> EXPIRING IN ≤ 7 DAYS
                                    </h5>
                                    <?php foreach ($urgentAlerts as $a): ?>
                                        <div class="alert-card priority-urgent">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong><?php echo htmlspecialchars($a['name']); ?></strong>
                                                    <small class="text-muted d-block">
                                                        <?php echo htmlspecialchars($a['company']); ?> • Batch: <?php echo $a['batch']; ?>
                                                    </small>
                                                </div>
                                                <div class="text-right">
                                                    <span class="priority-label text-warning">URGENT</span><br>
                                                    <small>Only <?php echo $a['days_left']; ?> days left</small>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <small>
                                                <strong>Stock Left:</strong> <?php echo $a['remaining']; ?> | 
                                                <strong>Expiry:</strong> <?php echo $a['expiry']; ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Warning (Yellow) -->
                                <?php $warningAlerts = array_filter($alerts, fn($a) => $a['priority'] === 'warning'); ?>
                                <?php if ($warningAlerts): ?>
                                    <h5 class="text-muted mb-3 mt-4">
                                        <i class="ni ni-watch-time"></i> EXPIRING SOON (8–30 Days)
                                    </h5>
                                    <?php foreach ($warningAlerts as $a): ?>
                                        <div class="alert-card priority-warning">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong><?php echo htmlspecialchars($a['name']); ?></strong>
                                                    <small class="text-muted d-block">
                                                        <?php echo htmlspecialchars($a['company']); ?> • Batch: <?php echo $a['batch']; ?>
                                                    </small>
                                                </div>
                                                <div class="text-right">
                                                    <span class="priority-label text-muted">PLAN AHEAD</span><br>
                                                    <small><?php echo $a['days_left']; ?> days remaining</small>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <small>
                                                <strong>Stock Left:</strong> <?php echo $a['remaining']; ?> | 
                                                <strong>Expiry:</strong> <?php echo $a['expiry']; ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Auto-hide email notification -->
    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
    <script>
        $('.email-status').delay(3000).fadeOut('slow');
    </script>
</body>
</html>