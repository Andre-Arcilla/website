<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup-login-styles.css">
</head>
<body>
    <div class="container">
        <form action="actions/signup-action.php" method="post" class="signup-form" onsubmit="return validateForm()">
            <h2>SIGN UP</h2>

            <!-- post username, phone, email1 and password1 to database -->

            <div class="error-message" id="match-error"></div>

            <div>
                <div class="name-box">
                    <label for="username">
                        FIRST NAME:
                        <input type="text" name="Fname" placeholder="Rudeus" required>
                    </label>

                    <label for="username">
                        LAST NAME:
                        <input type="text" name="Lname" placeholder="Greyrat" required>
                    </label>
                </div>

                <hr>

                <label for="phone">PHONE NUMBER:</label>
                <input type="tel" name="phone" pattern="[0-9]{4}-?[0-9]{3}-?[0-9]{4}" placeholder="0123-456-7890" required>

                <hr>

                <label for="email1">EMAIL:</label>
                <input type="email" id="email1" name="email1" placeholder="email@email.com" required>
                <label for="email2">CONFIRM EMAIL:</label>
                <input type="email" id="email2" name="email2" placeholder="email@email.com" required>

                <hr>

                <div class="show-password">
                    <label for="password1">PASSWORD:</label>
                    <input type="password" id="password1" name="password1" required>
                    <input type="checkbox" id="show-password1" onclick="togglePassword('password1')">
                    <label for="show-password1">show password</label>
                </div>
        
                <div class="show-password">
                    <label for="password2">CONFIRM PASSWORD:</label>
                    <input type="password" id="password2" name="password2" required>
                    <input type="checkbox" id="show-password2" onclick="togglePassword('password2')">
                    <label for="show-password2">show password</label>
                </div>
            </div>

            <a href="login.php" class="login-signup">already have an account?</a>

            <button type="submit">Sign up</button>
        </form>
    </div>

    <script>
    function togglePassword(passwordId) {
        const passwordField = document.getElementById(passwordId);
        const showPasswordCheckbox = document.getElementById('show-' + passwordId);
        if (showPasswordCheckbox.checked) {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
    
    function validateForm() {
        var email1 = document.getElementById('email1').value;
        var email2 = document.getElementById('email2').value;
        var password1 = document.getElementById('password1').value;
        var password2 = document.getElementById('password2').value;
        var matchError = document.getElementById('match-error');
    
        matchError.innerText = ""; // Clear previous error message
    
        if (email1 !== email2) {
            matchError.innerText = "Email addresses do not match.";
            matchError.style.display = 'block';
            return false; // Prevent form submission
        }
    
        if (password1 !== password2) {
            matchError.innerText = "Passwords do not match.";
            matchError.style.display = 'block';
            return false; // Prevent form submission
        }
    
        // Additional validations or form submission logic can be added here
        return true; // Allow form submission
    }
    </script>
</body>
</html>
