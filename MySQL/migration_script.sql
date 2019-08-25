-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: migrated_prjmgt
-- Source Schemata: prjmgt
-- Created: Sun Aug 25 10:54:36 2019
-- Workbench Version: 8.0.17
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- Schema migrated_prjmgt
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `migrated_prjmgt` ;
CREATE SCHEMA IF NOT EXISTS `migrated_prjmgt` ;

-- ----------------------------------------------------------------------------
-- Table migrated_prjmgt.customers
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrated_prjmgt`.`customers` (
  `cust_id` INT(11) NOT NULL,
  `prj_id` INT(11) NOT NULL,
  `name` VARCHAR(90) NOT NULL,
  `address` VARCHAR(99) NULL DEFAULT NULL,
  `phone_number` VARCHAR(15) NULL DEFAULT NULL,
  `email` VARCHAR(80) NULL DEFAULT NULL,
  `prj_location` VARCHAR(80) NULL DEFAULT NULL,
  PRIMARY KEY (`cust_id`),
  INDEX `customers_project` (`prj_id` ASC),
  CONSTRAINT `customers_project`
    FOREIGN KEY (`prj_id`)
    REFERENCES `migrated_prjmgt`.`projects` (`prj_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table migrated_prjmgt.departments
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrated_prjmgt`.`departments` (
  `dept_id` INT(11) NOT NULL,
  `name` VARCHAR(60) NULL DEFAULT NULL,
  `description` VARCHAR(99) NULL DEFAULT NULL,
  `mngr_emp_id` INT(11) NULL DEFAULT NULL,
  `location` VARCHAR(80) NULL DEFAULT NULL,
  PRIMARY KEY (`dept_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table migrated_prjmgt.dept_projects
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrated_prjmgt`.`dept_projects` (
  `prj_id` INT(11) NOT NULL,
  `dept_id` INT(11) NOT NULL,
  PRIMARY KEY (`prj_id`, `dept_id`),
  INDEX `projects_department` (`dept_id` ASC),
  CONSTRAINT `departments_project`
    FOREIGN KEY (`prj_id`)
    REFERENCES `migrated_prjmgt`.`projects` (`prj_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `projects_department`
    FOREIGN KEY (`dept_id`)
    REFERENCES `migrated_prjmgt`.`departments` (`dept_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table migrated_prjmgt.employees
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrated_prjmgt`.`employees` (
  `emp_id` INT(11) NOT NULL,
  `name` VARCHAR(90) NOT NULL,
  `job_title` VARCHAR(40) NULL DEFAULT NULL,
  `age` INT(3) NULL DEFAULT NULL,
  `dept_id` INT(11) NOT NULL,
  `gender` ENUM('M', 'F') NULL DEFAULT NULL,
  `wage` FLOAT NULL DEFAULT NULL,
  `emp_status` ENUM('Active', 'Inactive') NOT NULL,
  `email` VARCHAR(80) NULL DEFAULT NULL,
  `phone_number` VARCHAR(15) NULL DEFAULT NULL,
  PRIMARY KEY (`emp_id`),
  INDEX `employees_department` (`dept_id` ASC),
  CONSTRAINT `employees_department`
    FOREIGN KEY (`dept_id`)
    REFERENCES `migrated_prjmgt`.`departments` (`dept_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table migrated_prjmgt.projects
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrated_prjmgt`.`projects` (
  `prj_id` INT(11) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date_proj` DATE NOT NULL,
  `end_date_act` DATE NULL DEFAULT NULL,
  `description` VARCHAR(99) NULL DEFAULT NULL,
  `pm_emp_id` INT(11) NOT NULL,
  `budget_est` FLOAT NOT NULL,
  `hours_prj` FLOAT NOT NULL,
  `status` ENUM('Ongoing', 'Cancelled', 'Completed', 'Suspended') NOT NULL,
  PRIMARY KEY (`prj_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table migrated_prjmgt.resources
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrated_prjmgt`.`resources` (
  `res_id` INT(11) NOT NULL DEFAULT '0',
  `prj_id` INT(11) NOT NULL DEFAULT '0',
  `name` VARCHAR(50) NOT NULL,
  `cost` FLOAT NULL DEFAULT NULL,
  `description` VARCHAR(99) NULL DEFAULT NULL,
  `type` ENUM('Primary', 'Secondary', 'Tertiary') NULL DEFAULT NULL,
  `hrs_prj` FLOAT NULL DEFAULT NULL,
  PRIMARY KEY (`res_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table migrated_prjmgt.task_times
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrated_prjmgt`.`task_times` (
  `tasks_task_id` BIGINT(11) NOT NULL,
  `task_date` DATE NOT NULL,
  `prj_id` INT(11) NULL DEFAULT NULL,
  `emp_id` INT(11) NULL DEFAULT NULL,
  `hours` FLOAT NULL DEFAULT NULL,
  PRIMARY KEY (`task_date`, `tasks_task_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------------------------------------------------------
-- Table migrated_prjmgt.tasks
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrated_prjmgt`.`tasks` (
  `task_id` BIGINT(11) NOT NULL DEFAULT '0',
  `prj_id` INT(11) NOT NULL,
  `description` VARCHAR(99) NULL DEFAULT NULL,
  `emp_id` INT(11) NOT NULL,
  `daily_hrs` FLOAT NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`),
  INDEX `tasks_project` (`prj_id` ASC),
  CONSTRAINT `tasks_project`
    FOREIGN KEY (`prj_id`)
    REFERENCES `migrated_prjmgt`.`projects` (`prj_id`)
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Routine migrated_prjmgt.do_sum_project_hrs
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `migrated_prjmgt`$$
CREATE DEFINER=`Equipo_Catorce`@`%` PROCEDURE `do_sum_project_hrs`(IN prjID int)
BEGIN
	DECLARE totHours FLOAT DEFAULT 0.0;
	IF exists (select hours from task_times where prj_id = prjID) then
		select sum(hours) from task_times where prj_id = prjID into totHours;
        Update projects set hours_prj = totHours where prj_id = prjID;
    else
		set totHours = -1.0;
	End If; 
END$$

DELIMITER ;

-- ----------------------------------------------------------------------------
-- Routine migrated_prjmgt.get_newest_task_date
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `migrated_prjmgt`$$
CREATE DEFINER=`Equipo_Catorce`@`%` PROCEDURE `get_newest_task_date`(IN tID bigint, IN prjID int, IN empID int, OUT ndtd varchar(24))
BEGIN         
   if exists (select task_date from task_times where(tasks_task_id = tID and prj_id = prjID and emp_id = empID)) then
      select task_date from task_times where(tasks_task_id = tID and prj_id = prjID and emp_id = empID) order by task_date desc limit 1 into @ndtd;      
   else
      set ndtd = null;
   end if;
END$$

DELIMITER ;

-- ----------------------------------------------------------------------------
-- Routine migrated_prjmgt.get_project_manager
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `migrated_prjmgt`$$
CREATE DEFINER=`Equipo_Catorce`@`%` PROCEDURE `get_project_manager`(IN prjID int)
BEGIN
declare empid int default 0;
select pm_emp_id from projects where prj_id = prjID into empid;
IF empid > 0 then 
	select name from employees where emp_id = empid;
else
	select 'Not Found';
End If;
END$$

DELIMITER ;

-- ----------------------------------------------------------------------------
-- Routine migrated_prjmgt.get_sum_emp_project_hrs
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `migrated_prjmgt`$$
CREATE DEFINER=`Equipo_Catorce`@`%` PROCEDURE `get_sum_emp_project_hrs`(IN empID int, IN prjID int, OUT totHours float)
BEGIN
	IF exists (select hours from task_times where prj_id = prjID and emp_id = empID) then
		select sum(hours) from task_times where prj_id = prjID and emp_id = empID into totHours;
    else
		set totHours = -1;
	End If;
END$$

DELIMITER ;

-- ----------------------------------------------------------------------------
-- Routine migrated_prjmgt.get_sum_project_hrs
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `migrated_prjmgt`$$
CREATE DEFINER=`Equipo_Catorce`@`%` PROCEDURE `get_sum_project_hrs`(IN prjID int, OUT totHours float)
BEGIN
	IF exists (select hours from task_times where prj_id = prjID) then
		select sum(hours) from task_times where prj_id = prjID into totHours;
    else
		set totHours = 0.0;
	End If;
END$$

DELIMITER ;

-- ----------------------------------------------------------------------------
-- Routine migrated_prjmgt.get_tasks
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `migrated_prjmgt`$$
CREATE DEFINER=`Equipo_Catorce`@`%` PROCEDURE `get_tasks`()
BEGIN
   select tasks.task_id, tasks.prj_id, tasks.description, tasks.emp_id, 
   tasks.daily_hrs, task_times.time_entered from tasks 
   INNER JOIN task_times 
   ON tasks.task_id = task_times.tasks_task_id;
END$$

DELIMITER ;

-- ----------------------------------------------------------------------------
-- Routine migrated_prjmgt.set_task_times_date
-- ----------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$
USE `migrated_prjmgt`$$
CREATE DEFINER=`Equipo_Catorce`@`%` PROCEDURE `set_task_times_date`(IN tid bigint, IN prjID int, IN empID int)
BEGIN
	update task_times set task_date = date(now()) where(tasks_task_id = tid and prj_id = prjID and emp_id = empID); 
END$$

DELIMITER ;
SET FOREIGN_KEY_CHECKS = 1;
