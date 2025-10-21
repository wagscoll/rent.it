<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/sidebar.css">
    <link rel="stylesheet" href="../styles/card.css">
</head>

<body>
    <?php include '../templates/header.php'; ?>

    <main class="container">

        <aside class="sidebar">
            <h2 style="text-align: center; text-decoration: underline ;">Filter Listings</h2>
            <form>

                <!-- SIDEBAR - SEARCH -->
                <div class="sidebar-title" style="border-top: none;">
                    <label for="search">Search:</label>
                </div>

                <div class="sidebar-entry">
                    <input type="text" id="search" placeholder="Search for items ...">
                </div>


                <!-- SIDEBAR - CATEGORY -->
                <div class="sidebar-title">
                    <label for="category">Category:</label>
                </div>
                <div class="sidebar-entry">
                    <select id="category">
                        <option>All</option>
                        <option>Sports</option>
                        <option>Tools</option>
                        <option>Outdoor</option>
                    </select>
                </div>


                <!-- found at and adapted from: 
                 https://www.geeksforgeeks.org/javascript/price-range-slider-with-min-max-input-using-html-css-and-javascript/ -->
                <!-- SIDEBAR - PRICE RANGE -->
                <div class="sidebar-title">
                    <label for="price-range">Price Range:</label>
                </div>

                <div class="sidebar-item" style="text-indent: none; text-align: left; margin-bottom: -20px;">
                    <div class="price-input">
                        <div class="price-field">
                            <span class="flex-label" style="margin-left: flex-start;">Min</span>
                            <input type="number" class="min-input" value="0" min="0" max="150">
                            <span class="flex-label" style="margin-right: flex-end;">Max</span>
                            <input type="number" class="max-input" value="150" min="0" max="150">
                        </div>
                    </div>
                </div>

                <div class="sidebar-item" style="margin-top: -10px; padding: 0 25px 25px 10px;
                 border-bottom: 1px dashed #ffffffff;">
                    <div class="slider">
                        <div class="progress"></div>
                    </div>

                    <div class="range-input">
                        <input type="range" class="min-range" min="0" max="150" value="0" step="1">
                        <input type="range" class="max-range" min="0" max="150" value="150" step="1">
                    </div>
                </div>
                </div>

                <!-- SIDEBAR - RATING -->
                <div class="sidebar-title">
                    <label for="rating">Rating:</label>
                </div>
                <div class="sidebar-entry" style="border-bottom: 1px dashed #ffffffff;">
                    <select id="rating">
                        <option>Any</option>
                        <option>1 Star & Up</option>
                        <option>2 Stars & Up</option>
                        <option>3 Stars & Up</option>
                        <option>4 Stars & Up</option>
                    </select>
                </div>

                <div class="sidebar-item" style="text-align: center; padding-top: 15px; font-size: 1.3em;">
                    <button type="submit" style="font-size: .75em;">Apply Filters</button>
                </div>

                </div>
            </form>

        </aside>
        <!--  End Sidebar / Filter -->


        <!-- Main content - Card Selection -->
        <section class="main-content">

            <div class="title-bar" style="text-indent: 40px; font-size: 1.1em; color:
             #55ffd4d8; display: flex; flex-direction: row; align-items: center; 
             gap: 800px; margin-bottom: 20px;">
                <h1 style="text-align: flex-start;">Featured Listings</h1>

                <div style="text-align: center; font-size: 1.9em; color: black;">
                    <button onclick="window.location.href='../pages/add-post.php'">Add New Listing</button>
                </div>


            </div>

            <div class="outer-grid-border"

                style="padding: 15px; border: 4px solid #55ffd40b; border-radius: 8px; 
            max-width: 1200px; margin: auto; display: inline-block; 
             margin-bottom: 40px;">


                <div class="card-container">

                    <div class="card" data-category="Sports" data-price="15" data-rating="4.5">
                        <div class="card-header">
                            <h3>Mountain Bike</h3>
                            <span class="rating">⭐ 4.5</span>
                        </div>
                        <img src="../images/mountain-bike.webp" alt="Mountain Bike">
                        <p>$15/day</p>
                    </div>

                    <div class="card" data-category="Outdoor" data-price="55" data-rating="4.2">
                        <div class="card-header">
                            <h3>Grill</h3>
                            <span class="rating">⭐ 4.2</span>
                        </div>
                        <img src="../images/grill.png" alt="Grill">
                        <p>$55/day</p>
                    </div>

                    <div class="card" data-category="Outdoor" data-price="20" data-rating="4.8">
                        <div class="card-header">
                            <h3>Camping Tent</h3>
                            <span class="rating">⭐ 4.8</span>
                        </div>
                        <img src="../images/camping-tent.png" alt="Camping Tent">
                        <p>$20/day</p>
                    </div>

                    <div class="card" data-category="Tools" data-price="18" data-rating="4.6">
                        <div class="card-header">
                            <h3>Misc Tools</h3>
                            <span class="rating">⭐ 4.6</span>
                        </div>
                        <img src="../images/tools.png" alt="Misc Tools">
                        <p>$18/day</p>
                    </div>

                    <div class="card" data-category="Tools" data-price="12" data-rating="4.3">
                        <div class="card-header">
                            <h3>Hammer</h3>
                            <span class="rating">⭐ 4.3</span>
                        </div>
                        <img src="../images/hammer.webp" alt="Hammer">
                        <p>$12/day</p>
                    </div>

                    <div class="card" data-category="Outdoor" data-price="22" data-rating="4.7">
                        <div class="card-header">
                            <h3>Fishing Rod</h3>
                            <span class="rating">⭐ 4.7</span>
                        </div>
                        <img src="../images/fishing-rod.webp" alt="Fishing Rod">
                        <p>$22/day</p>
                    </div>

                    <div class="card" data-category="Sports" data-price="25" data-rating="2.1">
                        <div class="card-header">
                            <h3>Tennis Racket</h3>
                            <span class="rating">⭐ 2.1</span>
                        </div>
                        <img src="../images/tennis-racket.webp" alt="Tennis Racket">
                        <p>$25/day</p>
                    </div>

                    <div class="card"
                        data-category="Tools"
                        data-price="15"
                        data-rating="4.4">
                        <div class="card-header">
                            <h3>Screwdriver Set</h3>
                            <span class="rating">⭐ 4.4</span>
                        </div>
                        <img src="../images/screwdriver-set.webp" alt="Screwdriver Set">
                        <p>$15/day</p>
                    </div>

                    <div class="card"
                        data-category="Outdoor"
                        data-price="30"
                        data-rating="4.8">
                        <div class="card-header">
                            <h3>Kayak Paddle</h3>
                            <span class="rating">⭐ 4.8</span>
                        </div>
                        <img src="../images/kayak-paddle.webp" alt="Kayak Paddle">
                        <p>$30/day</p>
                    </div>

                    <div class="card"
                        data-category="Sports"
                        data-price="20"
                        data-rating="4.5">
                        <div class="card-header">
                            <h3>Basketball</h3>
                            <span class="rating">⭐ 4.5</span>
                        </div>
                        <img src="../images/basketball.png" alt="Basketball">
                        <p>$20/day</p>
                    </div>

                    <div class="card" data-category="Tools" data-price="18" data-rating="4.6">
                        <div class="card-header">
                            <h3>Electric Drill</h3>
                            <span class="rating">⭐ 4.6</span>
                        </div>
                        <img src="../images/electric-drill.webp" alt="Electric Drill">
                        <p>$18/day</p>
                    </div>

                    <div class="card" data-category="Outdoor" data-price="28" data-rating="4.7">
                        <div class="card-header">
                            <h3>Sleeping Bag</h3>
                            <span class="rating">⭐ 4.7</span>
                        </div>
                        <img src="../images/sleeping-bag.webp" alt="Sleeping Bag">
                        <p>$28/day</p>
                    </div>

                    <div class="card" data-category="Sports" data-price="22" data-rating="4.8">
                        <div class="card-header">
                            <h3>Yoga Mat</h3>
                            <span class="rating">⭐ 4.8</span>
                        </div>
                        <img src="../images/yoga-mat.webp" alt="Yoga Mat">
                        <p>$22/day</p>
                    </div>

                </div>
            </div>
        </section>
    </main>


    <?php include '../templates/footer.php'; ?>

    <script src="../scripts/filter.js"></script>
</body>

</html>