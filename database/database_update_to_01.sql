-- Update Platform Formation version 0.0 to 0.1

--
-- Create table `aquisition_level` with needed values
--

CREATE TABLE `plafor`.`acquisition_level` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `name` VARCHAR(20) NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `plafor`.`acquisition_level` (`id`, `name`) VALUES ('1', 'Expliqué'), ('2', 'Exercé'), ('3', 'Autonome');

--
-- Create table `objective`
--

CREATE TABLE `plafor`.`objective` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `fk_operational_competence` INT NOT NULL ,
    `fk_acquisition_level` INT NOT NULL,
    `name` VARCHAR(45) NOT NULL ,
    `description` TEXT NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--
-- Create table `operational_competence`
--

CREATE TABLE `plafor`.`operational_competence` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `professional` TEXT NOT NULL ,
    `methodologic` TEXT NOT NULL ,
    `social` TEXT NOT NULL ,
    `personal` TEXT NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--
-- Create table `course_plan`
--

CREATE TABLE `plafor`.`course_plan` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `name` VARCHAR(45) NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--
-- Create table `user_course`
--

CREATE TABLE `plafor`.`user_course` ( 
    `id` INT NOT NULL AUTO_INCREMENT ,
    `fk_user` INT NOT NULL ,
    `fk_course_plan` INT NOT NULL ,
    `fk_statut` INT NOT NULL ,
    `begin_date` DATE NOT NULL ,
    `end_date` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--
-- Create table `user_course_statut` with needed values
--

CREATE TABLE `plafor`.`user_course_statut` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `name` VARCHAR(20) NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `plafor`.`user_course_statut` (`id`, `name`) VALUES ('1', 'En cours'), ('2', 'Réussi'), ('3', 'Échouée'), ('4', 'Suspendue'), ('5', 'Abandonnée');

--
-- Create table `competence_domain`
--

CREATE TABLE `plafor`.`competence_domain` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `fk_course_plan` INT NOT NULL ,
    `name` VARCHAR(45) NOT NULL ,
    `description` TEXT NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--
-- Add relations
--

ALTER TABLE `plafor`.`competence_domain` ADD CONSTRAINT `constraint_competence_domain_course_plan` FOREIGN KEY (`fk_course_plan`) REFERENCES `course_plan` (`id`);
ALTER TABLE `plafor`.`user_course` ADD CONSTRAINT `constraint_user` FOREIGN KEY (`fk_user`) REFERENCES `user` (`id`);
ALTER TABLE `plafor`.`user_course` ADD CONSTRAINT `constraint_user_course_plan` FOREIGN KEY (`fk_course_plan`) REFERENCES `course_plan` (`id`);
ALTER TABLE `plafor`.`user_course` ADD CONSTRAINT `constraint_statut` FOREIGN KEY (`fk_statut`) REFERENCES `user_course_statut` (`id`);
ALTER TABLE `plafor`.`objective` ADD CONSTRAINT `constraint_operational_competence` FOREIGN KEY (`fk_operational_competence`) REFERENCES `operational_competence` (`id`);
ALTER TABLE `plafor`.`objective` ADD CONSTRAINT `constraint_acquisition_level` FOREIGN KEY (`fk_acquisition_level`) REFERENCES `acquisition_level` (`id`);