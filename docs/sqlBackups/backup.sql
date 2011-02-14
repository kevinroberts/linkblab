/*
SQLyog Enterprise - MySQL GUI v8.05 RC 
MySQL - 5.1.47-community : Database - linkblab
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `blabs` */

DROP TABLE IF EXISTS `blabs`;

CREATE TABLE `blabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `head_title` varchar(150) DEFAULT NULL,
  `description` varchar(1500) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `read_only` int(1) DEFAULT '0' COMMENT 'if read only no one can post new links to this blab',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `blabs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `blabs` */

insert  into `blabs`(`id`,`user_id`,`title`,`head_title`,`description`,`date_created`,`read_only`) values (1,6,'pics','Pictures and Images','links with interesting pictures and images','2009-10-21 14:44:04',0),(2,6,'videos','Videos','A great place for video content of all kinds but, please, no politics.','2010-10-23 14:44:17',0),(3,6,'programming','programming','A blab for discussion and news about computer programming','2010-10-22 14:45:44',0),(4,6,'technology','technology','This category is for new developments and innovations in technology - if your post suits another category it should go there, not here.','2010-10-22 14:45:47',0),(5,6,'wtf','WTF','All links that make you go what the f@&amp;$?!!','2010-10-22 14:45:44',0),(6,6,'gaming','gaming','Links about gaming! Trailers, release info, replays all can be found here','2010-10-22 14:45:44',0),(7,6,'music','Music','All things musical.','2010-10-22 14:45:44',0),(8,6,'webdev','Web Development','Links relating to the latest web development trends, technologies and techniques.','2010-10-24 14:10:02',0),(9,6,'wikipedia','wikipedia','The Most Interesting Pages on Wikipedia','2010-10-24 14:13:59',0),(10,6,'self','self.blab','A place to put self-posts for discussion, questions, or anything else you like.  Please report spam. It helps us remove them more quickly for an enhanced reading experience.','2010-10-24 14:17:20',0),(11,6,'apple','Apple','All links Apple Computer related.','2010-10-24 14:23:50',0),(12,6,'scifi','Science Fiction','Science Fiction, or Speculative Fiction if you prefer. Fantasy too.  PLEASE DO NOT POST SPOILERS IN YOUR SUBMISSION TITLE. IT WILL BE REMOVED. If you see a title with a spoiler in it, downvote it as hard as you can and then message the moderators. I will delete it ASAP.','2010-10-24 14:41:29',0),(13,6,'trees','trees','Links about trees','2010-10-24 16:02:09',0),(14,6,'funny','Funny','the place for all links funny and humorous.','2010-10-24 14:10:02',0),(15,6,'webgames','Web Games','links to web games that are playable without downloading','2010-10-24 20:05:16',0),(22,6,'politics','Politics','links about U.S. politics','2010-10-24 20:05:16',0),(23,6,'all','all','All of the most popular links across every category','2010-10-31 12:41:12',0),(24,6,'random','random','a randomly selected category','2010-10-31 12:41:15',1),(25,6,'linkblab.com','Link Blab General Category','A place for all links.','2010-10-31 12:41:18',0);

/*Table structure for table `comment_history` */

DROP TABLE IF EXISTS `comment_history`;

CREATE TABLE `comment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_up` int(1) DEFAULT '1',
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_submitted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comment_history` (`comment_id`),
  CONSTRAINT `FK_comment_history` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `comment_history` */

insert  into `comment_history`(`id`,`vote_up`,`comment_id`,`user_id`,`date_submitted`) values (10,1,17,6,'2011-01-08 21:57:01'),(11,1,18,6,'2011-01-09 12:24:38'),(12,1,19,6,'2011-01-09 12:26:56'),(13,1,20,6,'2011-01-22 12:11:21'),(14,1,21,10,'2011-02-06 10:13:12'),(15,1,22,10,'2011-02-06 18:20:34'),(16,1,23,10,'2011-02-06 18:22:33'),(17,1,24,10,'2011-02-06 18:24:32'),(18,1,25,10,'2011-02-06 19:07:41'),(19,1,26,10,'2011-02-06 20:24:49');

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text,
  `up_votes` int(11) DEFAULT NULL,
  `down_votes` int(11) DEFAULT NULL,
  `votes` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `controversy` float DEFAULT NULL,
  `hot` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `link_id` (`link_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `comments` */

