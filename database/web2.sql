-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 06, 2024 at 02:19 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `password`, `phone_number`, `address`, `image`, `username`, `fullname`) VALUES
('admin@gmail.com', '$2y$10$IhdzNosvdYwB5tft.25t7ei5XSWzxG3ycHUJ6X2KE8PcdedER2aJm', '09225049004', 'Liciada Bustos', 'img/login-signup.png', 'daniel', 'Maika Ordonez');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_price` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `product_name`, `product_image`, `quantity`, `price`, `created_at`, `updated_at`, `total_price`) VALUES
(414, 71, 60, 'Floral Ruffled Dress', 'img/dress-freepeople1.webp', 1, '5995', '2024-05-23 21:07:02', '2024-05-23 21:07:02', '5995'),
(422, 74, 73, 'Rhinestone Chococat Baby Tee', 'img/tops-forever5.jpeg', 1, '1800', '2024-05-24 06:07:12', '2024-05-24 06:07:12', '1800');

-- --------------------------------------------------------

--
-- Table structure for table `design_settings`
--

CREATE TABLE `design_settings` (
  `id` int(11) NOT NULL,
  `background_color` varchar(255) DEFAULT NULL,
  `font_color` varchar(255) DEFAULT NULL,
  `shop_name` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `image_one_path` varchar(255) DEFAULT NULL,
  `image_two_path` varchar(255) DEFAULT NULL,
  `image_three_path` varchar(255) DEFAULT NULL,
  `banner_one_path` varchar(255) DEFAULT NULL,
  `banner_two_path` varchar(255) DEFAULT NULL,
  `endorse_one_path` varchar(255) DEFAULT NULL,
  `endorse_two_path` varchar(255) DEFAULT NULL,
  `endorse_three_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `design_settings`
--

INSERT INTO `design_settings` (`id`, `background_color`, `font_color`, `shop_name`, `logo_path`, `image_one_path`, `image_two_path`, `image_three_path`, `banner_one_path`, `banner_two_path`, `endorse_one_path`, `endorse_two_path`, `endorse_three_path`) VALUES
(1, '#f5f5f5', '#000000', 'SHOP SAVVY', 'img/664d727acd5f9.png', 'img/664cf88f72ce9.png', 'img/bg-bdo.png', 'img/665035d0ea32a.jpeg', 'img/logo.png', 'img/login-signup.png', 'img/logo.png', 'img/logo.png', 'img/logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `code` varchar(8) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` enum('active','fully_redeemed') DEFAULT 'active',
  `user_id` int(11) DEFAULT NULL,
  `usage_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `code`, `amount`, `qty`, `status`, `user_id`, `usage_count`) VALUES
(8, 'FTLX6803', 80.00, 5, 'active', NULL, 0),
(9, 'AQYL3290', 100.00, 10, 'active', NULL, 0),
(10, 'WKUZ9376', 120.00, 20, 'active', NULL, 0),
(11, 'GHTI5607', 1000.00, 3, 'active', NULL, 0),
(12, 'AGJN1506', 500.00, 10, 'active', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `region_id` int(11) NOT NULL,
  `discount_code` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT 'approved\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_name`, `name`, `phone`, `address`, `region_id`, `discount_code`, `payment_method`, `total_amount`, `order_date`, `status`) VALUES
