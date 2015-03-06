-- Adminer 3.1.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_surname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `privileges` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` int(11) unsigned DEFAULT NULL,
  `reg_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yetkisiz',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admins` (`id`, `name_surname`, `username`, `password`, `email`, `status`, `privileges`, `language`, `reg_dt`, `role`) VALUES
(1,	'Salih Özkul',	'admin',	'40bd001563085fc35165329ea1ff5c5ecbdbbeef',	'salihozkul@yahoo.com',	1,	'0',	1,	'2010-06-29 19:10:56',	'Super Admin');

DROP TABLE IF EXISTS `award`;
CREATE TABLE `award` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `season_id` int(11) unsigned NOT NULL,
  `session_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `click_number` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  KEY `session_id` (`session_id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `award_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`),
  CONSTRAINT `award_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`),
  CONSTRAINT `award_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `award_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(11) unsigned NOT NULL,
  `company_category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  KEY `company_category_id` (`company_category_id`),
  CONSTRAINT `company_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  CONSTRAINT `company_ibfk_2` FOREIGN KEY (`company_category_id`) REFERENCES `company_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `company` (`id`, `company_name`, `location_id`, `company_category_id`) VALUES
(1,	'Özkul Bilişim-Ticaret-Turizm Ltd. Şti.',	44,	1),
(2,	'Hediyenikap.com ',	34,	1);

DROP TABLE IF EXISTS `company_category`;
CREATE TABLE `company_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `company_category` (`id`, `category`) VALUES
(1,	'Eğitim Hizmetleri'),
(2,	'Kırtasiye Malzemeleri'),
(3,	'Tekstil Ürünleri');

DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `content_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `content` (`id`, `page_id`, `title`, `content`, `status`) VALUES
(8,	17,	'asdasdasd',	'',	0),
(9,	17,	'asasdasd',	'',	0),
(10,	17,	'Deneme as',	'<p>\n	asda&nbsp; asd&nbsp; as&nbsp; as</p>\n',	0);

DROP TABLE IF EXISTS `image_settings`;
CREATE TABLE `image_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(11) unsigned NOT NULL,
  `type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `width` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `height` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `image_settings_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `image_settings` (`id`, `module_id`, `type`, `width`, `height`) VALUES
(1,	1,	'thumb',	'150',	'100'),
(2,	1,	'medium',	'600',	'400'),
(3,	1,	'large',	'1024',	'768');

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(11) unsigned NOT NULL COMMENT '1:product;2:content;3:company',
  `item_id` int(11) unsigned NOT NULL,
  `counter` int(11) unsigned NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '1',
  `directory` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `images` (`id`, `module_id`, `item_id`, `counter`, `order`, `directory`, `name`, `type`) VALUES
(1,	1,	1,	1,	1,	'files/product/',	'13002099534c72839d0cfd_thumb.jpg',	'thumb'),
(2,	1,	1,	1,	1,	'files/product/',	'13002099534c72839d0cfd_medium.jpg',	'medium'),
(3,	1,	1,	1,	1,	'files/product/',	'13002099534c72839d0cfd_large.jpg',	'large'),
(7,	1,	1,	3,	2,	'files/product/',	'13002099690ed23de8d2_thumb.jpg',	'thumb'),
(8,	1,	1,	3,	2,	'files/product/',	'13002099690ed23de8d2_medium.jpg',	'medium'),
(9,	1,	1,	3,	2,	'files/product/',	'13002099690ed23de8d2_large.jpg',	'large');

DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Türkiye',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `location` (`id`, `city`, `country`) VALUES
(1,	'Adana',	'Türkiye'),
(2,	'Adiyaman',	'Türkiye'),
(3,	'Afyon',	'Türkiye'),
(4,	'Ağri',	'Türkiye'),
(5,	'Amasya',	'Türkiye'),
(6,	'Ankara',	'Türkiye'),
(7,	'Antalya',	'Türkiye'),
(8,	'Artvin',	'Türkiye'),
(9,	'Aydin',	'Türkiye'),
(10,	'Balikesir',	'Türkiye'),
(11,	'Bilecik',	'Türkiye'),
(12,	'Bingöl',	'Türkiye'),
(13,	'Bitlis',	'Türkiye'),
(14,	'Bolu',	'Türkiye'),
(15,	'Burdur',	'Türkiye'),
(16,	'Bursa',	'Türkiye'),
(17,	'Çanakkale',	'Türkiye'),
(18,	'Çankiri',	'Türkiye'),
(19,	'Çorum',	'Türkiye'),
(20,	'Denizlİ',	'Türkiye'),
(21,	'Diyarbakir',	'Türkiye'),
(22,	'Edirne',	'Türkiye'),
(23,	'Elaziğ',	'Türkiye'),
(24,	'Erzincan',	'Türkiye'),
(25,	'Erzurum',	'Türkiye'),
(26,	'Eskişehir',	'Türkiye'),
(27,	'Gaziantep',	'Türkiye'),
(28,	'Giresun',	'Türkiye'),
(29,	'Gümüşhane',	'Türkiye'),
(30,	'Hakkari',	'Türkiye'),
(31,	'Hatay',	'Türkiye'),
(32,	'Isparta',	'Türkiye'),
(33,	'İçel',	'Türkiye'),
(34,	'İstanbul',	'Türkiye'),
(35,	'İzmir',	'Türkiye'),
(36,	'Kars',	'Türkiye'),
(37,	'Kastamonu',	'Türkiye'),
(38,	'Kayserİ',	'Türkiye'),
(39,	'Kirklareli',	'Türkiye'),
(40,	'Kirşehir',	'Türkiye'),
(41,	'Kocaeli',	'Türkiye'),
(42,	'Konya',	'Türkiye'),
(43,	'Kütahya',	'Türkiye'),
(44,	'Malatya',	'Türkiye'),
(45,	'Manisa',	'Türkiye'),
(46,	'Kahramanmaraş',	'Türkiye'),
(47,	'Mardin',	'Türkiye'),
(48,	'Muğla',	'Türkiye'),
(49,	'Muş',	'Türkiye'),
(50,	'Nevşehir',	'Türkiye'),
(51,	'Niğde',	'Türkiye'),
(52,	'Ordu',	'Türkiye'),
(53,	'Rize',	'Türkiye'),
(54,	'Sakarya',	'Türkiye'),
(55,	'Samsun',	'Türkiye'),
(56,	'Siirt',	'Türkiye'),
(57,	'Sinop',	'Türkiye'),
(58,	'Sivas',	'Türkiye'),
(59,	'Tekirdağ',	'Türkiye'),
(60,	'Tokat',	'Türkiye'),
(61,	'Trabzon',	'Türkiye'),
(62,	'Tuncelİ',	'Türkiye'),
(63,	'Şanliurfa',	'Türkiye'),
(64,	'Uşak',	'Türkiye'),
(65,	'Van',	'Türkiye'),
(66,	'Yozgat',	'Türkiye'),
(67,	'Zonguldak',	'Türkiye'),
(68,	'Aksaray',	'Türkiye'),
(69,	'Bayburt',	'Türkiye'),
(70,	'Karaman',	'Türkiye'),
(71,	'Kirikkale',	'Türkiye'),
(72,	'Batman',	'Türkiye'),
(73,	'Şirnak',	'Türkiye'),
(74,	'Bartin',	'Türkiye'),
(75,	'Ardahan',	'Türkiye'),
(76,	'Iğdir',	'Türkiye'),
(77,	'Yalova',	'Türkiye'),
(78,	'Karabük',	'Türkiye'),
(79,	'Kilis',	'Türkiye'),
(80,	'Osmaniye',	'Türkiye'),
(81,	'Düzce',	'Türkiye');

DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `module` (`id`, `module_name`) VALUES
(1,	'product'),
(2,	'content'),
(3,	'company');

DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `page` (`id`, `name`) VALUES
(17,	'Hakkımızda');

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `season_id` int(11) unsigned NOT NULL,
  `total` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product` (`id`, `name`, `description`, `season_id`, `total`) VALUES
(1,	'Bir Aylık Gitar Eğitimi',	'<div class=\"free-area\">\n	<table cellspacing=\"0\" class=\"deal-content\">\n		<tbody>\n			<tr>\n				<td class=\"deal-desc\">\n					<h3>\n						Bilindik hediyelerin ötesine geçin!</h3>\n					<p>\n						Önceleri saygının ifadesi olarak bilinen ve yayılan hediye verme geleneği, insanlık tarihinden günümüze kadar saygıdan öte sevginin en güzel tarifi arasında yerini alıyor. Sevginin ve ilginin en güzel halini çikolatalar ve çiçekler betimlerken, günümüzün sınırsız hayal gücü ile sonuçlanan müthiş bir ikili karşımıza çıkıyor! Çikolata, meyve, şekerleme ve aklınıza gelebilecek nice lezzetlerle donatılan çiçek buketleri! Kim düşünürdü ki, rengarenk bir buketi yiyebileceğinizi?!</p>\n					<p>\n						Güzel, sağlıklı, iyi ve hoş anlamına gelen <a href=\"http://www.bonnyfood.com/\" target=\"_blank\">Bonny</a>&#39;in enfes lezzetlere büründüğü hali; <a href=\"http://www.bonnyfood.com/\" target=\"_blank\">Bonnyfood</a> ile aklınızı alacak, gönlünüzü çelecek envai çeşit buketlerle size muhteşem bir şölen sunuyoruz! Bu şölende; sizleri yumuşacık, hafif ve mis gibi aromalarıyla marshmallow çiçekleri, kakaolu, hindistan cevizli ve tarçınlı olmak üzere tamamen doğal malzemelerle üretilen kurabiye çiçekleri, enfes aromalar ve çikolatalarla kaplanıp damak zevkinize sunulan çikolatalı çilek çiçekleri ve daha neler neler bekliyor...</p>\n					<p>\n						<em><strong>Grupanya&#39;dan renkli ve tatlı hediye cennetinden muhteşem bir fırsat; <a href=\"http://www.bonnyfood.com/\" target=\"_blank\">Bonnyfood</a>&#39;dan yapacağınız 30 TL değerindeki alışverişlerinize 3 TL ödeyin! <a href=\"http://www.bonnyfood.com/\" target=\"_blank\">Bonnyfood</a>&#39;un hediyelik lezzet çiçeklerinden sevdiklerinize armağan edeceğiniz enfes buketler ile damaklara tat katın, bu fırsatı sakın kaçırmayın!</strong></em></p>\n					<h4>\n						<a href=\"http://www.bonnyfood.com/\" target=\"_blank\">Bonnyfood</a>&#39;dan alabileceğiniz leziz hediyelik buketler;</h4>\n					<div>\n						<img height=\"200\" src=\"http://www.grupanya.com/DealImages/bonnyfood/14765.jpg\" width=\"450\" /><br />\n						&nbsp;</div>\n					<p>\n						Sol: Taze meyvelerle hazırlanmış özel bir aranjman</p>\n					<p>\n						Sağ: Yumuşak ve lezzetli çiçekler bir arada!</p>\n					<div>\n						<img height=\"225\" src=\"http://www.grupanya.com/DealImages/bonnyfood/28227.jpg\" width=\"450\" /><br />\n						&nbsp;</div>\n					<p>\n						Sol: &quot;İyi ki doğdun, iyi ki doğdun!.. Mutlu yıllar sana...&quot;</p>\n					<p>\n						Sağ: Altın varaklı saksıda badem tozu ve kakaodan yapılmış, inanılmaz kırmızı kek güller!</p>\n					<div>\n						<img height=\"225\" src=\"http://www.grupanya.com/DealImages/bonnyfood2/a2282.jpg\" width=\"450\" /><br />\n						&nbsp;</div>\n					<p>\n						Sol: Anneniz için çikolata kaplı kurabiyelerle oluşturulmuş lezzet dolu bir aranjman. Bu aranjman yaklaşık 55-60 adet kurabiye çiçeği içeriyor.</p>\n					<p>\n						Sağ: Özel kutusu içinde 12 adet bitter kaplı işlemeli kestane çiçeği, 12 adet vanilya kaplı işlemeli kestane çiçeği bulunuyor.</p>\n					<div>\n						<img height=\"200\" src=\"http://www.grupanya.com/DealImages/bonnyfood2/35702.jpg\" width=\"450\" /><br />\n						&nbsp;</div>\n					<p>\n						Sol: Kutu içerisinde 12 adet çikolata kaplı kalp ananas dilimi, 8 adet vanilya, frambuaz aromalı çikolata ile işlemeli çilek, 4 adet vanilya aromalı çikolata ile işlenmiş çilek bulunuyor.</p>\n					<p>\n						Sağ: Çikolatalı çileklere doyamayacaksınız... Kutu içerisinde 24 adet bitter çikolata kaplı çilek bulunuyor.</p>\n					<h4>\n						Güzel, sağlıklı, iyi, hoş; Bonny..</h4>\n					<p>\n						<strong>Keyifli bir görsel şölen sunan yenebilir bu buketler gönülleri fethedecek!</strong></p>\n					<p>\n						<a href=\"http://www.bonnyfood.com/\" target=\"_blank\">Bonnyfood</a>, sevdiklerinize hediye olarak göndermek isteyebileceğiniz lezzetli çiçekler üretiyor. Kek Çiçekleri, Çikolatalı Meyve Çiçekleri, Kurabiye Çiçekleri, Çikolata Şeker Çiçekleri ve Çikolata süslü Çilek Çiçekleri gibi leziz ürünleri dostlarınıza, iş arkadaşlarınıza, ailenize ve tüm sevdiklerinize hoş ve farklı hediyeler olarak gönderebilir; hem akılda hem de ağızda değişik tatlar bırakabilirsiniz.</p>\n					<p>\n						<strong>Bonnyfood markası nasıl oluştu?</strong></p>\n					<p>\n						Klasik hediyeler veya &quot;sade bir çiçek&quot; gönderme anlayışı, yurt dışında olduğu gibi artık ülkemizde de yavaş yavaş değişiyor. Çiçekten vazgeçemiyoruz ama aynı zamanda farklı türde bir hediye de sunmak istiyoruz. İşte <a href=\"http://www.bonnyfood.com/\" target=\"_blank\">Bonnyfood</a> bu anlayıştan hareketle doğdu. Artık Bonnyfood Lezzet Çiçekleri ile sevdiklerinize hem &quot;çiçek görselliği&quot; hem de lezzetli ve keyifli tatlar hediye ederek fark yaratabilir; böylece haklı ve övgü dolu teşekkürler alabilirsiniz.</p>\n					<p>\n						<strong>Ürünler;</strong></p>\n					<ul>\n						<li>\n							Hijyenik şartlarda üretiliyor.</li>\n						<li>\n							Katkı maddesi ve koruyucu madde içermediğinden taze tüketilmesi önerilir.</li>\n						<li>\n							Kullanılan çikolata, şekerleme ve gıda boyalarının tümü Türk Gıda Kodeksine uygundur.</li>\n					</ul>\n					<p>\n						Keyif yaratmak ve bunu sevdiklerinizle buluşturmak dileğiyle <a href=\"http://www.bonnyfood.com/\" target=\"_blank\">Bonnyfood</a> enfes tatlarla gününüzü lezzetlendirmeye ve renklendirmek için sizi bekliyor!</p>\n				</td>\n				<td class=\"deal-places\">\n					<p>\n						<strong>Bonnyfood</strong></p>\n					<p>\n						Adres: Tüm Türkiye</p>\n					<p style=\"text-align: center;\">\n						<a href=\"http://www.bonnyfood.com\" target=\"_blank\" title=\"www.bonnyfood.com\">www.bonnyfood.com</a></p>\n					<p>\n						&nbsp;</p>\n				</td>\n			</tr>\n		</tbody>\n	</table>\n	<div class=\"clear\">\n		&nbsp;</div>\n</div>\n',	1,	5);

DROP TABLE IF EXISTS `result`;
CREATE TABLE `result` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `session_id` int(11) unsigned NOT NULL,
  `click_counter` int(4) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `session_id` (`session_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `season`;
