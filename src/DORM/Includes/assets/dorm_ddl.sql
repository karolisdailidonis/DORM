CREATE TABLE IF NOT EXISTS dorm_model_list (
    table_name varchar(100) NOT NULL,
    class_name varchar(100) NOT NULL,
    create_timestamp timestamp NOT NULL,

    PRIMARY KEY(table_name)
);