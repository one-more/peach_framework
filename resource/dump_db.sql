-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: placorama
-- ------------------------------------------------------
-- Server version	5.5.40-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `RU_categories`
--

DROP TABLE IF EXISTS `RU_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RU_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RU_categories`
--

LOCK TABLES `RU_categories` WRITE;
/*!40000 ALTER TABLE `RU_categories` DISABLE KEYS */;
INSERT INTO `RU_categories` VALUES (1,'открытый воздух'),(2,'кафе, бары'),(3,'рестораны'),(4,'спорт'),(5,'красота и здоровье');
/*!40000 ALTER TABLE `RU_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RU_place_comments`
--

DROP TABLE IF EXISTS `RU_place_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RU_place_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `place` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL,
  `user` bigint(20) unsigned NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` text COLLATE utf8_unicode_ci,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RU_place_comments`
--

LOCK TABLES `RU_place_comments` WRITE;
/*!40000 ALTER TABLE `RU_place_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `RU_place_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RU_places`
--

DROP TABLE IF EXISTS `RU_places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RU_places` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `preview` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '/media/images/no_place_preview.png',
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Россия',
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Москва',
  `images` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `sum` double(20,2) NOT NULL DEFAULT '0.00',
  `sum_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category` bigint(20) unsigned NOT NULL DEFAULT '0',
  `likes` int(10) unsigned NOT NULL DEFAULT '0',
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RU_places`
--

LOCK TABLES `RU_places` WRITE;
/*!40000 ALTER TABLE `RU_places` DISABLE KEYS */;
INSERT INTO `RU_places` VALUES (1,'Шоколад','«Шоколад» — это молодая сеть городских кафе, где каждый может не только вкусно поесть, но и просто приятно провести время\r\nВ спальном районе мы ждем Вас всей семьей. Взрослые отдыхают, а малыши развлекаются в детской комнате под присмотром воспитателя. Вблизи бизнес-центров мы предложим Вам бизнес-ланчи, как японские, так и европейские. А каждый новый день предлагаем Вам начать с удовольствия – уже к 4-ем утра приготовив для Вас горячие блинчики и другие завтраки.\r\nМеню кафе «Шоколад» лаконично сочетает в себе японские и европейские блюда, которые давно не являются диковинкой, но, тем не менее, привлекают там, где они действительно сделаны с душой — \r\n «Шоколад» это гарантирует.','Страстной бул., 4\nМосква','chocolatemoscow.ru','/var/www/placorama.ru/www/media/images/places/36d9f10f7d60e18b24e513a4f40d1d12/171.jpg','Россия','Москва','/var/www/placorama.ru/www/media/images/places/36d9f10f7d60e18b24e513a4f40d1d12',1,1500.00,'средний чек',2,0,'8 (495) 718-79-10'),(2,'Экслибрис','Уютное кафе в здании Тургеневской библиотеки, небольшое на первый взгляд, обладает внушительными возможностями: круглый и квадратный залы встречают любителей посидеть в уголке, банкетный зал с отдельным входом способен принять серьёзные мероприятия, от свадьбы и юбилея до семинаров, деловых завтраков и небольших конференций, чему способствует наличие звукоусиливающей аппаратуры и видеопроектора. А совсем уж огромные действа происходят в читальном зале библиотеки, откуда в одно мгновение выносятся столы и светильники, заносятся стулья и оборудование - и вуаля, концертная площадка, готовая принять целый фестиваль, открыта! При этом в кафе можно продолжать бегать пить кофе (или не кофе) - благо, соседняя дверь.','Большая Грузинская улица, Д.2/12\r\nМосква','www.exlibriscafe.ru','/var/www/placorama.ru/www/media/images/places/78946e60c6a695ffe3d0a989c6164573/b1cab4d2c9.jpg','Россия','Москва','/var/www/placorama.ru/www/media/images/places/78946e60c6a695ffe3d0a989c6164573',1,0.00,'',2,0,''),(3,'кафе “МАРТ”','Кафе \"МАРТ\" открылось 8 июля 2010 года на нижнем уровне Московского музея современного искусства на Петровке в историческом особняке архитектора Матвея  Казакова. Оно объединило несколько, на первый взгляд, не очень связанных между собой проектов. Под одной крышей собраны кафе, выставочная площадка, винный рынок, музыкальный клуб и оранжерея. Их соединение - это органическое продолжение многоуровневого и запутанного пространства, где сама архитектура диктует его концептуальное построение: вместе отлично уживаются и детские праздники по воскресеньям, и периодически сменяющие друг друга выставки самых разных художников (среди которых - Александра Митлянская, Мария Арендт, Константин Тотибадзе, Татьяна Везел-Игнатова и другие), и джазовые концерты по четвергам, и \"Мастерская Кати Гердт\" - дизайнерское бюро и шоу-рум мебели и предметов интерьера из французского Прованса и Европы. \r\nСамо кафе состоит из трех залов - полутемного бара, где можно просто выпить рюмку; выставочного зала, где по вечерам в выходные устраиваются танцы, и зала со сводами и пианино, прекрасно подходящего для спокойного обеда или ужина.\r\nОтдельного внимания заслуживает \"Оранжерея\" - большая крытая веранда во дворе Музея современного искусства. Именно там проходят концерты, детские праздники, литературные презентации и другие события. Веранда работает и зимой, а с мая по сентябрь во дворе открывается летнее кафе, способное вместить огромное количество любителей прекрасного.\r\nПопасть в кафе, находящееся на цокольном этаже особняка, можно \r\nс Петровки, а можно и прямо из залов Музея современного искусства - \r\nна лифте. Кроме того, в  кафе есть и отдельный вход через \"Оранжерею\", расположенную во внутреннем дворике музея.','Палашевский Малый переулок, 6\nМосква','','/var/www/placorama.ru/www/media/images/places/d1e1426f87e5439e8d6c0b2f85b95e8c/1.jpg','Россия','Москва','/var/www/placorama.ru/www/media/images/places/d1e1426f87e5439e8d6c0b2f85b95e8c',1,0.00,'',2,0,''),(4,'«КАФЕ МАНОН»','Культовое московское заведение, известное своими вечеринками, эклектичным интерьером с дворцовыми потолками и великолепной панорамной террасой, меняет свой формат. До сих пор «Кафе Манон» представляло собой сочетание ресторана высокой кухни и ночного клуба. Сейчас второй этаж с танцполом закрывается на реконструкцию, но DJ-сеты сохранятся в качестве музыкального фона. «Кафе Манон» –первый гастрономический ресторан, где разрешены танцы. Музыка по-прежнему является важной составляющей нашей жизни - каждый четверг, пятницу и субботу ужин будет сопровождаться небольшим концертом. Но основное внимание мы уделяем развитию гастрономической составляющей. Долгое время кухня «Кафе Манон» не имела своего лица. Теперь ресторан обзавелся собственным шеф-поваром в лице именитого Michele Brogi','переулок Старопименовский, 11/6, строение 1\nМосква','','/var/www/placorama.ru/www/media/images/places/32ac4575fd6db7beab04ddc6be180380/lt(9).jpg','Россия','Москва','/var/www/placorama.ru/www/media/images/places/32ac4575fd6db7beab04ddc6be180380',1,0.00,'',2,0,''),(5,'КАФЕ БАР «ЧЕРРИ»','Есть очень уютное кафе бар в Одинцово с красивым названием Cherry. Посетите его и убедитесь в том, что нет прекраснее места для отдыха или организации торжества. Широкий ассортимент вкусных блюд вас приятно удивит. В зависимости от ваших предпочтений, можно выбрать европейскую, итальянскую, японскую или восточную кухню. Блюда, приготовленные на открытом огне, определенно, понравятся ценителям. Наш кафе-бар в Одинцово обладает тихим и уютным интерьером, который помогает расслабиться и отвлечься, забыть о проблемах и вечной городской суете. Если гости хотят уединиться, то они могут это сделать в европейском зале. Восточный зал рассчитан на 30-40 человек очень комфортный и хорошо подходит для небольших торжеств. Меню нашего кафе включает в себя более 300 наименований блюд европейской и японской кухни, в баре доступно более 100 видов алкогольных и безалкогольных напитков и коктейлей.','ул. 1-я Брестская, 62\nМосква','','/var/www/placorama.ru/www/media/images/places/97fbf06f174e57e172ad5edf28d967e6/191.jpg','Россия','Москва','/var/www/placorama.ru/www/media/images/places/97fbf06f174e57e172ad5edf28d967e6',1,0.00,'',2,0,''),(6,'кафе «SKвер»','Кафе \"SKвер\" - изысканное заведение, сочетающее в себе комфорт, первоклассное обслуживание и вкусную еду. Кажется, что тут каждый уголок помогает отвлечься от ежедневных проблем: тихая музыка, современный дизайн и гостеприимная атмосфера.\r\nРазнообразие меню поражает. В кафе вы можете отведать средиземноморскую кухню, итальянскую, греческую, грузинскую и фьюжн кухню. И все это возможно благодаря искусной работе шеф-повара кафе.','бульвар Страстной, 4/3, стр. 3\nМосква','','/var/www/placorama.ru/www/media/images/places/f96bde4257afdc053d449eda6ca3fc9b/EV_2014-10-10_1_9516.jpg','Россия','Москва','/var/www/placorama.ru/www/media/images/places/f96bde4257afdc053d449eda6ca3fc9b',1,0.00,'',2,0,''),(7,' test6',' test1','Большой Козихинский пер., 18\nМосква','','/var/www/placorama.ru/www/media/images/places/f601d520549b9703ad874479cbc604db/418294a5357684f4ce7a94ebf9c50c91.png','Россия','Москва','/var/www/placorama.ru/www/media/images/places/f601d520549b9703ad874479cbc604db',1,0.00,'',2,0,''),(8,' test7',' test1',' test1','','/var/www/placorama.ru/www/media/images/places/d3c7f916a1d518001e8ef592c65e2399/418294a5357684f4ce7a94ebf9c50c91.png','Россия','Москва','/var/www/placorama.ru/www/media/images/places/d3c7f916a1d518001e8ef592c65e2399',1,0.00,'',2,0,''),(9,' test8',' test1',' test1','','/var/www/placorama.ru/www/media/images/places/8421c2c2a702a8939eaa244978701ae1/418294a5357684f4ce7a94ebf9c50c91.png','Россия','Москва','/var/www/placorama.ru/www/media/images/places/8421c2c2a702a8939eaa244978701ae1',1,0.00,'',2,0,''),(10,' test9',' test1',' test1','','/var/www/placorama.ru/www/media/images/places/606685148782ee6eac6ef99d78783dde/418294a5357684f4ce7a94ebf9c50c91.png','Россия','Москва','/var/www/placorama.ru/www/media/images/places/606685148782ee6eac6ef99d78783dde',1,0.00,'',2,0,''),(11,' test11',' test11',' test11','','/var/www/placorama.ru/www/media/images/places/0e4dafee549642bc955c790f6e20ecf9/606f17b079afe157a92ac8c01eb3180b.png','Россия','Москва','/var/www/placorama.ru/www/media/images/places/0e4dafee549642bc955c790f6e20ecf9',1,8000.00,'в месяц',4,0,'');
/*!40000 ALTER TABLE `RU_places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorite_places`
--

DROP TABLE IF EXISTS `favorite_places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorite_places` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `place` bigint(20) unsigned NOT NULL,
  `user` bigint(20) unsigned NOT NULL,
  `place_table` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'RU_places',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorite_places`
--

LOCK TABLES `favorite_places` WRITE;
/*!40000 ALTER TABLE `favorite_places` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorite_places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `language_vars`
--

DROP TABLE IF EXISTS `language_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language_vars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `var_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `var_value` text COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `key_lang` (`var_key`,`lang`),
  KEY `page` (`page`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language_vars`
--

LOCK TABLES `language_vars` WRITE;
/*!40000 ALTER TABLE `language_vars` DISABLE KEYS */;
INSERT INTO `language_vars` VALUES (13,'IndexPage','login_btn_caption','войти','RU'),(14,'IndexPage','login_label','логин','RU'),(15,'IndexPage','password_label','пароль','RU'),(16,'IndexPage','sign_up_btn_caption','регистрация','RU'),(17,'IndexPage','email_label','email','RU'),(18,'IndexPage','login_error','неверный логин или пароль','RU'),(19,'IndexPage','sign_up_error','пользователь с таким логином уже существует','RU'),(20,'IndexPage','user_messages_link_label','сообщения','RU'),(21,'IndexPage','user_events_link_label','встречи','RU'),(22,'IndexPage','user_exit_link_label','выход','RU'),(23,'IndexPage','report_error_btn','Сообщить об ошибке','RU'),(24,'IndexPage','to_main_btn','Главная','RU'),(25,'IndexPage','up_btn','Вверх','RU'),(26,'IndexPage','advertisement_btn','Реклама','RU'),(27,'IndexPage','user_add_place_link_label','добавить место','RU'),(28,'IndexPage','user_add_category_link_label','добавить категорию','RU'),(29,'IndexPage','user_moderate_place_link_label','модерировать места','RU'),(30,'IndexPage','login_exists_error','логин занят','RU'),(31,'IndexPage','email_exists_error','email уже используется','RU'),(32,'IndexPage','invalid_email_error','некорректный email','RU'),(33,'IndexPage','name_label','Имя','RU'),(34,'IndexPage','last_name_label','Фамилия','RU'),(35,'IndexPage','next_label','далее','RU'),(36,'IndexPage','sign_up_finish_header','Регистрация почти завершена','RU'),(37,'IndexPage','sign_up_finish_text','Для завершения регистрации пройдите по ссылке в письме, которое мы выслали вам на почту','RU'),(40,'Errors','to_main','на главную','RU'),(41,'Errors','title','что-то пошло не так','RU'),(42,'Errors','invalid_link','ссылка недействительна','RU'),(43,'StubTpl::index','use_db','use db param is true','EN'),(44,'StubTpl::index','users','users in system','EN'),(45,'StubTpl::index','login','login','EN'),(46,'StubTpl::index','password','password','EN'),(47,'StubTpl::index','credentials','credentials','EN'),(48,'StubTpl::index','remember hash','remember hash','EN'),(49,'StubTpl::index','use_db','база данных подключена','RU'),(50,'StubTpl::index','users','пользователи в системе','RU'),(51,'StubTpl::index','login','логин','RU'),(52,'StubTpl::index','password','пароль','RU'),(53,'StubTpl::index','credentials','привилегии','RU'),(54,'StubTpl::index','remember_hash','хэш идентификации','RU'),(55,'AddPlacePage','add_category_btn_label','добавить категорию','RU'),(56,'AddPlacePage','no_categories_label','В данный момент невозможно добавить место, так как на сайте нет категорий. Обратитесь к администрации сайта.','RU'),(57,'Categories','add_category_header','Добавить категорию','RU'),(58,'AddPlacePage','select_category_label','выберите категорию','RU'),(59,'Frontend','request_error','ошибка запроса','RU'),(60,'Frontend','category_exists','категория существует','RU'),(61,'AddPlacePage','add_preview_label','добавить фото','RU'),(62,'AddPlacePage','load_gallery_label','загрузить изображения для галереи','RU'),(63,'AddPlacePage','description_label','Описание','RU'),(64,'AddPlacePage','name_label','Название','RU'),(65,'AddPlacePage','address_label','Адрес','RU'),(66,'AddPlacePage','currency_label','руб','RU'),(67,'PaymentTypes','average_bill','средний чек','RU'),(68,'PaymentTypes','day','оплата в день','RU'),(69,'PaymentTypes','week','оплата в неделю','RU'),(70,'PaymentTypes','month','оплата в месяц','RU'),(71,'AddPlacePage','select_category','выберите категорию','RU'),(72,'AddPlacePage','add_preview','выберите фото','RU'),(73,'AddPlacePage','add_description','введите описание','RU'),(74,'AddPlacePage','add_name','введите название','RU'),(75,'AddPlacePage','add_address','введите адрес','RU'),(76,'AddPlacePage','add_gallery','выберите изображения для галереи','RU'),(77,'AddPlacePage','site_label','Сайт','RU'),(78,'AddPlacePage','place_added','место успешно добавлено','RU'),(79,'AddPlacePage','place_exists','такое место существует','RU'),(80,'Frontend','image_size_exceeds','размер изображения не должен превышать','RU'),(81,'Frontend','images_size_exceeds','размер изображений не должен превышать','RU'),(82,'Errors','forbidden','На данную страницу могут заходить только зарегистрированные пользователи','RU'),(83,'Errors','old_browser','Ваш браузер устарел. Пожалуйста, скачайте актуальную версию браузера.  <a href=\"http://www.opera.com/download\">opera</a>, <a href=\"https://www.google.ru/chrome/browser/desktop/index.html\">chrome</a>, <a href=\"https://www.mozilla.org/ru/firefox/new/\">firefox</a>','RU'),(84,'PlacesPage','no_places','В систему пока не добавлено ни одного места','RU'),(85,'PlacesPage','find_place','Найдите лучшее место','RU'),(86,'IndexPage','site_description','Сайт является рекомендательным сервисом мест: от парков и кафе до спортивных секций.','RU'),(87,'IndexPage','user_profile_link_label','профиль','RU'),(88,'IndexPage','user_add_event_link_label','создать встречу','RU'),(89,'IndexPage','login_form_header','Войти в систему','RU'),(90,'IndexPage','sign_up_form_header','Зарегистрироваться','RU'),(91,'IndexPage','name_and_last_name_label','имя и фамилия','RU'),(92,'IndexPage','avatar_label','выберите аватар','RU'),(93,'PlacesPage','more_label','подробнее','RU'),(94,'PLacesPage','added_to_favorite','место добавлено в избранное','RU'),(95,'PLacesPage','must_be_login_to_add_favorite','войдите или зарегистрируйтесь, чтобы добавлять места в избранное','RU'),(96,'PlacePage','description_label','Описание','RU'),(97,'PlacePage','address_label','Адрес','RU'),(98,'PlacePage','info_label','Контактная информация','RU'),(99,'PlacePage','currency_label','руб','RU'),(100,'PlacePage','comments_label','Комментарии','RU'),(101,'PLacePage','no_comments_label','Комментариев пока нет','RU'),(102,'PLacePage','add_comment_label','Добавить комментарий','RU'),(103,'PlacePage','enter_comment_text','введите текст комментария','RU'),(104,'PlacePage','reply_label','Ответить','RU'),(105,'PlacePage','edit_label','Редактировать','RU'),(106,'PlacePage','delete_label','Удалить','RU');
/*!40000 ALTER TABLE `language_vars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  `vars` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
INSERT INTO `session` VALUES (1,9,'2015-01-06 12:15:48','{\"last_place_images_path\":\"\\/var\\/www\\/placorama.ru\\/www\\/media\\/images\\/places\\/0e4dafee549642bc955c790f6e20ecf9\"}'),(2,1,'2015-01-06 13:59:59',''),(3,1,'2015-01-06 14:01:56',''),(4,1,'2015-01-06 14:19:00',''),(5,1,'2015-01-06 16:42:06',''),(6,1,'2015-01-06 21:55:42',''),(7,0,'2015-01-06 21:55:45',''),(8,0,'2015-01-06 21:55:56',''),(9,0,'2015-01-06 21:56:57',''),(10,0,'2015-01-06 21:57:11',''),(11,0,'2015-01-06 21:57:30',''),(12,0,'2015-01-06 21:57:37',''),(13,0,'2015-01-06 21:58:46',''),(14,0,'2015-01-06 21:59:01',''),(15,0,'2015-01-06 21:59:21',''),(16,0,'2015-01-06 21:59:22',''),(17,1,'2015-01-06 22:01:44',''),(18,0,'2015-01-06 22:01:45',''),(19,0,'2015-01-06 22:01:49',''),(20,0,'2015-01-06 22:01:50',''),(21,0,'2015-01-06 22:01:50',''),(22,0,'2015-01-06 22:01:51',''),(23,0,'2015-01-06 22:02:04',''),(24,0,'2015-01-06 22:02:19',''),(25,0,'2015-01-06 22:02:30',''),(26,1,'2015-01-07 01:02:12',''),(27,0,'2015-01-07 16:35:42',''),(28,0,'2015-01-07 16:37:10',''),(29,0,'2015-01-07 16:37:16',''),(30,0,'2015-01-07 16:39:01',''),(31,0,'2015-01-07 16:39:22',''),(32,0,'2015-01-07 16:39:28',''),(33,0,'2015-01-07 16:42:07',''),(34,0,'2015-01-07 16:42:51',''),(35,6,'2015-01-08 00:24:31',''),(36,0,'2015-01-08 11:23:18',''),(37,0,'2015-01-08 11:48:06',''),(38,0,'2015-01-08 12:09:31',''),(39,8,'2015-01-08 12:15:47',''),(40,0,'2015-01-08 13:29:37',''),(41,0,'2015-01-08 20:50:40',''),(42,0,'2015-01-09 01:54:00',''),(43,1,'2015-01-10 05:37:45',''),(44,0,'2015-01-10 05:49:41',''),(45,0,'2015-01-10 06:02:25',''),(46,0,'2015-01-10 06:55:58',''),(47,9,'2015-01-11 14:22:20',''),(48,9,'2015-01-11 17:42:00',''),(49,9,'2015-01-11 18:30:45','');
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unconfirmed_users`
--

DROP TABLE IF EXISTS `unconfirmed_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unconfirmed_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` longtext COLLATE utf8_unicode_ci,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unconfirmed_users`
--

LOCK TABLES `unconfirmed_users` WRITE;
/*!40000 ALTER TABLE `unconfirmed_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `unconfirmed_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `credentials` enum('user','administrator','super_administrator') COLLATE utf8_unicode_ci DEFAULT 'user',
  `remember_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '/media/images/noavatar.png',
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `social_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `social_page` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sex` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `birthday` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `social_provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'root','63UBbHICRqA8w','super_administrator','$2y$10$W81vaw4frt1LPF01Bf0g5.qmTsLTDHl2YGlcbJsbkHHsX4zca0M7O','/media/images/noavatar.png','','','0','','','','',''),(5,'296652940530334facebook','7aYfygC.I5WO.','user','$2y$10$.Ae6U7uY1abnHCD6FcBZ8uG/68K9HOKOdQOSwg7cskHbQvV5xV.c2','/var/www/placorama.ru/www/media/images/avatars/e78038baff0b3b6789e2774dcee85960.png','Дмитрий','Николаев','296652940530334','greyjoi@yandex.ru','https://www.facebook.com/app_scoped_user_id/296652940530334/','мужской','','facebook'),(6,'12474816vk','4dEZqC1MQwxy6','user','$2y$10$Tpaut/B36IoHJhFv3q6.NuumFvIm0PNqmRZphxc8d/6s3B1GTlTqy','/var/www/placorama.ru/www/media/images/avatars/ca1c16b438d10f3fd01b9b90ae68d9e8.png','Дима','Николаев','12474816','','http://vk.com/id12474816','male','27.09.1992','vk'),(7,'163994610yandex','f4nX0prvcqqok','user','$2y$10$P3CA.Gzafu0aYMP7LACT4ecOwnoE/jbtkmvVLnr/pQOOgScoif3Ey','/media/images/noavatar.png','Дмитрий','Николаев','163994610','greyjoi@yandex.ru','','','','yandex'),(8,'101998756679590892293google','22yexmciR5UWw','user','$2y$10$7/ULiVk7ALQuKRdBX7ke.OF6/e2x3mJTaTHGwctRDDTQk9fDn0PXy','/var/www/placorama.ru/www/media/images/avatars/65b2ce7abb8a6bebd88ba2b5d5511134.png','Дмитрий','Николаев','101998756679590892293','zaebali.loginiv.net@gmail.com','https://plus.google.com/101998756679590892293','male','','google'),(9,'test','09al6vNID5onY','user','$2y$10$vkvCyMIJ7Smm/AC6rfFiyuNgSXC8eOxeZmU5jjKvFvrpot38HUZ6O','/media/images/avatars/39c70c402e822bdd279d427383057dea.png','Дмитрий','Николаев','','greyjoi@yandex.ru','','','','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-18 15:13:45
