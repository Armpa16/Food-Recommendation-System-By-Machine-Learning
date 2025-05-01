-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 12:38 PM
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
-- Database: `food_recommend_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `food_history`
--

CREATE TABLE `food_history` (
  `history_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `his_date` date NOT NULL,
  `meal_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `food_history`
--

INSERT INTO `food_history` (`history_id`, `users_id`, `food_id`, `his_date`, `meal_type`) VALUES
(698, 1, 52, '2025-03-30', 'breakfast'),
(699, 1, 24, '2025-03-30', 'breakfast'),
(700, 1, 72, '2025-03-30', 'breakfast'),
(701, 1, 158, '2025-03-30', 'breakfast'),
(702, 1, 58, '2025-03-30', 'lunch'),
(703, 1, 18, '2025-03-30', 'lunch'),
(704, 1, 167, '2025-03-30', 'lunch'),
(705, 1, 130, '2025-03-30', 'lunch'),
(706, 1, 158, '2025-03-30', 'lunch'),
(707, 1, 58, '2025-03-30', 'dinner'),
(708, 1, 22, '2025-03-30', 'dinner'),
(709, 1, 72, '2025-03-30', 'dinner'),
(710, 1, 34, '2025-03-30', 'dinner'),
(711, 1, 58, '2025-03-31', 'breakfast'),
(712, 1, 16, '2025-03-31', 'breakfast'),
(713, 1, 124, '2025-03-31', 'breakfast'),
(714, 1, 34, '2025-03-31', 'breakfast'),
(715, 1, 50, '2025-03-31', 'lunch'),
(716, 1, 20, '2025-03-31', 'lunch'),
(717, 1, 39, '2025-03-31', 'lunch'),
(718, 1, 130, '2025-03-31', 'lunch'),
(719, 1, 35, '2025-03-31', 'lunch'),
(720, 1, 58, '2025-03-31', 'dinner'),
(721, 1, 8, '2025-03-31', 'dinner'),
(722, 1, 84, '2025-03-31', 'dinner'),
(723, 1, 32, '2025-03-31', 'dinner'),
(776, 1, 51, '2025-04-02', 'breakfast'),
(777, 1, 32, '2025-04-02', 'breakfast'),
(778, 1, 130, '2025-04-02', 'breakfast'),
(779, 1, 37, '2025-04-02', 'breakfast'),
(780, 1, 54, '2025-04-02', 'lunch'),
(781, 1, 8, '2025-04-02', 'lunch'),
(782, 1, 70, '2025-04-02', 'lunch'),
(783, 1, 35, '2025-04-02', 'lunch'),
(784, 1, 167, '2025-04-02', 'lunch'),
(785, 1, 50, '2025-04-02', 'dinner'),
(786, 1, 21, '2025-04-02', 'dinner'),
(787, 1, 126, '2025-04-02', 'dinner'),
(788, 1, 52, '2025-04-02', 'dinner'),
(789, 1, 47, '2025-04-02', 'dinner'),
(803, 1, 49, '2025-04-03', 'breakfast'),
(804, 1, 19, '2025-04-03', 'breakfast'),
(805, 1, 121, '2025-04-03', 'breakfast'),
(806, 1, 26, '2025-04-03', 'breakfast'),
(807, 1, 70, '2025-04-03', 'breakfast'),
(808, 1, 52, '2025-04-03', 'lunch'),
(809, 1, 13, '2025-04-03', 'lunch'),
(810, 1, 39, '2025-04-03', 'lunch'),
(811, 1, 127, '2025-04-03', 'lunch'),
(812, 1, 32, '2025-04-03', 'lunch'),
(813, 1, 153, '2025-04-03', 'lunch'),
(814, 1, 58, '2025-04-03', 'dinner'),
(815, 1, 3, '2025-04-03', 'dinner'),
(816, 1, 126, '2025-04-03', 'dinner'),
(817, 1, 35, '2025-04-03', 'dinner'),
(856, 1, 47, '2025-04-01', 'breakfast'),
(857, 1, 22, '2025-04-01', 'breakfast'),
(858, 1, 130, '2025-04-01', 'breakfast'),
(859, 1, 26, '2025-04-01', 'breakfast'),
(860, 1, 50, '2025-04-01', 'lunch'),
(861, 1, 16, '2025-04-01', 'lunch'),
(862, 1, 167, '2025-04-01', 'lunch'),
(863, 1, 130, '2025-04-01', 'lunch'),
(864, 1, 47, '2025-04-01', 'dinner'),
(865, 1, 8, '2025-04-01', 'dinner'),
(866, 1, 127, '2025-04-01', 'dinner'),
(867, 1, 32, '2025-04-01', 'dinner'),
(879, 1, 58, '2025-04-05', 'breakfast'),
(880, 1, 18, '2025-04-05', 'breakfast'),
(881, 1, 70, '2025-04-05', 'breakfast'),
(882, 1, 26, '2025-04-05', 'breakfast'),
(883, 1, 50, '2025-04-05', 'lunch'),
(884, 1, 5, '2025-04-05', 'lunch'),
(885, 1, 87, '2025-04-05', 'lunch'),
(886, 1, 32, '2025-04-05', 'lunch'),
(887, 1, 153, '2025-04-05', 'lunch'),
(888, 1, 59, '2025-04-05', 'dinner'),
(889, 1, 122, '2025-04-05', 'dinner'),
(890, 1, 37, '2025-04-05', 'dinner'),
(891, 1, 52, '2025-04-19', 'breakfast'),
(892, 1, 21, '2025-04-19', 'breakfast'),
(893, 1, 70, '2025-04-19', 'breakfast'),
(894, 1, 37, '2025-04-19', 'breakfast'),
(895, 1, 49, '2025-04-19', 'lunch'),
(896, 1, 20, '2025-04-19', 'lunch'),
(897, 1, 121, '2025-04-19', 'lunch'),
(898, 1, 35, '2025-04-19', 'lunch'),
(899, 1, 53, '2025-04-19', 'dinner'),
(900, 1, 3, '2025-04-19', 'dinner'),
(901, 1, 126, '2025-04-19', 'dinner'),
(902, 1, 154, '2025-04-19', 'dinner');

-- --------------------------------------------------------

--
-- Table structure for table `food_menu`
--

CREATE TABLE `food_menu` (
  `food_id` int(11) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `calories` decimal(6,2) NOT NULL,
  `protein` decimal(6,2) NOT NULL,
  `carbohydrate` decimal(6,2) NOT NULL,
  `sugar` decimal(6,2) NOT NULL,
  `fat` decimal(6,2) NOT NULL,
  `sodium` decimal(6,2) NOT NULL,
  `food_type` enum('สมดุล','น้ำตาลต่ำ','โซเดียมต่ำ','ไขมันต่ำ') NOT NULL,
  `image_url` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `food_menu`
--

INSERT INTO `food_menu` (`food_id`, `food_name`, `amount`, `category`, `calories`, `protein`, `carbohydrate`, `sugar`, `fat`, `sodium`, `food_type`, `image_url`) VALUES
(1, 'กล้วยไข่', '100 g', 'ผลไม้', 107.00, 1.35, 24.30, 21.50, 0.11, 22.00, 'สมดุล', 'https://static.hd.co.th/768x390/webp/system/blog_articles/main_hero_images/000/003/801/original/iStock-621928004_%281%29.jpg'),
(2, 'กล้วยน้ำว้า', '100 g', 'ผลไม้', 118.00, 0.78, 27.18, 18.47, 0.15, 4.00, 'สมดุล', 'https://s.isanook.com/he/0/ud/1/9753/banana.jpg?ip/crop/w1200h700/q80/webp'),
(3, 'แก้วมังกร', '100 g', 'ผลไม้', 56.00, 1.30, 10.30, 9.30, 0.70, 4.00, 'น้ำตาลต่ำ', 'https://s.isanook.com/he/0/ud/2/13057/13057-thumbnail.jpg'),
(4, 'ขนุน', '100 g', 'ผลไม้', 101.00, 1.55, 21.73, 19.17, 0.42, 26.00, 'สมดุล', 'https://s.isanook.com/wo/0/ud/36/183361/n.jpg'),
(5, 'แคนตาลูป', '100 g', 'ผลไม้', 28.00, 0.50, 5.70, 10.00, 0.10, 6.00, 'น้ำตาลต่ำ', 'https://res.cloudinary.com/dk0z4ums3/image/upload/v1661307138/attached_image_th/wholeandslicedofjapanesemelonshoneymelonorcantaloupecucumis.jpg'),
(6, 'ฝรั่ง', '100 g', 'ผลไม้', 68.00, 2.60, 14.00, 9.00, 1.00, 2.00, 'น้ำตาลต่ำ', 'https://static.hd.co.th/system/blog_articles/main_hero_images/000/005/601/original/iStock-490663920.jpg'),
(7, 'เงาะ', '100 g', 'ผลไม้', 77.00, 0.97, 17.92, 0.00, 0.14, 14.00, 'สมดุล', 'https://res.cloudinary.com/dk0z4ums3/image/upload/v1662606494/attached_image_th/freshandriperambutansweettropicalfruitpeeledrambutanwith.jpg'),
(8, 'ชมพู่', '100 g', 'ผลไม้', 31.00, 0.46, 6.74, 0.00, 0.04, 66.00, 'น้ำตาลต่ำ', 'https://s359.kapook.com/pagebuilder/65ee2a74-5789-4344-a00a-d40e4085b8bb.jpg'),
(9, 'แตงโม', '100 g', 'ผลไม้', 37.00, 0.81, 8.07, 8.15, 0.07, 4.00, 'สมดุล', 'https://s.isanook.com/he/0/ud/6/32261/watermelon.jpg'),
(10, 'ทุเรียน', '100 g', 'ผลไม้', 140.00, 2.26, 23.81, 14.75, 3.34, 11.00, 'สมดุล', 'https://img.kapook.com/u/2021/sireeporn/Health-11/A04.jpg'),
(11, 'น้อยหน่า', '100 g', 'ผลไม้', 100.00, 1.32, 21.84, 17.23, 0.20, 4.00, 'สมดุล', 'https://static5-th.orstatic.com/userphoto/Article/0/35/000MH06CF067FF970A8E92j.jpg'),
(12, 'มังคุด', '100 g', 'ผลไม้', 77.00, 0.50, 17.70, 17.34, 0.06, 2.00, 'สมดุล', 'https://res.cloudinary.com/dk0z4ums3/image/upload/v1666863567/attached_image_th/2126-%25e0%25b8%25a1%25e0%25b8%25b1%25e0%25b8%2587%25e0%25b8%2584%25e0%25b8%25b8%25e0%25b8%2594-rs.jpg'),
(13, 'ส้ม', '100 g', 'ผลไม้', 46.00, 0.70, 9.04, 10.67, 0.12, 3.00, 'น้ำตาลต่ำ', 'https://static.cdntap.com/tap-assets-prod/wp-content/uploads/sites/25/2020/11/%E0%B8%9C%E0%B8%A5%E0%B9%84%E0%B8%A1%E0%B9%89%E0%B8%A1%E0%B8%B2%E0%B8%81%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B9%82%E0%B8%A2%E0%B8%8A%E0%B8%99%E0%B9%8C-1.jpg'),
(14, 'แอปเปิ้ล', '100 g', 'ผลไม้', 55.00, 0.20, 12.00, 10.09, 0.14, 58.00, 'น้ำตาลต่ำ', 'https://inwfile.com/s-dz/68gnuf.jpg'),
(15, 'องุ่น', '100 g', 'ผลไม้', 51.00, 0.49, 10.64, 11.17, 0.13, 6.00, 'สมดุล', 'https://s.isanook.com/he/0/ud/7/35753/grapes.jpg?ip/resize/w850/q80/jpg'),
(16, 'สตรอเบอรี่', '100 g', 'ผลไม้', 34.00, 0.80, 7.60, 3.82, 0.50, 1.00, 'น้ำตาลต่ำ', 'https://static.thairath.co.th/media/dFQROr7oWzulq5Fa5LTj7gwLriqhfMCs90UFGg15sPTl7uYDYACEJ28dp4ekTyu7Rn1.webp'),
(17, 'กีวี', '100 g', 'ผลไม้', 47.00, 0.90, 9.50, 0.00, 0.60, 4.00, 'น้ำตาลต่ำ', 'https://static.hd.co.th/system/blog_articles/main_hero_images/000/004/081/original/iStock-636969172_%281%29.jpg'),
(18, 'เชอรี่ ', '100 g', 'ผลไม้', 31.00, 0.40, 7.00, 7.36, 0.30, 7.00, 'น้ำตาลต่ำ', 'https://www.disthai.com/images/content/original-1662521246267.jpg'),
(19, 'ส้มโอ', '100 g', 'ผลไม้', 45.00, 1.00, 9.90, 11.34, 0.20, 33.00, 'น้ำตาลต่ำ', 'https://longdan.co.uk/cdn/shop/articles/rtyfh.png?v=1700554692&width=1024'),
(20, 'ส้มเขียวหวาน', '100 g', 'ผลไม้', 45.00, 1.00, 9.90, 11.34, 0.20, 33.00, 'น้ำตาลต่ำ', 'https://www.disthai.com/images/editor/%E0%B8%AA%E0%B9%89%E0%B8%A1%E0%B9%80%E0%B8%82%E0%B8%B5%E0%B8%A2%E0%B8%A7%E0%B8%AB%E0%B8%A7%E0%B8%B2%E0%B8%994.jpg'),
(21, 'สาลี่', '100 g', 'ผลไม้', 44.00, 0.40, 11.40, 9.64, 0.20, 15.00, 'น้ำตาลต่ำ', 'https://s.isanook.com/wo/0/ud/31/158115/1233377.jpg'),
(22, 'สับปะรด', '100 g', 'ผลไม้', 61.00, 0.40, 14.70, 11.11, 0.10, 5.00, 'น้ำตาลต่ำ', 'https://www.nsm.or.th/nsm/sites/default/files/2021-12/shutterstock_1068040016-1.jpg'),
(23, 'แตงไทย', '100 g', 'ผลไม้', 13.00, 0.20, 3.20, 2.48, 0.00, 0.00, 'น้ำตาลต่ำ', 'https://inandcoseeds.com/wp-content/uploads/2020/08/Chomae.jpg'),
(24, 'พุทราไทย', '100 g', 'ผลไม้', 89.00, 1.70, 18.70, 0.00, 0.80, 3.00, 'น้ำตาลต่ำ', 'https://www.matichonweekly.com/wp-content/uploads/2021/03/A-heap-of-jujube-fruits-337167-pixahive.webp'),
(25, 'อะโวคาโด้', '100 g', 'ผลไม้', 160.00, 2.00, 8.50, 0.70, 14.70, 0.00, 'ไขมันต่ำ', 'https://s.isanook.com/he/0/ud/5/28925/avocado.jpg?ip/resize/w850/q80/jpg'),
(26, 'กาแฟดำ', '1 ถ้วย / 180 ml', 'เครื่องดื่ม', 10.00, 0.43, 1.66, 0.00, 0.19, 0.00, 'น้ำตาลต่ำ', 'https://static.thairath.co.th/media/dFQROr7oWzulq5Fa4L4RfyWNORJkKtutYBvVlvlXOoH3W0P3u59p0iCknWsq5ZJmjQ2.jpg'),
(27, 'น้ำเก็กฮวย', '1 แก้ว / 200 ml', 'เครื่องดื่ม', 72.00, 0.00, 18.00, 15.00, 0.00, 10.00, 'สมดุล', 'https://img.kapook.com/u/2018/wanchalerm/Health_01_61/Chrysanthemum_5.jpg'),
(28, 'น้ำมะนาว', '1 แก้ว / 200 ml', 'เครื่องดื่ม', 60.00, 1.00, 20.00, 1.70, 0.20, 4.80, 'สมดุล', 'https://www.calforlife.com/image/food/Lemon-juice.jpg'),
(29, 'น้ำเสาวรส', '1 แก้ว / 200 ml', 'เครื่องดื่ม', 110.00, 4.00, 19.00, 17.00, 1.50, 40.00, 'สมดุล', 'https://food.mthai.com/app/uploads/2018/11/passion-fruit.jpg'),
(30, 'น้ำองุ่น', '1 แก้ว / 200 ml', 'เครื่องดื่ม', 142.00, 0.00, 36.00, 14.00, 0.00, 22.00, 'สมดุล', 'https://cdn1.productnation.co/stg/sites/6/5d39544490e99.jpeg'),
(31, 'น้ำส้ม', '1 แก้ว / 200 ml', 'เครื่องดื่ม', 111.00, 1.70, 25.00, 8.40, 0.50, 2.50, 'สมดุล', 'https://s359.kapook.com/r/600/auto/pagebuilder/3c09c1ab-5e43-49db-99af-a985a2d8c61b.jpg'),
(32, 'น้ำมะพร้าว', '1 แก้ว / 200 ml', 'เครื่องดื่ม', 45.00, 1.70, 9.00, 2.60, 0.50, 252.00, 'น้ำตาลต่ำ', 'https://s.isanook.com/he/0/ud/5/27321/coconut-juice.jpg'),
(33, 'น้ำมะเขือเทศ', '1 แก้ว / 200 ml', 'เครื่องดื่ม', 50.00, 2.00, 10.00, 7.00, 0.00, 135.00, 'ไขมันต่ำ', 'https://s359.kapook.com/pagebuilder/f8047767-e547-4e24-b734-c0397b7aeef0.jpg'),
(34, 'น้ำนมถั่วเหลือง แลคตาซอย สูตรไม่เติมน้ำตาลทราย 300 มล.', '1 กล่อง / 300 ml', 'เครื่องดื่ม', 140.00, 9.00, 6.00, 2.00, 9.00, 120.00, 'น้ำตาลต่ำ', 'https://sentosakhonkaen.com/wp-content/uploads/2023/03/%E0%B9%81%E0%B8%A5%E0%B8%84%E0%B8%95%E0%B8%B2%E0%B8%8B%E0%B8%AD%E0%B8%A2-%E0%B8%88%E0%B8%B7%E0%B8%94-200-p6.jpg'),
(35, 'น้ำนมถั่วเหลือง แลคตาซอย สูตรแคลเซียมสูง 300 มล.', '1 กล่อง / 300 ml', 'เครื่องดื่ม', 170.00, 8.00, 18.00, 14.00, 7.00, 105.00, 'น้ำตาลต่ำ', 'https://down-th.img.susercontent.com/file/th-11134208-7qukz-ljapx6emc1ht08'),
(36, 'น้ำนมถั่วเหลือง แลคตาซอย รสหวานออริจินัล', '1 กล่อง / 300 ml', 'เครื่องดื่ม', 220.00, 8.00, 24.00, 22.00, 10.00, 150.00, 'สมดุล', 'https://media.allonline.7eleven.co.th/pdmain/356695-02-soy-milk-lactasoy.jpg'),
(37, 'นมถั่วเหลืองผสมงาดำ ดีน่า', '1 กล่อง / 180 ml', 'เครื่องดื่ม', 100.00, 6.00, 14.00, 4.00, 2.50, 80.00, 'น้ำตาลต่ำ', 'https://assets.tops.co.th/DNA-DNAUHTSoyMilkBlackSesame230mlPack3-8853002080103-1'),
(38, 'แซนวิช, ไก่', '1 ชิ้น / 100 g', 'ฟาสต์ฟู้ด', 397.00, 18.00, 40.00, 38.00, 19.00, 372.00, 'สมดุล', 'https://s359.kapook.com//pagebuilder/3a38ae98-fe34-4d5c-8866-f8495ea636df.jpg'),
(39, 'แซนวิช, ปลา', '1 ชิ้น / 100 g', 'ฟาสต์ฟู้ด', 294.00, 10.46, 27.47, 0.00, 15.84, 0.00, 'น้ำตาลต่ำ', 'https://img.kapook.com/u/surauch/movie2/tuna-sandwich.jpg'),
(40, 'ฮอทดอก', '1 ชิ้น / 50 g', 'ฟาสต์ฟู้ด', 100.00, 3.00, 13.00, 1.00, 4.00, 400.00, 'สมดุล', 'https://img.kapook.com/u/pirawan/Cooking1/hotdog%20history.jpg'),
(41, 'แฮมเบอร์เกอร์, หมู', '1  ชิ้น / 108 g', 'ฟาสต์ฟู้ด', 266.00, 13.00, 31.00, 0.00, 10.00, 442.00, 'สมดุล', 'https://storage.googleapis.com/cpbs-bucket-cms-uat/web/recipe/b667ed15de5f5f50866aefd0d_20240827_122153.png'),
(42, 'แฮมเบอร์เกอร์, ไก่', '1  ชิ้น / 90 g', 'ฟาสต์ฟู้ด', 294.00, 13.29, 23.07, 0.00, 15.99, 515.00, 'สมดุล', 'https://st2.depositphotos.com/1326558/7501/i/450/depositphotos_75015245-stock-photo-sandwich-with-chicken-burger-and.jpg'),
(43, 'ขนมชั้น', '100 g', 'ขนม', 99.00, 0.00, 19.00, 12.00, 2.10, 29.00, 'โซเดียมต่ำ', 'https://agarmermaid.com/wp-content/uploads/2022/11/shutterstock_1623583255-1-1024x683.jpg'),
(44, 'ขนมปัง, หน้าเนย', '100 g', 'ขนม', 310.00, 7.00, 30.00, 2.00, 18.00, 420.00, 'สมดุล', 'https://i0.wp.com/ligorhomebakery.com/wp-content/uploads/2020/12/ligor63-1207-105.jpg?fit=600%2C600&ssl=1'),
(45, 'ข้าวต้มมัด', '100 g', 'ขนม', 183.00, 2.50, 38.00, 15.00, 2.30, 220.00, 'ไขมันต่ำ', 'https://i.pinimg.com/550x/aa/2b/94/aa2b9411ab52abd5eae5d153fa907484.jpg'),
(46, 'ครัวซอง', '100 g', 'ขนม', 406.00, 8.00, 46.00, 11.00, 21.00, 467.00, 'สมดุล', 'https://www.finallymebakery.com/wp-content/uploads/2024/06/%E0%B8%84%E0%B8%A3%E0%B8%B1%E0%B8%A7%E0%B8%8B%E0%B8%AD%E0%B8%87%E0%B8%95%E0%B9%8C%E0%B8%82%E0%B8%99%E0%B8%A1%E0%B8%A2%E0%B8%AD%E0%B8%94%E0%B8%AE%E0%B8%B4%E0%B8%95%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%97%E0%B8%B8%E0%B8%81%E0%B8%84%E0%B8%99%E0%B8%95%E0%B8%B4%E0%B8%94%E0%B9%83%E0%B8%88.jpg'),
(47, 'ทองหยอด ', '100 g', 'ขนม', 305.00, 3.53, 56.90, 56.58, 6.92, 39.00, 'โซเดียมต่ำ', 'https://www.pholfoodmafia.com/wp-content/uploads/2018/08/7%E0%B8%97%E0%B8%AD%E0%B8%87%E0%B8%AB%E0%B8%A2%E0%B8%AD%E0%B8%94-big.jpg'),
(48, 'ทองหยิบ', ' 100 g', 'ขนม', 395.00, 7.90, 45.90, 54.22, 19.70, 0.00, 'โซเดียมต่ำ', 'https://www.pholfoodmafia.com/wp-content/uploads/2018/08/6%E0%B8%97%E0%B8%AD%E0%B8%87%E0%B8%AB%E0%B8%A2%E0%B8%B4%E0%B8%9A-big.jpg'),
(49, 'ฝอยทอง', ' 100 g', 'ขนม', 419.00, 12.71, 40.67, 41.94, 22.51, 64.00, 'โซเดียมต่ำ', 'https://api2.krua.co/wp-content/uploads/2020/06/RK0132_ImageBanner_1140x507-01.jpg'),
(50, 'ลอดช่องไทย น้ำกะทิ', '100 g', 'ขนม', 108.00, 0.72, 18.70, 12.30, 3.04, 62.00, 'โซเดียมต่ำ', 'https://f.ptcdn.info/287/032/000/1433919781-IMG3046-o.jpg'),
(51, 'ขนมเปียกปูน', '100 g', 'ขนม', 160.00, 1.10, 35.46, 19.78, 1.62, 92.00, 'โซเดียมต่ำ', 'https://static.cdntap.com/tap-assets-prod/wp-content/uploads/sites/25/2022/03/coconut-sweet-pudding-lead.jpg'),
(52, 'ถั่วเขียวต้มน้ำตาล', '100 g', 'ขนม', 213.00, 7.00, 46.20, 29.00, 0.40, 8.00, 'โซเดียมต่ำ', 'https://fit-d.com/uploads/food/8dfd337afe66f7d76e181f2edc36e570.jpg'),
(53, 'เต้าทึงน้ำลำใย', '100 g', 'ขนม', 130.00, 4.50, 31.50, 21.00, 0.50, 14.00, 'โซเดียมต่ำ', 'https://static.cdntap.com/tap-assets-prod/wp-content/uploads/sites/25/2022/04/Cold-assorted-beans-in-syrup.jpg'),
(54, 'ธัญพืชอบกรอบ', '100 g', 'ขนม', 136.00, 5.00, 20.00, 5.00, 4.00, 120.00, 'โซเดียมต่ำ', 'https://th-test-11.slatic.net/p/2a76d47320c4f1921f7beb45ea0bfb6d.jpg'),
(55, 'คุ๊กกี้ธัญพืช', '100 g', 'ขนม', 501.00, 6.00, 65.00, 15.00, 24.00, 524.00, 'ไขมันต่ำ', 'https://img-global.cpcdn.com/recipes/40f66574f337884a/680x482cq70/%E0%B8%A3%E0%B8%9B-%E0%B8%AB%E0%B8%A5%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%87-%E0%B8%AA%E0%B8%95%E0%B8%A3-%E0%B8%84%E0%B8%81%E0%B8%81%E0%B8%98%E0%B8%8D%E0%B8%9E%E0%B8%8A-%E0%B9%84%E0%B8%A3%E0%B9%81%E0%B8%9B%E0%B8%87.jpg'),
(56, 'ขนมทองม้วน', '100 g', 'ขนม', 135.00, 2.00, 25.00, 13.00, 3.00, 164.00, 'ไขมันต่ำ', 'https://s359.kapook.com/pagebuilder/de9e1f99-7bb1-495c-9e79-31f721b0b390.jpg'),
(57, 'ขนมเบื้องไส้หวาน', '100 g', 'ขนม', 415.00, 14.40, 82.40, 8.40, 1.90, 59.20, 'ไขมันต่ำ', 'https://www.calforlife.com/image/food/Sweet-Cream-Pancake.jpg'),
(58, 'ขนมปังไส้สับปะรด', '100 g', 'ขนม', 131.00, 2.00, 24.00, 15.00, 3.00, 80.00, 'โซเดียมต่ำ', 'https://m.media-amazon.com/images/I/41aSkPaBZ3L._AC_UF894,1000_QL80_.jpg'),
(59, 'วุ้นกะทิ, ใบเตย', '100 g', 'ขนม', 133.00, 0.60, 20.50, 9.30, 7.80, 0.00, 'โซเดียมต่ำ', 'https://shopee.co.th/blog/wp-content/uploads/2023/08/Shopee-Blog-%E0%B8%A7%E0%B8%B8%E0%B9%89%E0%B8%99%E0%B8%81%E0%B8%B0%E0%B8%97%E0%B8%B4%E0%B9%83%E0%B8%9A%E0%B9%80%E0%B8%95%E0%B8%A2.jpg'),
(60, 'ข้าวเหนียวหน้าสังขยา', '100 g', 'ขนม', 199.00, 8.40, 26.20, 4.70, 6.30, 77.20, 'สมดุล', 'https://img.kapook.com/u/2022/wanwanat/599333402.jpg'),
(61, 'โดนัท, โรยน้ำตาล', ' 100 g', 'ขนม', 443.00, 5.95, 59.48, 30.42, 20.17, 0.00, 'สมดุล', 'https://api2.krua.co/wp-content/uploads/2020/09/RB0165_Gallery_-04.jpg'),
(62, 'กระเพาะปลา ', '1 ถ้วย / 250 g', 'อาหารจานหลัก', 240.00, 8.00, 49.00, 2.00, 15.00, 1230.00, 'สมดุล', 'https://th-test-11.slatic.net/p/0f896afc54a3f0c67237d859a8b06d92.jpg'),
(63, 'กระเพาะปลา, เส้นหมี่', '1 ถ้วย / 250 g', 'อาหารจานหลัก', 220.00, 7.00, 45.00, 0.00, 2.00, 1150.00, 'ไขมันต่ำ', 'https://img.wongnai.com/p/1920x0/2017/11/26/5cee6af64b484af795e641e0c2e57878.jpg'),
(64, 'ก๋วยจั๊บณวน', '1 ชาม / 250 g', 'อาหารจานหลัก', 240.00, 5.00, 46.00, 4.00, 4.00, 1550.00, 'ไขมันต่ำ', 'https://img.wongnai.com/p/1920x0/2017/06/15/fd84aa9604e44d63b36275b9b3320771.jpg'),
(65, 'ก๋วยเตี๋ยวคั่วไก่', '1 ชาม / 250 g', 'อาหารจานหลัก', 429.00, 21.00, 5.00, 0.00, 33.00, 941.00, 'สมดุล', 'https://www.unileverfoodsolutions.co.th/dam/global-ufs/mcos/SEA/calcmenu/recipes/TH-recipes/chicken-&-other-poultry-dishes/%E0%B8%81%E0%B9%8B%E0%B8%A7%E0%B8%A2%E0%B9%80%E0%B8%95%E0%B8%B5%E0%B9%8B%E0%B8%A2%E0%B8%A7%E0%B8%84%E0%B8%B1%E0%B9%88%E0%B8%A7%E0%B9%84%E0%B8%81%E0%B9%88/%E0%B8%81%E0%B9%8B%E0%B8%A7%E0%B8%A2%E0%B9%80%E0%B8%95%E0%B8%B5%E0%B9%8B%E0%B8%A2%E0%B8%A7%E0%B8%84%E0%B8%B1%E0%B9%88%E0%B8%A7%E0%B9%84%E0%B8%81%E0%B9%88_header.jpg'),
(66, 'ก๋วยเตี๋ยวต้มยำหมู', '1 ชาม / 250 g', 'อาหารจานหลัก', 506.00, 23.00, 54.00, 0.00, 22.00, 2507.00, 'สมดุล', 'https://p16-va.lemon8cdn.com/tos-alisg-v-a3e477-sg/oE4PvAAfmAB2wZ9ICrFEg7D29CEtybAIEWfcQD~tplv-tej9nj120t-origin.webp'),
(67, 'ก๋วยเตี๋ยววุ้นเส้น เย็นตาโฟ', '1 ชาม / 250 g', 'อาหารจานหลัก', 210.00, 5.60, 31.70, 0.00, 6.80, 1362.50, 'สมดุล', 'https://www.calforlife.com/image/food/-Yentafo-Large-Noodles.jpg'),
(68, 'ก๋วยเตี๋ยวเส้นบะหมี่หมูแดง น้ำ', '1 ชาม / 250 g', 'อาหารจานหลัก', 170.00, 9.00, 21.30, 0.00, 5.50, 990.00, 'สมดุล', 'https://img-global.cpcdn.com/recipes/3d32aea0894e28ca/680x482cq70/%E0%B8%A3%E0%B8%9B-%E0%B8%AB%E0%B8%A5%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%87-%E0%B8%AA%E0%B8%95%E0%B8%A3-%E0%B8%9A%E0%B8%B0%E0%B8%AB%E0%B8%A1%E0%B9%80%E0%B8%81%E0%B8%A2%E0%B8%A7%E0%B8%AB%E0%B8%A1%E0%B9%81%E0%B8%94%E0%B8%87.jpg'),
(69, 'ก๋วยเตี๋ยวเส้นเล็ก หมู น้ำตก', '1 ชาม / 250 g', 'อาหารจานหลัก', 202.00, 11.53, 29.10, 0.00, 4.45, 1157.50, 'สมดุล', 'https://cheewajit.com/app/uploads/2021/04/image-130-edited.png'),
(70, 'ก๋วยเตี๋ยวหลอด', '1 จาน / 300 g', 'อาหารจานหลัก', 225.00, 8.00, 30.00, 0.00, 10.00, 500.00, 'น้ำตาลต่ำ', 'https://www.maggi.co.th/sites/default/files/styles/home_stage_1500_700/public/srh_recipes/69edbfa26442402738bc00bcb17a5612.jpg?h=4e8f58d4&itok=o9u3SHVR'),
(71, 'เกาเหลาหมูตุ๋น', '1 ชาม / 300 g', 'อาหารจานหลัก', 372.00, 21.00, 18.00, 14.00, 24.00, 299.00, 'สมดุล', 'https://www.tripsabay.com/wp-content/uploads/2021/03/IMG_20210213_132757735-1024x768.jpg'),
(72, 'เกาเหลาลูกชิ้น', '1 ชาม / 300 g', 'อาหารจานหลัก', 200.00, 12.00, 1.00, 0.00, 17.00, 675.00, 'น้ำตาลต่ำ', 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgVxgjBQIzKXnshuN9itKtA7xBmhOYrAlZeusATLcjh9eOldM4cDSGlEVBwhSGN1W_lcWV162OUoyXo7IG7cC_rB2643se7Am5fQq2uAVTzEVM1wDVFJU_cUKWfmiYxtP7-OpiwzniAnco/s1600/9273.jpg'),
(73, 'เกี๊ยวน้ำหมู', '1 ถ้วย / 300 g', 'อาหารจานหลัก', 140.00, 7.00, 15.00, 3.00, 6.00, 930.00, 'ไขมันต่ำ', 'https://www.maggi.co.th/sites/default/files/styles/home_stage_944_531/public/srh_recipes/7226345eae541431be7ad68598a86325.jpg?h=c604f7cf&itok=KKj-hMxK'),
(74, 'ขนมจีน, แกงเขียวหวานไก่', '1 ชาม / 300 g', 'อาหารจานหลัก', 355.00, 13.00, 7.00, 0.00, 30.00, 1768.00, 'สมดุล', 'https://img-global.cpcdn.com/recipes/7260c52d2fa37a68/680x482cq70/%E0%B8%A3%E0%B8%9B-%E0%B8%AB%E0%B8%A5%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%87-%E0%B8%AA%E0%B8%95%E0%B8%A3-%E0%B9%81%E0%B8%81%E0%B8%87%E0%B9%80%E0%B8%82%E0%B8%A2%E0%B8%A7%E0%B8%AB%E0%B8%A7%E0%B8%B2%E0%B8%99%E0%B9%84%E0%B8%81.jpg'),
(75, 'ข้าวกล้องราดแกงคั่วสับปะรด', '1 ชาม / 300 g', 'อาหารจานหลัก', 393.00, 10.00, 63.00, 0.00, 11.00, 0.00, 'สมดุล', 'https://img.wongnai.com/p/1968x0/2018/06/10/eaf3682eb33b4c3e8d835a9d7e5d1c1e.jpg'),
(76, 'ราดหน้าหมู', '1 ชาม / 300 g', 'อาหารจานหลัก', 420.00, 13.00, 63.00, 15.00, 13.00, 1470.00, 'สมดุล', 'https://pengtawan.wordpress.com/wp-content/uploads/2016/09/maxresdefault-1.jpg'),
(77, 'ข้าวขาหมู', '1 จาน / 300 g', 'อาหารจานหลัก', 470.00, 21.00, 75.00, 0.00, 9.00, 1210.00, 'สมดุล', 'https://menu-ded.com/wp-content/uploads/2023/10/%E0%B8%82%E0%B9%89%E0%B8%B2%E0%B8%A7%E0%B8%82%E0%B8%B2%E0%B8%AB%E0%B8%A1%E0%B8%B8.webp'),
(78, 'ข้าวคลุกกะปิ', '1 จาน / 300 g', 'อาหารจานหลัก', 540.00, 16.00, 70.00, 9.00, 22.00, 910.00, 'สมดุล', 'https://img.wongnai.com/p/1920x0/2018/09/15/81c5e3d1df124f3db677a8ac16045ac4.jpg'),
(79, 'ข้าวผัดหมู', '1 จาน / 300 g', 'อาหารจานหลัก', 410.00, 13.00, 59.00, 4.00, 14.00, 710.00, 'สมดุล', 'https://s359.kapook.com/pagebuilder/2810e9c2-ac36-4970-bc50-d2fa431e2c3c.jpg'),
(80, 'ข้าวมันไก่', '1 จาน / 300 g', 'อาหารจานหลัก', 563.00, 34.00, 71.00, 6.00, 15.00, 2066.00, 'สมดุล', 'https://static.thairath.co.th/media/4DQpjUtzLUwmJZZSEmAUm74bI2EL8Sb34rOSLQkKjXQF.jpg'),
(81, 'ข้าวราดแกงขี้เหล็ก', '1 จาน / 300 g', 'อาหารจานหลัก', 352.00, 12.00, 53.00, 0.00, 10.00, 1547.00, 'สมดุล', 'https://img.wongnai.com/p/1920x0/2017/06/03/54f8f4ab8699486d99b3fa5b285242a2.jpg'),
(82, 'ข้าวราดแกงเขียวหวานไก่', '1 จาน / 300 g', 'อาหารจานหลัก', 340.00, 14.00, 63.00, 2.00, 4.00, 550.00, 'สมดุล', 'https://images.deliveryhero.io/image/fd-th/LH/txut-listing.jpg'),
(83, 'ข้าวราดแกงคั่วกลิ้งหมู', '1 จาน / 300 g', 'อาหารจานหลัก', 345.00, 50.00, 5.00, 0.00, 13.00, 335.00, 'โซเดียมต่ำ', 'https://api2.krua.co/wp-content/uploads/2020/07/RT1575_Gallery_-04-scaled.jpg'),
(84, 'ข้าวราดแกงไตปลา', '1 จาน / 300 g', 'อาหารจานหลัก', 428.00, 38.00, 8.00, 0.00, 3.00, 576.00, 'น้ำตาลต่ำ', 'https://img.wongnai.com/p/1920x0/2016/02/02/f557d498aaec4e73868b91848654dd02.jpg'),
(85, 'ข้าวราดแกงเทโพหมูสามชั้น', '1 จาน / 300 g', 'อาหารจานหลัก', 298.00, 7.00, 50.00, 0.00, 8.00, 347.00, 'โซเดียมต่ำ', 'https://s359.kapook.com/pagebuilder/ea344a81-9947-42cc-8dd9-37ae5d0e25f6.jpg'),
(86, 'ข้าวราดแกงพะแนงหมู', '1 จาน / 300 g', 'อาหารจานหลัก', 520.00, 17.00, 2.00, 0.00, 49.00, 418.00, 'น้ำตาลต่ำ', 'https://img.wongnai.com/p/1600x0/2016/04/16/c463a8dae475463a8dd4145ca6abf0b1.jpg'),
(87, 'ข้าวราดแกงมัสมั่นไก่', '1 จาน / 300 g', 'อาหารจานหลัก', 637.00, 24.00, 5.00, 0.00, 59.00, 481.00, 'น้ำตาลต่ำ', 'https://www.calforlife.com/image/food/Chicken-Massaman-Curry-with-Rice.jpg'),
(88, 'ข้าวราดแกงส้มชะอมทอด', '1 จาน / 300 g', 'อาหารจานหลัก', 397.00, 14.00, 57.00, 0.00, 13.00, 386.00, 'โซเดียมต่ำ', 'https://img.wongnai.com/p/1920x0/2020/03/06/c1aa44fb908f40d7a2677059f4932c8a.jpg'),
(89, 'ข้าวราดแกงส้มผักรวม', '1 จาน / 300 g', 'อาหารจานหลัก', 336.00, 58.00, 12.00, 4.00, 4.00, 410.00, 'น้ำตาลต่ำ', 'https://storage.googleapis.com/stateless-www-thaisabuy-com/2016/10/maxresdefault-1.jpg'),
(90, 'ข้าวราดไข่เจียวหมูสับ', '1 จาน / 300 g', 'อาหารจานหลัก', 518.00, 14.00, 67.00, 0.00, 21.00, 364.00, 'โซเดียมต่ำ', 'https://img.kapook.com/u/2016/wanwanat/0_edit/385698979x.jpg'),
(91, 'ข้าวราดต้มจืดเต้าหู้ หมูสับราดข้าว', '1 จาน / 300 g', 'อาหารจานหลัก', 263.00, 21.00, 25.00, 0.00, 9.00, 271.00, 'โซเดียมต่ำ', 'https://s359.kapook.com/pagebuilder/f5659e49-b143-4fbb-9240-fb5a675dd907.jpg'),
(92, 'ข้าวราดต้มพะโล้', '1 จาน / 300 g', 'อาหารจานหลัก', 535.00, 25.00, 66.00, 0.00, 19.00, 376.00, 'โซเดียมต่ำ', 'https://fit-d.com/uploads/food/34e28217c248f1603edc3c5f7c90dbce.jpg'),
(93, 'ข้าวราดทอดมันปลากราย', '1 จาน / 300 g', 'อาหารจานหลัก', 618.00, 23.00, 69.00, 0.00, 28.00, 392.00, 'โซเดียมต่ำ', 'https://www.bloggang.com/data/b/benyarat088/picture/1696510518.jpg'),
(94, 'ข้าวราดผัดกระเพราไก่', '1 จาน / 300 g', 'อาหารจานหลัก', 430.00, 17.00, 77.00, 8.00, 6.00, 620.00, 'สมดุล', 'https://s359.kapook.com/pagebuilder/7595f0f5-696a-4cfe-ad3a-9cd4dd3ccbf8.jpg'),
(95, 'ข้าวราดผัดคะน้าหมูกรอบ', '1 จาน / 300 g', 'อาหารจานหลัก', 550.00, 11.00, 96.00, 6.00, 13.00, 890.00, 'สมดุล', 'https://fit-d.com/uploads/food/afb11fe696d53169c7bce688ab5010c1.jpg'),
(96, 'ข้าวราดผัดคะน้าหมูชิ้น', '1 จาน / 300 g', 'อาหารจานหลัก', 501.00, 11.00, 60.90, 6.00, 7.50, 882.00, 'สมดุล', 'https://s359.kapook.com/pagebuilder/b4942cf9-2d42-4527-bf3a-bc17c388e2bb.jpg'),
(97, 'ข้าวราดไก่กระเทียม', '1 จาน / 300 g', 'อาหารจานหลัก', 520.00, 60.00, 140.00, 2.00, 5.00, 760.00, 'สมดุล', 'https://s359.kapook.com/r/600/auto/pagebuilder/683d4862-5951-4398-a53f-97af8490f14a.jpg'),
(98, 'ข้าวราดผัดเต้าหู้ไข่ทรงเครื่อง', '1 จาน / 300 g', 'อาหารจานหลัก', 350.00, 25.00, 13.00, 0.00, 26.00, 1839.00, 'สมดุล', 'https://api2.krua.co/wp-content/uploads/2020/06/SlideBanner1140x507-468.jpg'),
(99, 'ข้าวราดผัดผักรวม', '1 จาน / 300 g', 'อาหารจานหลัก', 332.00, 9.00, 61.00, 0.00, 6.00, 446.00, 'โซเดียมต่ำ', 'https://yalamarketplace.com/upload/1701004664867.jpg'),
(100, 'ข้าวราดผัดเผ็ดหน่อไม้ไก่', '1 จาน / 300 g', 'อาหารจานหลัก', 320.00, 10.00, 60.00, 3.00, 4.00, 690.00, 'สมดุล', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSK0q00102JedLBnrOouwCaA6wvEP5iuoPOIQ&s'),
(101, 'ข้าวราดผัดเผ็ดหอยแมลงภู่', '1 จาน / 300 g', 'อาหารจานหลัก', 424.00, 18.00, 64.00, 0.00, 11.00, 417.00, 'สมดุล', 'https://img-global.cpcdn.com/recipes/b1f3eacd4f4e2acd/1200x630cq70/photo.jpg'),
(102, 'ข้าวราดผัดเผ็ดหอยลาย', '1 จาน / 300 g', 'อาหารจานหลัก', 307.00, 6.00, 48.00, 0.00, 10.00, 392.00, 'โซเดียมต่ำ', 'https://img.wongnai.com/p/1920x0/2018/02/14/780e9c5539b944c1b9d9419ef51e8315.jpg'),
(103, 'ข้าวราดผัดมะเขือยาวกับหมูสับ', '1 จาน / 300 g', 'อาหารจานหลัก', 315.00, 12.00, 23.00, 9.00, 20.00, 900.00, 'สมดุล', 'https://www.pholfoodmafia.com/wp-content/uploads/2021/04/4Stir-fried-Eggplant-with-Soybean-Paste-Minced-Pork.jpg'),
(104, 'ข้าวราดผัดมะระกับไข่', '1 จาน / 300 g', 'อาหารจานหลัก', 341.00, 12.00, 11.00, 6.00, 28.00, 689.00, 'สมดุล', 'https://pbs.twimg.com/media/DDBIzjzUMAEMAF9.jpg:large'),
(105, 'ข้าวราดยำไข่ดาว', '1 จาน / 300 g', 'อาหารจานหลัก', 336.00, 20.00, 75.00, 0.00, 29.00, 468.00, 'โซเดียมต่ำ', 'https://img-global.cpcdn.com/recipes/763f277de2284416/680x482cq70/%E0%B8%A3%E0%B8%9B-%E0%B8%AB%E0%B8%A5%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%87-%E0%B8%AA%E0%B8%95%E0%B8%A3-%E0%B8%A2%E0%B8%B3%E0%B9%84%E0%B8%82%E0%B8%94%E0%B8%B2%E0%B8%A7-%E0%B8%A3%E0%B8%B2%E0%B8%94%E0%B8%82%E0%B8%B2%E0%B8%A7.jpg'),
(106, 'ข้าวราดยำหมูยอ', '1 จาน / 300 g', 'อาหารจานหลัก', 456.00, 14.00, 28.00, 0.00, 19.00, 503.00, 'สมดุล', 'https://www.sgethai.com/wp-content/uploads/2023/10/3_result-4.webp'),
(107, 'ข้าวราดหมูทอดกระเทียม', '1 จาน / 300 g', 'อาหารจานหลัก', 530.00, 26.00, 78.00, 8.00, 13.00, 620.00, 'สมดุล', 'https://chefoldschool.com/wp-content/uploads/2020/08/p86-1024x681.jpg'),
(108, 'ข้าวหมกไก่', '1 จาน / 300 g', 'อาหารจานหลัก', 560.00, 18.00, 84.00, 17.00, 17.00, 1850.00, 'สมดุล', 'https://www.pim.in.th/images/all-one-dish-food/chicken-biryani/chicken-biryani-17.jpg'),
(109, 'ข้าวหมูแดง', '1 จาน / 300 g', 'อาหารจานหลัก', 560.00, 20.00, 96.00, 16.00, 11.00, 1200.00, 'สมดุล', 'https://img-global.cpcdn.com/recipes/6e4a85a7219b1cc8/680x482cq70/%E0%B8%A3%E0%B8%9B-%E0%B8%AB%E0%B8%A5%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%87-%E0%B8%AA%E0%B8%95%E0%B8%A3-%E0%B8%82%E0%B8%B2%E0%B8%A7%E0%B8%AB%E0%B8%A1%E0%B9%81%E0%B8%94%E0%B8%87-by-%E0%B8%84%E0%B8%A3%E0%B8%A7%E0%B9%81%E0%B8%A1%E0%B8%A8%E0%B8%A3%E0%B9%80%E0%B8%AB%E0%B8%A5%E0%B8%A2%E0%B8%A1.jpg'),
(110, 'ข้าวหมูอบ', '1 จาน / 300 g', 'อาหารจานหลัก', 443.00, 37.00, 48.00, 3.00, 11.00, 849.00, 'สมดุล', 'https://api2.krua.co/wp-content/uploads/2020/06/SEOForm_RT0682_1200x630.jpg'),
(111, 'สุกี้, ไก่, น้ำ', '1 ชาม  / 300 g', 'อาหารจานหลัก', 253.00, 20.00, 30.00, 0.00, 6.00, 536.00, 'โซเดียมต่ำ', 'https://assets.unileversolutions.com/recipes-v2/117724.jpg'),
(112, 'สุกี้, รวมมิตร, น้ำ', '1 ชาม  / 300 g', 'อาหารจานหลัก', 207.00, 21.00, 18.00, 0.00, 6.00, 471.00, 'โซเดียมต่ำ', 'https://recipe.ajinomoto.co.th/_next/image?url=https%3A%2F%2Fwww.ajinomoto.co.th%2Fstorage%2Fphotos%2Fshares%2FRecipe%2FMenu%2F12sukiruammit%2F614b1b45534a0.jpg&w=3840&q=75'),
(113, 'สุกี้, ทะเล, น้ำ', '1 ชาม  / 300 g', 'อาหารจานหลัก', 216.00, 25.00, 4.00, 0.00, 9.00, 424.00, 'โซเดียมต่ำ', 'https://img-global.cpcdn.com/recipes/c520e9d478960816/680x482cq70/%E0%B8%A3%E0%B8%9B-%E0%B8%AB%E0%B8%A5%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%87-%E0%B8%AA%E0%B8%95%E0%B8%A3-%E0%B8%AA%E0%B8%81%E0%B8%97%E0%B8%B0%E0%B9%80%E0%B8%A5.jpg'),
(114, 'หอยทอด', '1 จาน / 300 g', 'อาหารจานหลัก', 625.00, 19.00, 35.00, 0.00, 46.00, 1190.00, 'สมดุล', 'https://img-global.cpcdn.com/recipes/8fd2df886f99c1cc/680x482cq70/%E0%B8%A3%E0%B8%9B-%E0%B8%AB%E0%B8%A5%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%87-%E0%B8%AA%E0%B8%95%E0%B8%A3-%E0%B8%AB%E0%B8%AD%E0%B8%A2%E0%B8%97%E0%B8%AD%E0%B8%94-%E0%B8%AB%E0%B8%AD%E0%B8%A2%E0%B9%81%E0%B8%A1%E0%B8%A5%E0%B8%87%E0%B8%A0.jpg'),
(115, 'ข้าวลาบหมู', '1 จาน / 300 g', 'อาหารจานหลัก', 380.00, 23.00, 61.00, 2.00, 5.00, 820.00, 'สมดุล', 'https://yalamarketplace.com/upload/1623729326188.jpg'),
(116, 'ข้าวต้มกุ้ง', '1 ชาม  / 300 g', 'อาหารจานหลัก', 203.00, 26.10, 22.70, 0.00, 0.50, 111.80, 'ไขมันต่ำ', 'https://s359.kapook.com/pagebuilder/a70f1080-df0d-4759-94f5-7e3bca9af692.jpg'),
(117, 'ข้าวต้มหมู', '1 ชาม  / 300 g', 'อาหารจานหลัก', 200.00, 9.00, 36.00, 1.00, 2.00, 740.00, 'ไขมันต่ำ', 'https://sharp-weeclub.com/wp-content/uploads/2023/08/%E0%B8%82%E0%B9%89%E0%B8%B2%E0%B8%A7%E0%B8%95%E0%B9%89%E0%B8%A12-990x510-1.jpg'),
(118, 'ข้าวต้มปลา', '1 ชาม  / 300 g', 'อาหารจานหลัก', 220.00, 21.00, 28.20, 0.10, 1.60, 73.00, 'ไขมันต่ำ', 'https://www.thammculture.com/wp-content/uploads/2023/03/Untitled-402.jpg'),
(119, 'โจ๊กหมู', '1 ชาม  / 300 g', 'อาหารจานหลัก', 157.00, 8.00, 20.00, 0.00, 5.00, 730.00, 'สมดุล', 'https://www.sgethai.com/wp-content/uploads/2021/09/210920-Content-%E0%B8%A7%E0%B8%B4%E0%B8%98%E0%B8%B5%E0%B8%97%E0%B8%B3%E0%B9%82%E0%B8%88%E0%B9%8A%E0%B8%81%E0%B8%AD%E0%B8%B2%E0%B8%AB%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%8A%E0%B9%89%E0%B8%B2%E0%B8%AA%E0%B8%B9%E0%B8%95%E0%B8%A3%E0%B9%80%E0%B8%94%E0%B9%87%E0%B8%94-%E0%B8%97%E0%B8%B3%E0%B8%A2%E0%B8%B1%E0%B8%87%E0%B9%84%E0%B8%87%E0%B9%83%E0%B8%AB%E0%B9%89%E0%B8%AD%E0%B8%A3%E0%B9%88%E0%B8%AD%E0%B8%A203.jpg'),
(120, 'โจ๊กไข่', '1 ชาม  / 300 g', 'อาหารจานหลัก', 210.00, 4.00, 24.00, 2.00, 5.00, 910.00, 'สมดุล', 'https://media-cdn.tripadvisor.com/media/photo-s/1b/ed/ab/d2/caption.jpg'),
(121, 'แกงส้มผักรวมปลา', '300 g', 'อาหารจานหลัก', 111.00, 11.60, 13.81, 8.70, 1.06, 1255.00, 'น้ำตาลต่ำ', 'https://steemitimages.com/p/3HaJVw3AYyXBD5Md5tUD9YKkzGo1eoR2RP1hYxRaFr2GBTWKWhp1nVzKowftwgQK3DdLvoitMxjg5VgqryBcrbcmxUtbD3ven9ojiC5?format=match&mode=fit&width=1280'),
(122, 'ผัดผักคะน้าใส่หมู', '300 g', 'อาหารจานหลัก', 316.00, 21.30, 12.60, 0.00, 20.26, 1294.00, 'น้ำตาลต่ำ', 'https://recipe.ajinomoto.co.th/_next/image?url=https%3A%2F%2Fwww.ajinomoto.co.th%2Fstorage%2Fphotos%2Fshares%2FRecipe%2FMenu%2F3-13Stirfriedkale%2F61a8f141b755c.jpg&w=3840&q=75'),
(123, 'สลัดผักรวม', '300 g', 'อาหารจานหลัก', 139.00, 5.00, 16.70, 1.70, 5.80, 96.00, 'น้ำตาลต่ำ', 'https://www.matichonacademy.com/wp-content/uploads/2020/02/%E0%B8%AA%E0%B8%A5%E0%B8%B1%E0%B8%94%E0%B8%9C%E0%B8%B1%E0%B8%81%E0%B8%A3%E0%B8%A7%E0%B8%A1%E0%B9%80%E0%B8%A1%E0%B8%A5%E0%B9%87%E0%B8%94%E0%B8%9F%E0%B8%B2%E0%B9%82%E0%B8%A3%E0%B8%AB%E0%B9%8C.jpg'),
(124, 'ผัดผักรวมมิตรกับเต้าหู้', '300 g', 'อาหารจานหลัก', 328.00, 8.20, 26.30, 17.00, 21.30, 758.00, 'น้ำตาลต่ำ', 'https://www.maggi.co.th/sites/default/files/srh_recipes/4215977e3ddab85369661aa7025ab94a.jpg'),
(125, 'แกงจืดเต้าหู้หมูสับใส่ผัก', '300 g', 'อาหารจานหลัก', 209.00, 21.40, 4.00, 0.00, 12.00, 576.00, 'น้ำตาลต่ำ', 'https://assets.unileversolutions.com/recipes-v2/242153.jpg'),
(126, 'ปลานึ่งมะนาว', '300 g', 'อาหารจานหลัก', 249.00, 23.70, 28.10, 14.70, 5.00, 1486.00, 'น้ำตาลต่ำ', 'https://img.kapook.com/u/2017/wanwanat/95_nile_lime/c1.jpg'),
(127, 'แกงป่าไก่', '300 g', 'อาหารจานหลัก', 252.00, 15.30, 16.40, 0.61, 13.90, 1124.00, 'น้ำตาลต่ำ', 'https://i.ytimg.com/vi/DLpFfFNxguE/maxresdefault.jpg'),
(128, 'แกงเลียงผักรวม', '300 g', 'อาหารจานหลัก', 135.00, 14.30, 16.00, 2.00, 1.10, 1175.00, 'น้ำตาลต่ำ', 'https://s.isanook.com/wo/0/ud/16/81453/81453-thumbnail.jpg?ip/resize/w850/q80/jpg'),
(129, 'แกงคั่วหมูเทโพ', '300 g', 'อาหารจานหลัก', 462.00, 25.70, 31.70, 0.80, 26.80, 2259.00, 'น้ำตาลต่ำ', 'https://www.sgethai.com/wp-content/uploads/2022/09/220920-Content-%E0%B8%A7%E0%B8%B4%E0%B8%98%E0%B8%B5%E0%B8%97%E0%B8%B3-%E0%B9%81%E0%B8%81%E0%B8%87%E0%B9%80%E0%B8%97%E0%B9%82%E0%B8%9E02.jpg'),
(130, 'ต้มจับฉ่าย', '300 g', 'อาหารจานหลัก', 231.00, 12.00, 15.00, 0.01, 13.50, 914.00, 'น้ำตาลต่ำ', 'https://www.maggi.co.th/sites/default/files/srh_recipes/0332d7a554d3fae5af703defd2fcd979.jpg'),
(131, 'แซลมอนลุยสวน', '300 g', 'อาหารจานหลัก', 371.00, 27.80, 19.90, 9.80, 20.00, 1468.00, 'ไขมันต่ำ', 'https://s359.kapook.com/pagebuilder/909ff474-3867-44dc-ab71-0b9e82b3ffb3.jpg'),
(132, 'น้ำพริกปลาทู', '150 g', 'อาหารจานหลัก', 77.00, 8.50, 8.90, 3.00, 1.10, 1419.00, 'ไขมันต่ำ', 'https://img.salehere.co.th/p/1200x0/2021/05/13/33ojyfsmaatn.jpg'),
(133, 'ปลากะพงผัดขึ้นฉ่าย', '300 g', 'อาหารจานหลัก', 157.00, 21.00, 9.00, 1.80, 3.50, 604.00, 'ไขมันต่ำ', 'https://img.wongnai.com/p/1920x0/2018/02/21/6133ef9fd65340248b23f6ec8c4b0c27.jpg'),
(134, 'ข้าวผัดปลาแซลมอน', '300 g', 'อาหารจานหลัก', 527.00, 44.00, 4.90, 2.60, 42.40, 689.00, 'ไขมันต่ำ', 'https://images.aws.nestle.recipes/original/6f75383b93687bbbe5052d644e632fe9_salmon-with-garlic-fried-rice.jpg'),
(135, 'ข้าวผัดน้ําพริกตาแดง', '300 g', 'อาหารจานหลัก', 588.00, 24.40, 47.90, 4.70, 33.70, 460.00, 'ไขมันต่ำ', 'https://img.wongnai.com/p/1920x0/2016/10/29/5b4c8acdde7445d2869832db6f6cb1cc.jpg'),
(136, 'ห่อหมกปลาดอรี่', '300 g', 'อาหารจานหลัก', 508.00, 38.30, 19.40, 6.90, 31.70, 933.00, 'ไขมันต่ำ', 'https://iloveveganlongbeach.com/wp-content/uploads/2021/12/%E0%B8%AB%E0%B9%88%E0%B8%AD%E0%B8%AB%E0%B8%A1%E0%B8%81%E0%B8%9B%E0%B8%A5%E0%B8%B2%E0%B8%94%E0%B8%AD%E0%B8%A5%E0%B8%A5%E0%B8%B5%E0%B9%88-%E0%B9%80%E0%B8%AB%E0%B8%A1%E0%B8%B2%E0%B8%B0%E0%B8%81%E0%B8%B1%E0%B8%9A%E0%B8%84%E0%B8%99%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B9%80%E0%B8%9A%E0%B8%B7%E0%B9%88%E0%B8%AD%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%97%E0%B8%B2%E0%B8%99%E0%B8%AB%E0%B9%88%E0%B8%AD%E0%B8%AB%E0%B8%A1%E0%B8%81%E0%B8%9B%E0%B8%A5%E0%B8%B2%E0%B9%81%E0%B8%9A%E0%B8%9A%E0%B9%80%E0%B8%94%E0%B8%B4%E0%B8%A1%E0%B9%862.jpg'),
(137, 'น้ำพริกอ่อง', '100 g', 'อาหารจานหลัก', 346.00, 34.40, 34.90, 9.50, 10.20, 3030.00, 'ไขมันต่ำ', 'https://storage.googleapis.com/cpbs-bucket-cms-uat/web/recipe/3978975fcdd092aeab0c7340c_20240630_205450.jpg'),
(138, 'ปลาผัดพริกสด', '300 g', 'อาหารจานหลัก', 439.00, 17.00, 68.00, 2.00, 11.00, 720.00, 'ไขมันต่ำ', 'https://i.ytimg.com/vi/un0I6TQySes/maxresdefault.jpg'),
(139, 'ต้มยำปลานิล', '300 g', 'อาหารจานหลัก', 290.00, 25.00, 45.00, 3.20, 2.80, 3819.00, 'ไขมันต่ำ', 'https://www.sgethai.com/wp-content/uploads/2024/04/16042024-tom-yum-pla-nil9.webp'),
(140, 'กุ้งทอดกระเทียม', '300 g', 'อาหารจานหลัก', 540.00, 34.20, 60.20, 0.20, 16.40, 423.70, 'ไขมันต่ำ', 'https://fit-d.com/uploads/food/f8da935dd2c81eca662e52dd1f002742.jpg'),
(141, 'เห็ดรวมผัดน้ำมันหอย', '300 g', 'อาหารจานหลัก', 82.00, 3.50, 8.10, 1.10, 4.90, 430.00, 'ไขมันต่ำ', 'https://img.wongnai.com/p/1920x0/2019/03/04/e3f0cb4547de4efe9203dfd16f303e43.jpg'),
(142, 'ฟักทองผัดไข่ขาว', '300 g', 'อาหารจานหลัก', 243.00, 14.30, 9.40, 4.50, 16.30, 826.00, 'ไขมันต่ำ', 'https://img.wongnai.com/p/1920x0/2017/07/16/022ed9a424f54c8cba7c533204958c57.jpg'),
(143, 'สลัดอกไก่ต้ม', '300 g', 'อาหารจานหลัก', 165.00, 20.00, 10.00, 0.00, 5.00, 121.00, 'ไขมันต่ำ', 'https://www.cpbrandsite.com/contents/images/9n63j5bw5evnzb1e4ewzuqsz39tmpjv6rwszyfan.jpg'),
(144, 'ปลานิลนึ่งตะไคร้', '300 g', 'อาหารจานหลัก', 384.00, 42.00, 12.70, 0.00, 7.00, 211.00, 'ไขมันต่ำ', 'https://www.pholfoodmafia.com/wp-content/uploads/2020/11/4Steamed-Tilapia-with-Lemongrass.jpg'),
(145, 'กุ้งอบวุ้นเส้น', '300 g', 'อาหารจานหลัก', 591.00, 33.10, 113.00, 9.50, 0.40, 385.00, 'โซเดียมต่ำ', 'https://images.aws.nestle.recipes/original/b30597ce4fa4e13cfe769011794e488b_%E0%B8%81%E0%B8%B8%E0%B9%89%E0%B8%87%E0%B8%AD%E0%B8%9A%E0%B8%A7%E0%B8%B8%E0%B9%89%E0%B8%99%E0%B9%80%E0%B8%AA%E0%B9%89%E0%B8%99.jpg'),
(146, 'ไก่อบสมุนไพร', '300 g', 'อาหารจานหลัก', 178.00, 23.30, 0.00, 0.00, 8.80, 95.00, 'โซเดียมต่ำ', 'https://api2.krua.co/wp-content/uploads/2020/06/SEOForm_RT0679_1200x630.jpg'),
(147, 'ข้าวอบธัญพืช', '300 g', 'อาหารจานหลัก', 240.00, 12.00, 42.00, 1.00, 3.00, 450.00, 'โซเดียมต่ำ', 'https://img.wongnai.com/p/1920x0/2018/09/11/716b208fc72a4fad80705c1f36b52d77.jpg'),
(148, 'ปลานึ่งขิง', '300 g', 'อาหารจานหลัก', 201.00, 48.00, 0.00, 0.00, 1.00, 350.00, 'โซเดียมต่ำ', 'https://img.wongnai.com/p/1920x0/2018/09/25/89edf9e8de684b5c999b8e37764d5cfd.jpg'),
(149, 'ต้มยำเห็ดรวมน้ำใส', '300 g', 'อาหารจานหลัก', 48.00, 3.30, 7.40, 2.00, 0.60, 339.00, 'โซเดียมต่ำ', 'https://sivasatciftligi.com/wp-content/uploads/2021/02/hot-and-sour-soup.jpg'),
(150, 'แกงเหลืองปลา', '300 g', 'อาหารจานหลัก', 41.00, 7.90, 1.30, 0.00, 0.10, 674.00, 'โซเดียมต่ำ', 'https://www.sgethai.com/wp-content/uploads/2022/09/8-21.jpg'),
(151, 'ชาเขียวนมสด', '1 แก้ว / 350 ml', 'เครื่องดื่ม', 244.00, 2.92, 39.65, 32.07, 8.27, 0.00, 'สมดุล', 'https://www.fnthaidairies.com/public/uploads/recipe_management/images/Is9H7sYQbngmCFJzss7TSoIsV085aA1pTvHD8niKKIyaYgEXvp1611739484.jpg'),
(152, 'ชาเย็น', '1 แก้ว / 350 ml', 'เครื่องดื่ม', 215.00, 2.67, 34.71, 26.57, 7.34, 0.00, 'สมดุล', 'https://www.punthaicoffee.com/stocks/article/o0x0/aw/r9/nqt4awr9b8k/article6-1-cover.jpg'),
(153, 'ชามะนาว', '1 แก้ว / 350 ml', 'เครื่องดื่ม', 180.00, 0.00, 45.00, 22.00, 0.00, 0.40, 'น้ำตาลต่ำ', 'https://s359.kapook.com/pagebuilder/aea20974-a1b8-44d5-be43-c018b6448758.jpg'),
(154, 'นมเปรี้ยว บีทาเก้นไลท์', '300 ml', 'เครื่องดื่ม', 180.00, 6.00, 39.00, 21.00, 0.00, 0.00, 'น้ำตาลต่ำ', 'https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/87/88/8850393800587/8850393800587_1.jpg'),
(155, 'นม ยูเอชที รสจืด', '1 กล่อง / 180 ml', 'เครื่องดื่ม', 110.00, 4.00, 12.00, 11.00, 6.00, 0.25, 'โซเดียมต่ำ', 'https://media.allonline.7eleven.co.th/pdmain/385366-00-allonline-sm.jpg'),
(156, 'นมสดหนองโพ รสหวาน', '1 กล่อง / 225 ml', 'เครื่องดื่ม', 180.00, 7.00, 20.00, 20.00, 8.00, 0.22, 'โซเดียมต่ำ', 'https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/93/88/8858862801493/8858862801493_3-20240716172051-.jpg'),
(157, 'นมรสกาแฟลาเต้ - เมจิ', '1 กล่อง / 200 ml', 'เครื่องดื่ม', 90.00, 5.00, 11.00, 10.00, 2.50, 0.25, 'ไขมันต่ำ', 'https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/82/88/8850329061082/8850329061082_1-20230516084854-.jpg'),
(158, 'ดัชมิลล์นมเปรี้ยวรสธรรมชาติ', '1 กล่อง / 200 ml', 'เครื่องดื่ม', 120.00, 4.00, 26.00, 24.00, 0.00, 0.15, 'น้ำตาลต่ำ', 'https://down-th.img.susercontent.com/file/f6fddb3f9501c8d99acc6cc7cb088f80'),
(159, 'โยเกิร์ตรสธรรมชาติ - ดัชชี่', '1 ถ้วย / 135 g', 'เครื่องดื่ม', 120.00, 3.70, 14.10, 10.40, 3.50, 0.17, 'สมดุล', 'https://gourmetmarketthailand.com/_next/image?url=https%3A%2F%2Fmedia-stark.gourmetmarketthailand.com%2Fproducts%2Fcover%2F8851717020087-1.webp&w=1200&q=75'),
(160, 'โยเกิร์ตรสผลไม้รวม - ดัชชี่', '1 ถ้วย / 135 g', 'เครื่องดื่ม', 140.00, 4.00, 25.00, 21.00, 3.00, 0.16, 'ไขมันต่ำ', 'https://www.calforlife.com/image/food/DUTCHIE-Yoghurt-with-mixed-fruit.jpg'),
(161, 'ขนมถ้วย, ใบเตย', '100 g', 'ขนม', 155.00, 1.40, 30.77, 14.00, 2.97, 263.00, 'ไขมันต่ำ', 'https://s359.kapook.com/pagebuilder/9ba2a880-6c91-4b83-9eab-c206851eaddc.jpg'),
(162, 'ข้าวเหนียวมูล ', '100 g', 'ขนม', 281.00, 3.10, 52.20, 8.10, 6.30, 342.00, 'สมดุล', 'https://api2.krua.co/wp-content/uploads/2023/11/ArticlePic_1670x1095-16-10-1024x672.jpg'),
(163, 'เอแคลร์ ครีมนมสด', '90 g', 'ขนม', 170.00, 0.00, 20.00, 9.00, 9.00, 0.42, 'สมดุล', 'https://s.isanook.com/wo/0/ud/39/196845/a1.jpg?ip/resize/w728/q80/jpg'),
(164, 'ขนมกล้วย', '100 g', 'ขนม', 181.00, 1.31, 37.94, 26.33, 2.74, 263.00, 'ไขมันต่ำ', 'https://www.fnthaidairies.com/public/uploads/recipe_management/images/iqNBJcwbAzWwQ7QifHgGXNu616lvX76Hq5rdLXCuzRnrZ6Y5si1615369723.jpg'),
(165, 'กล้วยไข่, เชื่อม ', '100 g', 'ขนม', 237.00, 1.20, 53.30, 8.40, 1.80, 0.60, 'ไขมันต่ำ', 'https://i.ytimg.com/vi/dwa_Cu9yw4k/maxresdefault.jpg'),
(166, 'เฟรนช์ฟรายส์', '100 g', 'ฟาสต์ฟู้ด', 311.00, 3.40, 41.00, 0.30, 15.00, 0.00, 'สมดุล', 'https://image.posttoday.com/media/content/2018/11/28/76B78DC9EB2F424DB1C041F0AF4A1E42.jpg?x-image-process=style/lg-webp'),
(167, 'นักเก็ตไก่', '100 g', 'ฟาสต์ฟู้ด', 295.00, 15.00, 14.00, 0.00, 20.00, 557.00, 'น้ำตาลต่ำ', 'https://food.mthai.com/app/uploads/2017/08/NUGGET.jpg'),
(168, 'แซนด์วิชแฮมชีส', '100 g', 'ฟาสต์ฟู้ด', 241.00, 14.17, 22.84, 0.00, 10.60, 528.00, 'สมดุล', 'https://www.maggi.co.th/sites/default/files/styles/home_stage_944_531/public/srh_recipes/e9cb0b147aba717d279300a86ab314aa.jpg?h=a9921f97&itok=vs3HhjMn'),
(169, 'ขนมปังกระเทียม', '100 g', 'ฟาสต์ฟู้ด', 380.00, 8.00, 45.00, 2.00, 20.00, 630.00, 'โซเดียมต่ำ', 'https://food.mthai.com/app/uploads/2013/06/13.jpg'),
(170, 'พิซซ่า หน้าฮาวายเอี่ยน', '100 g', 'ฟาสต์ฟู้ด', 260.00, 13.00, 25.00, 3.00, 10.00, 460.00, 'สมดุล', 'https://img.kapook.com/u/wanwanat/hawaiian%20pizza.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `profiles_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `gender` enum('ชาย','หญิง') NOT NULL,
  `age` tinyint(4) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `diseases` varchar(255) NOT NULL,
  `activity_level` enum('น้อย','ปานกลาง','มาก') NOT NULL,
  `bmi` decimal(5,2) NOT NULL,
  `status_bmi` enum('น้ำหนักต่ำกว่าเกณฑ์','น้ำหนักสมส่วน','น้ำหนักเกินเกณฑ์','อ้วน') NOT NULL,
  `daily_calorie` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`profiles_id`, `users_id`, `gender`, `age`, `weight`, `height`, `diseases`, `activity_level`, `bmi`, `status_bmi`, `daily_calorie`) VALUES
