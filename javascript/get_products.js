function fetchProducts() {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var productsXML = xhr.responseXML;
                if (productsXML !== null) {
                    displayProducts(productsXML);
                    console.log(productsXML);
                } else {
                    console.error("XML data is null");
                }
            } else {
                console.error("Error:", xhr.status);
            }
        }
    };

    xhr.open("GET", "products_crud.php", true);
    xhr.setRequestHeader('Accept', 'application/xml');
    xhr.send();
}


function displayProducts(productsXML) {
    var tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = '';

    var products = productsXML.getElementsByTagName("product");
    var productList = [];

    for (var i = 0; i < products.length; i++) {
        var product = products[i];
        var productId = product.getElementsByTagName("product_id")[0].textContent;
        var productImg = product.getElementsByTagName("product_image")[0].textContent;
        var productName = product.getElementsByTagName("product_name")[0].textContent;
        var productDesc = product.getElementsByTagName("product_description")[0].textContent;
        var productCategory = product.getElementsByTagName("product_type")[0].textContent;
        var productBrand = product.getElementsByTagName("product_brand")[0].textContent;
        var productPrice = parseFloat(product.getElementsByTagName("product_price")[0].textContent);
        var productStocks = parseInt(product.getElementsByTagName("product_stocks")[0].textContent);

        productList.push({
            id: productId,
            img: productImg,
            name: productName,
            desc: productDesc,
            category: productCategory,
            brand: productBrand,
            price: productPrice,
            stocks: productStocks
        });
    }

    var sort = document.getElementById('sort-products').value;
    if (sort !== "Default") {
        if (sort === "Ascending") {
            productList.sort(function(a, b) {
                return a.price - b.price;
            });
        } else if (sort === "Descending") {
            productList.sort(function(a, b) {
                return b.price - a.price;
            });
        }
    }
    
    var filterByType = document.getElementById('filter-type').value;
    if(filterByType != ""){
        productList = productList.filter(function(product) {
            return product.category === filterByType;
        });
    }

    var filterByBrand = document.getElementById('filter-brand').value;
    if(filterByBrand != ""){
        productList = productList.filter(function(product) {
            return product.brand === filterByBrand;
        });
    }

    productList.forEach(function(product) {
        var searchInput = document.getElementById('search-input').value.toLowerCase();
        if (product.name.toLowerCase().includes(searchInput)) {
            var row = tableBody.insertRow(-1);

            var selectCell = row.insertCell(0);
            var idCell = row.insertCell(1);
            var imgCell = row.insertCell(2);
            var nameCell = row.insertCell(3);
            var descCell = row.insertCell(4);
            var categoryCell = row.insertCell(5);
            var brandCell = row.insertCell(6);
            var priceCell = row.insertCell(7);
            var qtyCell = row.insertCell(8);
            // var totalCell = row.insertCell(9);
            var actionCell = row.insertCell(9);

            if (product.stocks === 0) {
                row.style.opacity = "50%";
                selectCell.innerHTML = '<input type="checkbox" name="select-box" disabled>';
            } else {
                selectCell.innerHTML = '<input type="checkbox" name="select-box">';
            }

            idCell.innerHTML = product.id;
            imgCell.innerHTML = '<img src="' + product.img + '" width="170" height="250">';
            nameCell.innerHTML = product.name;
            descCell.innerHTML = product.desc;
            categoryCell.innerHTML = product.category;
            brandCell.innerHTML = product.brand;
            priceCell.innerHTML = product.price;
            qtyCell.innerHTML = product.stocks;
            // totalCell.innerHTML = (product.price * product.stocks);
            actionCell.innerHTML = '<a href="edit_product_page.php?id=' + product.id + '"><i class="fa-solid fa-pen-to-square" style="color: #AD53A6"></i></a>';
        }
    });
}


document.getElementById('search-input').addEventListener('input', fetchProducts);
document.getElementById('sort-products').addEventListener('change', fetchProducts);
document.getElementById('filter-type').addEventListener('change', fetchProducts);
document.getElementById('filter-brand').addEventListener('change', fetchProducts);

fetchProducts();

