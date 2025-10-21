document.addEventListener("DOMContentLoaded", () => {
  const rangeInputs = document.querySelectorAll(".range-input input");
  const priceInputs = document.querySelectorAll(".price-input input");
  const progress = document.querySelector(".slider .progress");
  const priceGap = 5;
  const form = document.querySelector(".sidebar form");
  const cards = document.querySelectorAll(".card");

  /*
  Dual Range Slider Logic - This was found and adapted from:
  found at: https://www.geeksforgeeks.org/javascript/price-range-slider-with-min-max-input-using-html-css-and-javascript/

  */
  function updateProgress(min, max) {
    const maxRange = parseInt(rangeInputs[0].max);
    const minPercent = (min / maxRange) * 100;
    const maxPercent = (max / maxRange) * 100;
    progress.style.left = minPercent + "%";
    progress.style.width = maxPercent - minPercent + "%";
  }

  rangeInputs.forEach(input => {
    input.addEventListener('mousedown', () => {
      rangeInputs.forEach(i => i.style.zIndex = 2);
      input.style.zIndex = 3;
    });
    input.addEventListener('touchstart', () => {
      rangeInputs.forEach(i => i.style.zIndex = 2);
      input.style.zIndex = 3;
    });
  });

  priceInputs.forEach(input => {
    input.addEventListener("input", () => {
      let minPrice = parseInt(priceInputs[0].value);
      let maxPrice = parseInt(priceInputs[1].value);

      if (maxPrice - minPrice >= priceGap) {
        rangeInputs[0].value = minPrice;
        rangeInputs[1].value = maxPrice;
        updateProgress(minPrice, maxPrice);
      }
    });
  });

  rangeInputs.forEach(input => {
    input.addEventListener("input", () => {
      let minVal = parseInt(rangeInputs[0].value);
      let maxVal = parseInt(rangeInputs[1].value);

      if (maxVal - minVal < priceGap) {
        if (input.classList.contains("min-range")) {
          rangeInputs[0].value = maxVal - priceGap;
        } else {
          rangeInputs[1].value = minVal + priceGap;
        }
      }

      priceInputs[0].value = rangeInputs[0].value;
      priceInputs[1].value = rangeInputs[1].value;
      updateProgress(rangeInputs[0].value, rangeInputs[1].value);
    });
  });

  // Initialize progress
  updateProgress(parseInt(rangeInputs[0].value), parseInt(rangeInputs[1].value));

  // --------- Filter Logic ----------
  form.addEventListener("submit", e => {
    e.preventDefault();

    const searchTerm = document.getElementById("search").value.toLowerCase();
    const category = document.getElementById("category").value;
    const rating = document.getElementById("rating").value;
    const minPrice = parseFloat(priceInputs[0].value);
    const maxPrice = parseFloat(priceInputs[1].value);

    cards.forEach(card => {
      const name = card.querySelector("h3").textContent.toLowerCase();
      const cardCategory = card.dataset.category;
      const cardPrice = parseFloat(card.dataset.price);
      const cardRating = parseFloat(card.dataset.rating);

      let isVisible = true;

      if (searchTerm && !name.includes(searchTerm)) isVisible = false;
      if (category !== "All" && cardCategory !== category) isVisible = false;
      if (cardPrice < minPrice || cardPrice > maxPrice) isVisible = false;

      const minStars = parseInt(rating);
      if (!isNaN(minStars) && cardRating < minStars) isVisible = false;

      card.style.display = isVisible ? "block" : "none";
    });
  });
});
