    BEGIN;

    INSERT INTO `user` (`id`, `email`, `username`, `password`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (2, 'rick@matchbizz.com', NULL, NULL, '2018-05-17 14:35:03', '2018-05-17 14:35:03', NULL),
    (3, 'morty@matchbizz.com', NULL, NULL, '2018-06-19 18:19:02', '2018-06-29 20:57:28', NULL),
    (4, 'mark@matchbizz.com', NULL, NULL, '2018-06-29 18:40:17', '2018-06-29 18:40:17', NULL),
    (5, 'helena@matchbizz.com', NULL, NULL, '2018-06-29 20:57:40', '2018-06-29 20:57:40', NULL);

    INSERT INTO `userRole` (`id`, `userId`, `roleId`) VALUES
    (2, 1, 2),
    (3, 2, 2),
    (4, 3, 3),
    (5, 4, 3);

    INSERT INTO `businessProfile` (`id`, `name`, `email`, `phone`, `description`, `license`, `insurance`, `reviewCount`, `address`, `latitude`, `longitude`, `rating`, `userId`, `imageId`,`createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 'Work Space Art', 'workspaceart@bussines.com', '00-02-032',
    'We\'re here to provide custom solutions to re-energize your workspace, no matter what type of business you\'re in.',
    '999-999-999', '', 4, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '3.63', 1, NULL, '2018-05-16 17:29:03', '2018-08-11 16:02:49', NULL),
    (2, 'Macrium Software', 'macriumsoftware@bussines.com', '00-00-002',
    'About Macrium\r\n\r\nMacrium Software was founded in 2006 when CEO and Founder Nick Sills experienced a personal data disaster and in the process discovered existing backup tools were not as good as he expected. Wanting a software solution that was practical and easy to use, Nick and his team developed Macrium Reflect to create a safe and secure disk image with ultra-fast recovery times should disaster strike.\r\n\r\nSince then Macrium has gone from strength to strength. With well over 6 million downloads worldwide our customers tell us we’re the fastest and easiest to use disk imaging and recovery solution on the market. But don’t just take our word for it! Check out our customer testimonial page which details how all our different global customers use our product and how vital it has been for their business and personal use.\r\n\r\nBased in Manchester, United Kingdom, we have a highly experienced development and support team who are on hand 24/7 for premium support questions.\r\n\r\nWe have over 900 resellers globally who are active in supporting and helping customers install and maintain their disk imaging and recovery requirements. Use our Partner Locator to find a reseller local to you. Interested in becoming a partner? You can find out more here.\r\n\r\nWith the new release of Macrium Reflect 7 launched in February 2017, we’re excited about taking our product to the next level and bringing even faster disk imaging times and enhanced feature sets.',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '5.00', 1, NULL, '2018-05-16 17:29:03', '2018-08-11 16:02:52', NULL),
    (3, 'Realty Austin - Central', 'realtyaustin@bussines.com', '00-00-003',
    'Realty Austin - Central\r\nOpened in 2011, conveniently located just west of Downtown Austin near 5th and Lamar. Please contact the Agent Success Manager, Jennifer Korba, for more information.\r\n\r\nAddress\r\n1209 W. 5th Street, #300 Austin, Texas 78703',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '3.00', 1, NULL, '2018-05-17 12:45:51', '2018-08-11 16:02:54', NULL),
    (4, 'Don Powers - Blink Homes & Realty Mueller', 'home@blinkhomes.com', '00-00-004',
    'We are an Austin-area brokerage specializing in residential sales, consisting of partners Don Powers & Tina Daniel.\r\n\r\nTogether we have 30+ years of full-time real estate experience. We have managed 1000s of real estate contracts and closed 100s of happy buyers & sellers.\r\n\r\nWe are consistently rated among the very best real estate agents in Austin by our clients.',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '4.00', 1, NULL, '2018-05-17 12:45:51', '2018-08-11 16:02:55', NULL),
    (5, 'Reinae Kessler - Austin Home Girls Realty', 'reinae.kessler@gmail.com', '00-00-005',
    'ABOUT US\r\nAustin Home Girls Realty  is a boutique real estate agency located in the heart of downtown Austin on Historic 6th Street.   We aim at every turn, to help your buying or selling experience be a great one. Our mission is to assist you, advocate for your needs, and help to make your transaction seamless. Whether moving across town or across the country, we are here to be a lifetime resource.    Every person / family has a story and we want to know yours.\r\n\r\n​\r\n\r\nThank you for visiting our website, we hope you browse and enjoy. If you have any questions please don’t hesitate to contact directly through the website, or phone Reinae directly on her cell.  Yes, she answers the phone!  512.983.8556.',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '5.00', 1, NULL, '2018-05-17 12:45:51', '2018-08-11 16:02:58', NULL),
    (6, 'REILLY, REALTORS', 'experts@reillyrealtors.com', '00-00-006',
    'REILLY, REALTORS®, a residential Austin real estate brokerage, is located in the beautiful Hill Country Galleria in the west Austin area of Bee Cave. We are in the center of the Galleria across the street from Amy\'s Ice Cream, next door to Buenos Aires Café, and just down from the AT&T Store.',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '2.00', 1, NULL, '2018-05-17 12:45:51', '2018-08-11 16:03:00', NULL),
    (7, 'Lelands Barns & Sheds', 'leah@txbuildingsolutions.com', '830-201-4034',
    'At Building Solutions of Spicewood we are committed to providing you with quality products and excellent customer service from start to finish.  Our goal is to leave every customer satisfied with both the products they received as well as the interactions with our staff at all times. \r\n \r\nWe welcome comments or suggestions at any time in order to help us maintain high quality products and service!\r\n​\r\nThank you Hill Country for supporting your local businesses!',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '4.00', 1, NULL, '2018-05-30 15:11:45', '2018-08-11 16:03:02', NULL),
    (8, 'Buildings Solutions of Marble Falls', 'beth@tarragon-homes.com', '512-694-5810',
    'THE MIX OF CREATIVITY AND DETAIL\r\nREQUIRED TO BRING THE DREAMS OF\r\nOTHERS TO REALITY DEMANDS\r\nCOMMITMENT AND DEDICATION.\r\n\r\nTARRAGON HOMES\r\nHAS BEEN WORKING FOR OVER\r\nA DECADE IN THE AUSTIN AREA\r\nTRANSLATING VISION INTO\r\nDESIGN REALITY.',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '3.00', 1, NULL, '2018-05-30 15:11:45', '2018-08-11 16:03:04', NULL),
    (9, 'Tarragon Homes', 'karla@marblefallsbuildings.com', ' 830-613-7010 ',
    'At Building Solutions of Marble Falls we are committed to providing you with quality products and excellent customer service from start to finish.  Our goal is to leave every customer satisfied with both the products they received as well as the interactions with our staff at all times. \r\n \r\nWe welcome comments or suggestions at any time in order to help us maintain high quality products and service!\r\n​\r\nThank you Hill Country for supporting your local businesses!',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '5.00', 1, NULL, '2018-05-30 15:13:03', '2018-08-11 16:03:06', NULL),
    (10, 'Total Restoration of Texas', 'contact@trtexas.com', '512-698-8444',
    'Total Restoration of Texas is Austin’s premier emergency water removal, flood, water damage and fire experts.  You can count on us to provide quality emergency service at the most affordable prices. Our goal is to go beyond our customers’ expectations and provide quality work and exceptional customer service for all of our customers.',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '3.50', 1, NULL, '2018-05-30 15:13:03', '2018-08-11 16:03:08', NULL),
    (11, 'Scott & Company Residential Renovations', 'heatherscott@scottcompany-austin.com', '(512) 443-9697',
    'focused on providing high-quality service and customer satisfaction in residental renovations.  We specialize in residential projects where high standards for workmanship and excellent customer service are expected by owners.\r\n ',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '4.00', 1, NULL, '2018-05-30 15:13:03', '2018-08-11 16:03:10', NULL),
    (12, 'A R Lucas Construction Company', 'adam@arlucasconstruction.com', '(512) 801-7221',
    'From green-built custom new construction to full remodels, we take great pride in making our clients’ homes and businesses innovative, sustainable and true to their unique Austin characters. We collaborate closely with our clients on each new project we undertake, taking time to consider and understand their unique set of circumstances, wishes and concerns. Scroll through the photos below to see some of the outcomes of our work.',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '5.00', 1, NULL, '2018-05-30 15:13:03', '2018-08-11 16:03:13', NULL),
    (13, 'Jason Bernknopf', 'heatherscott@austin.com', '(512) 344-6000',
    'Jason Bernknopf moved to Austin in 2002 after graduating from The University of Georgia with a BA in Speech Communication. He has been practicing real estate for over 15 years and is proud to call Austin home. Jason got his start working with properties in the Central Austin area and his career quickly progressed to the point where he has sold many properties in every area of the city.\r\n',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '2.00', 1, NULL, '2018-05-30 15:32:31', '2018-08-11 16:03:15', NULL),
    (14, 'Rebecca Wilkins - Pauly Presley Realty', 'becca@paulypresley.com', '210-722-5777',
    'After serving 5 years in the Marine Corps including a tour in Iraq, Rebecca returned to Texas to start a new career. With her BS in Interior Design from Texas State, and 3 years of experience in both the residential and commercial sectors, Rebecca has an ability to view properties in a way that most Realtors can’t. Her pragmatic approach to design allows for even the pickiest of clients to see the potential in a property. Her eye for detail ensures that both first-time and seasoned clients will feel informed and prepared whether selling, buying or leasing. ',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '5.00', 1, NULL, '2018-05-30 15:32:31', '2018-08-11 16:03:16', NULL),
    (15, 'Myrna Garcia - GoodLife Realty', 'MyrnaGarciaRealtor@gmail.com', '512-771-5010',
    'My early years were spent in McAllen, Tx prior to my parents moving our family to Austin. I\'ve seen a lot of change since moving here 24 years ago and feel fortunate to remember Austin as it once was, and to see it transform into the town everyone wants to be in. \r\n\r\n \r\n\r\nMy career in real estate began while working as a commercial and residential property manager for a local investor. I soon realized property management was not a path I wanted to continue, however I loved working in real estate and made the transition to buying and selling residential properties. After five years with Coldwell Banker United, a well received national company, I made the switch to GoodLife Realty, a locally owned boutique real estate broker. Although small in size, GoodLife Realty has been recognized by Apple, Inman News and Docusign as one of the most innovated users of technology within our industry. ',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '4.00', 1, NULL, '2018-05-30 15:32:31', '2018-08-11 16:03:18', NULL),
    (16, 'Sean Austin McCormack - Realty Austin', 'mccormack@blinkhomes.com', '5126531213',
    'Sean Austin McCormack has practiced real estate ever since he graduated from the University of Texas at Austin. His father is a commercial broker and while growing up, Sean always helped him out in any way possible.\r\n\r\nSean has a strong set of principles that he uses throughout his life and in his business. These include trust, communication, integrity, service, and accountability.',
    '999-999-999', '', 0, 'Austin, Texas, EE. UU.', '30.28283200000000', '-97.72178200000000', '4.00', 1, NULL, '2018-05-30 15:32:31', '2018-08-11 16:03:19', NULL),
    (17, 'MyBusiness', 'aa@aa.com', '111111',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
    '6541651', '3616', 0, 'Austin, Texas, EE. UU.', '30.28280100000000', '-97.74667000000000', '0.00', 1, NULL, '2018-07-27 18:14:14', '2018-08-11 16:03:20', NULL);

    INSERT INTO `claim` (`id`, `status`, `businessProfileId`, `userId`) VALUES
    (1, 'approved', 1, 4),
    (2, 'approved', 2, 5);

    INSERT INTO `customerProfile` (`id`, `firstName`, `lastName`, `gender`, `email`, `phone`, `address`, `languageId`, `latitude`, `longitude`, `userId`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 'Rick', 'Sanchez', 'male', 'rickandmorty@example.com', '245-8596755', 'Austin tx', 3, '30.28283200000000', '-97.72178200000000', 1, '2018-06-02 15:27:15', '2018-07-16 15:44:33', NULL),
    (2, 'Morty', 'Smith', 'male', 'morty@matchbizz.com', '245-8596755', 'Austin tx', 2, '30.28283200000000', '-97.72178200000000', 2, '2018-06-19 18:20:44', '2018-07-06 14:28:14', NULL);

    INSERT INTO `conversation` (`id`, `businessProfileId`, `customerProfileId`, `topic`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 1, 1, 'Lets get in contact', '2018-06-01 17:28:47', '2018-06-01 17:28:47', NULL),
    (2, 2, 2, 'Congratulations', '2018-07-11 19:35:50', '2018-07-11 19:35:50', NULL);

    INSERT INTO `bookmark` (`id`, `businessProfileId`, `customerProfileId`) VALUES
    (1, 1, 1),
    (2, 2, 1),
    (3, 3, 1),
    (4, 4, 1),
    (5, 5, 2),
    (6, 6, 2),
    (7, 7, 2),
    (8, 8, 2);

    INSERT INTO `influenceArea` (`id`, `radius`, `displayId`, `latitude`, `longitude`, `businessProfileId`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, '2000.00000', 1, '30.29560419239500', '-97.75868361417800', 1, '2018-07-06 13:55:49', '2018-07-06 13:57:56', NULL),
    (2, '2000.00000', 2, '30.27218290314900', '-97.73602431242000', 1, '2018-07-06 13:55:49', '2018-07-06 13:58:00', NULL),
    (3, '776.76185', 3, '30.27321652043800', '-97.76143220786600', 1, '2018-07-06 13:55:49', '2018-07-06 13:58:04', NULL),
    (4, '2000.00000', 4, '30.30360765468000', '-97.75147383634600', 1, '2018-07-06 14:04:47', '2018-07-06 14:04:47', NULL),
    (5, '2000.00000', 5, '30.29530775532000', '-97.79404585783000', 1, '2018-07-06 14:04:47', '2018-07-06 14:04:47', NULL),
    (6, '1000.00000', 1, '30.28018100000000', '-97.73847800000000', 2, '2018-06-26 13:53:43', '2018-06-26 13:53:43', NULL),
    (7, '3473.37902', 2, '30.37958017972200', '-97.76383479659100', 2, '2018-07-06 14:01:13', '2018-07-06 14:01:36', NULL),
    (8, '4910.25505', 3, '30.34236154201000', '-97.68665313720700', 2, '2018-07-06 14:01:13', '2018-07-06 14:01:41', NULL),
    (9, '3040.31568', 4, '30.40193939747000', '-97.69708358299000', 2, '2018-07-06 14:01:13', '2018-07-06 14:01:45', NULL),
    (10, '2000.00000', 5, '30.31575981102700', '-97.67868941251800', 2, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (11, '2000.00000', 6, '30.34154110229300', '-97.69551222745900', 2, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (12, '1000.00000', 1, '30.29633900000000', '-97.74246500000000', 3, '2018-06-26 13:53:43', '2018-06-26 13:53:43', NULL),
    (13, '5452.76250', 2, '30.31978118477100', '-97.79391288006300', 3, '2018-07-06 14:01:13', '2018-07-06 14:01:49', NULL),
    (14, '2000.00000', 3, '30.34954081255700', '-97.75456374113100', 3, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (15, '2000.00000', 4, '30.26210113391500', '-97.79061263029100', 3, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (16, '6539.22745', 5, '30.26701428121000', '-97.69846915447700', 3, '2018-07-06 14:01:13', '2018-07-06 14:01:53', NULL),
    (17, '6866.76924', 6, '30.40332003116200', '-97.61037227223400', 3, '2018-07-06 14:01:14', '2018-07-06 14:01:56', NULL),
    (18, '2000.00000', 1, '30.29898500000000', '-97.72698100000000', 4, '2018-06-26 14:00:08', '2018-06-26 14:00:08', NULL),
    (19, '2000.00000', 2, '30.29293622646500', '-97.70649855558400', 4, '2018-07-06 14:04:47', '2018-07-06 14:06:09', NULL),
    (20, '2000.00000', 3, '30.32820679896800', '-97.72435133878700', 4, '2018-07-06 14:04:47', '2018-07-06 14:06:13', NULL),
    (21, '2000.00000', 4, '30.32879947323000', '-97.77275984708800', 4, '2018-07-06 14:04:47', '2018-07-06 14:06:16', NULL),
    (22, '2000.00000', 5, '30.27129337692000', '-97.82631819669700', 4, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (23, '2000.00000', 6, '30.23689218241800', '-97.78511946622800', 4, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (24, '2000.00000', 1, '30.33060400000000', '-97.70773700000000', 5, '2018-06-26 14:00:08', '2018-06-26 14:00:08', NULL),
    (25, '5202.09133', 2, '30.29606923498200', '-97.62685176442200', 5, '2018-07-06 14:01:14', '2018-07-06 14:02:08', NULL),
    (26, '4181.78723', 3, '30.26703492145700', '-97.86303392124300', 5, '2018-07-06 14:01:14', '2018-07-06 14:02:12', NULL),
    (27, '5050.55611', 4, '30.26492323268000', '-97.80864522296900', 5, '2018-07-06 14:01:14', '2018-07-06 14:02:16', NULL),
    (28, '2000.00000', 5, '30.24608678462500', '-97.83043806974400', 5, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (29, '2000.00000', 6, '30.24720460653400', '-97.81265258789100', 5, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (30, '2000.00000', 1, '30.28048475922000', '-97.73904646928700', 6, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (31, '2000.00000', 2, '30.27307242131700', '-97.67450179155300', 6, '2018-07-06 14:04:48', '2018-07-06 14:04:48', NULL),
    (32, '2000.00000', 3, '30.21984043307400', '-97.85465047199300', 6, '2018-07-06 14:01:14', '2018-07-06 14:02:20', NULL),
    (33, '2000.00000', 4, '30.32006219096500', '-97.73311421711000', 6, '2018-07-06 14:01:14', '2018-07-06 14:02:23', NULL),
    (34, '2000.00000', 5, '30.22280704218900', '-97.76813313800800', 6, '2018-07-06 14:01:14', '2018-07-06 14:02:27', NULL),
    (35, '2000.00000', 1, '30.20797310193800', '-97.82443806964900', 7, '2018-07-06 14:01:14', '2018-07-06 14:02:31', NULL),
    (36, '2000.00000', 2, '30.32895266756500', '-97.86632344562500', 7, '2018-07-06 14:01:14', '2018-07-06 14:02:36', NULL),
    (37, '2000.00000', 3, '30.37102330450600', '-97.82375142414100', 7, '2018-07-06 14:01:14', '2018-07-06 14:02:41', NULL),
    (38, '2000.00000', 1, '30.33510323792100', '-97.58810222839100', 8, '2018-07-06 14:01:14', '2018-07-06 14:02:46', NULL),
    (39, '2000.00000', 2, '30.43520717936400', '-97.67599285339100', 8, '2018-07-06 14:01:14', '2018-07-06 14:02:51', NULL),
    (40, '2000.00000', 3, '30.38368718850900', '-97.72474468444500', 8, '2018-07-06 14:01:14', '2018-07-06 14:02:55', NULL),
    (41, '2000.00000', 1, '30.20641720697200', '-97.73092449401600', 9, '2018-07-06 14:01:14', '2018-07-06 14:03:02', NULL),
    (42, '2000.00000', 2, '30.20048291086400', '-97.78173626159400', 9, '2018-07-06 14:01:14', '2018-07-06 14:03:06', NULL),
    (43, '2000.00000', 3, '30.23074407980700', '-97.62792766784400', 9, '2018-07-06 14:01:14', '2018-07-06 14:03:10', NULL),
    (44, '2000.00000', 1, '30.31139499601900', '-97.90121257995300', 10, '2018-07-06 14:01:14', '2018-07-06 14:03:14', NULL),
    (45, '2000.00000', 2, '30.42218170437900', '-97.79478252624200', 10, '2018-07-06 14:01:14', '2018-07-06 14:03:17', NULL),
    (46, '2000.00000', 3, '30.39138730562300', '-97.86070049499200', 10, '2018-07-06 14:01:14', '2018-07-06 14:03:21', NULL),
    (47, '2000.00000', 1, '30.24023586142200', '-97.70963848327300', 11, '2018-07-06 14:01:14', '2018-07-06 14:03:26', NULL),
    (48, '2000.00000', 2, '30.19039378646000', '-97.68560589050000', 11, '2018-07-06 14:01:14', '2018-07-06 14:03:30', NULL),
    (49, '2000.00000', 3, '30.19795952777100', '-97.66639709472700', 11, '2018-07-06 14:01:14', '2018-07-06 14:03:34', NULL),
    (50, '2000.00000', 1, '30.20701061690500', '-97.63616741393700', 12, '2018-07-06 14:01:14', '2018-07-06 14:03:38', NULL),
    (51, '2000.00000', 2, '30.17377415179100', '-97.64715374206200', 12, '2018-07-06 14:01:14', '2018-07-06 14:03:42', NULL),
    (52, '2000.00000', 1, '30.35406569929600', '-97.70208538268700', 13, '2018-07-06 14:01:14', '2018-07-06 14:03:45', NULL),
    (53, '2000.00000', 2, '30.32147169992300', '-97.82568157409400', 13, '2018-07-06 14:01:14', '2018-07-06 14:03:49', NULL),
    (54, '2000.00000', 3, '30.30783826488700', '-97.77074993346900', 13, '2018-07-06 14:01:14', '2018-07-06 14:03:53', NULL),
    (55, '2000.00000', 4, '30.39332434666800', '-97.79890239928900', 13, '2018-07-06 14:01:14', '2018-07-06 14:03:56', NULL),
    (56, '2000.00000', 1, '30.44543121933000', '-97.74053753112500', 14, '2018-07-06 14:01:15', '2018-07-06 14:04:00', NULL),
    (57, '2000.00000', 2, '30.41345984567700', '-97.83186138366400', 14, '2018-07-06 14:01:15', '2018-07-06 14:04:03', NULL),
    (58, '2000.00000', 3, '30.34178225996500', '-97.65814007018700', 14, '2018-07-06 14:01:15', '2018-07-06 14:04:09', NULL),
    (59, '2000.00000', 1, '30.40813026474000', '-97.65058696960200', 15, '2018-07-06 14:01:15', '2018-07-06 14:04:13', NULL),
    (60, '2000.00000', 2, '30.36074342733300', '-97.61968792175000', 15, '2018-07-06 14:01:15', '2018-07-06 14:04:18', NULL),
    (61, '2000.00000', 1, '30.26773519139900', '-97.76211684171700', 16, '2018-07-06 14:04:47', '2018-07-06 14:05:08', NULL),
    (62, '2000.00000', 2, '30.24401065931700', '-97.75593703214600', 16, '2018-07-06 14:04:47', '2018-07-06 14:05:11', NULL),
    (63, '2000.00000', 3, '30.26506646762600', '-97.72023146574000', 16, '2018-07-06 14:04:47', '2018-07-06 14:05:13', NULL);

    INSERT INTO `message` (`id`, `conversationId`, `content`, `from`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 1, 'Hello', 'customer', '2018-06-01 17:28:47', '2018-06-01 17:28:47', NULL),
    (2, 1, 'Hi', 'business', '2018-06-01 18:20:49', '2018-06-01 18:20:49', NULL),
    (3, 1, 'How are you doing?', 'customer', '2018-06-01 18:22:37', '2018-06-01 18:22:37', NULL),
    (4, 2, 'something', 'customer', '2018-07-11 19:35:50', '2018-07-11 19:35:50', NULL),
    (5, 2, 'Hi!', 'customer', '2018-07-25 15:45:42', '2018-07-25 15:45:42', NULL);

    INSERT INTO `planSubscription` (`id`, `businessProfileId`, `planId`, `status`, `startDate`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 1, 2, 'active', '2018-07-20 16:06:14', '2018-07-20 15:52:32', '2018-07-20 16:06:14', NULL),
    (2, 16, 4, 'active', '2018-07-26 13:23:42', '2018-07-20 19:11:44', '2018-07-26 13:23:42', NULL);

    INSERT INTO `review` (`id`, `title`, `content`, `rating`, `customerProfileId`, `businessProfileId`, `reply`, `offensive`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 'The best Business', 'The best Business', 5, 1, 1, 'offensive', 0, '2018-07-04 17:49:50', '2018-07-06 12:33:43', NULL),
    (2, 'Bad business', 'Bad business', 1, 1, 1, 'offensive', 1, '2018-07-04 18:33:08', '2018-07-06 12:48:05', NULL),
    (3, '', 'Thank you for the wonderful work you have done for our family-selling the place that was our home for almost 30 years.Thank you for your support,wisdom and expertise!Thank you for your approach that was extremely professional and sensitive to our needs.You were instrumental in making the entire process smooth,\nWith appreciation and many thanks.', 4, 2, 1, NULL, 0, '2018-07-06 14:27:13', '2018-07-06 14:27:13', NULL),
    (4, '', 'Whatever', 4, 2, 1, NULL, 0, '2018-07-11 19:26:02', '2018-07-11 19:26:02', NULL);

    INSERT INTO `schedule` (`id`, `type`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (16, 'always_open', '2018-07-11 19:06:23', '2018-07-23 18:32:47', NULL);

    INSERT INTO `service` (`id`, `businessProfileId`, `categoryId`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 1, 1, '2018-05-16 18:55:50', '2018-05-16 18:55:50', NULL),
    (2, 2, 1, '2018-05-17 12:56:02', '2018-05-30 15:09:40', NULL),
    (3, 3, 1, '2018-05-17 12:57:50', '2018-05-30 15:09:44', NULL),
    (4, 4, 1, '2018-05-17 12:57:50', '2018-05-30 15:09:46', NULL),
    (5, 5, 1, '2018-05-17 12:57:50', '2018-05-30 15:09:49', NULL),
    (6, 6, 1, '2018-05-17 12:57:50', '2018-05-30 15:09:51', NULL),
    (7, 7, 3, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (8, 8, 3, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (9, 9, 3, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (10, 10, 3, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (11, 11, 3, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (12, 12, 3, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (13, 13, 1, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (14, 14, 1, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (15, 15, 1, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL),
    (16, 16, 1, '2018-05-30 15:42:49', '2018-05-30 15:42:49', NULL);

    INSERT INTO `socialNetworkAccount` (`id`, `socialNetworkId`, `businessProfileId`, `urlSegment`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 1, 1, 'sample-facebook-account', '2018-05-17 15:14:02', '2018-05-30 13:51:22', NULL),
    (2, 2, 1, 'sample-twitter-account', '2018-05-17 17:32:48', '2018-05-30 13:51:01', NULL);

    INSERT INTO `workerProfile` (`id`, `age`, `name`, `yearsOfExperience`, `businessProfileId`, `ethnicityId`, `faithId`, `lifeStyleId`, `maritalStatusId`, `educationId`, `genderId`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
    (1, 30, ' ', 5, 2, 1, 1, 1, 1, 1, 1, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (2, 30, ' ', 5, 2, 1, 2, 2, 1, 2, 2, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (3, 35, ' ', 10, 2, 2, 2, 5, 1, 2, 1, '2018-06-21 17:06:37', '2018-07-06 15:13:41', NULL),
    (4, 40, ' ', 5, 3, 1, 2, 3, 1, 2, 1, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (5, 36, ' ', 6, 3, 3, 2, 3, 1, 2, 3, '2018-06-21 17:06:37', '2018-07-06 15:13:36', NULL),
    (6, 20, ' ', 4, 4, 3, 3, 2, 1, 2, 1, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (7, 26, ' ', 3, 4, 2, 3, 2, 1, 2, 3, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (8, 25, ' ', 7, 5, 1, 1, 2, 1, 3, 1, '2018-06-21 17:06:37', '2018-07-06 15:13:32', NULL),
    (9, 31, ' ', 8, 5, 1, 4, 2, 1, 2, 1, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (10, 32, ' ', 9, 6, 1, 2, 1, 2, 1, 1, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (11, 22, ' ', 11, 6, 1, 1, 2, 1, 1, 2, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (12, 38, ' ', 6, 7, 1, 3, 1, 1, 1, 3, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (13, 40, ' ', 8, 7, 1, 1, 3, 1, 3, 1, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (14, 36, ' ', 7, 8, 2, 3, 1, 2, 1, 2, '2018-06-21 17:06:37', '2018-07-06 15:13:45', NULL),
    (15, 35, ' ', 4, 8, 3, 2, 1, 2, 2, 3, '2018-06-21 17:06:37', '2018-07-06 15:13:51', NULL),
    (16, 27, ' ', 5, 9, 2, 1, 1, 1, 2, 1, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL),
    (17, 26, ' ', 5, 9, 1, 3, 1, 2, 1, 1, '2018-06-21 17:06:37', '2018-06-21 17:06:37', NULL);

    INSERT INTO `workerProfileHobbie` (`id`, `workerProfileId`, `hobbieId`) VALUES
    (1, 1, 1),
    (2, 1, 2),
    (3, 1, 3),
    (4, 2, 1),
    (5, 2, 2),
    (6, 2, 3),
    (7, 3, 1),
    (8, 3, 2),
    (9, 3, 3),
    (10, 4, 1),
    (11, 4, 2),
    (12, 4, 3),
    (13, 5, 1),
    (14, 5, 2),
    (15, 5, 3),
    (16, 6, 1),
    (17, 6, 2),
    (18, 6, 3),
    (19, 7, 1),
    (20, 7, 2),
    (21, 7, 3),
    (22, 8, 1),
    (23, 8, 2),
    (24, 8, 3),
    (25, 9, 1),
    (26, 9, 2),
    (27, 9, 3),
    (28, 10, 1),
    (29, 10, 2),
    (30, 10, 3),
    (31, 11, 1),
    (32, 11, 2),
    (33, 11, 3),
    (34, 12, 1),
    (35, 12, 2),
    (36, 12, 3),
    (37, 13, 1),
    (38, 13, 2),
    (39, 13, 3),
    (40, 14, 1),
    (41, 14, 2),
    (42, 14, 3),
    (43, 15, 1),
    (44, 15, 2),
    (45, 15, 3),
    (46, 16, 1),
    (47, 16, 2),
    (48, 16, 3),
    (49, 17, 1),
    (50, 17, 2);

    INSERT INTO `workerProfileLanguage` (`id`, `workerProfileId`, `languageId`) VALUES
    (1, 1, 1),
    (2, 1, 2),
    (3, 1, 3),
    (4, 2, 1),
    (5, 2, 2),
    (6, 2, 3),
    (7, 3, 1),
    (8, 3, 2),
    (9, 3, 3),
    (10, 4, 1),
    (11, 4, 2),
    (12, 4, 3),
    (13, 5, 1),
    (14, 5, 2),
    (15, 5, 3),
    (16, 6, 1),
    (17, 6, 2),
    (18, 6, 3),
    (19, 7, 1),
    (20, 7, 2),
    (21, 7, 3),
    (22, 8, 1),
    (23, 8, 2),
    (24, 8, 3),
    (25, 9, 1),
    (26, 9, 2),
    (27, 9, 3),
    (28, 10, 1),
    (29, 10, 2),
    (30, 10, 3),
    (31, 11, 1),
    (32, 11, 2),
    (33, 11, 3),
    (34, 12, 1),
    (35, 12, 2),
    (36, 12, 3),
    (37, 13, 1),
    (38, 13, 2),
    (39, 13, 3),
    (40, 14, 1),
    (41, 14, 2),
    (42, 14, 3),
    (43, 15, 1),
    (44, 15, 2),
    (45, 15, 3),
    (46, 16, 1),
    (47, 16, 2),
    (48, 16, 3),
    (49, 17, 1),
    (50, 17, 2);

    COMMIT;