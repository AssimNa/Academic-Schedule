--
-- Database: `testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
						  `id` int(11) NOT NULL,
						  `title` varchar(255) NOT NULL,
						  `start_event` datetime NOT NULL,
						  `end_event` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `start_event`, `end_event`) VALUES
																	 (1, 'Meeting with Mike', '2019-11-08 12:00:00', '2019-11-08 13:00:00'),
																	 (4, 'Meeting with Mike', '2019-11-11 15:30:00', '2019-11-11 16:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
	ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


