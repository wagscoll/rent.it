document.addEventListener("DOMContentLoaded", () => {
    const search = document.getElementById("searchInput");
    const categoryFilter = document.getElementById("categoryFilter");
    const priceMin = document.getElementById("priceMin");
    const priceMax = document.getElementById("priceMax");
    const cards = document.querySelectorAll(".card");

    function applyFilters() {
        const searchVal = search.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const minPrice = priceMin.value ? Number(priceMin.value) : 0;
        const maxPrice = priceMax.value ? Number(priceMax.value) : Infinity;

        cards.forEach(card => {
            const title = card.dataset.title;
            const desc = card.dataset.description;
            const category = card.dataset.category;
            const price = Number(card.dataset.price);

            let matches = true;

            // Search filter
            if (searchVal && !title.includes(searchVal) && !desc.includes(searchVal)) {
                matches = false;
            }

            // Category filter
            if (selectedCategory && selectedCategory !== category) {
                matches = false;
            }

            // Price filter
            if (price < minPrice || price > maxPrice) {
                matches = false;
            }

            card.style.display = matches ? "block" : "none";
        });
    }

    search.addEventListener("input", applyFilters);
    categoryFilter.addEventListener("change", applyFilters);
    priceMin.addEventListener("change", applyFilters);
    priceMax.addEventListener("change", applyFilters);
    
    applyFilters();
});