CREATE TABLE `season` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `season_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `showon_mainpage` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `order` tinyint(2) NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `click_per_user` tinyint(2) NOT NULL,
  `click_per_product` tinyint(2) NOT NULL,
  `total_click` int(5) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `season_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `season` (`id`, `season_name`, `showon_mainpage`, `status`, `order`, `company_id`, `click_per_user`, `click_per_product`, `total_click`, `start_date`, `end_date`) VALUES
(1,	'Gitar Eğitimi',	1,	1,	1,	1,	10,	50,	20000,	'2011-03-06 10:03:53',	'2011-03-06 10:03:53'),
(2,	'Yüzme Kursus',	1,	0,	0,	2,	20,	70,	127,	'2011-03-29 00:00:00',	'2011-03-31 00:00:00');

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `season_id` int(11) unsigned NOT NULL,
  `session_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_limit` tinyint(2) NOT NULL,
  `click_limit` tinyint(2) NOT NULL,
  `award_numbers` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  CONSTRAINT `session_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `session` (`id`, `season_id`, `session_name`, `product_limit`, `click_limit`, `award_numbers`, `start_date`, `end_date`) VALUES
(1,	1,	'Yüzme Öğrenmek İsteyenler İçin Müthiş Fırsat',	4,	20,	'5|10|15|20',	'2011-05-19 22:50:15',	'2011-05-27 15:38:56'),
(2,	1,	'Gitar 2 ',	1,	50,	'30',	'2011-05-26 00:00:00',	'2011-05-27 00:00:00');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tc` bigint(11) NOT NULL,
  `birthdate` int(4) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(11) unsigned NOT NULL,
  `phone` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tc` (`tc`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `password`, `name`, `surname`, `email`, `tc`, `birthdate`, `address`, `location_id`, `phone`, `status`, `register_date`) VALUES
(2,	'admin',	'40bd001563085fc35165329ea',	'Salih',	'Özkul',	'mail@yahoo.com',	127,	127,	'asasd',	1,	'0505555555',	0,	'2011-04-03 17:41:20'),
(3,	'panel5_user',	'40bd001563085fc35165329ea',	'Salih',	'Özkul',	'mail@yahoo.com',	2234,	127,	'',	1,	'0505555555',	0,	'2011-04-06 23:38:40');

-- 2011-06-24 02:15:36
