document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const sortSelect = document.getElementById('sort-products');
    const filterTypeSelect = document.getElementById('filter-type');
    const filterBrandSelect = document.getElementById('filter-brand');
    const products = document.querySelectorAll('.allProduct');

    function filterProducts() {
        console.log("Filtering products..."); 

        const searchFilter = searchInput.value.toLowerCase();
        const typeFilter = filterTypeSelect.value;
        const brandFilter = filterBrandSelect.value;
        const sortOption = sortSelect.value;

        let filteredProducts = Array.from(products);

        // Filter by search input
        filteredProducts = filteredProducts.filter(product => {
            const productName = product.querySelector('.prod-title span').textContent.toLowerCase();
            return productName.includes(searchFilter);
        });

        // Filter by type
        if (typeFilter) {
            filteredProducts = filteredProducts.filter(product => product.getAttribute('data-type') === typeFilter);
        }

        // Filter by brand
        if (brandFilter) {
            filteredProducts = filteredProducts.filter(product => product.getAttribute('data-brand') === brandFilter);
        }

        // Sort products
        if (sortOption === 'Ascending') {
            filteredProducts.sort((a, b) => parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price')));
        } else if (sortOption === 'Descending') {
            filteredProducts.sort((a, b) => parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price')));
        }

        // Display filtered products
        const productList = document.querySelector('.product-list');
        productList.innerHTML = '';

        filteredProducts.forEach(product => {
            productList.appendChild(product);
            // Add event listener to each product
            product.addEventListener('click', function() {
                // Extract product ID from data attribute and redirect to product details page
                const productId = this.getAttribute('data-id');
                window.location.href = 'product-details.php?id=' + productId;
            });
        });
    }

    searchInput.addEventListener('input', filterProducts);
    sortSelect.addEventListener('change', filterProducts);
    filterTypeSelect.addEventListener('change', filterProducts);
    filterBrandSelect.addEventListener('change', filterProducts);

    // Initial filtering when the page loads
    filterProducts();
});
