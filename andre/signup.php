<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <form id="signup-form" class="signup-form" onsubmit="return validateForm()">
            <h2>SIGN UP</h2>

            <!-- post username, phone, email1 and password1 to database -->

            <div class="error-message" id="match-error"></div>

            <label for="username">USERNAME:</label>
            <input type="text" id="username" name="username" required>
    
            <label for="phone">PHONE NUMBER:</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{4}-?[0-9]{3}-?[0-9]{4}" placeholder="e.g., 0123-456-7890" required>
    
            <label for="email1">EMAIL:</label>
            <input type="email" id="email1" name="email1" required>
            <label for="email2">CONFIRM EMAIL:</label>
            <input type="email" id="email2" name="email2" required>
    
            <label for="password1">PASSWORD:</label>
            <input type="password" id="password1" name="password1" required>
            <div class="show-password">
                <input type="checkbox" id="show-password1" onclick="togglePassword('password1')">
                <label for="show-password1">show password</label>
            </div>
    
            <label for="password2">CONFIRM PASSWORD:</label>
            <input type="password" id="password2" name="password2" required>
            <div class="show-password">
                <input type="checkbox" id="show-password2" onclick="togglePassword('password2')">
                <label for="show-password2">show password</label>
            </div>
    
            <button type="submit">sign up</button>
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
            document.getElementById('match-error').style.display = 'block';
            return false; // Prevent form submission
        }
    
        if (password1 !== password2) {
            matchError.innerText = "Passwords do not match.";
            document.getElementById('match-error').style.display = 'block';
            return false; // Prevent form submission
        }
    
        // Additional validations or form submission logic can be added here
        return true; // Allow form submission
    }

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

        if (email1 !== email2) {
            document.getElementById('match-error').innerText = "Email addresses do not match.";
            document.getElementById('match-error').style.display = 'block';
            return false; // Prevent form submission
        } else {
            document.getElementById('match-error').innerText = ""; // Clear error message
            document.getElementById('match-error').style.display = 'none';
        }

        if (password1 !== password2) {
            document.getElementById('match-error').innerText = "Passwords do not match.";
            document.getElementById('match-error').style.display = 'block';
            return false; // Prevent form submission
        } else {
            document.getElementById('match-error').innerText = ""; // Clear error message
            document.getElementById('match-error').style.display = 'none';
        }

        return true; // Allow form submission
    }

    function adjustScroll() {
        window.scrollTo(0, document.getElementById('signup-form').offsetTop - 10);
    }

    document.querySelectorAll('.signup-form input').forEach(input => {
        input.addEventListener('focus', adjustScroll);
    });
    </script>
</body>
</html>
