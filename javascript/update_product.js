function serializeFormToXML(form, productId) {
    var xmlData = '<data>';
    var inputs = form.querySelectorAll('input, select');

    xmlData += '<product_id>' + productId + '</product_id>';
    inputs.forEach(function(input) {
        var name = input.getAttribute('name');
        var value = input.value;

        if (name && value !== '') {
            if (input.type === 'file') {
                if (input.files.length > 0) {
                    var file = input.files[0];
                    var fileName = file.name;
                    var relativePath = 'img/' + fileName;
                    xmlData += '<' + name + '>' + relativePath + '</' + name + '>';
                }
            } else {
                xmlData += '<' + name + '>' + value + '</' + name + '>';
            }
        }
    });

    xmlData += '</data>';
    return xmlData;
}

function submitForm(productId) {
    var form = document.getElementById('update-info');
    var xmlData = serializeFormToXML(form, productId);
    console.log(xmlData);
    var xhr = new XMLHttpRequest();
    var url = "products_crud.php";
    xhr.open("PUT", url, true);
    xhr.setRequestHeader("Content-Type", "application/xml");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                alert(response.replace(/<\/?[^>]+(>|$)/g, ""));
                form.reset();
                window.location.href = "product_page.php"
            } else {
                var error = xhr.responseText;
                alert(error.replace(/<\/?[^>]+(>|$)/g, ""));
            }
        }
    };
    xhr.send(xmlData);
}

document.addEventListener("DOMContentLoaded", function() {
    var urlParams = new URLSearchParams(window.location.search);
    var productId = urlParams.get('id');
    var submitButton = document.querySelector('button');

    submitButton.addEventListener('click', function(event) {
        event.preventDefault();
        submitForm(productId);
    });
});
