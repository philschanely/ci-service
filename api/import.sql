CREATE DATABASE `lego_collection`;

USE `lego_collection`;

--
-- Table structure for table `Lego`
--

CREATE TABLE `Lego` (
  `id` int(5) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text,
  `image_path` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Lego`
--

INSERT INTO `Lego` (`id`, `name`, `description`, `image_path`) VALUES
(75156, 'Krennic''s Imperial Shuttle', '<p>When a tough transport ship is needed, Krennic''s Imperial Shuttle is the perfect choice. Put him into the pilot seat, open out the thick-armor plating and seat the Death Troopers. Lower the ramp and check the blasters are secured, then arm the spring-loaded shooters and seal the hull for takeoff. Raise the landing skids, lower the wings for flight mode and set off on another dangerous mission!</p>', '75156-krennic-shuttle.png'),
(75169, 'Duel on Naboo', '<p>Master Qui-Gon has cornered Darth Maul at the power generator on Naboo and needs your help to defeat him! Duel it out with the Lightsabers and if you fall in the generator core, activate the catapult to jump back out. If Maul is too strong, push the lever to open the laser doors so Obi-Wan can join the battle! Who will win this epic duel? That''s for you to decide...</p>', '75169-duel-on-naboo.png'),
(75170, 'The Phantom', '<p>Stay one step ahead of Admiral Thrawn with the Rebels’ cool starship, The Phantom. Load Kanan and his droid Chopper into place, raise the landing gear and launch! Open the rear hatch to access the detonator, and if you get into trouble, fire the shooters to keep the Imperials off your tail or detach the cockpit for a quick getaway!</p>', '75170-phantom.png'),
(75171, 'Battle on Skarif', '<p>Go on a daring mission to find the top-secret Death Star plans at the beach bunker. Uncover the hidden weapon stash, dodge the exploding floor panels and then tip the tower to unlock the bunker doors. Will you be able to grab the plans and escape planet Scarif before they catch you? That''s for you to decide.</p>', '75171-battle-on-scarif.png'),
(75172, 'Y-Wing Starfighter', '<p>When the Rebels need a tough starfighter, call in the Y-wing! Drive the weapons loader into position and lift the ammo into place. Then seat the Y-Wing Pilot, lift the landing gear and take to the skies. When you reach your target, fire the shooters and turn the gearwheel to open the hatch and drop the bombs!</p>', '75172-y-wing.png'),
(75173, 'Luke''s Landspeeder', '<p>Help Luke and C-3PO track down Jedi legend Ben Kenobi in his fast landspeeder. Open the trunk, grab the binoculars and scan the hori zon—but watch out for the dangerous Tusken Raider and hungry womp rat!</p>', '75173-lukes-landspeeder.png'),
(75174, 'Desert Skiff Escape', '<p>Help Han Solo rescue his furry friend Chewbacca before he walks the plank! Can he defeat the skiff guard and bring Boba Fett crashing down before Chewbacca falls from the skiff and becomes another tasty meal for the mighty Sarlacc? Only you can decide!</p>', '75174-desert-skiff.png'),
(75175, 'A-Wing Starfighter', '<p>The Empire is approaching so it’s time to scramble the A-Wing Starfighter! Use the service cart tools to check it over, load the shooters, detach the ladder and climb aboard. Then power up the engines and take off on another exciting LEGO® Star Wars mission!</p>', '75175-a-wing.png');

-- --------------------------------------------------------

--
-- Table structure for table `Type`
--

CREATE TABLE `Type` (
`id` int(1) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Type`
--

INSERT INTO `Type` (`id`, `name`) VALUES
(1, 'Wishlist'),
(2, 'Own'),
(3, 'Sold'),
(4, 'Lost');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
`id` int(2) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `first_name`, `last_name`) VALUES
(1, 'Phil', 'Schanely'),
(2, 'Ethan', 'Schanely');

-- --------------------------------------------------------

--
-- Table structure for table `User_Lego`
--

CREATE TABLE `User_Lego` (
`id` int(6) NOT NULL,
  `user` int(2) NOT NULL,
  `lego` int(5) NOT NULL,
  `type` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_Lego`
--

INSERT INTO `User_Lego` (`id`, `user`, `lego`, `type`) VALUES
(1, 1, 75171, 2),
(2, 1, 75173, 3),
(3, 1, 75156, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Lego`
--
ALTER TABLE `Lego`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Type`
--
ALTER TABLE `Type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User_Lego`
--
ALTER TABLE `User_Lego`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Type`
--
ALTER TABLE `Type`
MODIFY `id` int(1) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
MODIFY `id` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `User_Lego`
--
ALTER TABLE `User_Lego`
MODIFY `id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;


CREATE OR REPLACE VIEW `User_Lego_View` AS
select `User_Lego`.`id` AS `id`,
    `User_Lego`.`user` AS `user`,
    `Lego`.`id` AS `lego__id`,
    `Lego`.`name` AS `lego__name`,
    `Lego`.`description` AS `lego__description`,
    `Lego`.`image_path` AS `lego__image_path`,
    `Type`.`id` AS `type__id`,
    `Type`.`name` AS `type__name`
from ((`User_Lego`
    join `Lego` on((`User_Lego`.`lego` = `Lego`.`id`)))
    join `Type` on((`User_Lego`.`type` = `Type`.`id`)));
