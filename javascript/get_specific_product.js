document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    if (!productId) {
        console.error('Product ID not found in URL');
        return;
    }

    fetchProductDetails(productId);

    function fetchProductDetails(productId) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "get_specific_product.php?id=" + productId, true);
        xhr.setRequestHeader("Accept", "application/xml");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const productXML = xhr.responseXML;
                    if (productXML !== null) {
                        prefillForm(productXML);
                    } else {
                        console.error("XML data is null");
                    }
                } else {
                    console.error("Error fetching product details:", xhr.status);
                }
            }
        };

        xhr.send();
    }

    function prefillForm(productXML) {
        const product = productXML.getElementsByTagName("product")[0];
        if (!product) {
            console.error("Product details not found");
            return;
        }
        const productName = product.getElementsByTagName("product_name")[0].textContent;
        const productType = product.getElementsByTagName("product_type")[0].textContent;
        const productBrand = product.getElementsByTagName("product_brand")[0].textContent;
        const productDescription = product.getElementsByTagName("product_description")[0].textContent;
        const productPrice = product.getElementsByTagName("product_price")[0].textContent;
        const productStocks = product.getElementsByTagName("product_stocks")[0].textContent;
        const productImage = product.getElementsByTagName("product_image")[0].textContent;

        document.getElementById("product-name").value = productName;
        document.getElementById("product-type").value = productType;
        document.getElementById("product-brand").value = productBrand;
        document.getElementById("product-image-preview").src = productImage;
        document.getElementById("product-description").value = productDescription;
        document.getElementById("product-price").value = productPrice;
        document.getElementById("product-quantity").value = productStocks;
        updateBrands(productType, productBrand);
    }

    function updateBrands(selectedType, selectedBrand) {
        var brands = {
            "Dresses": ["Zara", "Saint Laurent", "Asos", "Reformation", "Free People", "Shein"],
            "Tops": ["Forever 21", "Gap", "Uniqlo", "Mango", "Express", "Geographic"],
            "Sweater": ["J.Crew", "Banana Republic", "Patagonia", "The North Face", "L.L Bean", "Vince"],
            "Bottoms": ["Levi's", "Topshop", "Madewell", "American Eagle Outfitters", "Penshoppe", "Pull and Bear"],
            "Swim Wears": ["Speedo", "Billabong", "Roxy", "Seafolly", "O'Neill", "Dolce and Gabbana"],
            "Sleep Wears": ["Bench", "Victoria Secret", "Eberjey", "Lingerie Diva", "PajamaGram", "BedHead Pajamas"],
            "Jumpsuits": ["Urban Outfitters", "Hugo Boss", "Boohoo", "Revolve", "Everlane", "NastyGal"],
            "Gowns": ["Marchesa", "Vera Wang", "Elie Saab", "Pronovias", "Monique Lhuillier", "Jenny Packham"],
            "Tuxedos": ["Arc'teryx", "Ralph Lauren", "Armani", "Tom Ford", "Brioni", "Brooks Brothers"]
        };

        var productTypeSelect = document.getElementById("product-type");
        var brandSelect = document.getElementById("product-brand");

        var brandsForProduct = brands[selectedType] || [];
        brandSelect.innerHTML = "";

        var defaultOption = document.createElement("option");
        defaultOption.text = "Select Product Brand";
        defaultOption.value = "";
        brandSelect.appendChild(defaultOption);

        brandsForProduct.forEach(function(brand) {
            var option = document.createElement("option");
            option.text = brand;
            option.value = brand;
            brandSelect.appendChild(option);
        });

        brandSelect.value = selectedBrand;
    }

    document.getElementById("product-type").addEventListener("change", function() {
        updateBrands(this.value, "");
    });
});
