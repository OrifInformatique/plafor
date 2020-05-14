-- Update Platform Formation version 0.0 to 0.1

--
-- Create table `aquisition_level` with needed values
--

CREATE TABLE `plafor`.`acquisition_level` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(20) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

INSERT INTO `aquisition_level` (`id`, `name`) VALUES ('1', 'Expliqué'), ('2', 'Exercé'), ('3', 'Autonome');

--
-- Create table `competence_domain`
--

CREATE TABLE `plafor`.`competence_domain` ( `id` INT NOT NULL AUTO_INCREMENT , `fk_acquisition_level` INT NOT NULL , `name` VARCHAR(45) NOT NULL , `description` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

--
-- Create table `objective`
--

CREATE TABLE `plafor`.`objective` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(45) NOT NULL , `description` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

--
-- Create table `objective_list`
--

CREATE TABLE `plafor`.`objective_list` ( `id` INT NOT NULL AUTO_INCREMENT , `fk_objective` INT NOT NULL , `fk_competence_domain` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

--
-- Create table `plan_formation`
--

CREATE TABLE `plafor`.`plan_formation` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(45) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

--
-- Create table `formation`
--

CREATE TABLE `plafor`.`formation` ( `id` INT NOT NULL AUTO_INCREMENT , `fk_user` INT NOT NULL , `fk_plan_formation` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

--
-- Create table `competence_list`
--

CREATE TABLE `plafor`.`competence_list` ( `id` INT NOT NULL AUTO_INCREMENT , `fk_plan_formation` INT NOT NULL , `fk_competence_domain` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

--
-- Add relations
--

ALTER TABLE competence_domain ADD CONSTRAINT constraint_acquisition_level FOREIGN KEY (fk_acquisition_level) REFERENCES acquisition_level(id);
ALTER TABLE objective_list ADD CONSTRAINT constraint_objective FOREIGN KEY (fk_objective) REFERENCES objective(id);
ALTER TABLE objective_list ADD CONSTRAINT constraint_competence_domain FOREIGN KEY (fk_competence_domain) REFERENCES competence_domain(id);
ALTER TABLE competence_list ADD CONSTRAINT constraint_competence_list_domain FOREIGN KEY (fk_competence_domain) REFERENCES competence_domain(id);
ALTER TABLE competence_list ADD CONSTRAINT constraint_plan_formation FOREIGN KEY (fk_plan_formation) REFERENCES plan_formation(id);
ALTER TABLE formation ADD CONSTRAINT constraint_user FOREIGN KEY (fk_user) REFERENCES user(id);
ALTER TABLE formation ADD CONSTRAINT constraint_formation FOREIGN KEY (fk_plan_formation) REFERENCES plan_formation(id);