(2, 1, 'ชาย', 21, 45.00, 160.00, 'โรคเบาหวาน, โรคความดันโลหิตสูง', 'ปานกลาง', 17.58, 'น้ำหนักต่ำกว่าเกณฑ์', 2077),
(3, 4, 'ชาย', 60, 53.00, 167.00, 'โรคความดันโลหิตสูง', 'ปานกลาง', 19.00, 'น้ำหนักสมส่วน', 1890),
(4, 5, 'หญิง', 78, 65.00, 154.00, 'โรคเบาหวาน', 'ปานกลาง', 27.41, 'อ้วน', 1844),
(5, 6, 'ชาย', 60, 56.00, 158.00, 'โรคหัวใจ', 'มาก', 22.43, 'น้ำหนักสมส่วน', 2066),
(6, 7, 'ชาย', 65, 52.00, 178.00, 'ไม่มีโรค', 'ปานกลาง', 16.41, 'น้ำหนักต่ำกว่าเกณฑ์', 1901),
(8, 9, 'ชาย', 50, 50.00, 150.00, 'โรคเบาหวาน', 'ปานกลาง', 22.22, 'น้ำหนักสมส่วน', 1800);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `email`, `username`, `password`) VALUES
(1, 'panudech1419@gmail.com', 'armpa', '$2y$10$NuvtSjQWdeQ8Og6Ptcm0.eUtNEy9IYJCxKirPGdOoyDMbRIz8gf8q'),
(4, 'susankunthorn_p@silpakorn.edu', 'test2', '$2y$10$vSWjmXHvpYjGN0SMx5f9AuoMQAXvXGEE/oAHoUiCsn7G58phnwpU.'),
(5, 'test@gmail.com', 'test3', '$2y$10$z0.LzRb9yStchuCmw3pvWeHDFDIhJordfL75FzFxs3E6TpcOpstye'),
(6, 'kkn@gmail.com', 'kkn', '$2y$10$Jb3ZubIZendhDkG6kYcVgOEDKWdzVtlT/rTDNcC4t2YkxFWgiNYcC'),
(7, 'Fas@gmail.com', 'Fas', '$2y$10$44xogme1Dn72rnY4gaR/dOKaV6mV6xqVCGVVJ2y95rp.gvOn24Uru'),
(9, 'test4@gmail.com', 'test4', '$2y$10$D/.lyaIrcr/HGzF4HN4pcu0DCJc/rJN/VtBEHQrkc8XUSI7KM6GIK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `food_history`
--
ALTER TABLE `food_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `food_menu`
--
ALTER TABLE `food_menu`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`profiles_id`),
  ADD KEY `user_profilefk_1` (`users_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `food_history`
--
ALTER TABLE `food_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=903;

--
-- AUTO_INCREMENT for table `food_menu`
--
ALTER TABLE `food_menu`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `profiles_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `user_profilefk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