insert  into `comments`(`id`,`comment`,`up_votes`,`down_votes`,`votes`,`date_added`,`date_modified`,`deleted`,`user_id`,`link_id`,`parent_id`,`controversy`,`hot`) values (1,'Partial to the wall on what we was, as biled -- [b]Mrs Joe[/b] read this, and shaking his teeth, without which, I had a constitutional im- mensely relieved to the terror was so sud- den and her in its pole by hand in mind about?\' [quote=\"Kevin\"]My opinion of nothing of Bone, here and I don\'t mean pretty wide open, away somewhere in its effect the week; I was a person took up to death for me, `Stop half a jug in his lips and having been no more questions why he were all at A bitter sleet came along the[/quote]\r\n\r\noration over [color=blue]partial[/color] to seven to take of drinking at the company came. Mr Wopsle; and very insulting.\' (She was a thoughtful bite out on the definition like the sergeant. `And six? \' Mr. Pumblechook then ran home last turn of a pint; but I added this boy.\' `With you. Hob and spare grass, there with its being sworn, and who was so finely perceived and compared them at the doors of graW to-day, Joe apologetically drew the open it. But such times as being ashamed of bringing me to wear me on his bedding cut my chest, and pull',1,0,1,'2010-12-05 17:17:45',NULL,0,6,35,NULL,0.5,0.579017),(2,'test 2\r\ntest 2\r\ntest 2\ntest 2',1,0,1,'2010-12-05 17:18:45',NULL,0,6,35,1,0.5,0.559017),(3,'Test 3\r\nTest 3\nTest 3',1,0,1,'2010-12-05 17:18:45',NULL,0,6,35,NULL,0.5,0.559017),(4,'Test one two three',1,5,-4,'2010-12-05 17:18:45',NULL,0,6,35,2,0.5,0.559017),(5,'as E = MC[sup]2[/sup]',1,0,1,'2010-12-05 17:18:45',NULL,0,6,35,NULL,0.5,0.559017),(6,'Testing 4',1,2,-1,'2010-12-05 17:18:45',NULL,0,6,35,2,0.5,0.559017),(7,'agfsdfasfs',1,0,1,'2010-12-05 17:18:45',NULL,0,6,35,1,0.5,0.559017),(10,'Hello man :)\n\nI love [b]linkblab[/b]\r\n\r\n\r\n[olist]\r\n[li]Brush your teeth[/li]\r\n[li]Shave your beard[/li]\r\n[li]Go to sleep[/li]\r\n[/olist]',1,0,1,'2011-01-08 21:29:47',NULL,0,6,35,NULL,0.5,0.559017),(17,'Nullam in nisl eget [b]justo tempor[/b] pulvinar a sed orci. Sed venenatis, risus sed volutpat porttitor, ligula erat mollis ante, viverra egestas nisi nibh quis mauris. Aliquam commodo, orci et dignissim fermentum, mi diam bibendum turpis, vel bibendum risus elit vitae lectus. Quisque congue luctus erat pretium porttitor. Praesent purus libero, porta non blandit ac, eleifend sit amet tortor. Mauris mattis dui id arcu vehicula sit amet imperdiet libero sollicitudin. Duis tincidunt convallis tortor, a tempor lorem facilisis id. In eget nisi non neque adipiscing ultrices non quis velit. In posuere dictum arcu, eget hendrerit velit tempor vel. Phasellus eget eros at enim dignissim feugiat eget vel neque.\n\nCum sociis natoque penatibus et magnis [url=http://www.google.com]google[/url] dis parturient montes, nascetur ridiculus mus. Fusce consequat ullamcorper mattis. Etiam lobortis sem sit amet arcu convallis at cursus libero volutpat. Duis arcu felis, pretium in mollis quis, luctus ac tellus. Integer a nulla id quam consectetur pretium vitae eu quam. \n\n[quote]Curabitur a nunc eget tellus tincidunt mattis ac dignissim ante. Vestibulum varius dolor eget ligula scelerisque sit amet congue turpis laoreet. Phasellus eleifend, leo quis vehicula sollicitudin, magna dui lobortis leo, vitae aliquet metus est imperdiet ipsum.[/quote]\n\nSed id felis sapien. Vivamus scelerisque pharetra magna id congue. ',1,0,1,'2011-01-08 21:57:01',NULL,0,6,19,NULL,0.5,0.559017),(18,'It\'s funny because its true :P',1,0,1,'2011-01-09 12:24:38',NULL,0,6,1,NULL,0.5,0.559017),(19,'Sample linky [url=http://www.kevinroberts.us]kevin\'s homepage[/url]',1,0,1,'2011-01-09 12:26:56',NULL,0,6,13,NULL,0.5,0.559017),(20,'I love this quote by kevin man:\n[quote=\"kevin\"]You what they say about peds man, they smell.[/quote]',1,0,1,'2011-01-22 12:11:21',NULL,0,6,35,NULL,0.5,0.559017),(21,'This is the most complicated post.  8) \n\n[code]\nclass kevin {\nkf jsd oieajf;io jeriojf i;dijglkdjglkdfj;lkfsdjlkjlkjklsd;fjgioerj giodjfdfgs ldfk gioer jod\n}\n[/code]',1,0,1,'2011-02-06 10:13:12',NULL,0,10,14,NULL,0.5,0.559017),(22,'This is a test',1,0,1,'2011-02-06 18:20:34',NULL,0,10,35,NULL,0.5,0.559017),(23,'sadfasdfa',1,0,1,'2011-02-06 18:22:33',NULL,0,10,35,NULL,0.5,0.559017),(24,'dsfads dfsa ssda',1,0,1,'2011-02-06 18:24:32',NULL,0,10,35,NULL,0.5,0.559017),(25,'This is a better comment :)\r\n\r\nsdfs ',1,0,1,'2011-02-06 19:07:41','2011-02-06 22:26:52',1,10,42,NULL,0.5,0.559017),(26,'你好，你今天好吗？ \r\n\r\nsimplified chinese: for hello, how are you today?',1,0,1,'2011-02-06 20:24:49','2011-02-06 22:27:19',1,10,42,NULL,0.5,0.559017);

