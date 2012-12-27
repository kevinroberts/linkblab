-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 27, 2012 at 12:15 PM
-- Server version: 5.5.27
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `linkbl5_linkblab`
--

-- --------------------------------------------------------

--
-- Table structure for table `blabs`
--

CREATE TABLE IF NOT EXISTS `blabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `head_title` varchar(150) DEFAULT NULL,
  `description` varchar(1500) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `read_only` int(1) DEFAULT '0' COMMENT 'if read only no one can post new links to this blab',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=26 ;

--
-- Dumping data for table `blabs`
--

INSERT INTO `blabs` (`id`, `user_id`, `title`, `head_title`, `description`, `date_created`, `read_only`) VALUES
(1, 6, 'pics', 'Pictures and Images', 'links with interesting pictures and images', '2009-10-21 14:44:04', 0),
(2, 6, 'videos', 'Videos', 'A great place for video content of all kinds but, please, no politics.', '2010-10-23 14:44:17', 0),
(3, 6, 'programming', 'programming', 'A blab for discussion and news about computer programming', '2010-10-22 14:45:44', 0),
(4, 6, 'technology', 'technology', 'This category is for new developments and innovations in technology - if your post suits another category it should go there, not here.', '2010-10-22 14:45:47', 0),
(5, 6, 'wtf', 'WTF', 'All links that make you go what the f@&amp;$?!!', '2010-10-22 14:45:44', 0),
(6, 6, 'gaming', 'gaming', 'Links about gaming! Trailers, release info, replays all can be found here', '2010-10-22 14:45:44', 0),
(7, 6, 'music', 'Music', 'All things musical.', '2010-10-22 14:45:44', 0),
(8, 6, 'webdev', 'Web Development', 'Links relating to the latest web development trends, technologies and techniques.', '2010-10-24 14:10:02', 0),
(9, 6, 'wikipedia', 'wikipedia', 'The Most Interesting Pages on Wikipedia', '2010-10-24 14:13:59', 0),
(10, 6, 'self', 'self.blab', 'A place to put self-posts for discussion, questions, or anything else you like.  Please report spam. It helps us remove them more quickly for an enhanced reading experience.', '2010-10-24 14:17:20', 0),
(11, 6, 'apple', 'Apple', 'All links Apple Computer related.', '2010-10-24 14:23:50', 0),
(12, 6, 'scifi', 'Science Fiction', 'Science Fiction, or Speculative Fiction if you prefer. Fantasy too.  PLEASE DO NOT POST SPOILERS IN YOUR SUBMISSION TITLE. IT WILL BE REMOVED. If you see a title with a spoiler in it, downvote it as hard as you can and then message the moderators. I will delete it ASAP.', '2010-10-24 14:41:29', 0),
(13, 6, 'trees', 'trees', 'Links about trees', '2010-10-24 16:02:09', 0),
(14, 6, 'funny', 'Funny', 'the place for all links funny and humorous.', '2010-10-24 14:10:02', 0),
(15, 6, 'webgames', 'Web Games', 'links to web games that are playable without downloading', '2010-10-24 20:05:16', 0),
(22, 6, 'politics', 'Politics', 'links about U.S. politics', '2010-10-24 20:05:16', 0),
(23, 6, 'all', 'all', 'All of the most popular links across every category', '2010-10-31 12:41:12', 0),
(24, 6, 'random', 'random', 'a randomly selected category', '2010-10-31 12:41:15', 1),
(25, 6, 'linkblab.com', 'Link Blab General Category', 'A place for all links.', '2010-10-31 12:41:18', 0);

--
-- Triggers `blabs`
--
DROP TRIGGER IF EXISTS `blab_search_after_insert`;
DELIMITER //
CREATE TRIGGER `blab_search_after_insert` AFTER INSERT ON `blabs`
 FOR EACH ROW BEGIN
	INSERT INTO search_index VALUES (NULL, NULL, NEW.id, if (NEW.description IS NOT NULL, CONCAT(NEW.head_title , ' ', NEW.description), NEW.head_title) , '');
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
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
  KEY `link_id` (`link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=36 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment`, `up_votes`, `down_votes`, `votes`, `date_added`, `date_modified`, `deleted`, `user_id`, `link_id`, `parent_id`, `controversy`, `hot`) VALUES
(1, 'Partial to the wall on what we was, as biled -- [b]Mrs Joe[/b] read this, and shaking his teeth, without which, I had a constitutional im- mensely relieved to the terror was so sud- den and her in its pole by hand in mind about?'' [quote="Kevin"]My opinion of nothing of Bone, here and I don''t mean pretty wide open, away somewhere in its effect the week; I was a person took up to death for me, `Stop half a jug in his lips and having been no more questions why he were all at A bitter sleet came along the[/quote]\r\n\r\noration over [color=blue]partial[/color] to seven to take of drinking at the company came. Mr Wopsle; and very insulting.'' (She was a thoughtful bite out on the definition like the sergeant. `And six? '' Mr. Pumblechook then ran home last turn of a pint; but I added this boy.'' `With you. Hob and spare grass, there with its being sworn, and who was so finely perceived and compared them at the doors of graW to-day, Joe apologetically drew the open it. But such times as being ashamed of bringing me to wear me on his bedding cut my chest, and pull', 3, 0, 3, '2010-12-05 17:17:45', NULL, 0, 6, 35, NULL, 0.75, 0.800391),
(2, 'test 2\r\ntest 2\r\ntest 2\ntest 2', 3, 0, 3, '2010-12-05 17:18:45', NULL, 0, 6, 35, 1, 0.75, 0.800391),
(3, 'Test 3\r\nTest 3\nTest 3', 1, 0, 1, '2010-12-05 17:18:45', NULL, 0, 6, 35, NULL, 0.5, 0.559017),
(4, 'Test one two three', 1, 5, -4, '2010-12-05 17:18:45', NULL, 0, 6, 35, 2, 0.5, 0.559017),
(5, 'as E = MC[sup]2[/sup]', 1, 1, 0, '2010-12-05 17:18:45', NULL, 0, 6, 35, NULL, 2, 0.5),
(6, 'Testing 4', 2, 3, -1, '2010-12-05 17:18:45', NULL, 0, 6, 35, 2, 2.5, 0.554026),
(7, 'agfsdfasfs', 2, 0, 2, '2010-12-05 17:18:45', NULL, 0, 6, 35, 1, 0.666667, 0.726483),
(10, 'Hello man :)\n\nI love [b]linkblab[/b]\r\n\r\n\r\n[olist]\r\n[li]Brush your teeth[/li]\r\n[li]Shave your beard[/li]\r\n[li]Go to sleep[/li]\r\n[/olist]', 2, 0, 2, '2011-01-08 21:29:47', NULL, 0, 6, 35, NULL, 0.666667, 0.726483),
(17, 'Nullam in nisl eget [b]justo tempor[/b] pulvinar a sed orci. Sed venenatis, risus sed volutpat porttitor, ligula erat mollis ante, viverra egestas nisi nibh quis mauris. Aliquam commodo, orci et dignissim fermentum, mi diam bibendum turpis, vel bibendum risus elit vitae lectus. Quisque congue luctus erat pretium porttitor. Praesent purus libero, porta non blandit ac, eleifend sit amet tortor. Mauris mattis dui id arcu vehicula sit amet imperdiet libero sollicitudin. Duis tincidunt convallis tortor, a tempor lorem facilisis id. In eget nisi non neque adipiscing ultrices non quis velit. In posuere dictum arcu, eget hendrerit velit tempor vel. Phasellus eget eros at enim dignissim feugiat eget vel neque.\n\nCum sociis natoque penatibus et magnis [url=http://www.google.com]google[/url] dis parturient montes, nascetur ridiculus mus. Fusce consequat ullamcorper mattis. Etiam lobortis sem sit amet arcu convallis at cursus libero volutpat. Duis arcu felis, pretium in mollis quis, luctus ac tellus. Integer a nulla id quam consectetur pretium vitae eu quam. \n\n[quote]Curabitur a nunc eget tellus tincidunt mattis ac dignissim ante. Vestibulum varius dolor eget ligula scelerisque sit amet congue turpis laoreet. Phasellus eleifend, leo quis vehicula sollicitudin, magna dui lobortis leo, vitae aliquet metus est imperdiet ipsum.[/quote]\n\nSed id felis sapien. Vivamus scelerisque pharetra magna id congue. ', 1, 0, 1, '2011-01-08 21:57:01', NULL, 0, 6, 19, NULL, 0.5, 0.559017),
(18, 'It''s funny because its true :P', 1, 0, 1, '2011-01-09 12:24:38', NULL, 0, 6, 1, NULL, 0.5, 0.559017),
(19, 'Sample linky [url=http://www.kevinroberts.us]kevin''s homepage[/url]', 1, 0, 1, '2011-01-09 12:26:56', NULL, 0, 6, 13, NULL, 0.5, 0.559017),
(20, 'I love this quote by kevin man:\n[quote="kevin"]You what they say about peds man, they smell.[/quote]', 1, 0, 1, '2011-01-22 12:11:21', NULL, 0, 6, 35, NULL, 0.5, 0.559017),
(21, 'This is the most complicated post.  8) \n\n[code]\nclass kevin {\nkf jsd oieajf;io jeriojf i;dijglkdjglkdfj;lkfsdjlkjlkjklsd;fjgioerj giodjfdfgs ldfk gioer jod\n}\n[/code]', 1, 0, 1, '2011-02-06 10:13:12', NULL, 0, 10, 14, NULL, 0.5, 0.559017),
(22, 'This is a test', 2, 0, 2, '2011-02-06 18:20:34', NULL, 0, 10, 35, NULL, 0.666667, 0.726483),
(23, 'sadfasdfa', 2, 0, 2, '2011-02-06 18:22:33', NULL, 0, 10, 35, NULL, 0.666667, 0.726483),
(24, 'dsfads dfsa ssda', 2, 0, 2, '2011-02-06 18:24:32', NULL, 0, 10, 35, NULL, 0.666667, 0.726483),
(25, 'This is a better comment :)\r\n\r\nsdfs ', 1, 0, 1, '2011-02-06 19:07:41', '2011-02-06 22:26:52', 1, 10, 42, NULL, 0.5, 0.559017),
(26, '你好，你今天好吗？ \r\n\r\nsimplified chinese: for hello, how are you today?', 1, 0, 1, '2011-02-06 20:24:49', '2011-02-06 22:27:19', 1, 10, 42, NULL, 0.5, 0.559017),
(27, 'areply', 1, 0, 1, '2011-02-19 09:16:55', NULL, 0, 6, 35, 22, 0.5, 0.559017),
(28, 'hyhhghg', 1, 0, 1, '2011-02-19 09:37:03', NULL, 0, 6, 35, 27, 0.5, 0.559017),
(29, 'asd adas asd', 1, 0, 1, '2011-02-19 09:41:03', NULL, 0, 6, 26, NULL, 0.5, 0.559017),
(30, 'WTF did you just say?? :O', 1, 0, 1, '2011-02-19 09:45:17', NULL, 0, 6, 19, 17, 0.5, 0.559017),
(31, 'Hey this is a test comment  :O \r\n\r\nWoah dude :) ', 1, 0, 1, '2011-02-19 10:20:59', NULL, 1, 6, 24, NULL, 0.5, 0.559017),
(33, 'To answer some of your questions:\n[list]\n[li]The net is very comfortable to sit on and work on but it gets old to sleep on. You generally wake up in the middle of the night uncomfortable as its not flat but curved. If you are used to sleeping in a bed this takes some getting used to.[/li]\n[li]If you put a blanket down it doesn''t leave any gridlines[/li]\n[li]Its much easier to walk on with shoes than without[/li]\n[li]Its not good to jump on as it doesn''t stretch much. Its much better to just step on and hang out.[/li]\n[li]We''ve had up to 12 people on here - its attached to the walls with i-hooks and climbing rope and can hold a lot of weight.[/li]\n[li]Anyone who has a space between floors should consider a net. It lets light through, connects the space and makes usable hang-out space in the middle of an open area. Its really easy to implement and impresses a lot of people.[/li]\n[/list]\n', 1, 0, 1, '2011-02-20 15:28:33', NULL, 0, 10, 45, NULL, 0.5, 0.559017),
(34, 'and Nokia engineers have lots of spare time on there hands :P', 1, 0, 1, '2012-12-12 08:35:10', NULL, 0, 6, 70, NULL, 0.5, 0.559017),
(35, 'n,n,n,,', 1, 0, 1, '2012-12-13 22:20:02', NULL, 0, 1, 74, NULL, 0.5, 0.559017);

-- --------------------------------------------------------

--
-- Table structure for table `comment_history`
--

CREATE TABLE IF NOT EXISTS `comment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_up` int(1) DEFAULT '1',
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_submitted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comment_history` (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=41 ;

--
-- Dumping data for table `comment_history`
--

INSERT INTO `comment_history` (`id`, `vote_up`, `comment_id`, `user_id`, `date_submitted`) VALUES
(10, 1, 17, 6, '2011-01-08 21:57:01'),
(11, 1, 18, 6, '2011-01-09 12:24:38'),
(12, 1, 19, 6, '2011-01-09 12:26:56'),
(13, 1, 20, 6, '2011-01-22 12:11:21'),
(14, 1, 21, 10, '2011-02-06 10:13:12'),
(15, 1, 22, 10, '2011-02-06 18:20:34'),
(16, 1, 23, 10, '2011-02-06 18:22:33'),
(17, 1, 24, 10, '2011-02-06 18:24:32'),
(18, 1, 25, 10, '2011-02-06 19:07:41'),
(19, 1, 26, 10, '2011-02-06 20:24:49'),
(20, 1, 27, 6, '2011-02-19 09:16:55'),
(21, 1, 28, 6, '2011-02-19 09:37:03'),
(22, 1, 29, 6, '2011-02-19 09:41:03'),
(23, 1, 30, 6, '2011-02-19 09:45:17'),
(24, 1, 31, 6, '2011-02-19 10:20:59'),
(26, 1, 1, 6, '2011-02-19 18:02:36'),
(27, 1, 2, 6, '2011-02-19 18:05:39'),
(28, 1, 6, 6, '2011-02-19 18:06:45'),
(29, 1, 24, 6, '2011-02-19 18:08:40'),
(30, 1, 23, 6, '2011-02-19 18:08:58'),
(31, 1, 22, 6, '2011-02-19 18:13:19'),
(32, 1, 10, 6, '2011-02-19 18:34:58'),
(33, 1, 1, 10, '2011-02-19 18:36:19'),
(34, 1, 2, 10, '2011-02-19 18:41:11'),
(35, 0, 6, 10, '2011-02-19 18:41:19'),
(36, 0, 5, 10, '2011-02-19 18:42:32'),
(37, 1, 33, 10, '2011-02-20 15:28:33'),
(38, 1, 7, 10, '2011-02-20 16:19:11'),
(39, 1, 34, 6, '2012-12-12 08:35:10'),
(40, 1, 35, 1, '2012-12-13 22:20:02');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_url` varchar(200) DEFAULT NULL,
  `thumbnail` varchar(200) DEFAULT NULL,
  `domain` varchar(150) DEFAULT NULL,
  `up_votes` int(11) DEFAULT '1',
  `down_votes` int(11) DEFAULT '0',
  `votes` int(11) DEFAULT '1',
  `url_safe_title` varchar(150) DEFAULT NULL,
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
  KEY `blab_id` (`blab_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=80 ;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `link_url`, `thumbnail`, `domain`, `up_votes`, `down_votes`, `votes`, `url_safe_title`, `title`, `description`, `date_created`, `blab_id`, `user_id`, `is_nsfw`, `is_self`, `times_reported`, `controversy`, `hot`) VALUES
(1, 'http://imgur.com/PorNF.jpg', NULL, 'imgur.com', 4, 0, 4, 'every-single-day', 'Every single day.', '<p><a href="http://www.prguitarman.com/index.php?id=283">LOL Comics</a></p> <p>The resemblance is uncanny.</p>', '2010-11-11 15:11:00', 14, 6, 0, 0, 0, 0.8, 3488.27),
(2, 'http://imgur.com/Yvn9S.gif', NULL, 'imgur.com', 5, 1, 4, 'deal-with-it', 'Deal With it.', '', '2010-11-12 16:13:14', 12, 6, 0, 0, 0, 1.2, 3498),
(3, 'http://wimp.com/dubfx/', '/images/thumbs/3.png', 'wimp.com', 5, 0, 5, 'man-loops-his-voice-into-spectacular-song', 'Man loops his voice into spectacular song', NULL, '2010-11-14 14:00:00', 2, 6, 0, 0, 0, 0.833333, 3622.01),
(4, 'http://imgur.com/Omdg8.jpg', '/images/thumbs/4.png', 'imgur.com', 4, 0, 4, 'good-question', 'Good question!', NULL, '2010-11-14 14:10:00', 1, 6, 0, 0, 0, 0.8, 3488.27),
(5, 'http://upload.wikimedia.org/wikipedia/commons/b/b5/London_%2C_Kodachrome_by_Chalmers_Butterfield_edit.jpg', '/images/thumbs/5.png', 'upload.wikimedia.org', 4, 3, 1, 'london-1949-pic', 'London, 1949 (PIC) ', NULL, '2010-11-14 14:10:10', 1, 6, 0, 0, 0, 3.5, 4920.34),
(6, 'http://upload.wikimedia.org/wikipedia/commons/2/24/Lockheed_XF-104.jpg', '/images/thumbs/6.png', 'upload.wikimedia.org', 4, 1, 3, 'the-first-xf-104', 'The first XF-104', NULL, '2010-11-14 14:10:11', 1, 6, 0, 0, 0, 1.25, 3488.14),
(7, 'http://upload.wikimedia.org/wikipedia/commons/f/fb/Stereographic_projection_of_Paris.jpg', '/images/thumbs/7.png', 'upload.wikimedia.org', 5, 0, 5, 'so-trippy-zoom-in-for-full-effect', 'SO TRIPPY (zoom in for full effect)', NULL, '2010-11-14 14:10:11', 1, 6, 0, 0, 0, 0.833333, 3498.09),
(8, 'http://en.wikipedia.org/wiki/William_Leonard_Pickard', NULL, 'en.wikipedia.org', 4, 0, 4, 'the-man-responsible-for-manufacturing-90-worlds-of-lsd', 'The man responsible for manufacturing 90% world''s of LSD', NULL, '2010-11-14 14:10:11', 9, 6, 0, 0, 0, 0.8, 3488.27),
(9, 'http://imgur.com/qJekB.jpg', NULL, 'imgur.com', 4, 0, 4, 'awesome-kitten', 'Awesome Kitten.', NULL, '2010-11-14 16:01:27', 1, 6, 0, 0, 0, 0.8, 3488.27),
(10, 'http://i.imgur.com/3uCT2.jpg', NULL, 'i.imgur.com', 4, 1, 3, 'pirates-nsfw', 'Pirates (NSFW)', NULL, '2010-11-14 20:52:33', 14, 6, 1, 0, 0, 1.25, 3488.15),
(11, 'http://www.youtube.com/watch?v=9ieWrWLjii0', NULL, 'www.youtube.com', 2, 0, 2, 'sling-blade-has-nothing-on-this-65-year-old-sling-shot-man', 'Sling Blade Has Nothing On This 65 Year Old Sling Shot Man', NULL, '2010-12-03 19:45:22', 2, 6, 0, 0, 0, 0.666667, 3497.7),
(12, 'http://imgur.com/Fomvi.jpg', NULL, 'imgur.com', 2, 0, 2, 'how-to-hack-an-electric-road-sign', 'How to hack an electric road sign', NULL, '2010-12-03 20:23:18', 1, 6, 0, 0, 0, 0.666667, 3497.69),
(13, 'http://hyperboleandahalf.blogspot.com/2010/11/dogs-dont-understand-basic-concepts.html', NULL, 'hyperboleandahalf.blogspot.com', 2, 0, 2, 'hyperbole-and-a-half-dogs-dont-understand-basic-concepts-like-moving', 'Hyperbole and a Half: Dogs don''t understand basic concepts like moving.', NULL, '2010-12-03 20:31:13', 25, 6, 0, 0, 0, 0.666667, 3497.69),
(14, NULL, NULL, NULL, 1, 1, 0, 'finigans-wake-yall', 'Finigans Wake yall', '<p style="color: blue;">Poldier, wishing oftebeen but how becrimed, becursekissed and Church. And you''ll agree. She may rise you with her fluffballs safe in em, boaston nightgarters and other spring offensive on his tile to say, the bonny bawn blooches. This is interdum believed, a common thing.\r\n</p>\r\nWell, all sections and polarised fucking for me when he''s plane member for bone, a parody''s bird, a fish and blouseman business? Our cubehouse still open;\r\n\r\nthe third charm? And malers fuck abushed, keep of his footwear, say. Not unintoxicated, fair green the Base All, Nopper Tipped a yangster to bewray how the two peaches with katya when\r\n\r\n<script>alert('''');</script>', '2010-12-03 21:00:56', 10, 6, 0, 1, 0, 2, 0),
(15, 'http://www.flickr.com/photos/11032335@N00/5229202756', NULL, 'www.flickr.com', 1, 0, 1, 'redwood-eye', 'Redwood Eye', NULL, '2010-12-04 11:28:29', 1, 6, 0, 0, 0, 0.5, 3498.54),
(16, 'http://www.flickr.com/photos/23563181@N06/5226940612#/photos/marknauta/5226940612/', NULL, 'www.flickr.com', 1, 0, 1, 'porsche-cayenne-turbo', 'Porsche Cayenne Turbo', NULL, '2010-12-04 11:30:49', 1, 6, 0, 0, 0, 0.5, 3498.54),
(17, 'http://www.flickr.com/photos/soshiro/5228822140/', NULL, 'www.flickr.com', 1, 0, 1, 'cameraapp-explored', 'Camera.app [Explored]', NULL, '2010-12-04 11:32:10', 1, 6, 0, 0, 0, 0.5, 3498.54),
(18, 'http://www.flickr.com/photos/36298698@N06/5226830086', NULL, 'www.flickr.com', 1, 0, 1, 'icy-embrace', 'Icy Embrace', NULL, '2010-12-04 11:34:09', 1, 6, 0, 0, 0, 0.5, 3498.54),
(19, 'http://www.readwriteweb.com/archives/paypal_announces_it_will_no_longer_handle_wikileak.php', NULL, 'www.readwriteweb.com', 1, 0, 1, 'paypal-announces-it-will-no-longer-handle-wikileaks-donations', 'PayPal Announces It Will No Longer Handle Wikileaks Donations', NULL, '2010-12-04 11:39:29', 5, 6, 0, 0, 0, 0.5, 3498.55),
(20, 'http://www.flickr.com/photos/14551451@N00/5226746082', NULL, 'www.flickr.com', 1, 0, 1, 'an-edinburgh-winter', 'An Edinburgh Winter', NULL, '2010-12-04 11:40:50', 1, 6, 0, 0, 0, 0.5, 3498.55),
(21, 'http://www.flickr.com/photos/93197965@N00/5228556617', NULL, 'www.flickr.com', 1, 0, 1, 'delicious-looking-latte', 'Delicious Looking Latte', NULL, '2010-12-04 11:46:26', 1, 6, 0, 0, 0, 0.5, 3498.56),
(22, 'http://www.flickr.com/photos/ashtonsterlingphotography/5228645714', NULL, 'www.flickr.com', 2, 1, 1, 'sweet-cabaret', 'Sweet cabaret.', NULL, '2010-12-04 11:48:43', 1, 6, 1, 0, 0, 1.5, 4920.34),
(23, 'http://www.flickr.com/photos/13547802@N05/5227669810', NULL, 'www.flickr.com', 1, 0, 1, 'spiked-aurora-northern-lights-near-blfjoumlll-iceland', 'Spiked Aurora - Northern Lights near Bl&aacute;fj&ouml;ll, Iceland', NULL, '2010-12-04 12:08:43', 1, 6, 0, 0, 0, 0.5, 3498.59),
(24, 'http://www.flickr.com/photos/lerefs/5223516924/', NULL, 'www.flickr.com', 1, 0, 1, 'under-reflex', '&lt;Under Reflex&gt;', NULL, '2010-12-04 12:10:33', 1, 6, 0, 0, 0, 0.5, 3498.59),
(25, 'http://www.flickr.com/photos/stevenwalden/5223375290/', NULL, 'www.flickr.com', 1, 0, 1, 'the-lighthouse-outside-blyth', 'The lighthouse outside Blyth', NULL, '2010-12-04 12:12:34', 1, 6, 0, 0, 0, 0.5, 3498.59),
(26, 'http://www.flickr.com/photos/eric5dmark2/5223491948/#/', NULL, 'www.flickr.com', 1, 0, 1, 'griffith-observatory', 'griffith observatory', NULL, '2010-12-04 12:13:31', 1, 6, 0, 0, 0, 0.5, 3498.6),
(35, NULL, NULL, NULL, 2, 0, 2, 'test-of-decoda', 'Test of decoda', '[b]Lorem ipsum dolor sit amet[/b], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.\r\n\r\n[i]Lorem ipsum dolor sit amet[/i], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.\r\n\r\n[quote][u]Lorem ipsum dolor sit amet[/u], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/quote]\r\n\r\n[quote="Miles"][url=http://www.milesj.me]Lorem ipsum dolor sit amet[/url], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor.[/quote]\r\n\r\nHey I sure like emoticons :) :( n''shit[sup]2[/sup]\r\n\r\nNeed to grab a quick bite to eat :brb:\r\n\r\nHarry potter spoiler test - Hover over the black box to see the spoiler [spoiler] Snape kills Dumbledore! This was the spoiler from Harry Potter, I hope it didn''t ruin anything for you.[/spoiler]\r\n\r\nhttp://www.milesj.me\r\nwww.milesj.me\r\n\r\n[list]\r\n[li][url]http://www.milesj.me[/url][/li]\r\n[li]http://www.milesj.me[/li]\r\n[li]www.milesj.me[/li]\r\n[li][email]test@milesj.me[/email][/li]\r\n[li][mail]test@milesj.me[/mail][/li]\r\n[li][email=test@milesj.me]Email me![/email][/li]\r\n[/list]\r\n[code lang="php" hl="15"]/**\r\n * Parse the default markup depending on the allowed\r\n * @param string $string\r\n * @return string\r\n */\r\nprotected function parseDefaults($string) {\r\n	if (empty($this->allowed)) {\r\n		$code = $this->markupCode;\r\n		$result = $this->markupResult;\r\n	} else {\r\n		$code = array();\r\n		$result = array();\r\n		foreach ($this->markupCode as $tag => $regex) {\r\n			if (in_array($tag, $this->allowed)) {\r\n				$code[$tag] = $this->markupCode[$tag];\r\n\r\n				$result[$tag] = $this->markupResult[$tag];\r\n			}\r\n		}\r\n	}\r\n	\r\n	$string = preg_replace($code, $result, $string);\r\n	return $string;\r\n}[/code]', '2010-12-05 13:17:45', 10, 6, 0, 1, 0, 0.666667, 3621.63),
(40, 'http://www.flickr.com/photos/14516334@N00/345009210/', NULL, 'www.flickr.com', 1, 0, 1, 'honeybee-lift-off', 'Honeybee Lift off', NULL, '2010-12-05 19:04:42', 1, 6, 0, 0, 0, 0.5, 3501.06),
(41, 'http://www.flickr.com/photos/49895716@N08/5228930214', NULL, 'www.flickr.com', 1, 0, 1, 'zebra-toys-paper-craft', 'Zebra toys - Paper Craft', NULL, '2010-12-05 19:11:20', 25, 6, 0, 0, 0, 0.5, 3501.07),
(42, NULL, NULL, NULL, 2, 0, 2, 'test-decoda-code', 'Test Decoda code', 'This is a test of Decoda [url=http://qbnz.com/highlighter/]Geshi[/url] markup parsing. :)\r\n\r\n[code lang="php"]\r\n<?php\r\nclass SimpleClass\r\n{\r\n    // property declaration\r\n    public $var = ''a default value'';\r\n\r\n    // method declaration\r\n    public function displayVar() {\r\n        echo $this->var;\r\n    }\r\n}\r\n?>\r\n[/code]', '2011-02-06 10:04:10', 3, 10, 0, 1, 0, 0.666667, 3646.64),
(43, 'http://tinypic.cc/4ypxn', NULL, 'tinypic.cc', 1, 0, 1, 'best-friends', 'Best Friends', NULL, '2011-02-20 14:44:23', 1, 10, 0, 0, 0, 0.5, 3648.56),
(44, 'http://i.imgur.com/sZRqR.jpg', NULL, 'i.imgur.com', 1, 0, 1, 'sup-dog', '''Sup dog?', NULL, '2011-02-20 15:20:58', 14, 10, 0, 0, 0, 0.5, 3648.61),
(45, 'http://i.imgur.com/1wIou.jpg', NULL, 'i.imgur.com', 1, 0, 1, 'how-many-of-you-would-sleep-here', 'How many of you would sleep here?', NULL, '2011-02-20 15:25:00', 1, 10, 0, 0, 0, 0.5, 3648.61),
(46, 'http://www.shapeways.com/model/364717/a-little-sad-keanu-reeves.html?li=productBoxB&materialId=26', NULL, 'www.shapeways.com', 2, 0, 2, 'get-your-own-sad-keanu-reeves', 'Get Your Own Sad Keanu Reeves', NULL, '2012-12-11 17:10:35', 14, 6, 0, 0, 0, 0.666667, 4918.53),
(47, 'http://online.wsj.com/article_email/SB10001424127887323981504578174532274021230-lMyQjAxMTAyMDEwMjExNDIyWj.html', NULL, 'online.wsj.com', 1, 0, 1, 'apple-tests-designs-for-tv', 'Apple Tests Designs for TV', NULL, '2012-12-12 09:36:37', 4, 6, 0, 0, 0, 0.5, 4917.27),
(48, 'http://googleblog.blogspot.com/2012/12/zeitgeist2012.html', NULL, 'googleblog.blogspot.com', 1, 0, 1, 'zeitgeist-2012-what-piqued-your-curiosity-this-year', 'Zeitgeist 2012: What piqued your curiosity this year?', NULL, '2012-12-12 09:37:08', 4, 6, 0, 0, 0, 0.5, 4917.27),
(49, 'http://newsroom.fb.com/News/549/Facebook-Year-in-Review-2012', NULL, 'newsroom.fb.com', 1, 1, 0, 'facebook-year-in-review-2012', 'Facebook Year in Review 2012', NULL, '2012-12-12 09:37:41', 4, 6, 0, 0, 0, 2, 0),
(50, 'http://thenextweb.com/apple/2012/12/12/yahoo-launches-instagram-inspired-flickr-iphone-app-update-adds-simple-sign-ins-16-filters-and-more/', NULL, 'thenextweb.com', 1, 0, 1, 'yahoo-launches-instagram-inspired-flickr-iphone-app-update-adds-simple-sign-ins-16-filters-and-more', 'Yahoo launches Instagram-inspired Flickr iPhone app update, adds simple sign-ins, 16 filters and more', NULL, '2012-12-12 09:43:34', 4, 6, 0, 0, 0, 0.5, 4917.28),
(51, 'http://allthingsd.com/20121211/the-redbox-verizon-movie-service-is-almost-ready-to-take-on-netflix/', NULL, 'allthingsd.com', 1, 0, 1, 'the-redbox-verizon-movie-service-is-almost-ready-to-take-on-netflix', 'The Redbox Verizon Movie Service Is Almost Ready to Take On Netflix', NULL, '2012-12-12 09:44:14', 4, 6, 0, 0, 0, 0.5, 4917.28),
(52, 'http://www.engadget.com/2012/12/12/oppo-find-5-launch/', NULL, 'www.engadget.com', 1, 0, 1, 'oppo-launches-find-5-touts-5-inch-1080p-display-quad-core-and-13mp-camera', 'Oppo launches Find 5, touts 5-inch 1080p display, quad-core and 13MP camera', NULL, '2012-12-12 09:45:10', 4, 6, 0, 0, 0, 0.5, 4917.28),
(53, 'http://torrentfreak.com/verizon-determined-to-expose-bittorrent-copyright-trolls-121211/', NULL, 'torrentfreak.com', 1, 0, 1, 'verizon-determined-to-expose-bittorrent-copyright-trolls', 'Verizon Determined to Expose BitTorrent Copyright Trolls', NULL, '2012-12-12 09:45:47', 4, 6, 0, 0, 0, 0.5, 4917.28),
(54, 'http://www.geekwire.com/2012/facebook-rated-place-work-steve-ballmer-amazon-enjoy-increasing-approval-ratings/', NULL, 'www.geekwire.com', 2, 0, 2, 'facebook-is-the-best-place-to-work-steve-ballmer-amazon-enjoy-better-approval-ratings', 'Facebook is the best place to work; Steve Ballmer, Amazon enjoy better approval ratings', NULL, '2012-12-12 09:46:41', 4, 6, 0, 0, 0, 0.666667, 4918.52),
(55, 'http://online.wsj.com/article/SB10001424127887324478304578171690940069794.html', NULL, 'online.wsj.com', 1, 0, 1, 'san-francisco-49ers-raid-silicon-valley-hiring-former-facebook-youtube-talent', 'San Francisco 49ers Raid Silicon Valley, Hiring Former Facebook, YouTube Talent', NULL, '2012-12-12 09:47:17', 25, 6, 0, 0, 0, 0.5, 4917.28),
(56, 'http://gigaom.com/2012/12/11/a-question-that-twitter-needs-to-ask-itself/', NULL, 'gigaom.com', 1, 0, 1, 'a-question-that-twitter-needs-to-ask-itself', 'A question that Twitter needs to ask itself', NULL, '2012-12-12 09:48:05', 4, 6, 0, 0, 0, 0.5, 4917.28),
(57, 'http://news.cnet.com/8301-1035_3-57558641-94/blackberry-10-to-feature-deep-integration-of-evernote/', NULL, 'news.cnet.com', 1, 0, 1, 'blackberry-10-to-feature-deep-integration-of-evernote', 'BlackBerry 10 to feature deep integration of Evernote', NULL, '2012-12-12 09:48:35', 4, 6, 0, 0, 0, 0.5, 4917.28),
(58, 'http://betakit.com/2012/12/12/with-2-5m-in-funding-koozoo-launching-to-be-google-street-view-for-video', NULL, 'betakit.com', 1, 0, 1, 'with-25m-in-funding-koozoo-launching-to-be-google-street-view-for-video', 'With $2.5M in Funding, Koozoo Launching to Be Google Street View for Video', NULL, '2012-12-12 09:49:07', 4, 6, 0, 0, 0, 0.5, 4917.28),
(59, 'http://techcrunch.com/2012/12/12/costanoa-venture-capital-a-100m-fund-for-startups-that-develop-cloud-services-for-business-and-consumer-markets/', NULL, 'techcrunch.com', 2, 0, 2, 'costanoa-venture-capital-a-100m-fund-for-startups-that-develop-cloud-services-for-business-and-consumer-markets', 'Costanoa Venture Capital: A $100M Fund For Startups That Develop Cloud Services For Business And Consumer Markets', NULL, '2012-12-12 09:49:28', 4, 6, 0, 0, 0, 0.666667, 4918.53),
(60, 'http://techcrunch.com/2012/12/11/google-shuts-down-its-shopping-service-in-china/', NULL, 'techcrunch.com', 1, 0, 1, 'google-shuts-down-its-shopping-service-in-china', 'Google Shuts Down Its Shopping Service in China', NULL, '2012-12-12 09:50:06', 4, 6, 0, 0, 0, 0.5, 4917.28),
(61, 'http://www.zdnet.com/linux-3-7-arrives-arm-developers-rejoice-7000008638/', NULL, 'www.zdnet.com', 1, 0, 1, 'linux-37-arrives-arm-developers-rejoice', 'Linux 3.7 arrives, ARM developers rejoice', NULL, '2012-12-12 09:50:40', 4, 6, 0, 0, 0, 0.5, 4917.29),
(62, 'http://thenextweb.com/apps/2012/12/11/adobe-brings-retina-support-to-photoshop-and-illustrator/', NULL, 'thenextweb.com', 1, 0, 1, 'adobe-photoshop-and-illustrator-finally-get-retina-support-available-now-for-all-cs-6-users', 'Adobe Photoshop and Illustrator finally get Retina support, available now for all CS 6 users', NULL, '2012-12-12 09:51:32', 4, 6, 0, 0, 0, 0.5, 4917.29),
(63, 'http://www.engadget.com/2012/12/12/wsj-reports-apple-has-tested-tv-designs-dont-get-too-excited/', NULL, 'www.engadget.com', 1, 0, 1, 'wsj-reports-apple-has-tested-tv-designs-dont-get-too-excited', 'WSJ reports Apple has tested TV designs', NULL, '2012-12-12 10:10:03', 4, 6, 0, 0, 0, 0.5, 4917.31),
(64, 'http://allthingsd.com/20121212/the-leweb-conference-sold-to-reed-midem/', NULL, 'allthingsd.com', 1, 0, 1, 'the-leweb-conference-sold-to-reed-midem', 'The LeWeb Conference Sold to Reed Midem', NULL, '2012-12-12 10:10:49', 4, 6, 0, 0, 0, 0.5, 4917.31),
(65, 'http://bits.blogs.nytimes.com/2012/12/12/facebook-changes-privacy-settings-again/', NULL, 'bits.blogs.nytimes.com', 1, 0, 1, 'facebook-changes-privacy-settings-again', 'Facebook Changes Privacy Settings, Again', NULL, '2012-12-12 10:11:51', 4, 6, 0, 0, 0, 0.5, 4917.31),
(66, 'http://bgr.com/2012/12/07/google-fiber-nationwide-build-out-estimate/', NULL, 'bgr.com', 1, 0, 1, 'cold-water-for-google-fiber-fans-covering-the-whole-country-could-cost-140-billion', 'Cold water for Google Fiber fans: Covering the whole country could cost $140 billion', NULL, '2012-12-12 10:12:51', 4, 6, 0, 0, 0, 0.5, 4917.31),
(67, 'http://blogs.reuters.com/felix-salmon/2012/12/10/why-bloomberg-is-interested-in-linkedin/', NULL, 'blogs.reuters.com', 1, 0, 1, 'why-bloomberg-is-interested-in-linkedin', 'Why Bloomberg is interested in LinkedIn', NULL, '2012-12-12 10:14:37', 4, 6, 0, 0, 0, 0.5, 4917.32),
(68, 'http://appleinsider.com/articles/12/12/11/47-of-consumers-interested-in-apple-television-willing-to-pay-20-premium', NULL, 'appleinsider.com', 1, 0, 1, '47-of-consumers-interested-in-apple-television-willing-to-pay-20-premium', '47&#37; of consumers interested in Apple television, willing to pay 20&#37; premium', NULL, '2012-12-12 10:31:17', 11, 6, 0, 0, 0, 0.5, 4917.34),
(69, 'http://www.droid-life.com/2012/12/11/verizon-announces-the-samsung-galaxy-camera-with-4g-lte-available-december-13-for-549/', NULL, 'www.droid-life.com', 1, 0, 1, 'verizon-announces-the-samsung-galaxy-camera-with-4g-lte-available-december-13-for-549', 'Verizon Announces the Samsung Galaxy Camera With 4G LTE, Available December 13 for $549', NULL, '2012-12-12 10:31:58', 4, 6, 0, 0, 0, 0.5, 4917.34),
(70, 'http://www.theverge.com/2012/12/11/3754006/windows-8-games-hack-piracy-in-app-purchases-justin-angel', NULL, 'www.theverge.com', 1, 0, 1, 'nokia-engineer-shows-how-to-pirate-games-from-the-windows-8-store', 'Nokia engineer shows how to pirate games from the Windows 8 store', NULL, '2012-12-12 10:32:35', 4, 6, 0, 0, 0, 0.5, 4917.34),
(71, 'http://allthingsd.com/20121211/facebook-pushes-out-gifts-to-all-u-s-users-complete-with-holiday-booze/', NULL, 'allthingsd.com', 1, 0, 1, 'facebook-pushes-out-gifts-to-all-us-users-complete-with-holiday-booze', 'Facebook Pushes Out Gifts to All U.S. Users (Complete With Holiday Booze)', NULL, '2012-12-12 10:37:18', 25, 6, 0, 0, 0, 0.5, 4917.35),
(72, 'http://www.google.com/zeitgeist/2012/#the-world', NULL, 'www.google.com', 1, 0, 1, 'googles-zeitgeist-2012-the-most-searched-terms-for-2012', 'Google''s Zeitgeist 2012 - The Most Searched Terms For 2012', NULL, '2012-12-12 12:08:15', 25, 6, 0, 0, 0, 0.5, 4917.47),
(73, 'http://www.toxel.com/inspiration/2012/12/10/lego-office-in-denmark/', NULL, 'www.toxel.com', 1, 0, 1, 'legos-flippin-sweet-office-in-denmark', 'Lego''s Flippin Sweet Office in Denmark', NULL, '2012-12-12 13:28:40', 25, 6, 0, 0, 0, 0.5, 4917.58),
(74, 'http://googleblog.blogspot.com/2012/12/google-maps-is-now-available-for-iphone.html', NULL, 'googleblog.blogspot.com', 2, 0, 2, 'google-maps-is-now-available-for-iphone', 'Google Maps is now available for iPhone', NULL, '2012-12-13 15:14:46', 11, 6, 0, 0, 0, 0.666667, 4920.66),
(75, 'http://buyersguide.macrumors.com/', NULL, 'buyersguide.macrumors.com', 1, 0, 1, 'mac-rumors-buyers-guide-know-when-to-buy-a-new-apple-product', 'Mac Rumors Buyers Guide - Know When to buy a new apple product!', NULL, '2012-12-14 09:36:37', 11, 6, 0, 0, 0, 0.5, 4921.11),
(76, 'http://www.kickstarter.com/projects/breadpig/to-be-or-not-to-be-that-is-the-adventure', NULL, 'www.kickstarter.com', 1, 0, 1, 'choose-your-own-adventure-kickstarter-project-is-most-funded-publishing-project-on-kickstarter-ever', 'Choose your own adventure Kickstarter Project is most funded publishing project on Kickstarter ever', NULL, '2012-12-19 16:02:04', 25, 6, 0, 0, 0, 0.5, 4931.22),
(77, 'http://online.wsj.com/article_email/SB10001424127887324677204578188073738910956-lMyQjAxMTAyMDIwNTEyNDUyWj.html', NULL, 'online.wsj.com', 1, 0, 1, 'apple-vs-google-vs-facebook-vs-amazon', 'Apple vs. Google vs. Facebook vs. Amazon', NULL, '2012-12-26 23:12:09', 4, 6, 0, 0, 0, 0.5, 4945.23),
(78, 'http://1.bp.blogspot.com/__9RmAheEGT4/TGF6A83C-lI/AAAAAAAAAYQ/BiBAPh9YFbM/s1600/7upmilk.jpg', NULL, '1.bp.blogspot.com', 1, 0, 1, '7up-and-milk-mmm', '7Up and Milk. Mmm.', NULL, '2012-12-26 23:24:18', 5, 6, 0, 0, 0, 0.5, 4945.25),
(79, 'http://www.evernote.com', NULL, 'www.evernote.com', 1, 0, 1, 'the-most-useful-way-to-track-development-notes-evernote', 'The most useful way to track development notes : Evernote!', NULL, '2012-12-27 14:07:16', 8, 6, 0, 0, 0, 0.5, 4946.43);

--
-- Triggers `links`
--
DROP TRIGGER IF EXISTS `link_search_after_insert`;
DELIMITER //
CREATE TRIGGER `link_search_after_insert` AFTER INSERT ON `links`
 FOR EACH ROW BEGIN
	INSERT INTO search_index VALUES (null, NEW.id, null, '' , if (NEW.description IS NOT NULL, CONCAT(NEW.title , ' ', NEW.description), NEW.title));
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `link_history`
--

CREATE TABLE IF NOT EXISTS `link_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_up` tinyint(1) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_submitted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=129 ;

--
-- Dumping data for table `link_history`
--

INSERT INTO `link_history` (`id`, `vote_up`, `link_id`, `user_id`, `date_submitted`) VALUES
(33, 1, 5, 6, '2010-12-03 19:45:22'),
(34, 1, 8, 6, '2010-12-03 19:45:22'),
(35, 1, 4, 6, '2010-12-03 19:45:22'),
(36, 1, 7, 6, '2010-12-03 19:45:22'),
(37, 1, 9, 6, '2010-12-03 19:45:22'),
(38, 1, 3, 6, '2010-12-03 19:45:22'),
(39, 1, 6, 6, '2010-12-03 19:45:22'),
(40, 1, 1, 6, '2010-12-03 19:45:22'),
(41, 1, 2, 6, '2010-12-03 19:45:22'),
(42, 1, 10, 6, '2010-12-03 19:45:22'),
(43, 1, 11, 6, '2010-12-03 19:45:22'),
(44, 1, 12, 6, '2010-12-03 20:23:18'),
(45, 1, 13, 6, '2010-12-03 20:31:13'),
(46, 0, 14, 6, '2010-12-03 21:00:56'),
(47, 1, 7, 10, '2010-12-03 21:11:42'),
(48, 1, 12, 10, '2010-12-03 21:11:48'),
(49, 1, 13, 10, '2010-12-03 21:12:01'),
(50, 1, 5, 10, '2010-12-03 21:12:07'),
(51, 0, 2, 10, '2010-12-03 21:17:38'),
(52, 1, 11, 10, '2010-12-03 21:17:44'),
(53, 1, 15, 6, '2010-12-04 11:28:29'),
(54, 1, 16, 6, '2010-12-04 11:30:49'),
(55, 1, 17, 6, '2010-12-04 11:32:10'),
(56, 1, 18, 6, '2010-12-04 11:34:09'),
(57, 1, 19, 6, '2010-12-04 11:39:29'),
(58, 1, 20, 6, '2010-12-04 11:40:50'),
(59, 1, 21, 6, '2010-12-04 11:46:26'),
(60, 1, 22, 6, '2010-12-04 11:48:43'),
(61, 1, 23, 6, '2010-12-04 12:08:43'),
(62, 1, 24, 6, '2010-12-04 12:10:33'),
(63, 1, 25, 6, '2010-12-04 12:12:34'),
(64, 1, 26, 6, '2010-12-04 12:13:31'),
(72, 1, 35, 6, '2010-12-05 13:17:45'),
(77, 1, 40, 6, '2010-12-05 19:04:42'),
(78, 1, 41, 6, '2010-12-05 19:11:20'),
(79, 1, 42, 10, '2011-02-06 10:04:10'),
(80, 1, 3, 10, '2011-02-06 10:06:29'),
(81, 0, 22, 10, '2011-02-06 10:07:17'),
(82, 1, 35, 10, '2011-02-06 10:24:50'),
(83, 1, 42, 6, '2011-02-19 10:59:16'),
(84, 1, 43, 10, '2011-02-20 14:44:23'),
(85, 1, 44, 10, '2011-02-20 15:20:58'),
(86, 1, 45, 10, '2011-02-20 15:25:00'),
(88, 1, 46, 6, '2012-12-11 15:10:35'),
(89, 1, 47, 6, '2012-12-12 07:36:37'),
(90, 1, 48, 6, '2012-12-12 07:37:08'),
(91, 1, 49, 6, '2012-12-12 07:37:41'),
(92, 1, 50, 6, '2012-12-12 07:43:34'),
(93, 1, 51, 6, '2012-12-12 07:44:14'),
(94, 1, 52, 6, '2012-12-12 07:45:10'),
(95, 1, 53, 6, '2012-12-12 07:45:47'),
(96, 1, 54, 6, '2012-12-12 07:46:41'),
(97, 1, 55, 6, '2012-12-12 07:47:17'),
(98, 1, 56, 6, '2012-12-12 07:48:05'),
(99, 1, 57, 6, '2012-12-12 07:48:35'),
(100, 1, 58, 6, '2012-12-12 07:49:07'),
(101, 1, 59, 6, '2012-12-12 07:49:28'),
(102, 1, 60, 6, '2012-12-12 07:50:06'),
(103, 1, 61, 6, '2012-12-12 07:50:40'),
(104, 1, 62, 6, '2012-12-12 07:51:32'),
(105, 1, 63, 6, '2012-12-12 08:10:03'),
(106, 1, 64, 6, '2012-12-12 08:10:49'),
(107, 1, 65, 6, '2012-12-12 08:11:51'),
(108, 1, 66, 6, '2012-12-12 08:12:51'),
(109, 1, 67, 6, '2012-12-12 08:14:37'),
(110, 1, 68, 6, '2012-12-12 08:31:17'),
(111, 1, 69, 6, '2012-12-12 08:31:58'),
(112, 1, 70, 6, '2012-12-12 08:32:35'),
(113, 1, 71, 6, '2012-12-12 08:37:18'),
(114, 1, 72, 6, '2012-12-12 10:08:15'),
(115, 1, 73, 6, '2012-12-12 11:28:40'),
(116, 1, 54, 1, '2012-12-12 19:34:18'),
(117, 0, 49, 1, '2012-12-12 19:34:34'),
(118, 1, 59, 1, '2012-12-12 19:35:06'),
(119, 1, 46, 1, '2012-12-12 19:35:18'),
(120, 1, 74, 6, '2012-12-13 13:14:46'),
(121, 1, 22, 1, '2012-12-13 21:58:35'),
(122, 1, 5, 1, '2012-12-13 21:59:02'),
(123, 1, 74, 1, '2012-12-13 22:19:30'),
(124, 1, 75, 6, '2012-12-14 07:36:37'),
(125, 1, 76, 6, '2012-12-19 14:02:04'),
(126, 1, 77, 6, '2012-12-26 21:12:09'),
(127, 1, 78, 6, '2012-12-26 21:24:18'),
(128, 1, 79, 6, '2012-12-27 12:07:16');

--
-- Triggers `link_history`
--
DROP TRIGGER IF EXISTS `link_history_before_insert`;
DELIMITER //
CREATE TRIGGER `link_history_before_insert` BEFORE INSERT ON `link_history`
 FOR EACH ROW BEGIN
	IF NEW.date_submitted = '0000-00-00 00:00:00' THEN
         SET NEW.date_submitted = NOW();
     END IF;
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `search_index`
--

CREATE TABLE IF NOT EXISTS `search_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_id` int(11) DEFAULT NULL,
  `blab_id` int(11) DEFAULT NULL,
  `blab_text` text,
  `link_text` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `blab_text` (`blab_text`),
  FULLTEXT KEY `link_text` (`link_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

--
-- Dumping data for table `search_index`
--

INSERT INTO `search_index` (`id`, `link_id`, `blab_id`, `blab_text`, `link_text`) VALUES
(1, 1, NULL, '', 'Every single day. <p><a href="http://www.prguitarman.com/index.php?id=283">LOL Comics</a></p> <p>The resemblance is uncanny.</p>'),
(2, 2, NULL, '', 'Deal With it. '),
(3, 3, NULL, '', 'Man loops his voice into spectacular song'),
(4, 4, NULL, '', 'Good question!'),
(5, 5, NULL, '', 'London, 1949 (PIC) '),
(6, 6, NULL, '', 'The first XF-104'),
(7, 7, NULL, '', 'SO TRIPPY (zoom in for full effect)'),
(8, 8, NULL, '', 'The man responsible for manufacturing 90% world''s of LSD'),
(9, 9, NULL, '', 'Awesome Kitten.'),
(10, 10, NULL, '', 'Pirates (NSFW)'),
(11, 11, NULL, '', 'Sling Blade Has Nothing On This 65 Year Old Sling Shot Man'),
(12, 12, NULL, '', 'How to hack an electric road sign'),
(13, 13, NULL, '', 'Hyperbole and a Half: Dogs don''t understand basic concepts like moving.'),
(14, 14, NULL, '', 'Finigans Wake yall <p style="color: blue;">Poldier, wishing oftebeen but how becrimed, becursekissed and Church. And you''ll agree. She may rise you with her fluffballs safe in em, boaston nightgarters and other spring offensive on his tile to say, the bonny bawn blooches. This is interdum believed, a common thing.\r\n</p>\r\nWell, all sections and polarised fucking for me when he''s plane member for bone, a parody''s bird, a fish and blouseman business? Our cubehouse still open;\r\n\r\nthe third charm? And malers fuck abushed, keep of his footwear, say. Not unintoxicated, fair green the Base All, Nopper Tipped a yangster to bewray how the two peaches with katya when\r\n\r\n<script>alert('''');</script>'),
(15, 15, NULL, '', 'Redwood Eye'),
(16, 16, NULL, '', 'Porsche Cayenne Turbo'),
(17, 17, NULL, '', 'Camera.app [Explored]'),
(18, 18, NULL, '', 'Icy Embrace'),
(19, 19, NULL, '', 'PayPal Announces It Will No Longer Handle Wikileaks Donations'),
(20, 20, NULL, '', 'An Edinburgh Winter'),
(21, 21, NULL, '', 'Delicious Looking Latte'),
(22, 22, NULL, '', 'Sweet cabaret.'),
(23, 23, NULL, '', 'Spiked Aurora - Northern Lights near Bl&aacute;fj&ouml;ll, Iceland'),
(24, 24, NULL, '', '&lt;Under Reflex&gt;'),
(25, 25, NULL, '', 'The lighthouse outside Blyth'),
(26, 26, NULL, '', 'griffith observatory'),
(27, 35, NULL, '', 'Test of decoda [b]Lorem ipsum dolor sit amet[/b], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.\r\n\r\n[i]Lorem ipsum dolor sit amet[/i], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.\r\n\r\n[quote][u]Lorem ipsum dolor sit amet[/u], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/quote]\r\n\r\n[quote="Miles"][url=http://www.milesj.me]Lorem ipsum dolor sit amet[/url], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor.[/quote]\r\n\r\nHey I sure like emoticons :) :( n''shit[sup]2[/sup]\r\n\r\nNeed to grab a quick bite to eat :brb:\r\n\r\nHarry potter spoiler test - Hover over the black box to see the spoiler [spoiler] Snape kills Dumbledore! This was the spoiler from Harry Potter, I hope it didn''t ruin anything for you.[/spoiler]\r\n\r\nhttp://www.milesj.me\r\nwww.milesj.me\r\n\r\n[list]\r\n[li][url]http://www.milesj.me[/url][/li]\r\n[li]http://www.milesj.me[/li]\r\n[li]www.milesj.me[/li]\r\n[li][email]test@milesj.me[/email][/li]\r\n[li][mail]test@milesj.me[/mail][/li]\r\n[li][email=test@milesj.me]Email me![/email][/li]\r\n[/list]\r\n[code lang="php" hl="15"]/**\r\n * Parse the default markup depending on the allowed\r\n * @param string $string\r\n * @return string\r\n */\r\nprotected function parseDefaults($string) {\r\n	if (empty($this->allowed)) {\r\n		$code = $this->markupCode;\r\n		$result = $this->markupResult;\r\n	} else {\r\n		$code = array();\r\n		$result = array();\r\n		foreach ($this->markupCode as $tag => $regex) {\r\n			if (in_array($tag, $this->allowed)) {\r\n				$code[$tag] = $this->markupCode[$tag];\r\n\r\n				$result[$tag] = $this->markupResult[$tag];\r\n			}\r\n		}\r\n	}\r\n	\r\n	$string = preg_replace($code, $result, $string);\r\n	return $string;\r\n}[/code]'),
(28, 40, NULL, '', 'Honeybee Lift off'),
(29, 41, NULL, '', 'Zebra toys - Paper Craft'),
(30, 42, NULL, '', 'Test Decoda code This is a test of Decoda [url=http://qbnz.com/highlighter/]Geshi[/url] markup parsing. :)\r\n\r\n[code lang="php"]\r\n<?php\r\nclass SimpleClass\r\n{\r\n    // property declaration\r\n    public $var = ''a default value'';\r\n\r\n    // method declaration\r\n    public function displayVar() {\r\n        echo $this->var;\r\n    }\r\n}\r\n?>\r\n[/code]'),
(31, 43, NULL, '', 'Best Friends'),
(32, 44, NULL, '', '''Sup dog?'),
(33, 45, NULL, '', 'How many of you would sleep here?'),
(34, 46, NULL, '', 'Get Your Own Sad Keanu Reeves'),
(35, 47, NULL, '', 'Apple Tests Designs for TV'),
(36, 48, NULL, '', 'Zeitgeist 2012: What piqued your curiosity this year?'),
(37, 49, NULL, '', 'Facebook Year in Review 2012'),
(38, 50, NULL, '', 'Yahoo launches Instagram-inspired Flickr iPhone app update, adds simple sign-ins, 16 filters and more'),
(39, 51, NULL, '', 'The Redbox Verizon Movie Service Is Almost Ready to Take On Netflix'),
(40, 52, NULL, '', 'Oppo launches Find 5, touts 5-inch 1080p display, quad-core and 13MP camera'),
(41, 53, NULL, '', 'Verizon Determined to Expose BitTorrent Copyright Trolls'),
(42, 54, NULL, '', 'Facebook is the best place to work; Steve Ballmer, Amazon enjoy better approval ratings'),
(43, 55, NULL, '', 'San Francisco 49ers Raid Silicon Valley, Hiring Former Facebook, YouTube Talent'),
(44, 56, NULL, '', 'A question that Twitter needs to ask itself'),
(45, 57, NULL, '', 'BlackBerry 10 to feature deep integration of Evernote'),
(46, 58, NULL, '', 'With $2.5M in Funding, Koozoo Launching to Be Google Street View for Video'),
(47, 59, NULL, '', 'Costanoa Venture Capital: A $100M Fund For Startups That Develop Cloud Services For Business And Consumer Markets'),
(48, 60, NULL, '', 'Google Shuts Down Its Shopping Service in China'),
(49, 61, NULL, '', 'Linux 3.7 arrives, ARM developers rejoice'),
(50, 62, NULL, '', 'Adobe Photoshop and Illustrator finally get Retina support, available now for all CS 6 users'),
(51, 63, NULL, '', 'WSJ reports Apple has tested TV designs'),
(52, 64, NULL, '', 'The LeWeb Conference Sold to Reed Midem'),
(53, 65, NULL, '', 'Facebook Changes Privacy Settings, Again'),
(54, 66, NULL, '', 'Cold water for Google Fiber fans: Covering the whole country could cost $140 billion'),
(55, 67, NULL, '', 'Why Bloomberg is interested in LinkedIn'),
(56, 68, NULL, '', '47&#37; of consumers interested in Apple television, willing to pay 20&#37; premium'),
(57, 69, NULL, '', 'Verizon Announces the Samsung Galaxy Camera With 4G LTE, Available December 13 for $549'),
(58, 70, NULL, '', 'Nokia engineer shows how to pirate games from the Windows 8 store'),
(59, 71, NULL, '', 'Facebook Pushes Out Gifts to All U.S. Users (Complete With Holiday Booze)'),
(60, 72, NULL, '', 'Google''s Zeitgeist 2012 - The Most Searched Terms For 2012'),
(61, 73, NULL, '', 'Lego''s Flippin Sweet Office in Denmark'),
(62, 74, NULL, '', 'Google Maps is now available for iPhone'),
(63, 75, NULL, '', 'Mac Rumors Buyers Guide - Know When to buy a new apple product!'),
(64, 76, NULL, '', 'Choose your own adventure Kickstarter Project is most funded publishing project on Kickstarter ever'),
(65, 77, NULL, '', 'Apple vs. Google vs. Facebook vs. Amazon'),
(66, 78, NULL, '', '7Up and Milk. Mmm.'),
(67, NULL, 1, 'Pictures and Images links with interesting pictures and images', ''),
(68, NULL, 2, 'Videos A great place for video content of all kinds but, please, no politics.', ''),
(69, NULL, 3, 'programming A blab for discussion and news about computer programming', ''),
(70, NULL, 4, 'technology This category is for new developments and innovations in technology - if your post suits another category it should go there, not here.', ''),
(71, NULL, 5, 'WTF All links that make you go what the f@&amp;$?!!', ''),
(72, NULL, 6, 'gaming Links about gaming! Trailers, release info, replays all can be found here', ''),
(73, NULL, 7, 'Music All things musical.', ''),
(74, NULL, 8, 'Web Development Links relating to the latest web development trends, technologies and techniques.', ''),
(75, NULL, 9, 'wikipedia The Most Interesting Pages on Wikipedia', ''),
(76, NULL, 10, 'self.blab A place to put self-posts for discussion, questions, or anything else you like.  Please report spam. It helps us remove them more quickly for an enhanced reading experience.', ''),
(77, NULL, 11, 'Apple All links Apple Computer related.', ''),
(78, NULL, 12, 'Science Fiction Science Fiction, or Speculative Fiction if you prefer. Fantasy too.  PLEASE DO NOT POST SPOILERS IN YOUR SUBMISSION TITLE. IT WILL BE REMOVED. If you see a title with a spoiler in it, downvote it as hard as you can and then message the moderators. I will delete it ASAP.', ''),
(79, NULL, 13, 'trees Links about trees', ''),
(80, NULL, 14, 'Funny the place for all links funny and humorous.', ''),
(81, NULL, 15, 'Web Games links to web games that are playable without downloading', ''),
(82, NULL, 22, 'Politics links about U.S. politics', ''),
(83, NULL, 23, 'all All of the most popular links across every category', ''),
(84, NULL, 24, 'random a randomly selected category', ''),
(85, NULL, 25, 'Link Blab General Category A place for all links.', ''),
(86, 79, NULL, '', 'The most useful way to track development notes : Evernote!');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(32) NOT NULL,
  `save_path` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`,`save_path`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `blab_id` int(11) NOT NULL,
  `display_order` int(11) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`blab_id`),
  KEY `blab_id` (`blab_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=179 ;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `blab_id`, `display_order`) VALUES
(17, 1, 1, 1),
(18, 1, 2, 2),
(19, 1, 3, 3),
(20, 1, 4, 4),
(21, 1, 5, 5),
(22, 1, 6, 6),
(23, 1, 7, 7),
(24, 1, 8, 8),
(25, 1, 9, 9),
(26, 1, 10, 10),
(27, 1, 11, 11),
(28, 1, 12, 12),
(29, 1, 13, 13),
(30, 1, 14, 14),
(31, 1, 15, 15),
(32, 1, 22, 16),
(53, 6, 8, 6),
(56, 6, 1, 5),
(57, 6, 13, 7),
(58, 9, 1, 1),
(59, 9, 25, 2),
(60, 9, 2, 3),
(61, 9, 3, 4),
(62, 9, 4, 5),
(63, 9, 5, 6),
(64, 9, 6, 7),
(65, 9, 7, 8),
(66, 9, 8, 9),
(67, 9, 9, 10),
(68, 9, 10, 11),
(69, 9, 11, 12),
(70, 9, 12, 13),
(71, 9, 13, 14),
(72, 9, 14, 15),
(74, 6, 12, 4),
(75, 6, 22, 12),
(76, 6, 3, 3),
(77, 6, 14, 8),
(78, 6, 11, 2),
(79, 6, 7, 9),
(80, 6, 5, 10),
(81, 6, 10, 11),
(82, 6, 25, 1),
(83, 6, 6, 13),
(84, 6, 4, 14),
(85, 6, 9, 15),
(87, 6, 2, 16),
(88, 6, 15, 17),
(89, 10, 1, 1),
(90, 10, 2, 3),
(91, 10, 3, 4),
(92, 10, 4, 5),
(93, 10, 5, 6),
(94, 10, 6, 7),
(95, 10, 7, 8),
(96, 10, 8, 9),
(97, 10, 9, 10),
(98, 10, 10, 11),
(99, 10, 11, 12),
(100, 10, 12, 13),
(101, 10, 13, 14),
(102, 10, 14, 15),
(103, 10, 25, 2),
(119, 12, 1, 1),
(120, 12, 2, 3),
(121, 12, 3, 4),
(122, 12, 4, 5),
(123, 12, 5, 6),
(124, 12, 6, 7),
(125, 12, 7, 8),
(126, 12, 8, 9),
(127, 12, 9, 10),
(128, 12, 10, 11),
(129, 12, 11, 12),
(130, 12, 12, 13),
(131, 12, 13, 14),
(132, 12, 14, 15),
(133, 12, 25, 2),
(149, 14, 1, 1),
(150, 14, 2, 3),
(151, 14, 3, 4),
(152, 14, 4, 5),
(153, 14, 5, 6),
(154, 14, 6, 7),
(155, 14, 7, 8),
(156, 14, 8, 9),
(157, 14, 9, 10),
(158, 14, 10, 11),
(159, 14, 11, 12),
(160, 14, 12, 13),
(161, 14, 13, 14),
(162, 14, 14, 15),
(163, 14, 25, 2),
(164, 15, 1, 1),
(165, 15, 2, 3),
(166, 15, 3, 4),
(167, 15, 4, 5),
(168, 15, 5, 6),
(169, 15, 6, 7),
(170, 15, 7, 8),
(171, 15, 8, 9),
(172, 15, 9, 10),
(173, 15, 10, 11),
(174, 15, 11, 12),
(175, 15, 12, 13),
(176, 15, 13, 14),
(177, 15, 14, 15),
(178, 15, 25, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(90) NOT NULL,
  `email` varchar(255) NOT NULL,
  `salt` varchar(50) CHARACTER SET latin1 NOT NULL,
  `role` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT 'basic',
  `date_created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `user_ip_address` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `password_reset_token` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=16 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `salt`, `role`, `date_created`, `last_login`, `user_ip_address`, `password_reset_token`, `password_reset_expires`) VALUES
(1, 'admin', '73f14358afa258826dcc6fff6c8d7f7a26fa822c2757ed277d425fcaa31c5747', 'kevin.roberts10@gmail.com', '89766ad34e506ea2dde7de1597ad33326bf19b34', 'administrator', '2010-09-12 21:39:55', '2012-12-13 21:58:27', '127.0.0.1', NULL, NULL),
(6, 'kevin', 'f851b52bc757e711480aadae84ecc93e78527d818f8bbda627d9bea00fd58d01', 'kevin.roberts10@gmail.com', '91f2829d0f90ed965a3d7e3f31f88416d415e073', 'administrator', '2010-10-21 18:10:30', '2012-12-27 12:06:33', '127.0.0.1', NULL, NULL),
(9, 'categoryModel', 'b759d2f685590bf1\0\0\0\0', 'kevin.roberts10@gmail.com', 'f2d9e8f0a23a052d4ae0f6d40f54de07f3530d2a', 'disabled', '2010-10-31 18:00:55', '2010-12-03 21:10:52', '127.0.0.1', '28111c42b05123d248e570c77eec8357c4a252f1f5a7eeb2083854e57e46264b', '2010-11-11 10:01:03'),
(10, 'bubbagump', '03364cfd44f4197a\0\0\0\0', 'kevin.roberts10@gmail.com', 'e22fd644d7d4fe6571f985469885e84207d0d77f', 'basic', '2010-12-03 21:11:36', '2011-02-20 14:43:39', '127.0.0.1', NULL, NULL),
(12, 'kevon', '4993c1053ce1245d\0\0\0\0', 'kevin.roberts10@gmail.com', '34cda2cee9d80d845837ca55bfcc4ed7de5ce0d7', 'basic', '2012-12-09 20:40:00', NULL, '108.245.8.162', NULL, NULL),
(14, 'dude', 'da3cd7ea97c7d80cae0525b48f427815ab1a27f2', 'kevin.roberts10@gmail.com', 'cbc17dac4d599958609162b1f659c4af12d4f78f', 'basic', '2012-12-09 21:13:21', NULL, '108.245.8.162', NULL, NULL),
(15, 'testerdude', '6de62e6c81c263cc4e06a0d982c659ee784dccec', 'kevin.roberts10@gmail.com', '4cd9fd40f6850fddb7180e939af80df93d56e705', 'basic', '2012-12-09 21:18:21', NULL, '108.245.8.162', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE IF NOT EXISTS `user_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `lookup_id` int(11) DEFAULT NULL COMMENT 'optional reference id - ex blab id\nMost cases it will be used for referencing a blab.ID to specify which users are a moderator of that blab.',
  `key` varchar(50) NOT NULL,
  `value` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`key`),
  KEY `key` (`key`),
  KEY `lookup_id` (`lookup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `user_meta`
--

INSERT INTO `user_meta` (`id`, `user_id`, `lookup_id`, `key`, `value`) VALUES
(1, 6, 1, 'moderator', '1'),
(3, 6, 2, 'moderator', '1'),
(4, 6, 3, 'moderator', '1'),
(5, 6, 4, 'moderator', '1'),
(6, 6, 5, 'moderator', '1'),
(7, 6, 6, 'moderator', '1'),
(8, 6, 7, 'moderator', '1'),
(9, 6, 8, 'moderator', '1'),
(10, 6, 9, 'moderator', '1'),
(11, 6, 10, 'moderator', '1'),
(12, 6, 11, 'moderator', '1'),
(13, 6, 12, 'moderator', '1'),
(14, 6, 13, 'moderator', '1'),
(15, 6, 14, 'moderator', '1'),
(16, 6, 15, 'moderator', '1'),
(17, 6, 22, 'moderator', '1'),
(18, 6, 23, 'moderator', '1'),
(19, 6, 25, 'moderator', '1'),
(20, 6, NULL, 'sort', 'hot');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blabs`
--
ALTER TABLE `blabs`
  ADD CONSTRAINT `blabs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `comment_history`
--
ALTER TABLE `comment_history`
  ADD CONSTRAINT `FK_comment_history` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `blab_id` FOREIGN KEY (`blab_id`) REFERENCES `blabs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `link_history`
--
ALTER TABLE `link_history`
  ADD CONSTRAINT `link_history_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `link_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`blab_id`) REFERENCES `blabs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD CONSTRAINT `user_meta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
