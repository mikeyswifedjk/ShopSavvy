var brands = {
    "Dresses": ["Zara", "Saint Laurent", "Asos", "Reformation", "Free People", "Shein"],
    "Tops": ["Forever 21", "Gap", "Uniqlo", "Mango", "Express", "Geographic"],
    "Bottoms": ["Levi's", "Topshop", "Madewell", "American Eagle Outfitters", "Penshoppe", "Pull and Bear"],
    "Swim Wears": ["Speedo", "Billabong", "Roxy", "Seafolly", "O'Niell", "Dolce and Gabbana"],
    "Sleep Wears": ["Bench", "Victoria Secret", "Eberjey", "Lingerie Diva", "PajamaGram", "BedHead Pajamas"],
    "Jumpsuits": ["Urban Outfitters", "Hugo Boss", "Boohoo", "Revolve", "Everlane", "NastyGal"],
    "Gowns": ["Machesa", "Vera Wang", "Elie Saab", "Pronovias", "Monique Lhuillier", "Jenny Packham"],
    "Tuxedos": ["Arc'teryx", "Ralph Lauren", "Armani", "Tom Ford", "Brioni", "Brooks Brothers"]
};


var productTypeSelect = document.getElementById("product-type");
var brandSelect = document.getElementById("product-brand");

var filterProductTypeSelect = document.getElementById("filter-type");
var filterBrandSelect = document.getElementById("filter-brand");

function updateBrands() {
    var productType = productTypeSelect.value;
    var brandsForProduct = brands[productType] || [];

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

}

productTypeSelect.addEventListener("change", updateBrands);

updateBrands();