/*Table structure for table `link_history` */

DROP TABLE IF EXISTS `link_history`;

CREATE TABLE `link_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_up` tinyint(1) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_submitted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `link_history_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `link_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

/*Data for the table `link_history` */

insert  into `link_history`(`id`,`vote_up`,`link_id`,`user_id`,`date_submitted`) values (33,1,5,6,'2010-12-03 19:45:22'),(34,1,8,6,'2010-12-03 19:45:22'),(35,1,4,6,'2010-12-03 19:45:22'),(36,1,7,6,'2010-12-03 19:45:22'),(37,1,9,6,'2010-12-03 19:45:22'),(38,1,3,6,'2010-12-03 19:45:22'),(39,1,6,6,'2010-12-03 19:45:22'),(40,1,1,6,'2010-12-03 19:45:22'),(41,1,2,6,'2010-12-03 19:45:22'),(42,1,10,6,'2010-12-03 19:45:22'),(43,1,11,6,'2010-12-03 19:45:22'),(44,1,12,6,'2010-12-03 20:23:18'),(45,1,13,6,'2010-12-03 20:31:13'),(46,0,14,6,'2010-12-03 21:00:56'),(47,1,7,10,'2010-12-03 21:11:42'),(48,1,12,10,'2010-12-03 21:11:48'),(49,1,13,10,'2010-12-03 21:12:01'),(50,1,5,10,'2010-12-03 21:12:07'),(51,0,2,10,'2010-12-03 21:17:38'),(52,1,11,10,'2010-12-03 21:17:44'),(53,1,15,6,'2010-12-04 11:28:29'),(54,1,16,6,'2010-12-04 11:30:49'),(55,1,17,6,'2010-12-04 11:32:10'),(56,1,18,6,'2010-12-04 11:34:09'),(57,1,19,6,'2010-12-04 11:39:29'),(58,1,20,6,'2010-12-04 11:40:50'),(59,1,21,6,'2010-12-04 11:46:26'),(60,1,22,6,'2010-12-04 11:48:43'),(61,1,23,6,'2010-12-04 12:08:43'),(62,1,24,6,'2010-12-04 12:10:33'),(63,1,25,6,'2010-12-04 12:12:34'),(64,1,26,6,'2010-12-04 12:13:31'),(72,1,35,6,'2010-12-05 13:17:45'),(77,1,40,6,'2010-12-05 19:04:42'),(78,1,41,6,'2010-12-05 19:11:20'),(79,1,42,10,'2011-02-06 10:04:10'),(80,1,3,10,'2011-02-06 10:06:29'),(81,0,22,10,'2011-02-06 10:07:17'),(82,1,35,10,'2011-02-06 10:24:50');