(36, 'itsmy', 'Maika Ybiza Simbulan', '09225049004', 'Liciada Bustos Bulacan', 1, 'WKUZ9376', 'GCash', 2960.00, '2024-05-23 20:19:27', 'approved\r\n'),
(37, 'itsmy', 'Mark James Villagonzalo', '09236785463', 'Tibag Baliwag Bulacan', 1, 'WKUZ9376', 'BDO', 12754.00, '2024-05-23 21:10:17', 'approved\r\n'),
(38, 'itsmy', 'Rosalina Ordonez', '09345567394', 'Liciada Bustos Bulacan', 3, 'WKUZ9376', 'COD', 2975.00, '2024-05-23 22:04:58', 'approved\r\n'),
(39, 'itsmy', 'Albert Junio Jr', '09236785463', 'Bagong Bario Pandi Bulacan', 6, 'FTLX6803', 'GCash', 1934.00, '2024-05-23 22:13:49', 'approved\r\n'),
(40, 'itsmy', 'Albert Junio Jr', '09225049004', 'Liciada Bustos Bulacan', 4, 'WKUZ9376', 'GCash', 1975.00, '2024-05-24 01:59:53', 'approved\r\n'),
(41, 'itsmy', 'Maybilline Bonifacio', '09236785463', 'San Agustin San Rafael Bulacan', 3, 'WKUZ9376', 'BDO', 779.00, '2024-05-24 05:27:09', 'approved\r\n'),
(42, 'itsmy', 'Eva Marie Castro', '09764567893', 'Poblacion Baliwag Bulacan', 2, 'GHTI5607', 'GCash', 4885.00, '2024-05-24 05:35:03', 'approved\r\n'),
(43, 'itsmy', 'Marilou Dela Cruz', '094563785', 'Maginaw San Simon Pampanga', 4, 'WKUZ9376', 'BDO', 2980.00, '2024-05-24 05:44:51', 'approved\r\n'),
(44, 'itsmy', 'Ronald Dela Cruz', '(+63) 912 312 3490', 'Liciada Bustos Bulacan', 5, 'AQYL3290', 'COD', 3005.00, '2024-05-24 05:58:51', 'approved\r\n'),
(45, 'itsmy', 'Christel Racar', '09345567394', 'Poblacion Plaridel Bulacan', 5, 'AQYL3290', 'BDO', 1700.00, '2024-05-24 06:01:09', 'approved\r\n'),
(46, 'itsmy', 'Erick Dave Calleja', '09225049004', 'Binagbag Angat Bulacan', 6, 'WKUZ9376', 'GCash', 1985.00, '2024-05-24 06:08:15', 'approved\r\n'),
(47, 'itsmy', 'Maika Ybiza Simbulan', '09225049004', 'Liciada Bustos Bulacan', 1, 'AQYL3290', 'COD', 1975.00, '2024-05-24 06:08:59', 'approved\r\n'),
(48, 'itsmy', 'Mark James Villagonzalo', '(+63) 912 312 3490', 'Plaridel Bulacan', 3, 'FTLX6803', 'BDO', 4660.00, '2024-05-24 06:11:58', 'approved\r\n'),
(49, 'itsmy', 'Erick Dave Calleja', '09667937463', 'Liciada Bustos Bulacan', 3, 'WKUZ9376', 'BDO', 2270.00, '2024-05-24 06:13:44', 'approved\r\n'),
(50, 'itsmy', 'Daniel Bantog', '09225049004', 'Liciada Bustos Bulacan', 3, 'GHTI5607', 'COD', 5090.00, '2024-05-24 06:16:48', 'approved\r\n'),
(51, 'daniel', 'Daniel Bantog', '09236785463', 'Liciada Bustos Bulacan', 1, 'GHTI5607', 'GCash', 5627.00, '2024-05-24 06:41:15', 'approved\r\n'),
(52, 'daniel', 'maika', '(+63) 912 312 3490', 'Tibag Baliwag Bulacan', 1, 'FTLX6803', 'COD', 804.00, '2024-05-24 06:43:03', 'approved\r\n'),
(53, 'itsmy', 'Maika Ybiza Simbulan', '(+63) 912 312 3490', 'Liciada Bustos Bulacan', 6, 'AGJN1506', 'GCash', 3595.00, '2024-05-25 01:20:44', 'approved\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `price`, `total_price`, `product_image`) VALUES
(41, 37, 'Floral Ruffled Dress', 11, 5995.00, 11990.00, 'img/dress-freepeople1.webp'),
(42, 37, 'Tie-strap Smocked Dress', 8, 799.00, 799.00, 'img/dress-HM4.jpeg'),
(43, 38, 'Crepe Dress with Neckline Applique ', 20, 2995.00, 2995.00, 'img/dress-zara6.jpeg'),
(44, 39, 'Cole Vintage Dress', 20, 1899.00, 1899.00, 'img/dress-shein5.webp'),
(45, 40, 'Scalloped Hem Dress', 1, 1990.00, 1990.00, 'img/dress-HM5.jpeg'),
(46, 41, 'Tie-strap Smocked Dress', 17, 799.00, 799.00, 'img/dress-HM4.jpeg'),
(47, 42, 'Polyamide Draped Dress', 1, 2395.00, 2395.00, 'img/dress-zara5.jpeg'),
(48, 42, 'Asymmetric Voluminous Dress', 10, 3395.00, 3395.00, 'img/dress-reformation4.jpeg'),
(49, 43, 'Crepe Dress with Neckline Applique ', 1, 2995.00, 2995.00, 'img/dress-zara6.jpeg'),
(50, 44, 'Crepe Dress with Neckline Applique ', 1, 2995.00, 2995.00, 'img/dress-zara6.jpeg'),
(51, 45, 'Belted Shirt Dress', 1, 1690.00, 1690.00, 'img/dress-HM1.jpeg'),
(52, 46, 'Smocked Maxi Dress', 1, 1990.00, 1990.00, 'img/dress-asos3.jpeg'),
(53, 47, 'Scalloped Hem Dress', 1, 1990.00, 1990.00, 'img/dress-HM5.jpeg'),
(54, 48, 'Cotton-Silk suit Sweater Tank', 1, 4640.00, 4640.00, 'img/sweater-jcrew4.webp'),
(55, 49, 'Smock-detail Dress', 1, 2290.00, 2290.00, 'img/dress-HM3.jpeg'),
(56, 50, 'Crepe Dress with Neckline Applique ', 2, 2995.00, 5990.00, 'img/dress-zara6.jpeg'),
(57, 51, 'Tie-strap Smocked Dress', 2, 799.00, 1598.00, 'img/dress-HM4.jpeg'),
(58, 51, 'Multi Color Dress', 1, 2549.00, 2549.00, 'img/dress-reformation2.webp'),
(59, 51, 'Polyamide Draped Dress', 1, 2395.00, 2395.00, 'img/dress-zara5.jpeg'),
(60, 52, 'Tie-strap Smocked Dress', 1, 799.00, 799.00, 'img/dress-HM4.jpeg'),
(61, 53, 'Scalloped Hem Dress', 2, 1990.00, 3980.00, 'img/dress-HM5.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `product_brand` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_description` varchar(500) NOT NULL,
  `product_price` int(11) NOT NULL,
  `product_stocks` int(11) NOT NULL,
  `product_sales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_type`, `product_brand`, `product_image`, `product_description`, `product_price`, `product_stocks`, `product_sales`) VALUES
(35, 'Microfibre Maxi Dress', 'Dresses', 'Saint Laurent', 'img/dress-HM6.jpeg', 'Long, fitted, sleeveless dress in soft microfibre with a round neckline.', 999, 10, 120),
(37, 'Scalloped Hem Dress', 'Dresses', 'Saint Laurent', 'img/dress-HM5.jpeg', 'Short, fitted, one shoulder dress with an asymmetric neckline, one wide shoulder strap and a concealed zip and hook and eye fastener at one side. Darts at the back, a seam at the waist and a laser cut, scalloped hem. Unlined.', 1990, 30, 16),
(38, 'Tie-strap Smocked Dress', 'Dresses', 'Saint Laurent', 'img/dress-HM4.jpeg', 'Calf-length jersey dress with a fitted, smocked bodice, square neckline and narrow, tie-top shoulder straps. Gathered seam at the waist and a gently flared skirt.', 799, 20, 0),
(39, 'Smock-detail Dress', 'Dresses', 'Saint Laurent', 'img/dress-HM3.jpeg', 'Calf-length, sleeveless dress in woven fabric featuring a round, frill-trimmed neckline and a keyhole opening with a button at the back of the neck. Smocked sections at the top and waist creating gathers. Gently flared skirt.', 2290, 20, 0),
(40, 'Tie-belt Crepe Dress', 'Dresses', 'Saint Laurent', 'img/dress-HM2.jpeg', 'Ankle-length dress in a crepe weave with a V-shaped neckline, covered buttons down the front and a detachable tie-belt at the waist. Long balloon sleeves in a raglan cut with narrow elastication at the cuffs. Gathered tier above the hem for added volume. Unlined.', 1990, 20, 0),
(41, 'Belted Shirt Dress', 'Dresses', 'Saint Laurent', 'img/dress-HM1.jpeg', 'Short, loose-fit dress in a cotton crepe weave with a collar, V-shaped opening and buttons down the front. Short sleeves with turn-up cuffs and a braided waist belt with a round, wooden buckle. Pleats at the front and back for a flattering silhouette. Unlined.', 1690, 300, 120),
(42, 'Draped Midi Dress', 'Dresses', 'Zara', 'img/dress-zara1.jpeg', 'Midi dress in knit fabric with a straight neckline and adjustable thin straps. Draped fabric on the sides. Open back with adjustable straps with ties. Back slit at the hem. Invisible zip fastening.', 2595, 20, 0),
(43, 'Printed Draped Tulle Dress', 'Dresses', 'Zara', 'img/dress-zara2.jpeg', 'Midi dress with underwire and thin straps. Draped fabric. Matching lining. Invisible side zip fastening.', 2995, 20, 0),
(44, 'Floral Tulle Dress', 'Dresses', 'Zara', 'img/dress-zara3.jpeg', 'Short dress with a straight neckline and thin double straps in contrast satin fabric. Raised floral detailing and draped fabric. Contrast lining. Invisible back zip fastening.', 2995, 20, 0),
(45, 'Polka Dot Print Midi Dress', 'Dresses', 'Zara', 'img/dress-zara4.jpeg', 'V-neck dress with thin straps that cross at the back with ties. Gathered detail at the sides. Front slit. Invisible side zip fastening.', 2595, 20, 0),
(46, 'Polyamide Draped Dress', 'Dresses', 'Zara', 'img/dress-zara5.jpeg', 'Long fitted dress with a straight-cut neckline and thin straps. Featuring a raised flower detail on the chest and draped fabric.', 2395, 20, 0),
(47, 'Crepe Dress with Neckline Applique ', 'Dresses', 'Zara', 'img/dress-zara6.jpeg', 'Sleeveless midi dress with a V-neckline and metal applique. Gathered detail at the waist. Invisible back zip fastening.', 2995, 300, 157),
(48, 'Printed Linen Blend Dress with Knot', 'Dresses', 'Asos', 'img/dress-asos1.jpeg', 'Linen blend dress. Featuring a V-neckline with knot and puff sleeves falling below the elbow. Cut-out detail at the front of the waist and elasticated trim at the back. Tied opening at the back.', 4394, 20, 0),
(49, 'Asymmetric Cape Dress', 'Dresses', 'Asos', 'img/dress-asos2.jpeg', 'Midi dress with an asymmetric neckline and an open back. Featuring an adjustable cape in the same fabric with tied detail and a long sleeve with an elasticated cuff. Matching lining. Invisible side zip fastening.', 6595, 20, 0),
(50, 'Smocked Maxi Dress', 'Dresses', 'Asos', 'img/dress-asos3.jpeg', 'Long dress in woven fabric featuring smocking down the bodice and over the hips. Straight cut at the top with a small frill trim, adjustable spaghetti shoulder straps and a flared skirt. Unlined.', 1990, 20, 0),
(51, 'Frill-trimmed bandeau dress', 'Dresses', 'Asos', 'img/dress-asos4.webp', 'Ankle-length, fitted bandeau dress in plissé jersey with a concealed silicone trim at the top to keep it in place. Concealed zip and hook-and-eye fastener at one side, diagonal frills down the skirt and a raw-cut hem. Lined.', 2690, 20, 0),
(52, 'Animal Print Draped Dress', 'Dresses', 'Asos', 'img/dress-asos5.jpeg', 'Midi dress with a draped cowl neckline and adjustable halterneck with tie fastening at the neck.', 2395, 20, 0),
(53, 'Desigual Women\'s Short Sleeved Dress', 'Dresses', 'Asos', 'img/dress-asos6.jpeg', 'A short, fitted skater dress with short sleeves in ribbed knit with an A-line skirt.', 4680, 20, 0),
(54, 'Draped Beach Dress', 'Dresses', 'Reformation', 'img/dress-reformation1.webp', 'Multi shade draped maxi beach dress', 3049, 20, 0),
(55, 'Multi Color Dress', 'Dresses', 'Reformation', 'img/dress-reformation2.webp', 'Floral print wrap detail midi dress with pleated skirt', 2549, 350, 180),
(56, 'Pleated Asymmetric Dress with Ruffles', 'Dresses', 'Reformation', 'img/dress-reformation3.jpeg', 'Off-the-shoulder dress with a straight neckline. Asymmetric hem with ruffles. Matching lining. Invisible side zip fastening.', 2595, 100, 78),
(57, 'Asymmetric Voluminous Dress', 'Dresses', 'Reformation', 'img/dress-reformation4.jpeg', 'Short dress with a straight neckline and exposed shoulders. Asymmetric hem with voluminous ruffles.', 3395, 20, 0),
(58, 'Fitted One Shoulder Midi Dress', 'Dresses', 'Reformation', 'img/dress-reformation5.webp', 'Multi hue printed midi dress', 2099, 20, 0),
(59, 'Slit Midi Dress', 'Dresses', 'Reformation', 'img/dress-reformation6.webp', 'Monochrome hue midi dress with slit design.', 2399, 20, 0),
(60, 'Floral Ruffled Dress', 'Dresses', 'Free People', 'img/dress-freepeople1.webp', 'Floral print maxi dress with ruffle', 5995, 100, 59),
(61, 'Oversize Striped Dress', 'Dresses', 'Free People', 'img/dress-freepeople2.webp', 'Stripe print woven midi dress with back slit', 3995, 20, 0),
(62, 'Belt Shirt Dress', 'Dresses', 'Free People', 'img/dress-freepeople3.webp', 'Solid shade belted shirt midi dress', 3295, 20, 0),
(63, 'Fitted Knitted Evening Dress', 'Dresses', 'Free People', 'img/dress-freepeople4.webp', 'Transform your look with this sultry dress. Its open back and accentuating tie detail promise to flatter, while the diamond neckline adds a touch of elegance. The sleeveless, slim-fit design is also stretchy for ultimate comfort and easy wear. A versatile addition to any wardrobe.', 2439, 20, 0),
(64, 'Off-The-Shoulder Long Dress', 'Dresses', 'Free People', 'img/dress-freepeople5.webp', 'Experience the enchantment of our dip-dyed maxi dress. A delightful off-shoulder ensemble that presents an alluring silhouette. Its billowing long sleeves and easy pull-on design make it an effortless choice. Not just a dress, but a style statement.', 2995, 20, 0),
(65, 'Cut Out Detailed Evening Dress', 'Dresses', 'Free People', 'img/dress-freepeople6.webp', 'Sleeveless midi dress with front flower design, Halter neckline.', 2549, 20, 0),
(66, 'Xanthea Dress', 'Dresses', 'Shein', 'img/dress-shein1.webp', 'Solid tone tie detail sleeveless maxi dress', 1899, 20, 0),
(67, 'Ruffle Evening Maxi Dress', 'Dresses', 'Shein', 'img/dress-shein2.webp', 'Solid tone cut out ruffle maxi dress, Back button and zip fastening.', 2649, 20, 0),
(68, 'Frilled Sleeves Dress', 'Dresses', 'Shein', 'img/dress-shein3.webp', 'Monochrome hue midi dress with frilled sleeves design.', 2799, 20, 0),
(69, 'V Neck Midi Dress', 'Dresses', 'Shein', 'img/dress-shein4.webp', 'A flowy, lightweight black midi dress with a flower pattern, a v neck, empire waist and side slit.', 510, 20, 0),
(70, 'Cole Vintage Dress', 'Dresses', 'Shein', 'img/dress-shein5.webp', 'Solid tone twisted overlap sleeveless maxi dress.', 1899, 20, 0),
(71, 'Long Sleeves V-Neck Dress', 'Dresses', 'Shein', 'img/dress-shein6.webp', 'A blue comfy dress featuring a floral print, long sleeves and a V-neck. This one’s sure to be both a day and evening favorite.', 2300, 30, 0),
(72, 'Seamless Lace Cropped Tube Top', 'Tops', 'Forever 21', 'img/tops-forever6.jpeg', 'A seamless knit tube top featuring a straight-cut neckline and back, sheer lace overlay, and cropped handkerchief hem.', 1950, 20, 0),
(73, 'Rhinestone Chococat Baby Tee', 'Tops', 'Forever 21', 'img/tops-forever5.jpeg', 'A ribbed knit baby tee featuring a contrasting crew neck, front rhinestone Chococat graphic, short sleeves, and a cropped hem.', 1800, 20, 0),
(74, 'Rosette Lace Cami Bodysuit', 'Tops', 'Forever 21', 'img/tops-forever1.jpeg', 'A knit bodysuit featuring sheer lace overlay, scalloped trim, rosette chest detail, adjustable cami straps, and a fitted silhouette.', 1650, 20, 0),
(75, 'Ruched One-Shoulder Crop Top', 'Tops', 'Forever 21', 'img/tops-forever2.webp', 'A knit top featuring a one-shoulder neckline, ruched strap detail, and a cropped hem.', 1399, 20, 0),
(76, 'Crochet Hello Kitty Sweater', 'Tops', 'Forever 21', 'img/tops-forever3.jpeg', 'From our Hello Kitty and Friends x Forever 21 collection, this crochet sweater features a sheer construction, front \"Hello Kitty\" text, long sleeves with Hello Kitty embroidered bow, scalloped trim, and a crew neck. (Layering garments not included.)', 3799, 20, 0),
(77, 'Coca-Cola Diet Coke Sequin Tee', 'Tops', 'Forever 21', 'img/tops-forever4.jpeg', 'From our Coca-Cola x Forever 21 collection, this knit tee features allover metallic sequins, front \"Diet Coke\" text, a boxy fit, dropped short sleeves, a cropped hem, and crew neck.', 5499, 20, 0),
(78, 'Gap Logo T-Shirt', 'Tops', 'Gap', 'img/tops-gap1.webp', 'Soft jersey knit, Short sleeves, Gap logo at front.', 1450, 20, 0),
(79, 'Linen-Blend Tank Top', 'Tops', 'Gap', 'img/tops-gap2.webp', 'Classic linen with the comfort turned up high. Easy and effortless — now made live-in worthy.', 1650, 20, 0),
(80, ' brown stripe brown stripe brown stripe Ribbed High Neck Tank', 'Tops', 'Gap', 'img/tops-gap3.webp', 'Soft ribbed knit. Tank straps. Ribbed crewneck. Select styles have allover stripes.', 1450, 20, 0),
(81, 'Modern Rib Cropped Halter Top', 'Tops', 'Gap', 'img/tops-gap4.webp', 'Our soft and stretchy cotton modal blend that hugs you just right. Its your new 7 days a week essential. Supersoft ribbed knit cotton modal blend cropped halter top.', 1450, 20, 0),
(82, 'Modern Rib Halter Tank Top', 'Tops', 'Gap', 'img/tops-gap5.webp', 'Soft cotton blend ribbed knit tank top. Halter neckline. Sleeveless.', 1450, 20, 0),
(83, 'Linen-Blend Polo Shirt', 'Tops', 'Gap', 'img/tops-gap6.webp', 'Soft linen blend polo shirt. Polo collar. Short sleeves.', 1950, 20, 0),
(84, 'Linen Blend Band Collar Sleeve Shirt', 'Tops', 'Uniqlo', 'img/tops-uniqlo1.avif', 'The smooth texture of rayon meets the cool feel of linen. Versatile design also styles as a light outer layer. Striped pattern.', 990, 30, 0),
(85, 'American Sleeve Cropped Bra Sleeveless Top', 'Tops', 'Uniqlo', 'img/tops-uniqlo2.avif', 'Sleek tank-top design. Opaque narrow ribbed fabric. Built in bra cups mean you dont need to worry about your bra straps showing.', 990, 20, 0),
(86, 'Seamless Half Bra Camisole', 'Tops', 'Uniqlo', 'img/tops-uniqlo3.avif', 'Two-layer construction with casual ribbed fabric on the outside and flat fabric on the inside.', 790, 20, 0),
(87, 'Halter Neck Bra Sleeveless Top', 'Tops', 'Uniqlo', 'img/tops-uniqlo4.avif', 'Cotton rib fabric complements the gently fitted silhouette. Halter neck.', 990, 20, 0),
(88, 'Ruffle Sleeve T-Shirt', 'Tops', 'Uniqlo', 'img/tops-uniqlo5.avif', 'Added paneling at the front and sides creates a moderately fitted feel. Cotton blend fabric has a textured surface for non clingy comfort.', 790, 20, 0),
(89, 'Graphic Short Sleeve T-Shirt', 'Tops', 'Uniqlo', 'img/tops-uniqlo6.avif', 'Combines Marimekko’s signature bold print with an easy-to-wear silhouette. 100% cotton. Relaxed cut pairs well with skirts and pants.', 790, 20, 0),
(91, 'Crochet striped top', 'Tops', 'Mango', 'img/tops-mango1.avif', 'Cotton-blend fabric. Crochet fabric. Cropped design. Straight design. Striped design. V-neck. Sleeveless. Front button closure. Co-ord. Festival Season.', 1995, 20, 0),
(92, 'Openwork detail linen', 'Tops', 'Mango', 'img/tops-mango2.avif', 'Linen mix. Straight design. Round neck. Wide straps. Button fastening on the front. Bow closure on the front. Scalloped edges.', 2495, 20, 0),
(93, 'Ruffled cotton top', 'Tops', 'Mango', 'img/tops-mango3.avif', 'Cotton-blend fabric. Slim fit. Tailored dress. V-neck. Sleeveless. Ruffles detail.', 1295, 20, 0),
(94, 'Tweed top with frayed detail', 'Tops', 'Mango', 'img/tops-mango4.avif', 'Cotton-blend fabric. Tweed fabric. Straight design. Medium knit. Rounded neck. Sleeveless. Ruched waist. Frayed finish.', 2495, 20, 0),
(95, 'Asymmetrical top with embroidered panel', 'Tops', 'Mango', 'img/tops-mango5.avif', '100% cotton fabric. Cropped design. Straight design. Asymmetric design. Asymmetric neckline. Back neckline. Sleeveless. Thin strap. Bow fastening on the back. Embroidered details. Selection Collection.  A selection of refined garments, made with quality materials to create a feminine and contemporary wardrobe for special occasions.', 2995, 20, 0),
(96, 'Top crochet buttons', 'Tops', 'Mango', 'img/tops-mango6.avif', 'Cotton-blend fabric. Crochet design. Cropped design. Straight design. V-neck. Sleeveless. Wide straps. Front button closure. Rounded hem. Inner lining. Co-ord.', 2995, 20, 0),
(97, 'Body Contour High Compression Scoop Neck Cap Sleeve Bodysuit', 'Tops', 'Express', 'img/tops-express1.avif', 'Smooth, sculpt and define your silhouette. The scoop neck and cap sleeves on this soft matte bodysuit complete any look this season. Easy to style solo and easy to layer up.', 2495, 20, 0),
(98, 'Body Contour High Compression Cropped Tube Top', 'Tops', 'Express', 'img/tops-express2.webp', 'Smooth, sculpt and define your silhouette. This staple cropped tube top style has the comfort and support you need in a base layer. Grab one in every color.', 2987, 20, 0),
(99, 'Body Contour Compression High Neck Bodysuit', 'Tops', 'Express', 'img/tops-express3.avif', 'Smooth, sculpt and define your silhouette. The thick double layer of silky fabric flatters the way you want it to, while a snap-shut bottom keeps your look crisp, cool and tucked.', 3862, 20, 0),
(100, 'Satin V-Neck Downtown Cami', 'Tops', 'Express', 'img/tops-express4.avif', 'In a chic satin material and a sleek V-neck this is the perfect staple you need in your closet. Style under a blazer for a day in the office or wear it solo for a brunch date.', 2953, 20, 0),
(101, 'Relaxed Off The Shoulder Short Sleeve London Tee', 'Tops', 'Express', 'img/tops-express5.avif', 'Extra soft with an easy, oversized fit, this off-the-shoulder London Tee is cool enough to wear out, yet comfy enough to lounge in. It\'s ready for anything.', 2392, 20, 0),
(102, 'Skimming One Shoulder Draped Tank', 'Tops', 'Express', 'img/tops-express6.avif', 'Made with a polished matte material, this cute one shoulder top promises comfort and style. Pair with faux leather pants or trousers for a night out.', 3827, 20, 0),
(103, 'Chunky-knit gilet', 'Tops', 'Geographic', 'img/tops-mango7.avif', 'Wool mix fabric. Thick knitted fabric. Straight design. Rounded neck. Sleeveless. Unclosed. Cable knit finish.', 2295, 20, 0),
(104, 'Draped detail top', 'Tops', 'Geographic', 'img/tops-mango8.avif', 'Flowy fabric. Strapless. Tailored dress. Long design. Turn-down neckline. Invisible back zip fastening.', 2295, 20, 0),
(105, 'Striped knit top', 'Tops', 'Geographic', 'img/tops-mango9.avif', 'Fine knit fabric. Straight design. Short design. Striped print. Rounded neck. Sleeveless. Straps. Co-ord.', 1495, 20, 0),
(106, 'Knitted top with bow closure', 'Tops', 'Geographic', 'img/tops-mango10.avif', 'otton-blend fabric. Knitted fabric. Short design. Straight design. V-neck. Short sleeve. Tie closure at front.', 2295, 20, 0),
(107, 'Crop top with zip', 'Tops', 'Geographic', 'img/tops-mango11.avif', 'Flowy fabric. Cropped design. Tailored dress. Sleeveless. Lapel-collar. Front zip closure.', 1995, 20, 0),
(108, 'Embroidered cotton top', 'Tops', 'Geographic', 'img/tops-mango12.avif', '100% cotton fabric. Regular fit. Straight design. Rounded neck. Sleeveless. Wide straps. Button fastening at back.', 1995, 20, 0),
(109, 'Forever Sweater', 'Jumpsuits', 'Urban Outfitters', 'img/sweater-jcrew2.webp', '-V-neck. Long sleeves. -Ribbed neck, arm cuffs and hem. -Straight hem.', 2750, 20, 0),
(111, 'Cotton-Silk Sweater Tank', 'Jumpsuits', 'Urban Outfitters', 'img/sweater-jcrew3.webp', '-We designed this sweater tank to stun through the warmest weather, cutting it in a not-to-tight silhouette with high, spaghetti straps so it feels modern. For materials, we selected a beautiful yarn spun from breathable cotton and luxuriously soft silk. -Semi-fitted. -High crew neck. -Straight hem.', 4650, 20, 0),
(112, 'Cotton-Silk suit Sweater Tank', 'Jumpsuits', 'Urban Outfitters', 'img/sweater-jcrew4.webp', '-We designed this sweater tank to stun through the warmest weather, cutting it in a not-to-tight silhouette with high, spaghetti straps so it feels modern. For materials, we selected a beautiful yarn spun from breathable cotton and luxuriously soft silk. -Semi-fitted. -High crew neck. -Straight hem.', 4640, 20, 0),
(113, 'Luna Cashmere Sweater Top', 'Jumpsuits', 'Urban Outfitters', 'img/sweater-jcrew5.webp', '-Supremely soft and warm, this luxurious cashmere yarn instantly elevates the look of this t-shirt-style sweater top. -Relaxed fit. -Good Cashmere Standard™: The cashmere for this product is produced according to the Good Cashmere Standard™, which aims to improve the welfare of the cashmere goats, protect natural resources, and support local farmers. -Crew neck. -Straight hem.', 4750, 20, 0),
(114, ' Luna Cashmere Sweater Top', 'Jumpsuits', 'Urban Outfitters', 'img/sweater-jcrew6.webp', '-Supremely soft and warm, this luxurious cashmere yarn instantly elevates the look of this t-shirt-style sweater top. -Relaxed fit. -Good Cashmere Standard™: The cashmere for this product is produced according to the Good Cashmere Standard™, which aims to improve the welfare of the cashmere goats, protect natural resources, and support local farmers. -Crew neck. -Straight hem.', 7450, 20, 0),
(115, 'Linen-Cotton Sweater Polo', 'Jumpsuits', 'Hugo Boss', 'img/sweater-br1.webp', '-Our take on the season\'s mesh trend, this retro sweater polo has an open-stitch texture, knitted using one of our softest cotton and linen yarns. -Standard fit. -Polo collar with button placket. -Straight hem.', 4950, 20, 0),
(116, 'Point Sur Cotton Sweater Polo', 'Jumpsuits', 'Hugo Boss', 'img/sweater-br2.webp', 'This retro-inspired polo is knitted it in our signature mercerized cotton yarn—a special process that produces a smoother, silkier handfeel with a subtle luster. -Standard fit. -Polo collar. -Straight hem.', 4950, 20, 0),
(117, 'Point Sur Sweater Polo', 'Jumpsuits', 'Hugo Boss', 'img/sweater-br3.webp', '-This retro-inspired polo is knitted it in our signature mercerized cotton yarn—a special process that produces a smoother, silkier handfeel with a subtle luster. -Standard fit. -Polo collar. -Straight hem', 4950, 20, 0),
(118, 'Raoul Merino Sweater', 'Jumpsuits', 'Hugo Boss', 'img/sweater-br4.webp', '-Crafted from soft jersey, this Merino wool sweater uses a versatile half-zip neckline to layer with ease.', 5250, 20, 0),
(119, 'Forever smooth Sweater ', 'Jumpsuits', 'Hugo Boss', 'img/sweater-br5.webp', '-V-neck. Long sleeves. -Ribbed neck, arm cuffs and hem. -Straight hem.', 2750, 20, 0),
(120, 'Nezha Merino Sweater Shell', 'Jumpsuits', 'Hugo Boss', 'img/sweater-br6.webp', '-The sweater vest is crafted with a wide-ribbed knit from our favorite extra-fine Merino wool for a soft finish and sculpted fit against the skin.', 3250, 20, 0),
(121, 'Discovery logo t-shirt', 'Jumpsuits', 'Boohoo', 'img/sweater-nf1.avif', 'Fine knit fabric. Striped print. V-neck. Long sleeve. Cable knit finish. Plus Size Available. Office looks.  Recycled polyester is obtained from polyester textile waste, marine litter or PET plastic bottles that are used to produce new fabric.', 1695, 20, 0),
(122, 'V-neck knit sweater', 'Jumpsuits', 'Boohoo', 'img/sweater-nf2.avif', 'Fine knit fabric. Straight design. V-neck. Long sleeve. Unclosed. Cable knit finish. Plus Size Available. Basics Collection.', 1695, 20, 0),
(123, 'Openwork polo neck top', 'Jumpsuits', 'Boohoo', 'img/sweater-nf3.avif', 'Fine knit fabric. Straight design. Openwork design. Polo neck. Sleeveless. Unclosed. Cable knit finish.', 2295, 20, 0),
(124, 'Button knit cardigan', 'Jumpsuits', 'Hugo Boss', 'img/sweater-nf4.avif', 'Thick knitted fabric. Straight design. V-neck. Long sleeve. Front button closure. Cable knit finish. Co-ord.', 2295, 20, 0),
(125, 'Contrasting V-neck sweater', 'Jumpsuits', 'Boohoo', 'img/sweater-nf5.avif', 'Fine knit fabric. Straight design. V-neck. Long sleeve. Contrasting trims. Plus Size Available.', 2495, 20, 0),
(126, 'Knitted polo neck sweater', 'Jumpsuits', 'Boohoo', 'img/sweater-nf6.avif', 'Fine knit fabric. Straight design. Polo neck. Short sleeve. Unclosed. Contrasting trims. Office looks.', 1695, 20, 0),
(128, 'Lightweight cotton hooded sweatshirt', 'Jumpsuits', 'Revolve', 'img/sweater-LL1.avif', 'ESSENTIALS: Made to last. The light grey model is an online exclusive . 100% cotton fabric. Relaxed fit. Drawstring hood. Long sleeve with elastic cuffs. Pouch pocket. Hem with elastic band. The model is 187 cm tall and is wearing a size M.', 1995, 20, 0),
(129, 'Cotton sweatshirt with zip neck', 'Jumpsuits', 'Revolve', 'img/sweater-LL2.avif', 'The dark grey and vision grey models are online exclusives. Regular fit. Cotton-blend fabric. Quilted cotton interior. Perkins collar with zip closure. Long sleeve with elastic cuffs. Straight hem with ribbed ends. The model is 190 cm tall and is wearing a size M.', 2495, 20, 0),
(130, 'Breathable structured bomber jacket', 'Jumpsuits', 'Revolve', 'img/sweater-LL3.avif', 'Performance Collection. Breathable: fabric that evaporates moisture and keeps you dry. Quick Dry. Bomber design. Bomber jacket with ribbed collar. Front zip closure. Two front pockets with invisible zip. Long sleeve. Straight hem. The model is 190 cm tall and is wearing a size M.', 4295, 20, 0),
(131, 'Cotton polo sweatshirt', 'Jumpsuits', 'Revolve', 'img/sweater-LL4.avif', 'ESSENTIALS: Made to last. Polo-neck with button closure. Long sleeve with elastic cuffs. Straight hem with ribbed ends. Quilted cotton interior. The model is 190 cm tall and is wearing a size M.', 2995, 20, 0),
(132, 'Cotton zip-up hoodie', 'Jumpsuits', 'Revolve', 'img/sweater-LL5.avif', 'ESSENTIALS: Made to last. Cotton fabric. Quilted cotton interior. Regular fit. Drawstring hood. Front zip closure. Pouch pocket. Long sleeve with elastic cuffs. Straight hem. The model is 190 cm tall and is wearing a size M.', 1995, 20, 0),
(133, '100% cotton basic sweatshirt', 'Jumpsuits', 'Revolve', 'img/sweater-LL6.avif', 'ESSENTIALS: Made to last. The aqua green model is an online exclusive. 100% cotton fabric. Regular fit. Rounded neck. Long sleeve with elastic cuffs. Ribbed finishes on the collar, sleeve and . Hem with elastic band. The model is 183 cm and is wearing a size M.', 2295, 20, 0),
(134, 'Men\'s Better Sweater® Fleece Vest', 'Jumpsuits', 'Everlane', 'img/sweater-patagonia1.jpeg', 'This warm, 100% recycled polyester full-zip vest combines a sweater-knit aesthetic with the easy care of Better Sweater fleece. It’s dyed with a low-impact process that reduces the use of dyestuffs, energy and water compared to conventional heather dyeing methods. Made in a Fair Trade Certified™ factory.', 2986, 20, 0),
(135, 'Men\'s Recycled Wool-Blend Sweater', 'Jumpsuits', 'Everlane', 'img/sweater-patagonia2.jpeg', 'A durable crewneck sweater made with a blend of recycled wool and recycled nylon for everyday warmth and layering.', 2983, 20, 0),
(136, 'Women\'s Recycled Wool-Blend 1/4-Zip Sweater', 'Jumpsuits', 'Everlane', 'img/sweater-patagonia3.jpeg', 'Warm, comfortable and durable, this daily-wear sweater can be worn alone or as a toasty layer and is made with a blend of recycled wool and recycled nylon.', 2995, 20, 0),
(137, 'Men\'s Recycled Wool-Blend Buttoned Sweater', 'Jumpsuits', 'Everlane', 'img/sweater-patagonia4.jpeg', 'Built for daily wear in cool weather, this sweater is made with recycled wool and recycled nylon. Whether you’re camping or about town, it’s a warm and comfortable go-to that can be layered and dressed up or down.', 3972, 20, 0),
(138, 'Women\'s Recycled Wool-Blend Crewneck Sweater', 'Jumpsuits', 'Everlane', 'img/sweater-patagonia5.jpeg', 'Built to be worn in cool weather as an everyday sweater or a durable warm layer for camping. Made of a blend of recycled wool and recycled nylon. Offered in a jersey, cable or jacquard stitch.', 2983, 20, 0),
(139, 'Men\'s Better Sweater® 1/4-Zip Fleece', 'Jumpsuits', 'Everlane', 'img/sweater-patagonia6.jpeg', 'This warm, 100% recycled polyester quarter-zip jacket combines a sweater-knit aesthetic with the easy care of Better Sweater fleece. It’s dyed with a low-impact process that reduces the use of dyestuffs, energy and water compared to conventional heather dyeing methods. Made in a Fair Trade Certified™ factory.', 2995, 20, 0),
(140, 'Lee Luke slim tapered fit jeans', 'Bottoms', 'Levi\'s', 'img/IMG_2865.WEBP', 'vinatge Lee jeans Men 32 x 28 cotton', 2995, 20, 0),
(141, 'Anthropologies Worn Kept Jeans ', 'Bottoms', 'Levi\'s', 'img/IMG_2866.WEBP', 'Anthropologies Worn Kept Jeans  L30 Straight Raw hern', 2995, 20, 0),
(142, 'hello', 'Gowns', 'Machesa', 'img/664d727acd5f9.png', 'hello world', 120, 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `seller_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `number` varchar(20) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `blocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`seller_id`, `fname`, `mname`, `lname`, `address`, `number`, `username`, `email`, `password`, `image`, `blocked`) VALUES
(1, 'Maika', 'Ordonez', 'Simbulan', 'Liciada', '09225049004', 'seller', 'seller@gmail.com', '$2y$10$hyKSw9wI2b.dGDNqnWv1Bu9IgLheC5F2jNx7mNoI4cK2gg81EgGJy', NULL, 0),
(4, 'Mark ', 'Jame', 'Villagonzalo', 'Tibag Bustos Bulacan', '09225049004', 'mj', 'seller1@gmail.com', '$2y$10$shpXo76uYGfV0z/xAMfFdOJKIFpvHXyebZSccOmSi7nObFFSfwvdq', NULL, 0),
(5, 'Albert', 'Jr', 'Junio', 'Bagong Barrio Pandi Bulacan', '09246738756', 'alberto', 'seller2@gmail.com', '$2y$10$0QvByJVKI8GVHWnGcw.fQehO6seUV8bZV51A.vzNcKbvWpW4fDAAW', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `fee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`id`, `address`, `fee`) VALUES
(1, 'NCR', 85),
(2, 'North Luzon', 95),
(3, 'South Luzon', 100),
(4, 'Visayas', 105),
(5, 'Mindanao', 110),
(6, 'Island', 115);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL,
  `email_verified_at` datetime(6) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiration` varchar(255) DEFAULT NULL,
  `last_attempt` timestamp NOT NULL DEFAULT current_timestamp(),
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `contact_number` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `verification_code`, `email_verified_at`, `reset_token`, `reset_token_expiration`, `last_attempt`, `blocked`, `attempts`, `contact_number`, `address`, `image_path`, `first_name`, `middle_name`, `last_name`) VALUES
(70, 'aling_rosa', 'maikaybiza.simbulan.o@bulsu.edu.ph', '$2y$10$QJ6xlXe8y2mPQqTl6Btn.O.5D4MlIC22z30cenV6IW1a25WKlBE6e', 284744, '2024-05-18 00:40:10.000000', '', '', '2024-05-17 16:39:51', 0, 2, '09496563656', 'Liciada Bustos Bulacan', '', 'Rosalina', 'Calpito', 'Ordonez'),
(71, 'mj', 'villagonzalomarkjames21@gmail.com', '$2y$10$CSHoN7X.Iui31ZcAHdDiLO./u6qnmh2LWBof6nRbdIXiE71BWg8I6', 257925, '2024-05-19 01:11:50.000000', '', '', '2024-05-18 17:11:37', 0, 1, '09225049004', 'Tibag Baliwag', 'img/664fafede65f5.png', 'Mark ', 'James', 'Villagonzalo'),
(74, 'itsmy', 'Ybiza2018@gmail.com', '$2y$10$oyaRn3uZb4PdUJf48zyBwet/ZrmY86oIxhauTZpimsJoLQPz2T9xO', 277775, '2024-05-20 00:11:43.000000', '', '', '2024-05-19 16:11:31', 0, 2, '09225049005', 'Liciada Bustos Bulacan', 'img/664eab41ea59d.jpeg', 'Maika', 'Ybiza', 'Simbulan'),
(75, 'ben', 'albertjunio664@gmail.com', '$2y$10$Sz/2JivcNm1xJa/5yocH3ua5ypbBYNnQC6IQGADduQJagsstbq1Mm', 423976, '2024-05-24 12:34:02.000000', NULL, NULL, '2024-05-24 04:33:07', 0, 0, '09645327364', 'teresa rizal', '', 'Benedick', 'S', 'Ygloria'),
(76, 'itsDian', 'albertjunio2930@gmail.com', '$2y$10$lWTAtojbN7i1oS8OZAGCXOmllQ.Z/D0UAGNcBxXvdaaSvINDd9.2C', 658818, '2024-05-24 12:36:43.000000', NULL, NULL, '2024-05-24 04:36:28', 0, 0, '09287635473', 'teresa rizal', '', 'Dian', 'B', 'Flores'),
(77, 'markcy', 'bbeng8031@gmail.com', '$2y$10$LvF2lP.sGjRCD2Q8niIY7.319a8fMvHq4tWreEKjWTS/wkfpUKInK', 221229, '2024-05-24 12:39:50.000000', NULL, NULL, '2024-05-24 04:39:32', 0, 0, '09214536746', 'Liciada Bustos', '', 'Mark John', 'L', 'Lopez'),
(78, 'itstel', 'cristeldinosaur@gmail.com', '$2y$10$qK3jouDGvwyGdLvn54Nuy.6yQZJG0ghXCwrOootVf8G0hOVaIkTXi', 644528, '2024-05-24 12:48:57.000000', NULL, NULL, '2024-05-24 04:48:08', 0, 0, '09837462737', 'Tibag Baliwag Bulacan', '', 'Cristel', 'M', 'Dela Cuz '),
(79, 'daniel', 'daniel.bantog@bulsu.edu.ph', '$2y$10$ozJdSRADF3qn4vrEHVy6qeS1LJCxcVWUb4mHYCl7N6lA89HWjHQlK', 830688, '2024-05-24 14:35:22.000000', '', '', '2024-05-24 06:34:51', 0, 0, '09551041534', 'Liciada Bustos Bulacan', 'img/665035d0ea32a.jpeg', 'Daniel', 'O', 'Bantog');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `design_settings`
--
ALTER TABLE `design_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`seller_id`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=432;

--
-- AUTO_INCREMENT for table `design_settings`
--
ALTER TABLE `design_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `seller_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
