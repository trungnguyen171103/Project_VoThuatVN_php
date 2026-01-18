<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Học Phí - {{ $payment->student->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }

        .bill-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #dc2626;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .bill-info {
            margin-bottom: 30px;
        }

        .bill-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .bill-info td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .bill-info td:first-child {
            font-weight: bold;
            width: 200px;
            color: #333;
        }

        .amount-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            border-left: 4px solid #dc2626;
        }

        .amount-section .label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .amount-section .amount {
            font-size: 32px;
            font-weight: bold;
            color: #dc2626;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-paid {
            background: #10b981;
            color: white;
        }

        .status-pending {
            background: #f59e0b;
            color: white;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-button button {
            background: #dc2626;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .print-button button:hover {
            background: #b91c1c;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .bill-container {
                box-shadow: none;
                padding: 20px;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="print-button">
        <button onclick="window.print()">
            <i>🖨️</i> In Hóa Đơn
        </button>
    </div>

    <div class="bill-container">
        <div class="header">
            <img src="{{ asset('images/logoPNL.png') }}" alt="Logo"
                style="width: 100px; height: 100px; margin-bottom: 15px; object-fit: contain; filter: brightness(1.1) contrast(1.1);">
            <h1>VÕ THUẬT VIỆT NAM</h1>
            <p>HÓA ĐƠN HỌC PHÍ</p>
        </div>

        <div class="bill-info">
            <table>
                <tr>
                    <td>Họ và tên học viên:</td>
                    <td><strong>{{ $payment->student->full_name }}</strong></td>
                </tr>
                <tr>
                    <td>Câu lạc bộ:</td>
                    <td>{{ $payment->tuition->classModel->club->name }}</td>
                </tr>
                <tr>
                    <td>Lớp học:</td>
                    <td>{{ $payment->tuition->classModel->name }} ({{ $payment->tuition->classModel->class_code }})</td>
                </tr>
                <tr>
                    <td>Tháng:</td>
                    <td>{{ str_pad($payment->tuition->month, 2, '0', STR_PAD_LEFT) }}/{{ $payment->tuition->year }}</td>
                </tr>
                <tr>
                    <td>Hạn đóng:</td>
                    <td>{{ \Carbon\Carbon::parse($payment->tuition->due_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Trạng thái:</td>
                    <td>
                        @if($payment->status === 'paid')
                            <span class="status-badge status-paid">Đã thanh toán</span>
                        @else
                            <span class="status-badge status-pending">Chưa thanh toán</span>
                        @endif
                    </td>
                </tr>
                @if($payment->status === 'paid' && $payment->paid_at)
                    <tr>
                        <td>Ngày thanh toán:</td>
                        <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="amount-section">
            <div class="label">Số tiền học phí:</div>
            <div class="amount">{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</div>
        </div>

        <div class="footer">
            <p>Cảm ơn bạn đã tin tưởng và đồng hành cùng Võ Thuật Việt Nam</p>
            <p>Ngày in: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <script>
        // Auto focus for better print experience
        window.onload = function () {
            document.querySelector('.print-button button').focus();
        };
    </script>
</body>

</html>