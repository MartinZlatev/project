

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `alumni_album` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `alumni_album`;


CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `course` int NOT NULL,
  `major` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `images` (
  `path` varchar(2048) NOT NULL,
  `number_instances` int(11) NOT NULL DEFAULT 0,
  `id` int(11) NOT NULL,
  `original_filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Структура на таблица `image_instances`
--


CREATE TABLE `image_instances` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `imagePath` (`path`) USING HASH;

--
-- Indexes for table `image_instances`
--
ALTER TABLE `image_instances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `image_id` (`image_id`);

--
-- Indexes for table `users`
--
--
-- AUTO_INCREMENT for dumped tables
--
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `image_instances`
--
ALTER TABLE `image_instances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ограничения за таблица `image_instances`
--
ALTER TABLE `image_instances`
  ADD CONSTRAINT `image_instances_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `image_instances_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `images`
  ADD picked boolean;

INSERT INTO `users`(`id`, `name`, `email`, `password`, `course`, `major`) VALUES 
(1,'Мартин Златев','mzlatev@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(2,'Йоанна Благоева','yblagoeva@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(3,'Джейлян Садъкова','dsadukova@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(4,'Полина Петрова','ppetrova@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(5,'Ана Стоянова','astoyanova@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(6,'Виктор Венков','vvenkov@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(7,'Даниел Георгиев','dgeorgiev@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(8,'Даниел Янев','dyanev@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(9,'Аргир Бояджиев','aboyadzhiev@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(10,'Иван Станчев','istanchev@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство'),
(11,'Иво Песев','ipesev@uni-sofia.bg','$2y$10$MemJBUxr9rKSFOa9pJchgugKg49ae0pjzPT36wUrIenhdewAtLWOO',3,'Софтуерно инженерство');

INSERT INTO `images`(`id`, `number_instances`,`path`, `original_filename`, `picked`) 
VALUES 
(1,1,'martin.jpg','martin.jpg',1),
(2,1,'yo.jpg','yo.jpg',1),
(3,1,'djey.jpg','djey.jpg',1),
(4,1,'poli.jpg','poli.jpg',1),
(5,1,'ani.jpg','ani.jpg',1),
(6,1,'viktorv.jpg','viktorv.jpg',1),
(7,1,'danig.jpg','danig.jpg',1),
(8,1,'daniq.jpg','daniq.jpg',1),
(9,1,'argir.jpg','argir.jpg',1),
(10,1,'ivan.jpg','ivan.jpg',1),
(11,1,'ivo.jpg','ivo.jpg',1);


INSERT INTO `image_instances`(`id`, `user_id`, `image_id`) 
VALUES 
(1,1,1),
(2,2,2),
(3,3,3),
(4,4,4),
(5,5,5),
(6,6,6),
(7,7,7),
(8,8,8),
(9,9,9),
(10,10,10),
(11,11,11);


