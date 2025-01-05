<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset styles for compatibility */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }
        /* Remove extra spacing for Outlook */
        table {
            border-collapse: collapse !important;
        }
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }
        /* General styles */
        a {
            color: #1a82e2;
        }
        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            color: #ffffff;
            background-color: #1a82e2;
            text-decoration: none;
            border-radius: 5px;
        }
        .otp-code {
            font-weight: bold;
            color: #1a82e2;
        }
    </style>
</head>
<body style="background-color: #f2f2f2;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 5px;">
                    <tr>
                        <td align="center" style="padding: 40px;">
                            <img src="https://via.placeholder.com/100x100.png?text=Logo" alt="Logo" style="display: block; width: 100px; height: 100px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; text-align: center; font-family: Arial, sans-serif; font-size: 18px; line-height: 24px; color: #333333;">
                            <p>Hello,</p>
                            <p>Use the following One-Time Password (OTP) to complete your verification process. This code is valid for 10 minutes.</p>
                            <p class="otp-code">{{ $data['code'] }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; text-align: center;">
                            <a href="#" class="button">Verify Now</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; text-align: center; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; color: #777777;">
                            <p>If you did not request this code, please ignore this email.</p>
                            <p>Thank you!</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