/*Table structure for table `links` */

DROP TABLE IF EXISTS `links`;

CREATE TABLE `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_url` varchar(200) DEFAULT NULL,
  `thumbnail` varchar(200) DEFAULT NULL,
  `domain` varchar(150) DEFAULT NULL,
  `up_votes` int(11) DEFAULT '1',
  `down_votes` int(11) DEFAULT '0',
  `votes` int(11) DEFAULT '1',
  `title` varchar(150) DEFAULT NULL,
  `description` text,
  `date_created` datetime DEFAULT NULL,
  `blab_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_nsfw` int(1) DEFAULT '0' COMMENT 'is this link considered NSFW?',
  `is_self` int(1) DEFAULT '0' COMMENT 'is this a self-post',
  `times_reported` int(11) DEFAULT '0' COMMENT 'number of times this link has been reported as spam',
  `controversy` float DEFAULT '0.5',
  `hot` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `blab_id` (`blab_id`),
  CONSTRAINT `blab_id` FOREIGN KEY (`blab_id`) REFERENCES `blabs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `links` */

insert  into `links`(`id`,`link_url`,`thumbnail`,`domain`,`up_votes`,`down_votes`,`votes`,`title`,`description`,`date_created`,`blab_id`,`user_id`,`is_nsfw`,`is_self`,`times_reported`,`controversy`,`hot`) values (1,'http://imgur.com/PorNF.jpg',NULL,'imgur.com',4,0,4,'Every single day.','<p><a href=\"http://www.prguitarman.com/index.php?id=283\">LOL Comics</a></p> <p>The resemblance is uncanny.</p>','2010-11-11 15:11:00',14,6,0,0,0,0.8,3488.27),(2,'http://imgur.com/Yvn9S.gif',NULL,'imgur.com',5,1,4,'Deal With it.',NULL,'2010-11-12 16:13:14',12,6,0,0,0,1.2,3498),(3,'http://wimp.com/dubfx/','/images/thumbs/3.png','wimp.com',5,0,5,'Man loops his voice into spectacular song',NULL,'2010-11-14 14:00:00',2,6,0,0,0,0.833333,3622.01),(4,'http://imgur.com/Omdg8.jpg','/images/thumbs/4.png','imgur.com',4,0,4,'Good question!',NULL,'2010-11-14 14:10:00',1,6,0,0,0,0.8,3488.27),(5,'http://upload.wikimedia.org/wikipedia/commons/b/b5/London_%2C_Kodachrome_by_Chalmers_Butterfield_edit.jpg','/images/thumbs/5.png','upload.wikimedia.org',3,3,0,'London, 1949 (PIC) ',NULL,'2010-11-14 14:10:10',1,6,0,0,0,6,0),(6,'http://upload.wikimedia.org/wikipedia/commons/2/24/Lockheed_XF-104.jpg','/images/thumbs/6.png','upload.wikimedia.org',4,1,3,'The first XF-104',NULL,'2010-11-14 14:10:11',1,6,0,0,0,1.25,3488.14),(7,'http://upload.wikimedia.org/wikipedia/commons/f/fb/Stereographic_projection_of_Paris.jpg','/images/thumbs/7.png','upload.wikimedia.org',5,0,5,'SO TRIPPY (zoom in for full effect)',NULL,'2010-11-14 14:10:11',1,6,0,0,0,0.833333,3498.09),(8,'http://en.wikipedia.org/wiki/William_Leonard_Pickard',NULL,'en.wikipedia.org',4,0,4,'The man responsible for manufacturing 90% world\'s of LSD',NULL,'2010-11-14 14:10:11',9,6,0,0,0,0.8,3488.27),(9,'http://imgur.com/qJekB.jpg',NULL,'imgur.com',4,0,4,'Awesome Kitten.',NULL,'2010-11-14 16:01:27',1,6,0,0,0,0.8,3488.27),(10,'http://i.imgur.com/3uCT2.jpg',NULL,'i.imgur.com',4,1,3,'Pirates (NSFW)',NULL,'2010-11-14 20:52:33',14,6,1,0,0,1.25,3488.15),(11,'http://www.youtube.com/watch?v=9ieWrWLjii0',NULL,'www.youtube.com',2,0,2,'Sling Blade Has Nothing On This 65 Year Old Sling Shot Man',NULL,'2010-12-03 19:45:22',2,6,0,0,0,0.666667,3497.7),(12,'http://imgur.com/Fomvi.jpg',NULL,'imgur.com',2,0,2,'How to hack an electric road sign',NULL,'2010-12-03 20:23:18',1,6,0,0,0,0.666667,3497.69),(13,'http://hyperboleandahalf.blogspot.com/2010/11/dogs-dont-understand-basic-concepts.html',NULL,'hyperboleandahalf.blogspot.com',2,0,2,'Hyperbole and a Half: Dogs don\'t understand basic concepts like moving.',NULL,'2010-12-03 20:31:13',25,6,0,0,0,0.666667,3497.69),(14,NULL,NULL,NULL,1,1,0,'Finigans Wake yall','<p style=\"color: blue;\">Poldier, wishing oftebeen but how becrimed, becursekissed and Church. And you\'ll agree. She may rise you with her fluffballs safe in em, boaston nightgarters and other spring offensive on his tile to say, the bonny bawn blooches. This is interdum believed, a common thing.\r\n</p>\r\nWell, all sections and polarised fucking for me when he\'s plane member for bone, a parody\'s bird, a fish and blouseman business? Our cubehouse still open;\r\n\r\nthe third charm? And malers fuck abushed, keep of his footwear, say. Not unintoxicated, fair green the Base All, Nopper Tipped a yangster to bewray how the two peaches with katya when\r\n\r\n<script>alert(\'\');</script>','2010-12-03 21:00:56',10,6,0,1,0,2,0),(15,'http://www.flickr.com/photos/11032335@N00/5229202756',NULL,'www.flickr.com',1,0,1,'Redwood Eye',NULL,'2010-12-04 11:28:29',1,6,0,0,0,0.5,3498.54),(16,'http://www.flickr.com/photos/23563181@N06/5226940612#/photos/marknauta/5226940612/',NULL,'www.flickr.com',1,0,1,'Porsche Cayenne Turbo',NULL,'2010-12-04 11:30:49',1,6,0,0,0,0.5,3498.54),(17,'http://www.flickr.com/photos/soshiro/5228822140/',NULL,'www.flickr.com',1,0,1,'Camera.app [Explored]',NULL,'2010-12-04 11:32:10',1,6,0,0,0,0.5,3498.54),(18,'http://www.flickr.com/photos/36298698@N06/5226830086',NULL,'www.flickr.com',1,0,1,'Icy Embrace',NULL,'2010-12-04 11:34:09',1,6,0,0,0,0.5,3498.54),(19,'http://www.readwriteweb.com/archives/paypal_announces_it_will_no_longer_handle_wikileak.php',NULL,'www.readwriteweb.com',1,0,1,'PayPal Announces It Will No Longer Handle Wikileaks Donations',NULL,'2010-12-04 11:39:29',5,6,0,0,0,0.5,3498.55),(20,'http://www.flickr.com/photos/14551451@N00/5226746082',NULL,'www.flickr.com',1,0,1,'An Edinburgh Winter',NULL,'2010-12-04 11:40:50',1,6,0,0,0,0.5,3498.55),(21,'http://www.flickr.com/photos/93197965@N00/5228556617',NULL,'www.flickr.com',1,0,1,'Delicious Looking Latte',NULL,'2010-12-04 11:46:26',1,6,0,0,0,0.5,3498.56),(22,'http://www.flickr.com/photos/ashtonsterlingphotography/5228645714',NULL,'www.flickr.com',1,1,0,'Sweet cabaret.',NULL,'2010-12-04 11:48:43',1,6,1,0,0,2,0),(23,'http://www.flickr.com/photos/13547802@N05/5227669810',NULL,'www.flickr.com',1,0,1,'Spiked Aurora - Northern Lights near Bl&aacute;fj&ouml;ll, Iceland',NULL,'2010-12-04 12:08:43',1,6,0,0,0,0.5,3498.59),(24,'http://www.flickr.com/photos/lerefs/5223516924/',NULL,'www.flickr.com',1,0,1,'&lt;Under Reflex&gt;',NULL,'2010-12-04 12:10:33',1,6,0,0,0,0.5,3498.59),(25,'http://www.flickr.com/photos/stevenwalden/5223375290/',NULL,'www.flickr.com',1,0,1,'The lighthouse outside Blyth',NULL,'2010-12-04 12:12:34',1,6,0,0,0,0.5,3498.59),(26,'http://www.flickr.com/photos/eric5dmark2/5223491948/#/',NULL,'www.flickr.com',1,0,1,'griffith observatory',NULL,'2010-12-04 12:13:31',1,6,0,0,0,0.5,3498.6),(35,NULL,NULL,NULL,2,0,2,'Test of decoda','[b]Lorem ipsum dolor sit amet[/b], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.\r\n\r\n[i]Lorem ipsum dolor sit amet[/i], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.\r\n\r\n[quote][u]Lorem ipsum dolor sit amet[/u], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/quote]\r\n\r\n[quote=\"Miles\"][url=http://www.milesj.me]Lorem ipsum dolor sit amet[/url], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor.[/quote]\r\n\r\nHey I sure like emoticons :) :( n\'shit[sup]2[/sup]\r\n\r\nNeed to grab a quick bite to eat :brb:\r\n\r\nHarry potter spoiler test - Hover over the black box to see the spoiler [spoiler] Snape kills Dumbledore! This was the spoiler from Harry Potter, I hope it didn\'t ruin anything for you.[/spoiler]\r\n\r\nhttp://www.milesj.me\r\nwww.milesj.me\r\n\r\n[list]\r\n[li][url]http://www.milesj.me[/url][/li]\r\n[li]http://www.milesj.me[/li]\r\n[li]www.milesj.me[/li]\r\n[li][email]test@milesj.me[/email][/li]\r\n[li][mail]test@milesj.me[/mail][/li]\r\n[li][email=test@milesj.me]Email me![/email][/li]\r\n[/list]\r\n[code lang=\"php\" hl=\"15\"]/**\r\n * Parse the default markup depending on the allowed\r\n * @param string $string\r\n * @return string\r\n */\r\nprotected function parseDefaults($string) {\r\n	if (empty($this->allowed)) {\r\n		$code = $this->markupCode;\r\n		$result = $this->markupResult;\r\n	} else {\r\n		$code = array();\r\n		$result = array();\r\n		foreach ($this->markupCode as $tag => $regex) {\r\n			if (in_array($tag, $this->allowed)) {\r\n				$code[$tag] = $this->markupCode[$tag];\r\n\r\n				$result[$tag] = $this->markupResult[$tag];\r\n			}\r\n		}\r\n	}\r\n	\r\n	$string = preg_replace($code, $result, $string);\r\n	return $string;\r\n}[/code]','2010-12-05 13:17:45',10,6,0,1,0,0.666667,3621.63),(40,'http://www.flickr.com/photos/14516334@N00/345009210/',NULL,'www.flickr.com',1,0,1,'Honeybee Lift off',NULL,'2010-12-05 19:04:42',1,6,0,0,0,0.5,3501.06),(41,'http://www.flickr.com/photos/49895716@N08/5228930214',NULL,'www.flickr.com',1,0,1,'Zebra toys - Paper Craft',NULL,'2010-12-05 19:11:20',25,6,0,0,0,0.5,3501.07),(42,NULL,NULL,NULL,1,0,1,'Test Decoda code','This is a test of Decoda [url=http://qbnz.com/highlighter/]Geshi[/url] markup parsing. :)\r\n\r\n[code lang=\"php\"]\r\n<?php\r\nclass SimpleClass\r\n{\r\n    // property declaration\r\n    public $var = \'a default value\';\r\n\r\n    // method declaration\r\n    public function displayVar() {\r\n        echo $this->var;\r\n    }\r\n}\r\n?>\r\n[/code]','2011-02-06 10:04:10',3,10,0,1,0,0.5,3621.3);

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(32) NOT NULL,
  `save_path` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`,`save_path`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sessions` */

/*Table structure for table `subscriptions` */

DROP TABLE IF EXISTS `subscriptions`;

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `blab_id` int(11) NOT NULL,
  `display_order` int(11) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`blab_id`),
  KEY `blab_id` (`blab_id`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`blab_id`) REFERENCES `blabs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;

