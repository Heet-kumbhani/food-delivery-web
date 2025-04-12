-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2025 at 08:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tometo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adm_id` int(97) NOT NULL,
  `full_name` varchar(97) NOT NULL,
  `password` varchar(97) NOT NULL,
  `email` varchar(97) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone_no` varchar(15) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `last_login` datetime DEFAULT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adm_id`, `full_name`, `password`, `email`, `date`, `phone_no`, `status`, `last_login`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(17, 'test2', '$2y$10$0gmFjY0SbBEHbwuQSSE00uKkG0mPuQN1exDjW0NP5CuenGMbgMXEa', 'kumbhaniheet@gmail.com', '2025-04-05 05:52:46', '1234567890', 'active', '2025-03-12 02:51:33', '0d65189f130d2bf0205401cf58a49633ef2ef2f002bfff989a2c0ce1e25781df', '2025-04-05 08:22:46'),
(19, 'heet', '$2y$10$2P968TPostPTrBQwPeZW3eFZVdOMbTOhOgUw7pF94MAyEEJBr5YsO', 'heet@gmail.com', '2025-04-05 05:53:03', '1234543212', 'active', '2025-04-05 11:23:03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_boys`
--

INSERT INTO `delivery_boys` (`id`, `full_name`, `phone_no`, `address`, `password`, `status`, `date_created`, `email`) VALUES
(1, 'prince', '1234567890', 'some prince address', '$2y$10$IRcrBuSNOUOidV54qY1cg.1Wb86YDfs31WHG05b1pPa0sIKfEQTN6', 'active', '2025-03-03 05:25:21', 'prince@gmai.com'),
(4, 'meet', '1234567890', 'SUNSHINE FLATS (VARACHAA) (VARACHHA POLICE STATIO) 403', '$2y$10$FpSmk9dtg8hTs2stAjylt.9GfGXk48n0oR1ZbjILkpZooRlA28.2y', 'active', '2025-04-04 14:29:09', 'meet@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `dishes`
--

CREATE TABLE `dishes` (
  `d_id` int(91) NOT NULL,
  `subcat_id` int(97) NOT NULL,
  `d_name` varchar(97) NOT NULL,
  `d_discription` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `img` varchar(150) NOT NULL,
  `rs_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dishes`
--

INSERT INTO `dishes` (`d_id`, `subcat_id`, `d_name`, `d_discription`, `price`, `img`, `rs_id`, `status`) VALUES
(6, 38, 'Greek salad', 'Food provides essential nutrients for overall health and well-being', 110.00, '67d3f1a6c0ba3.png', 63, 'active'),
(7, 38, 'Veg salad', 'Food provides essential nutrients for overall health and well-being', 90.00, '67d3f7b7dc230.png', 63, 'active'),
(8, 16, 'Lasagna Rolls', 'Food provides essential nutrients for overall health and well-being', 150.00, '67d3f912eca0b.png', 63, 'active'),
(9, 30, 'vanilla Ice Cream', 'Vanilla Ice Cream with Sprinkles, served in a sprinkle-coated waffle bowl.', 70.00, '67d3fdad49d1c.png', 63, 'active'),
(10, 16, 'Creamy Pasta', 'pasta with creamy souce, olive, jalapenos and paarsley.', 200.00, '67d3ff43af9f9.png', 63, 'active'),
(11, 13, 'Manchurian Dry', 'Spicy - Mix Veg Balls Tossed In Ginger, Garlic, Chilli & Soya.', 120.00, '67d401d626dba.jpg', 64, 'active'),
(12, 12, 'veg fried rice', 'Veg Fried Rice is a flavorful stir-fried dish made with rice, mixed vegetables, aromatic taste.', 130.00, '67d42382d2f27.jpg', 64, 'active'),
(13, 14, 'spring roll', 'They are typically served with a dipping sauce like sweet chili or soy sauce.', 120.00, '67d424ac4e9b8.jpg', 64, 'active'),
(14, 15, 'Veg Hakka Noodles', 'Delectable stands of soft noodles tossed with assorted chopped veggies and hakka sauces, perfect entry to satiate your cravings.', 140.00, '67d4253058d11.jpg', 64, 'active'),
(15, 15, 'maggi', 'Double Masala Maggi', 150.00, '67d4266813d8f.jpg', 64, 'active'),
(16, 16, 'White Sauce Pasta', 'Cheesy and creme Pasta', 190.00, '67d429e083ff8.jpg', 65, 'active'),
(17, 17, 'Double Cheese Margherita Pizza', 'Topped with two type cheese.', 300.00, '67d42ad4f3fc2.jpg', 65, 'active'),
(18, 18, 'Leonardo Ravioli Spinach & Ricotta', 'Chef’s secret recipe of handcrafted pasta stuffed with spinach & ricotta, pan sautéed with mix vegetables, fresh cream & parmesan cheese.', 250.00, '67d42b6cb80cd.jpg', 65, 'active'),
(19, 16, 'Pesto Pasta', 'Pesto Pasta is a delicious and aromatic Italian dish made with pasta coated in a rich basil pesto sauce.', 300.00, '67d42c3c7506b.jpg', 65, 'active'),
(20, 16, 'Pink Pasta', ' It is typically prepared with penne or spaghetti and enhanced with garlic, Parmesan cheese, and herbs for added depth of flavor.', 345.00, '67d42cc2bd888.jpg', 65, 'active'),
(21, 17, 'Farm Fresh Pizza', 'Freshly topped with capsicum, fresh tomatoes, sweet corn, onion and red paprika.', 445.00, '67d42dcfb2d71.jpg', 65, 'active'),
(22, 19, 'Mushrooms, Corns & Onion Taco\'s', 'Honestly Tempting! Crispy Taco Stuffed with Mushrooms, Corns, Onion & Creamy Sauce', 245.00, '67d42f9c98eae.jpg', 66, 'active'),
(23, 19, 'Paneer & Corns Taco\'s', 'Crispy Taco filled with Paneer & Corns & Creamy Sauce\r\n', 99.00, '67d43012a8787.jpg', 66, 'active'),
(24, 19, 'Paneer Tikka Butter Masala & Red Paprika Taco\'s', 'Truly Delicous! Taco Stuffed with Paneer Tikka Butter Masala & Red Paprika', 105.00, '67d4304f9e604.jpg', 66, 'active'),
(25, 21, 'Loaded Nachos', 'It is a perfect combination of crunchy, creamy, spicy, and cheesy flavors, making it a favorite appetizer or snack', 130.00, '67d431d26beb3.jpg', 66, 'active'),
(26, 22, 'Jr Udta Punjab Cheese Burger', '\"Indulge in our value Punjabi burger layered with a cheesy patty, crisp lettuce, fresh onions, and a generous layer of rich cheesy sauce\"', 169.00, '67d432c572822.jpg', 67, 'active'),
(27, 22, 'Veg Churmur Pandey Burger', 'Crispy chips, Delicious veggie patty, onions, cheesy sauce in a finger licking Makhani sauce. Wah Churmur Pandey, wah. What A Star!', 98.00, '67d433b1cb47f.jpg', 67, 'active'),
(28, 22, 'Potato Crunch Burger', 'A crunchy potato patty crowned with lettuce, onions and Burger', 139.00, '67d43488c6723.jpg', 67, 'active'),
(29, 23, 'The Best Grilled Hot Dogs', 'A hot dog is a popular American fast-food dish consisting of a grilled or steamed sausage, typically made of beef or pork, served in a sliced soft bun', 90.00, '67d436ce46483.jpg', 67, 'active'),
(30, 23, 'Chicago-Style Hot Dog', 'A Chicago-style hot dog is a classic American street food originating from Chicago', 149.00, '67d43749be69c.jpg', 67, 'active'),
(31, 33, 'Chocolate Brownie', 'Our Best Seller Spongy brownie made with Love', 99.00, '67d4477ab5ac6.jpg', 68, 'active'),
(32, 32, 'Biscoff Cheesecake', 'Biscoff cheesecake: Indulgent blend of creamy cheesecake filling atop a crunchy Biscoff cookie crust, a heavenly delight for dessert lovers.', 349.00, '67d447e3374d1.png', 68, 'active'),
(33, 32, 'Blueberry Cheesecake', 'Blueberry cheesecake: Creamy perfection on a buttery graham cracker crust, crowned with a burst of vibrant blueberries, a delightful harmony of flavors.', 305.00, '67d44812028e0.jpg', 68, 'active'),
(34, 34, 'Cafe Latte', 'A latte (short for \"caffè latte\") is a popular coffee beverage made with espresso and steamed milk, topped with a light layer of milk foam.', 229.00, '67d448d4e50c7.jpg', 68, 'active'),
(35, 34, 'Honey Spiced Latte', 'A Honey Latte is a comforting espresso-based drink made with espresso, steamed milk, and honey for natural sweetness.', 299.00, '67d44923a5abb.jpg', 68, 'active'),
(36, 35, 'Black Tea (2 Cups)', 'The perfect refreshment for those who like their cuppa to be hot and black! Just pure taste and flavour of authentic Assamese tea leaves and sugar as per your preferences.', 130.00, '67d4498d3553d.jpg', 68, 'active'),
(37, 36, 'Panjabi Lassi [250 ml]', 'Matka Lassi is a traditional Indian yogurt-based drink served in an earthen clay cup (matka).', 99.00, '67d44a862bf07.jpg', 68, 'active'),
(38, 38, 'Mexican Cali Bowl', 'A Burrito Bowl is a Mexican-inspired dish featuring a deconstructed burrito served in a bowl instead of a tortilla.', 299.00, '67d44fd4df512.jpg', 69, 'active'),
(41, 9, 'Food1', 'TFYTRTYR', 657.00, '67eb9ea1d69d4.jpg', 74, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `food_category`
--

CREATE TABLE `food_category` (
  `c_id` int(97) NOT NULL,
  `c_name` varchar(97) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `food_category`
--

INSERT INTO `food_category` (`c_id`, `c_name`, `date`, `status`) VALUES
(12, 'South Indian', '2025-03-13 16:35:08', 'active'),
(13, 'Chinese', '2025-03-13 16:35:07', 'active'),
(14, 'Italian', '2025-03-13 16:35:05', 'active'),
(15, 'Mexican', '2025-03-13 16:35:04', 'active'),
(16, 'Fast Food', '2025-03-13 16:35:03', 'active'),
(17, 'Street Food', '2025-03-13 16:35:02', 'active'),
(18, 'Desserts', '2025-03-13 16:35:01', 'active'),
(19, 'Beverages', '2025-03-13 16:35:00', 'active'),
(20, 'Healthy Food', '2025-03-13 16:41:42', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `rating_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_ratings`
--

INSERT INTO `product_ratings` (`rating_id`, `order_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 43, 34, 1, 'some\r\n', '2025-02-03 09:32:55'),
(2, 44, 34, 3, '1234', '2025-02-03 09:33:44'),
(3, 44, 34, 3, '1234', '2025-02-03 09:34:39'),
(4, 44, 34, 2, 'some\r\n', '2025-02-03 09:35:16'),
(5, 141, 35, 5, 'some', '2025-03-06 15:06:51'),
(6, 141, 35, 5, 'some', '2025-03-06 15:07:24'),
(7, 141, 35, 5, 'some', '2025-03-06 15:07:42'),
(8, 556, 41, 3, 'some', '2025-04-04 17:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `remark`
--

CREATE TABLE `remark` (
  `id` int(97) NOT NULL,
  `status` varchar(97) NOT NULL,
  `remark` mediumtext NOT NULL,
  `remarkDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `o_id` int(97) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `remark`
--

INSERT INTO `remark` (`id`, `status`, `remark`, `remarkDate`, `o_id`) VALUES
(207, 'in process', '', '2025-04-05 02:38:05', 574);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `rs_id` int(97) NOT NULL,
  `res_name` varchar(97) NOT NULL,
  `email` varchar(97) NOT NULL,
  `phone` varchar(97) NOT NULL,
  `url` varchar(97) NOT NULL,
  `address` text NOT NULL,
  `image` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `password` varchar(97) NOT NULL,
  `open_or_close` enum('close','open') NOT NULL DEFAULT 'close'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`rs_id`, `res_name`, `email`, `phone`, `url`, `address`, `image`, `date`, `status`, `password`, `open_or_close`) VALUES
(62, 'The Lime Tree Restaurant', 'LimeTree@gmail.com', '0932828402', 'THeLimeTreeRes.com', 'The Lime Tree Restaurant, Lords Plaza Hotel, Opposite Linear Bus Stand, Gujarat 395003.', 'The Lime Tree Restaurant.jpg', '2025-04-05 05:54:53', 'active', '$2y$10$0cbQ7CNepDY3AX9vqBzSD.mcrWdnrk8.TogHY7MsJlz5oS0OqcBrK', 'open'),
(63, 'Taste of Bhagwati', 'TasteofBhagwati@gmail.com', '0743588900', 'TasteofBhagwati.com', 'Ring Rd, near Sakar Textile House, Sahara Darwaja, Moti Begumwadi, Begampura, Surat, Gujarat 395002', 'Taste of Bhagwati.jpg', '2025-04-05 05:54:53', 'active', '$2y$10$OsC/MumZ3kYrp2moHTG1eOhABmq7e5NP2ZkcEkliOTAa3kBbl4MMW', 'open'),
(64, 'Pavilion Restaurant', 'Pavilion@gmail.com', '0743588800', 'http://www.pavilionrestaurant.in/', 'VIP Road, behind CB Patel Health Club, Vesu, Surat, Gujarat 395007', 'Pavilion Restaurant.png', '2025-04-05 05:54:53', 'active', '$2y$10$RuATNf8eZ.9KqKe/toMVR.kbuMYA2u13WXYHCY3nYvu17B93V9tn2', 'open'),
(65, 'Spice Terrace Restaurant', 'spiceTerrace@gmail.com', '0261711700', 'https://SpiceTerrace.com', 'Marriott Hotel, Surat Marriot Rd, Umra Gam, Athwa, Surat, Gujarat 395007', 'Spice Terrace Restaurant.jpeg', '2025-04-05 05:54:53', 'active', '$2y$10$gXxJaKoVdoUdd7ez/.aAiegH4cRbIC85dNdFExNb3m4RPYHPdfAj2', 'open'),
(66, 'Kandeel', 'Kandeel@gmail.com', '0261661601', 'www.kandeel.com', '5RRR+6WJ Tex-Palazzo Hotel, Ring Rd, J.J. Market, Moti Begumwadi, New Textile Market, Surat, Gujarat 395002', 'Kandeel.jpg', '2025-04-05 05:54:53', 'active', '$2y$10$wzjDkpGs71.XeQByl715GO/1z5EpnhfgbqP.XdWW.xJYsUeRBii1.', 'open'),
(67, 'Hotel Amiras', 'Amiras@gmail.com', '0992526440', '', 'Galaxy Point, Surat - Kamrej Hwy, Bhagavan Nagar, Sarthana Jakat Naka, Sarthi Society, Nana Varachha, Surat, Gujarat 395006', 'Hotel Amiras.jpg', '2025-04-05 05:54:53', 'active', '$2y$10$xRr4qoR2jyStyuAZcW.f/OASri2ekMDxtfgCzrDF/wO6y4aTuidfC', 'open'),
(68, 'Sorriso Cafe', 'SorrisoCafe@gmail.com', '9104885040', 'www.sorriso.com', '307, Millennium, Business Hub, Bhagavan Nagar, Sarthana Jakat Naka, Sarthi Society, Nana Varachha, Surat, Gujarat 395006', 'Sorriso Cafe.jpg', '2025-04-05 05:54:53', 'active', '$2y$10$Adzh8dC4WTqRI.vtMPT9AOb3Jpg6VeAXp9KkzX6sW6OXTkz06x3sG', 'open'),
(69, 'Cafe Coastaz', 'cafecoastaz@gmail.com', '0987654321', 'www.cafecoastaz.com', 'Gajera Compound, beside Nadi Katho Restaurant, Mota Varachha, Surat, Gujarat 394101', 'cafeCoastaz.jpg', '2025-04-05 05:54:53', 'active', '$2y$10$MH11.uX5Im8rdEVbuqFQOeyFFj.Qr81kGBeJJdA300fOpwEDVRpsG', 'open'),
(74, 'test', 'test@gmail.com', '0987643211', 'https://www.Jkrestaurant.com', 'dcds', '20240425_213952.jpg', '2025-04-05 05:54:53', 'active', '$2y$10$uao6oNivVTeEyj2y0FRhR.W7QTfBLIQjeYle3it.O8dENJbAWDJIu', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_messages`
--

CREATE TABLE `restaurant_messages` (
  `id` int(11) NOT NULL,
  `res_message` text NOT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant_messages`
--

INSERT INTO `restaurant_messages` (`id`, `res_message`, `status`, `created_at`) VALUES
(3, 'something adds', 'active', '2025-02-26 12:12:04'),
(6, 'idli', 'active', '2025-03-05 01:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `subcat_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `subcat_name` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`subcat_id`, `c_id`, `subcat_name`, `status`) VALUES
(9, 12, 'Dosa', 'active'),
(10, 12, 'Idli', 'active'),
(11, 12, 'uttapam', 'active'),
(12, 13, 'Fried Rice', 'active'),
(13, 13, 'Manchurian', 'active'),
(14, 13, 'Rolls', 'active'),
(15, 13, 'Noodles', 'active'),
(16, 14, 'Pasta', 'active'),
(17, 14, 'Pizza', 'active'),
(18, 14, 'Risotto', 'active'),
(19, 15, 'Tacos', 'active'),
(20, 15, 'Burritos', 'active'),
(21, 15, 'Nachos', 'active'),
(22, 16, 'Burgers', 'active'),
(23, 16, 'Hot Dogs', 'active'),
(24, 16, 'Sandwiches', 'active'),
(25, 17, 'Pani Puri', 'active'),
(26, 17, 'Samosa', 'active'),
(27, 17, 'Pav Bhaji', 'active'),
(28, 17, 'Kathi Rolls', 'active'),
(29, 17, 'Momos', 'active'),
(30, 18, 'Ice Cream', 'active'),
(31, 18, 'Gulab Jamun', 'active'),
(32, 18, 'Cheesecake', 'active'),
(33, 18, 'Brownies', 'active'),
(34, 19, 'Coffee', 'active'),
(35, 19, 'Tea', 'active'),
(36, 19, 'Lassi', 'active'),
(37, 19, 'Mojito', 'active'),
(38, 20, 'Salads', 'active'),
(39, 20, 'Quinoa Bowls', 'active'),
(40, 20, 'Smoothie Bowls', 'active'),
(41, 20, 'Oats', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(97) NOT NULL,
  `username` varchar(97) NOT NULL,
  `f_name` varchar(97) NOT NULL,
  `l_name` varchar(97) NOT NULL,
  `email` varchar(97) NOT NULL,
  `phone` varchar(97) NOT NULL,
  `password` varchar(97) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `address` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `username`, `f_name`, `l_name`, `email`, `phone`, `password`, `reset_token`, `address`, `date`, `status`, `reset_token_expires`) VALUES
(34, 'jhone Dee', 'jhone', 'dee', 'jhone@gmail.com', '9998887776', 'e10adc3949ba59abbe56e057f20f883', '26505047b92b4f3c5816049115bb2f042ad5355cfc0754f25f8b909e1d960d03', '123, some soc. katargam, surat.', '2025-03-07 07:49:14', 'active', NULL),
(41, 'rahul_kumar', 'rahul', 'kumar', 'rahul.kumar@example.in', '9876543210', 'e10adc3949ba59abbe56e057f20f883e', NULL, '123 MG Road, surat, gujrat, 395006', '2025-03-14 08:35:48', 'active', NULL),
(42, 'rootpriya_sharma', 'priya', 'sharma', 'priya.sharma@example.in', '9876543211', 'e10adc3949ba59abbe56e057f20f883e', NULL, '45, Sector 18, surat, gujrat, 395006', '2025-03-14 08:36:51', 'active', NULL),
(43, 'amit_verma', 'amit', 'verma', 'amit.verma@ex.in', '9765432109', 'e10adc3949ba59abbe56e057f20f883e', NULL, '78, Chandra Nagar, surat, gujrat, 395006', '2025-03-14 08:37:54', 'active', NULL),
(44, 'neha_gupta', 'neha', 'gupta', 'neha.gupta@gmail.com', '9901234567', 'e10adc3949ba59abbe56e057f20f883e', NULL, '55, Kanchan Bagh, surat, gujrat.', '2025-03-14 08:38:48', 'active', NULL),
(45, 'arun_patel', 'arun', 'patel', 'arun.patel@example.in', '7878787878', 'e10adc3949ba59abbe56e057f20f883e', NULL, '123, Vraj Society, Surat, Gujarat, 395006', '2025-03-14 08:39:29', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_orders`
--

CREATE TABLE `users_orders` (
  `o_id` int(97) NOT NULL,
  `u_id` int(97) NOT NULL,
  `title` varchar(97) NOT NULL,
  `quantity` int(97) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `d_id` int(11) NOT NULL,
  `order_status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `rs_id` int(11) NOT NULL,
  `d_boy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users_orders`
--

INSERT INTO `users_orders` (`o_id`, `u_id`, `title`, `quantity`, `price`, `date`, `d_id`, `order_status`, `rs_id`, `d_boy`) VALUES
(573, 1, 'Cafe Latte', 1, 229.00, '2025-04-05 05:56:24', 34, 'pending', 68, NULL),
(574, 42, 'Chocolate Brownie', 1, 99.00, '2025-04-05 06:00:55', 31, 'accepted', 68, NULL),
(575, 42, 'Blueberry Cheesecake', 1, 305.00, '2025-04-05 06:06:38', 33, 'accepted', 68, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adm_id`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`);

--
-- Indexes for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`d_id`),
  ADD KEY `fk_subcat_id` (`subcat_id`),
  ADD KEY `fk_rs_id` (`rs_id`);

--
-- Indexes for table `food_category`
--
ALTER TABLE `food_category`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `remark`
--
ALTER TABLE `remark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`rs_id`);

--
-- Indexes for table `restaurant_messages`
--
ALTER TABLE `restaurant_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`subcat_id`),
  ADD KEY `fk_category` (`c_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `reset_token` (`reset_token`);

--
-- Indexes for table `users_orders`
--
ALTER TABLE `users_orders`
  ADD PRIMARY KEY (`o_id`),
  ADD UNIQUE KEY `unique_order` (`u_id`,`d_id`),
  ADD KEY `idx_d_id` (`d_id`),
  ADD KEY `fk_delivery_boy` (`d_boy`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adm_id` int(97) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dishes`
--
ALTER TABLE `dishes`
  MODIFY `d_id` int(91) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `food_category`
--
ALTER TABLE `food_category`
  MODIFY `c_id` int(97) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `remark`
--
ALTER TABLE `remark`
  MODIFY `id` int(97) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `rs_id` int(97) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `restaurant_messages`
--
ALTER TABLE `restaurant_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `subcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(97) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users_orders`
--
ALTER TABLE `users_orders`
  MODIFY `o_id` int(97) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=576;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `fk_rs_id` FOREIGN KEY (`rs_id`) REFERENCES `restaurant` (`rs_id`),
  ADD CONSTRAINT `fk_subcat_id` FOREIGN KEY (`subcat_id`) REFERENCES `subcategories` (`subcat_id`);

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `users_orders` (`o_id`),
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`c_id`) REFERENCES `food_category` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `food_category` (`c_id`);

--
-- Constraints for table `users_orders`
--
ALTER TABLE `users_orders`
  ADD CONSTRAINT `fk_delivery_boy` FOREIGN KEY (`d_boy`) REFERENCES `delivery_boys` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
