<?php

class PDFGenerator {
    
    public static function generateBookingsPDF($bookings) {
        $date = date('Y-m-d H:i:s');
        $count = count($bookings);
        
        $tableRows = '';
        foreach ($bookings as $b) {
            $statusClass = 'status-' . strtolower($b['status']);
            $tableRows .= '
                <tr>
                    <td>' . htmlspecialchars($b['id']) . '</td>
                    <td><strong>' . htmlspecialchars($b['property_title']) . '</strong></td>
                    <td>' . htmlspecialchars($b['check_in']) . '</td>
                    <td>' . htmlspecialchars($b['check_out']) . '</td>
                    <td>' . htmlspecialchars($b['guests']) . '</td>
                    <td>FCFA ' . number_format($b['total_price'], 0) . '</td>
                    <td><span class="status-badge ' . $statusClass . '">' . strtoupper($b['status']) . '</span></td>
                </tr>';
        }
        
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bookings Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; line-height: 1.6; background: #f5f5f5; }
        .container { padding: 40px; max-width: 210mm; margin: 0 auto; background: white; }
        .header { 
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); 
            color: white; 
            padding: 30px; 
            border-radius: 8px; 
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 28px; margin-bottom: 10px; font-weight: 700; }
        .header p { font-size: 12px; opacity: 0.9; }
        .info-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
            padding-bottom: 10px; 
            border-bottom: 1px solid #eee;
        }
        .info-item { font-size: 12px; }
        .info-label { color: #666; font-weight: 600; margin-bottom: 5px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            background: white;
            font-size: 12px;
        }
        thead { 
            background: #007bff; 
            color: white; 
        }
        th { 
            padding: 12px; 
            text-align: left; 
            font-weight: 600;
            border: none;
        }
        td { 
            padding: 10px 12px; 
            border-bottom: 1px solid #eee;
        }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody tr:hover { background: #f0f7ff; }
        .footer { 
            margin-top: 40px; 
            padding-top: 20px; 
            border-top: 2px solid #007bff;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📅 Bookings Report</h1>
            <p>Guillaume Housing Management System</p>
        </div>
        <div class="info-row">
            <div class="info-item">
                <div class="info-label">Generated Date</div>
                <div>$date</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Records</div>
                <div>$count</div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Property</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Guests</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
$tableRows
            </tbody>
        </table>
        <div class="footer">
            <p>This is an automatically generated report from Guillaume Housing Management System</p>
            <p style="margin-top: 10px; font-size: 10px;">© 2026 Guillaume Housing. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
        return $html;
    }

    public static function generatePropertiesPDF($properties) {
        $date = date('Y-m-d H:i:s');
        $count = count($properties);
        
        $tableRows = '';
        foreach ($properties as $p) {
            $type = ucfirst($p['type'] ?? 'Residential');
            $typeClass = 'type-' . strtolower($p['type'] ?? 'residential');
            $statusClass = 'status-' . str_replace(' ', '-', strtolower($p['status']));
            $statusDisplay = str_replace('-', ' ', strtoupper($p['status']));
            
            $tableRows .= '
                <tr>
                    <td>' . htmlspecialchars($p['id']) . '</td>
                    <td><strong>' . htmlspecialchars($p['title']) . '</strong></td>
                    <td>' . htmlspecialchars($p['location']) . '</td>
                    <td>' . number_format($p['price'], 0) . '</td>
                    <td>' . htmlspecialchars($p['bedrooms']) . '</td>
                    <td>' . htmlspecialchars($p['bathrooms']) . '</td>
                    <td>' . htmlspecialchars($p['area']) . ' sq ft</td>
                    <td><span class="type-badge ' . $typeClass . '">' . $type . '</span></td>
                    <td><span class="status-badge ' . $statusClass . '">' . $statusDisplay . '</span></td>
                </tr>';
        }
        
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Properties Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; line-height: 1.6; background: #f5f5f5; }
        .container { padding: 40px; max-width: 210mm; margin: 0 auto; background: white; }
        .header { 
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); 
            color: white; 
            padding: 30px; 
            border-radius: 8px; 
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 28px; margin-bottom: 10px; font-weight: 700; }
        .header p { font-size: 12px; opacity: 0.9; }
        .info-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
            padding-bottom: 10px; 
            border-bottom: 1px solid #eee;
        }
        .info-item { font-size: 12px; }
        .info-label { color: #666; font-weight: 600; margin-bottom: 5px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            background: white;
            font-size: 11px;
        }
        thead { 
            background: #28a745; 
            color: white; 
        }
        th { 
            padding: 12px; 
            text-align: left; 
            font-weight: 600;
            border: none;
        }
        td { 
            padding: 10px 12px; 
            border-bottom: 1px solid #eee;
        }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody tr:hover { background: #f0fff4; }
        .footer { 
            margin-top: 40px; 
            padding-top: 20px; 
            border-top: 2px solid #28a745;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .type-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }
        .type-residential { background: #cfe2ff; color: #084298; }
        .type-commercial { background: #e2e3e5; color: #383d41; }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-available { background: #d4edda; color: #155724; }
        .status-for-rent { background: #cfe2ff; color: #084298; }
        .status-for-sale { background: #fff3cd; color: #856404; }
        .status-rented { background: #d1ecf1; color: #0c5460; }
        .status-sold { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏠 Properties Report</h1>
            <p>Guillaume Housing Management System</p>
        </div>
        <div class="info-row">
            <div class="info-item">
                <div class="info-label">Generated Date</div>
                <div>$date</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Properties</div>
                <div>$count</div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Price (FCFA)</th>
                    <th>Beds</th>
                    <th>Baths</th>
                    <th>Area</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
$tableRows
            </tbody>
        </table>
        <div class="footer">
            <p>This is an automatically generated report from Guillaume Housing Management System</p>
            <p style="margin-top: 10px; font-size: 10px;">© 2026 Guillaume Housing. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
        return $html;
    }

    public static function generateMessagesPDF($messages) {
        $date = date('Y-m-d H:i:s');
        $count = count($messages);
        
        $tableRows = '';
        foreach ($messages as $m) {
            $tableRows .= '
                <tr>
                    <td>' . htmlspecialchars($m['id']) . '</td>
                    <td><strong>' . htmlspecialchars($m['name']) . '</strong></td>
                    <td>' . htmlspecialchars($m['email']) . '</td>
                    <td>' . htmlspecialchars($m['subject'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars(substr($m['message'], 0, 60)) . '...</td>
                    <td>' . date('Y-m-d', strtotime($m['created_at'])) . '</td>
                </tr>';
        }
        
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Messages Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; line-height: 1.6; background: #f5f5f5; }
        .container { padding: 40px; max-width: 210mm; margin: 0 auto; background: white; }
        .header { 
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); 
            color: white; 
            padding: 30px; 
            border-radius: 8px; 
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 28px; margin-bottom: 10px; font-weight: 700; }
        .header p { font-size: 12px; opacity: 0.9; }
        .info-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
            padding-bottom: 10px; 
            border-bottom: 1px solid #eee;
        }
        .info-item { font-size: 12px; }
        .info-label { color: #666; font-weight: 600; margin-bottom: 5px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            background: white;
            font-size: 11px;
        }
        thead { 
            background: #dc3545; 
            color: white; 
        }
        th { 
            padding: 12px; 
            text-align: left; 
            font-weight: 600;
            border: none;
        }
        td { 
            padding: 10px 12px; 
            border-bottom: 1px solid #eee;
        }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody tr:hover { background: #fff5f5; }
        .footer { 
            margin-top: 40px; 
            padding-top: 20px; 
            border-top: 2px solid #dc3545;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Messages Report</h1>
            <p>Guillaume Housing Management System</p>
        </div>
        <div class="info-row">
            <div class="info-item">
                <div class="info-label">Generated Date</div>
                <div>$date</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Messages</div>
                <div>$count</div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
$tableRows
            </tbody>
        </table>
        <div class="footer">
            <p>This is an automatically generated report from Guillaume Housing Management System</p>
            <p style="margin-top: 10px; font-size: 10px;">© 2026 Guillaume Housing. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
        return $html;
    }
}