/*Data for the table `subscriptions` */

insert  into `subscriptions`(`id`,`user_id`,`blab_id`,`display_order`) values (17,1,1,1),(18,1,2,2),(19,1,3,3),(20,1,4,4),(21,1,5,5),(22,1,6,6),(23,1,7,7),(24,1,8,8),(25,1,9,9),(26,1,10,10),(27,1,11,11),(28,1,12,12),(29,1,13,13),(30,1,14,14),(31,1,15,15),(32,1,22,16),(53,6,8,6),(56,6,1,5),(57,6,13,7),(58,9,1,1),(59,9,25,2),(60,9,2,3),(61,9,3,4),(62,9,4,5),(63,9,5,6),(64,9,6,7),(65,9,7,8),(66,9,8,9),(67,9,9,10),(68,9,10,11),(69,9,11,12),(70,9,12,13),(71,9,13,14),(72,9,14,15),(74,6,12,4),(75,6,22,12),(76,6,3,3),(77,6,14,8),(78,6,11,2),(79,6,7,9),(80,6,5,10),(81,6,10,11),(82,6,25,1),(83,6,6,13),(84,6,4,14),(85,6,9,15),(87,6,2,16),(88,6,15,17),(89,10,1,1),(90,10,2,3),(91,10,3,4),(92,10,4,5),(93,10,5,6),(94,10,6,7),(95,10,7,8),(96,10,8,9),(97,10,9,10),(98,10,10,11),(99,10,11,12),(100,10,12,13),(101,10,13,14),(102,10,14,15),(103,10,25,2);

