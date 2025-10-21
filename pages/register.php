<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/sidebar.css">
    <link rel="stylesheet" href="../styles/card.css">
</head>


<body>
    <?php include '../templates/header.php'; ?>



    <div class="filler" style="color: green; text-align: center; margin-top: 100px;">
        <h1>Welcome to the Registration Page</h1>
    </div>

    <div class="color-filler" style="color: white; border: 1px solid white; border-radius: 8px; padding: 20px; max-width: 400px; margin: auto; margin-top: 20px;">
    <form>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Login">
    </form>
    </div>


    <?php include '../templates/footer.php'; ?>
</body>

</html>