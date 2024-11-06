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

var filterProductTypeSelect = document.getElementById("filter-type");
var filterBrandSelect = document.getElementById("filter-brand");


function updateFilterBrands(){
    
    var filterProductType = filterProductTypeSelect.value;
    var filterBrandsForProduct = brands[filterProductType] || [];

    filterBrandSelect.innerHTML = "";
    
    // Create a default option
    var defaultOption = document.createElement("option");
    defaultOption.text = " Product Brand";
    defaultOption.value = "";
    filterBrandSelect.appendChild(defaultOption);

    filterBrandsForProduct.forEach(function(brand) {
        var option = document.createElement("option");
        option.text = brand;
        option.value = brand;
        filterBrandSelect.appendChild(option);
    });
}

filterProductTypeSelect.addEventListener("change", updateFilterBrands);

updateFilterBrands();
