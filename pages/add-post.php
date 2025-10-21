<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/sidebar.css">
    <link rel="stylesheet" href="../styles/card.css">
</head>

<body>

    <?php include '../templates/header.php'; ?>


    <main class="card-container add-post-container" style=" align-items: center; height: 80vh; margin-top: 40px;">
        <div class="card" style="transform: translateY(0);">
            <h2 style="text-align: center;">Add New Post</h2>

            <form id="post-form" method="POST" enctype="multipart/form-data">

                <div class="add-post-form-group">
                    <p> Title:</p>
                    <input type="text" id="post-title" name="post_title" placeholder="Object Name" required>
                </div>

                <div class="add-post-form-group">
                    <p> Description:</p>
                    <textarea required id="post-description" name="post_description" placeholder="Describe your item..."></textarea>
                </div>

                <div class="add-post-form-group">
                    <p> Category:</p>
                    <select id="post-category" name="post_category" style="width: 56%;">
                        <option value="">Select Category</option>
                        <option value="Sports">Sports</option>
                        <option value="Tools">Tools</option>
                        <option value="Outdoor">Outdoor</option>
                    </select>
                </div>

                <div class="add-post-form-group">
                    <p>Rate:</p>
                    <input type="number" id="post-price" name="post_price" placeholder="Price per day" required>
                </div>

                <input type="file" id="post-image" name="post_image" accept="image/*">
                <button type="submit">Submit Post</button>
            </form>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = htmlspecialchars($_POST['post_title']);
            $desc = htmlspecialchars($_POST['post_description']);
            $cat  = htmlspecialchars($_POST['post_category']);
            $price = htmlspecialchars($_POST['post_price']);

            $imagePath = null;
            if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === 0) {
                $uploadDir = '../uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $imagePath = $uploadDir . basename($_FILES['post_image']['name']);
                move_uploaded_file($_FILES['post_image']['tmp_name'], $imagePath);
            }

            echo "
            <div class='listing-added-confirmation'>
                <div class='card'>
                    <h3>Listing Added Successfully!</h3> 
                </div>
            </div>";
        }
        ?>

    </main>

    <?php include '../templates/footer.php'; ?>
    <script src="../scripts/filter.js"></script>


    <!-- Found at: https://www.w3schools.com/howto/howto_js_timed_alert.asp -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const notification = document.querySelector(".listing-added-confirmation");
            if (notification) {
                setTimeout(() => {
                    notification.remove();
                }, 3000); //<- duration (3s)
            }
        });
    </script>
</body>

</html>