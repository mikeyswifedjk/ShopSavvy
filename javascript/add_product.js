// Function to serialize form data to XML
function serializeFormToXML(form) {
    var xmlData = '<data>';
    var inputs = form.querySelectorAll('input, select');

    inputs.forEach(function(input) {
        var name = input.getAttribute('name');
        var value = input.value;

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
    });

    xmlData += '</data>';
    return xmlData;
}

function submitForm() {
    var form = document.getElementById('product-form');
    var xmlData = serializeFormToXML(form);
    var xhr = new XMLHttpRequest();
    var url = "products_crud.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/xml");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                alert(response.replace(/<\/?[^>]+(>|$)/g, ""));
                form.reset();
                fetchProducts();
            } else {
                var error = xhr.responseText;
                alert(error.replace(/<\/?[^>]+(>|$)/g, ""));
            }
        }
    };
    xhr.send(xmlData);
}

var submitButton = document.querySelector('button');
submitButton.addEventListener('click', function(event) {
    event.preventDefault();
    submitForm();
});
