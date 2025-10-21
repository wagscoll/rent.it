<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/sidebar.css">
    <link rel="stylesheet" href="../styles/card.css">

</head>

<body>
    <?php include '../templates/header.php'; ?>

    <div class="card-container">
        <div class="card" style="transform: translateY(0); padding: 40px; max-width: 600px; margin: auto; max-height: 75vh; margin-top: 80px;">

            <!-- !! Convert these styles to a class !! -->
            <p style="text-align: left; font-weight: bold; font-size: 1.25em; color:black;">Already have an account?</p>
            <p style="text-align: left; text-indent: 10%; margin-bottom: 15px; margin-top: 10px; color:black; font-size: 1.1em;">Login here</p>
            <form>
                <div class="form-section-title">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <br><br>
                    <input type="submit" value="Login" style="width: 60%; padding: 10px; font-size: 16px; cursor: pointer; margin-top: 15px;
                    margin-bottom: 20px;">
                </div>
            </form>

            <p style="text-align: left; font-weight: bold; font-size: 1.25em; color:black; margin-top: 40px; color:white;">New user?</p>
            <p style="text-align: left; text-indent: 10%; margin-bottom: 15px; margin-top: 10px; color:white; font-size: 1.1em;">Create an account!</p>

            <div class="form-section-title" style="text-align: center; padding-top: 10px; padding-bottom: 10px;">
                <button onclick="window.location.href='register.php'">Register</button>
            </div>
        </div>

    </div>

    <?php include '../templates/footer.php'; ?>
</body>

</html>