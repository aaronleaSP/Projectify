CREATE DATABASE projectify;

CREATE TABLE `projectify`.`projects_table` (
  `project_id` INT NOT NULL AUTO_INCREMENT,
  `project_name` VARCHAR(50) NOT NULL,
  `project_desc` TEXT,
  PRIMARY KEY (`project_id`));

CREATE TABLE `projectify`.`tasks_table` (
  `task_id` INT NOT NULL AUTO_INCREMENT,
  `project_id` INT NOT NULL,
  `task_name` TEXT NOT NULL,
  `task_description` TEXT,
  `task_status` VARCHAR(11) NOT NULL,
  `assignee_email` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`task_id`),
  INDEX `project_id_idx` (`project_id` ASC) VISIBLE,
  CONSTRAINT `fk_tasks_project_id`
    FOREIGN KEY (`project_id`)
    REFERENCES `projectify`.`projects_table` (`project_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


CREATE TABLE `projectify`.`permissions_table` (
  `permission_id` INT NOT NULL AUTO_INCREMENT,
  `project_id` INT NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `permission_type` VARCHAR(6) NOT NULL,
  PRIMARY KEY (`permission_id`),
  INDEX `project_id_idx` (`project_id` ASC) VISIBLE,
  CONSTRAINT `fk_permissions_project_id`
    FOREIGN KEY (`project_id`)
    REFERENCES `projectify`.`projects_table` (`project_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


CREATE TABLE `projectify`.`reminders_table` (
  `reminder_id` INT NOT NULL AUTO_INCREMENT,
  `project_id` INT NOT NULL,
  `task_id` INT NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `remind_datetime` DATETIME NOT NULL,
  `remind_option` VARCHAR(19) NOT NULL,
  PRIMARY KEY (`reminder_id`),
  INDEX `fk_reminders_task_id_idx` (`task_id` ASC) VISIBLE,
  CONSTRAINT `fk_reminders_task_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `projectify`.`tasks_table` (`task_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  INDEX `fk_reminders_project_id_idx` (`project_id` ASC) VISIBLE,
  CONSTRAINT `fk_reminders_project_id`
    FOREIGN KEY (`project_id`)
    REFERENCES `projectify`.`projects_table` (`project_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

