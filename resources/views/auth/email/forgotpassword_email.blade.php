<!DOCTYPE html>
<html lang="en">

<head>
    <title>Title</title>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    <div style="width:770px;margin:auto;border:1px solid #e9e6e8;">
        <!-- <table style="height: 33px;background-color: #65a2ce;margin: 0;width:100%;">
            <tr>
                <td
                    style="height:33px;vertical-align: center;text-align: center;font-size: 12px;font-weight: normal;font-style: normal;font-stretch: normal;line-height: normal;letter-spacing: normal;text-align: center;color: #ffffff;">
                    If you can’t read this email, click here for the web version
                </td>
            </tr>
        </table> -->
       
        <table style="height: 113px;background-color:#6a69e8;margin: 0;width:100%;">
            <tr>
                <td style="width:25px;"></td>
                <td style="vertical-align: center;font-size: 30px;
                  font-weight: bold;
                  font-style: normal;
                  font-stretch: normal;
                  line-height:109px;
                  letter-spacing: normal;
                  color: #ffffff;text-align: center;">Transluc.io - Please Confirm Your Email</td>
                <td style="width:25px;"></td>
            </tr>
        </table>

        <div style="height:25px"></div>
        <table style="margin: 0;width:100%;">
            <tr>
                <td style="width:33px;"></td>
                <td>
                    <p style="    margin: 0;
                    font-size: 20px;
                    font-weight: 500;
                    line-height: 34px;"></p>
                        <div style="height:10px"></div>
                    <p style="color: #535353;
                    margin: 0;
                    font-size: 16px;">You are receiving this email because we received a password reset request for your account.
                    This password reset link will expire in 60 minutes.

If you did not request a password reset, no further action is required.</p>

                    </h2>
                    <div style="height:20px"></div>
                    
                    <a href="{{url('/reset-forgot-password/'.$data['token'])}}" style="background: #EB3142;
                    display: inline-block;
                    color: #fff;
                    padding: 3px 27px;
                    text-align: center;
                    text-decoration: none;
                    border-radius: 26px;
                    height: 31px;
                    line-height: 31px;">Reset Password</a>
                </td>
                <td style="width:33px;"></td>
            </tr>
        </table>
        <div style="height:100px;"> </div>

        <table style="margin: 0;width:100%;">
            <tr>
                <td style="width:33px;"></td>
                <td>
                    <img style="width: 160px;
                    display: block;"
                        src="http://template1.teexponent.com/translucio/html/assets/images/logo/logo.png" />
                    <span style="color: #9086a3;
                    font-size: 13px;
                    display: inline-block;
                    line-height: 50px;">
                        © 2018-2019 Transluc.io. All Rights Reserved.

                    </span>
                </td>
                <td style="width:33px;"></td>
            </tr>
        </table>
        <div style="height:100px;"> </div>

    </div>
</body>
</html>