/*Table structure for table `user_meta` */

DROP TABLE IF EXISTS `user_meta`;

CREATE TABLE `user_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `lookup_id` int(11) DEFAULT NULL COMMENT 'optional reference id - ex blab id\nMost cases it will be used for referencing a blab.ID to specify which users are a moderator of that blab.',
  `key` varchar(50) NOT NULL,
  `value` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`key`),
  KEY `key` (`key`),
  KEY `lookup_id` (`lookup_id`),
  CONSTRAINT `user_meta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Data for the table `user_meta` */

insert  into `user_meta`(`id`,`user_id`,`lookup_id`,`key`,`value`) values (1,6,1,'moderator','1'),(3,6,2,'moderator','1'),(4,6,3,'moderator','1'),(5,6,4,'moderator','1'),(6,6,5,'moderator','1'),(7,6,6,'moderator','1'),(8,6,7,'moderator','1'),(9,6,8,'moderator','1'),(10,6,9,'moderator','1'),(11,6,10,'moderator','1'),(12,6,11,'moderator','1'),(13,6,12,'moderator','1'),(14,6,13,'moderator','1'),(15,6,14,'moderator','1'),(16,6,15,'moderator','1'),(17,6,22,'moderator','1'),(18,6,23,'moderator','1'),(19,6,25,'moderator','1');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) CHARACTER SET latin1 NOT NULL,
  `email` varchar(255) NOT NULL,
  `salt` varchar(50) CHARACTER SET latin1 NOT NULL,
  `role` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT 'basic',
  `date_created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `user_ip_address` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `password_reset_token` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`email`,`salt`,`role`,`date_created`,`last_login`,`user_ip_address`,`password_reset_token`,`password_reset_expires`) values (1,'admin','a62e14b4a19c17f89674ba6a12741eadc437c924','kevin@kevinroberts.us','ce8d96d579d389e783f95b3772785783ea1a9854','administrator','2010-09-12 21:39:55','2010-10-28 16:07:58','127.0.0.1',NULL,NULL),(6,'kevin','eead9b9e9d3133da5ea757d0f8eaafd2d67ef693','kevin@kevinroberts.us','3c7e7bc688e96b6c23bbb2cc1f0f91e3f08992ec','administrator','2010-10-21 18:10:30','2011-02-13 18:57:04','127.0.0.1',NULL,NULL),(9,'categoryModel','b759d2f685590bf1d04078056c8a50adac129ba1','kevin@kevinroberts.us','f2d9e8f0a23a052d4ae0f6d40f54de07f3530d2a','disabled','2010-10-31 18:00:55','2010-12-03 21:10:52','127.0.0.1','28111c42b05123d248e570c77eec8357c4a252f1f5a7eeb2083854e57e46264b','2010-11-11 10:01:03'),(10,'bubbagump','03364cfd44f4197a873efefbff1b97c76260a084','kevon202@gmail.com','e22fd644d7d4fe6571f985469885e84207d0d77f','basic','2010-12-03 21:11:36','2011-02-13 16:17:29','127.0.0.1',NULL,NULL);

/* Trigger structure for table `link_history` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `link_history_before_insert` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `link_history_before_insert` BEFORE INSERT ON `link_history` FOR EACH ROW BEGIN
	IF NEW.date_submitted = '0000-00-00 00:00:00' THEN
         SET NEW.date_submitted = NOW();
     END IF;
    END */$$


DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
