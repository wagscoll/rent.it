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


    <main class="card-container add-post-container" style="justify-content: center; align-items: center; height: 80vh;">
        <div class="card" style="transform: translateY(0);">
            <h2 style="text-align: center;">Add New Post</h2>
            <form id="post-form">

                <div class="add-post-form-group">
                    <p> Title:</p>
                    <input type="text" id="post-title" placeholder="Object Name" required>
                </div>

                <div class="add-post-form-group">
                    <p> Description:</p>
                    <textarea  required id="post-description" placeholder="Describe your item..."></textarea>
                </div>

                <div class="add-post-form-group">
                    <p> Category:</p>
                        <select id="post-category" style="width: 56%;">
                            <option value="" >Select Category</option>
                            <option value="Sports">Sports</option>
                            <option value="Tools">Tools</option>
                            <option value="Outdoor">Outdoor</option>
                        </select>
                </div>

                <div class="add-post-form-group">
                    <p>Rate:</p>
                    <input type="number" id="post-price" placeholder="Price per day" required>
                </div>

                <input type="file" id="post-image" accept="image/*">
                <button type="submit">Submit Post</button>
            </form>
        </div>
    </main>

    <?php include '../templates/footer.php'; ?>
    <script src="../scripts/filter.js"></script>
</body>

</html>