<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="signup-login-styles.css">
</head>
<body>
    <div class="container">
        <form action="actions/login-action.php" method="post" class="signup-form" id="login-form">
            <h2>LOGIN</h2>

            <!-- error message, hidden unless triggered -->
            <div class="error-message" id="error-message">email or password incorrect</div>

            <label for="email">EMAIL:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">PASSWORD:</label>
            <input type="password" id="password" name="password" required>
            <div class="show-password">
                <input type="checkbox" id="show-password">
                <label for="show-password">show password</label>
            </div>

            <a href="signup.php" class="login-signup">dont have an account yet?</a>

            <button type="submit"><img class="easter-egg" src="images\arisbm.gif">sign up<img class="easter-egg" src="images\arisbm.gif"></button>
        </form>
    </div>
    <script>
        document.getElementById('show-password').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>
</body>
</html>
