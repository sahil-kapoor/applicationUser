create index location_index on restaurants (location_id);


SET @DATABASE_NAME = 'foozup_restaurant';

SELECT  CONCAT('ALTER TABLE `', table_name, '` ENGINE=InnoDB;') AS sql_statements
FROM    information_schema.tables AS tb
WHERE   table_schema = @DATABASE_NAME
AND     `ENGINE` = 'MyISAM'
AND     `TABLE_TYPE` = 'BASE TABLE'
ORDER BY table_name DESC;

ALTER TABLE `foozup_restaurant`.`updates_tags` 
ADD CONSTRAINT `update_f_key`
  FOREIGN KEY (`update_id`)
  REFERENCES `foozup_restaurant`.`restaurant_updates` (`id`)
   ON DELETE CASCADE
  ON UPDATE NO ACTION;
  
  ALTER TABLE `foozup_restaurant`.`updates_tags` 
DROP FOREIGN KEY `tag_f_key`;
ALTER TABLE `foozup_restaurant`.`updates_tags` 
ADD CONSTRAINT `tag_f_key`
  FOREIGN KEY (`tag_id`)
  REFERENCES `foozup_restaurant`.`tags` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;


  
  