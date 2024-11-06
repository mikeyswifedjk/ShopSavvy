function deleteSelectedProducts(productIds) {
    var xhr = new XMLHttpRequest();
    xhr.open('DELETE', 'products_crud.php', true);
    xhr.setRequestHeader('Content-Type', 'application/xml');

    xhr.onload = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = xhr.responseXML;
                var message = response.getElementsByTagName('message')[0].textContent;
                alert(message);
                fetchProducts();
            } else {
                console.error('Error deleting products: ' + xhr.status);
                var error = xhr.responseXML.getElementsByTagName('error')[0].textContent;
                alert(error);
            }
        }
    };

    xhr.onerror = function () {
        console.error('Request failed');
    };

    var xmlData = '<product_ids>';
    productIds.forEach(function(productId) {
        xmlData += '<product_id>' + productId + '</product_id>';
    });
    xmlData += '</product_ids>';
    xhr.send(xmlData);
}

function getSelectedProductIds() {
    var selectedIds = [];
    var checkboxes = document.querySelectorAll('input[name="select-box"]:checked');
    checkboxes.forEach(function(checkbox) {
        var row = checkbox.parentNode.parentNode; 
        var productId = row.cells[1].textContent; 
        selectedIds.push(productId);
    });
    return selectedIds;
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.delete-text').addEventListener('click', function () {
        var selectedIds = getSelectedProductIds();
        if (selectedIds.length > 0) {
            if (confirm("Are you sure you want to delete these products?")) {
                deleteSelectedProducts(selectedIds);
            }
        } else {
            alert("Please select at least one product to delete.");
        }
    });
});
