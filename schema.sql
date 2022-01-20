CREATE TABLE `user`
(
    `username`        varchar(20) PRIMARY KEY               NOT NULL,
    `name`            varchar(50)                           NOT NULL,
    `password`        varchar(256)                          NOT NULL,
    `user_type`       ENUM ('ADMIN', 'MANAGER', 'EMPLOYEE') NOT NULL DEFAULT 'EMPLOYEE',
    `profile_picture` varchar(40)                           NULL     DEFAULT NULL
);

CREATE TABLE `project`
(
    `id`          varchar(20) PRIMARY KEY                     NOT NULL,
    `manager`     varchar(20)                                 NULL,
    `title`       varchar(50)                                 NOT NULL,
    `description` text                                                 DEFAULT NULL,
    `created_at`  timestamp                                   NOT NULL DEFAULT (current_timestamp()),
    `deadline`    date                                        NULL     DEFAULT NULL,
    `status`      ENUM ('CREATED', 'IN_PROGRESS', 'COMPLETE') NOT NULL DEFAULT 'CREATED'
);

CREATE TABLE `task`
(
    `id`             varchar(20) PRIMARY KEY NOT NULL,
    `project_id`     varchar(20)             NOT NULL,
    `title`          varchar(50)             NOT NULL,
    `description`    text                    NULL     DEFAULT NULL,
    `username`       varchar(20)             NULL     DEFAULT NULL,
    `created_at`     timestamp               NOT NULL DEFAULT (current_timestamp()),
    `deadline`       date                    NULL     DEFAULT NULL,
    `completed_date` date                    NULL     DEFAULT NULL,
    `status`         ENUM (
        'CREATED',
        'ASSIGNED',
        'IN_PROGRESS',
        'PENDING',
        'COMPLETE'
        )                                    NOT NULL DEFAULT 'CREATED',
    `effort`         int(11)                 NULL     DEFAULT NULL,
    `ind`            int(11)                 NOT NULL
);

CREATE TABLE `milestone`
(
    `id`         varchar(20) PRIMARY KEY      NOT NULL,
    `project_id` varchar(20)                  NOT NULL,
    `title`      varchar(50)                  NOT NULL,
    `created_at` timestamp                    NOT NULL DEFAULT (current_timestamp()),
    `status`     ENUM ('CREATED', 'COMPLETE') NOT NULL DEFAULT 'CREATED',
    `ind`        int(11)                      NOT NULL
);

CREATE TABLE `comment`
(
    `id`         varchar(20) PRIMARY KEY NOT NULL,
    `task_id`    varchar(20)             NOT NULL,
    `username`   varchar(20)             NOT NULL,
    `body`       text                    NOT NULL,
    `created_at` timestamp               NOT NULL DEFAULT (current_timestamp())
);

CREATE TABLE `file`
(
    `id`         varchar(40) PRIMARY KEY NOT NULL,
    `item_id`    varchar(20)             NOT NULL,
    `name`       varchar(50)             NOT NULL,
    `created_at` timestamp               NOT NULL DEFAULT (current_timestamp())
);

CREATE TABLE `notification`
(
    `id`         varchar(40) PRIMARY KEY NOT NULL,
    `username`   varchar(20)             NOT NULL,
    `item_id`    varchar(20)             NOT NULL,
    `created_at` timestamp               NOT NULL DEFAULT (current_timestamp()),
    `type`       ENUM (
        'TASK_ASSIGNMENT',
        'TASK_PENDING_APPROVAL',
        'TASK_COMPLETED'
        )                                NOT NULL,
    `is_read`    tinyint(1)              NULL     DEFAULT NULL
);


ALTER TABLE `project`
    ADD FOREIGN KEY (`manager`) REFERENCES `user` (`username`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `task`
    ADD FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `task`
    ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `milestone`
    ADD FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `comment`
    ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE;
ALTER TABLE `comment`
    ADD FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `notification`
    ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
CREATE FUNCTION `GetNextIndex`(`proj_id` VARCHAR(20)) RETURNS INT(11)
    NOT DETERMINISTIC
BEGIN
    DECLARE max_task_ind INT(11);
    DECLARE max_milestone_ind INT(11);
    SELECT MAX(ind) into max_task_ind FROM task WHERE project_id = proj_id;
    SELECT MAX(ind) into max_milestone_ind FROM milestone WHERE project_id = proj_id;
    RETURN GREATEST(COALESCE(max_task_ind, 0), COALESCE(max_milestone_ind, 0)) + 1;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `task_creation_notification`
    AFTER INSERT
    ON `task`
    FOR EACH ROW IF (NEW.username IS NOT NULL) THEN
    INSERT INTO notification (id, username, item_id, created_at, type, is_read)
    VALUES (REPLACE(UUID(), '-', ''), NEW.username, NEW.id, DEFAULT, 'TASK_ASSIGNMENT', 0);
END IF $$

CREATE TRIGGER `task_assignment_notification`
    AFTER UPDATE
    ON `task`
    FOR EACH ROW IF (OLD.username IS NULL AND NEW.username IS NOT NULL) THEN
    INSERT INTO notification (id, username, item_id, created_at, type, is_read)
    VALUES (REPLACE(UUID(), '-', ''), NEW.username, NEW.id, DEFAULT, 'TASK_ASSIGNMENT', 0);
END IF $$

CREATE TRIGGER `task_pending_notification`
    AFTER UPDATE
    ON `task`
    FOR EACH ROW IF (OLD.status = 'IN_PROGRESS' AND NEW.status = 'PENDING') THEN
    INSERT INTO notification (id, username, item_id, created_at, type, is_read)
    SELECT REPLACE(UUID(), '-', ''), project.manager, NEW.id, CURRENT_TIMESTAMP(), 'TASK_PENDING_APPROVAL', 0
    FROM project
    WHERE project.id = NEW.project_id;
END IF $$

CREATE TRIGGER `task_completed_notification`
    AFTER UPDATE
    ON `task`
    FOR EACH ROW IF (OLD.status = 'PENDING' AND NEW.status = 'COMPLETE') THEN
    INSERT INTO notification (id, username, item_id, created_at, type, is_read)
    VALUES (REPLACE(UUID(), '-', ''), NEW.username, NEW.id, DEFAULT, 'TASK_COMPLETED', 0);
END IF $$

CREATE TRIGGER `task_completed`
    BEFORE UPDATE
    ON `task`
    FOR EACH ROW IF (OLD.status = 'PENDING' AND NEW.status = 'COMPLETE') THEN
    SET NEW.completed_date = CURRENT_DATE();
END IF $$

CREATE TRIGGER `user_deleted`
    AFTER DELETE
    ON `user`
    FOR EACH ROW
    UPDATE task SET status = 'CREATED'
    WHERE task.username = OLD.username;
$$
DELIMITER ;
