<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="img/logo.png" />
    <link rel="stylesheet" type="text/css" href="css/product_page.css">
    <title>SHOP SAVVY</title>
</head>
<body>
    <main>
        <?php include 'admin-navigation.php'; ?>
        <div class="dashboard-content">
            <div class="dashboard-view">
                <h1> PRODUCT MANAGEMENT </h1>
                <div class="contents">
                    <div class="add-product-container">
                        <form id="product-form">
                            <div class="form-column">
                                <div class="input">
                                    <label for="product-name">Product Name:</label>
                                    <input type="text" id="product-name" name="product_name" required/>
                                </div>
                                <div class="image">
                                    <label for="product-image">Product Image:</label>
                                    <div class="file-upload-wrapper">
                                        <label for="product-image" class="custom-file-upload">
                                            <i class="fa fa-cloud-upload"></i> Choose File
                                        </label>
                                        <input type="file" id="product-image" name="product_image" accept=".jpg, .jpeg, .png, .webp, .avif" required/>
                                    </div>
                                </div>
                                <div class="select">
                                    <label for="product-type">Product Type:</label>
                                    <select name="product_type" id="product-type">
                                        <option value=""> Select Product Type </option>
                                        <option value="Dresses"> Dresses </option>
                                        <option value="Tops"> Tops </option>
                                        <option value="Bottoms"> Bottoms </option>
                                        <option value="Swim Wears"> Swim Wears </option>
                                        <option value="Sleep Wears"> Sleep Wears</option>
                                        <option value="Jumpsuits"> Sweater </option>
                                        <option value="Gowns"> Gowns </option>
                                        <option value="Tuxedos"> Tuxedos </option>
                                    </select>
                                </div>
                                <button> 
                                    ADD PRODUCT
                                </button>
                            </div>
                            <div class="form-column">
                                <div class="select">
                                    <label for="product-brand">Product Brand:</label>
                                    <select name="product_brand" id="product-brand"></select>
                                </div>
                                <div class="input">
                                    <label for="product-price">Price:</label>
                                    <input type="number" id="product-price" name="product_price" min="1" required/>
                                </div>
                                <div class="input">
                                    <label for="product-quantity">Quantity:</label>
                                    <input type="text" id="product-quantity" name="product_stocks" required/>
                                </div>
                                <div class="input">
                                    <label for="product-description">Description/Specifications:</label>
                                    <input type="text" id="product-description" name="product_description" required/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-accounts">
                        <div class="search-and-delete">
                            <div class="delete-text">
                                <i class="fa-solid fa-trash" style="color: #AD53A6"></i>
                            </div>
                            <label for="sort-products">Sort by Price:</label>
                            <div class="search-account">
                                <div class="filter">     
                                    <select name="sort-products" id="sort-products">
                                        <option value="Default">Default</option>
                                        <option value="Ascending">Ascending</option>
                                        <option value="Descending">Descending</option>
                                    </select>
                                    <select name="filter-type" id="filter-type">
                                        <option value="">Product Type</option>
                                        <option value="Dresses"> Dresses </option>
                                        <option value="Tops"> Tops </option>
                                        <option value="Bottoms"> Bottoms </option>
                                        <option value="Swim Wears"> Swim Wears </option>
                                        <option value="Sleep Wears"> Sleep Wears</option>
                                        <option value="Jumpsuits"> Sweater </option>
                                        <option value="Gowns"> Gowns </option>
                                        <option value="Tuxedos"> Tuxedos </option>
                                    </select>
                                    <select name="filter-brands" id="filter-brand"></select>
                                </div>
                            </div>
                            <div class="search-container">
                                <input type="search" class="search-item" id="search-input" placeholder="Search..." />
                                <i class="fa-solid fa-magnifying-glass" style="color: #AD53A6"></i>
                            </div>
                        </div>
                        
                        <div class="table">
                            <table id="product-table">
                                <tr>
                                    <th>Select</th>
                                    <th>Id</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Description/Specification</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Action</th>
                                </tr>
                                <tbody id="tableBody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="javascript/add_product.js"></script>
    <script src="javascript/get_products.js"></script>
    <script src="javascript/dynamic-brands.js"></script>
    <script src="javascript/dynamic-filter-brands.js"></script>
    <script src="javascript/delete_products.js"></script>
</body>
</html>