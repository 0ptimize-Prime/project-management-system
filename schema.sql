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
    `manager`     varchar(20)                                 NOT NULL,
    `title`       varchar(50)                                 NOT NULL,
    `description` text                                                 DEFAULT NULL,
    `created_at`  timestamp                                   NOT NULL DEFAULT (current_timestamp()),
    `deadline`    date                                        NULL     DEFAULT NULL,
    `status`      ENUM ('CREATED', 'IN_PROGRESS', 'COMPLETE') NOT NULL DEFAULT 'CREATED'
);

CREATE TABLE `task`
(
    `id`          varchar(20) PRIMARY KEY NOT NULL,
    `project_id`  varchar(20)             NOT NULL,
    `title`       varchar(50)             NOT NULL,
    `description` text                    NULL     DEFAULT NULL,
    `username`    varchar(20)             NULL     DEFAULT NULL,
    `created_at`  timestamp               NOT NULL DEFAULT (current_timestamp()),
    `deadline`    date                    NULL     DEFAULT NULL,
    `completed_date`    date              NULL     DEFAULT NULL,
    `status`      ENUM (
        'CREATED',
        'ASSIGNED',
        'IN_PROGRESS',
        'PENDING',
        'COMPLETE'
        )                                 NOT NULL DEFAULT 'CREATED',
    `effort`      int(11)                 NULL     DEFAULT NULL,
    `ind`         int(11)                 NOT NULL
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
    ADD FOREIGN KEY (`manager`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `task`
    ADD FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `task`
    ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `milestone`
    ADD FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `comment`
    ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE;
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
