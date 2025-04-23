<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name ="viewport" content= "width=device-width,initial-scale=1.0">
    <title> TT ONline Account - Login</title>
    <link rel="shortcut icon" href="/images/TT_LOGO.png">
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js"></script>
    
</head>
<body>
    <div class= "loginTop">
        <a href="/login.php">
            <img src="/image/TT_LOGO.png" class="loginlogo">
        </a>
        <a href="/login.php">TT Account</a>

    </div>

    <div class="container">
        <div class ="tab-switch">
            <button class="tab-button active">LOGIN</button>
            <button class="tab-button active">REGISTER</button>
        </div>

        <div class="country-selector">
            <span>Country/Area</span>
            <span>Malaysia</span>
        </div>
        <div class="form-group">
            <div class="country-selector">
                <span>Phone.No</span>
                <span>+60</span>
            </div>
            <input type="tel" placeholder="Phone Number">
            </div>
            <div class="form-group captcha-group">
            <input type="text" placeholder="PLease enter code">
            <button class="get-captcha">Get Code</button>
        </div>
        <div class="checkbox-group">
            <input type="checkbox" id="agree">
            <label for="agree">I have read and agreed to the Account User Agreement and Privacy Policy</label>
        </div>

        <button class="register-btn">REGISTER</button>

        <div class="bottom-link">
            <a href="/">Unable to receive verification code</a>
        </div>
    </div>
</body>