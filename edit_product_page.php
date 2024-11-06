<?php include('admin-navigation.php'); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/edit_product_page.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="icon" type="image/x-icon" href="img/logo.png" />
    <link rel="stylesheet" type="text/css" href="css/edit_product_page.css">
    <title>SHOP SAVVY</title>
  </head>
  <body>
    <div class="dashboard-content">
      <div class="dashboard-view">
        <div class="back">
          <h1>PRODUCT MANAGEMENT</h1>
          <a href="product_page.php"><i class="fa-solid fa-arrow-left-long" style="color: #AD53A6"></i></a>
        </div>
        <div class="contents">
          <div class="edit-product-container">
            <form id="update-info">
              <div class="form-column">
                <div class="input">
                  <label for="product-name">Product Name:</label>
                  <input type="text" id="product-name" name="product_name" />
                </div>
                <div class="select">
                  <label for="product-type">Product Type:</label>
                  <select name="product_type" id="product-type">
                    <option value="Dresses">Dresses</option>
                    <option value="Tops">Tops</option>
                    <option value="Bottoms">Bottoms</option>
                    <option value="Swim Wears">Swim Wears</option>
                    <option value="Sleep Wears">Sleep Wears</option>
                    <option value="Jumpsuits">Jumpsuits</option>
                    <option value="Gowns">Gowns</option>
                    <option value="Tuxedos">Tuxedos</option>
                  </select>
                </div>
                <div class="image">
                  <label for="product-image">Product Image:</label>
                  <div class="file-upload-wrapper">
                    <label for="product-image" class="custom-file-upload">
                      <i class="fa fa-cloud-upload"></i> Choose File
                    </label>
                    <input type="file" id="product-image" name="product_image" accept=".jpg, .jpeg, .png, .webp, .avif" required />
                  </div>
                </div>
              </div>
              <div class="form-column">
                <div class="select">
                  <label for="product-brand">Product Brand:</label>
                  <select name="product_brand" id="product-brand">
                  </select>
                </div>
                <div class="input">
                  <label for="product-price">Price:</label>
                  <input type="number" id="product-price" name="product_price" min="1" required />
                </div>
                <div class="input">
                  <label for="product-quantity">Quantity:</label>
                  <input type="text" id="product-quantity" name="product_stocks" />
                </div>
                <div class="input">
                  <label for="product-description">Description/Specifications:</label>
                  <input type="text" id="product-description" name="product_description" />
                </div>
                <button type="submit">
                  Save Changes
                </button>
              </div>
            </form>
            <div class="image-preview">
              <img id="product-image-preview" src="" alt="Product Image Preview" />
            </div>
          </div>
        </div>
      </div>
    </main>
    <script src="javascript/dynamic-brands.js"></script>
    <script src="javascript/get_specific_product.js"></script>
    <script src="javascript/update_product.js"></script>
  </body>
</html>
