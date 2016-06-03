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
  
  
  delete from franchisee_updates_restaurant where update_id not in( select id from franchisee_updates);
  delete from franchisee_updates_restaurant where restaurant_id not in( select id from restaurants);
  
  ALTER TABLE `foozup_restaurant`.`franchisee_updates_restaurant` 
ADD INDEX `franchisee_updaety_fk_idx` (`update_id` ASC);
ALTER TABLE `foozup_restaurant`.`franchisee_updates_restaurant` 
ADD CONSTRAINT `franchisee_updaety_fk`
  FOREIGN KEY (`update_id`)
  REFERENCES `foozup_restaurant`.`franchisee_updates` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION

ALTER TABLE `foozup_restaurant`.`franchisee_updates_restaurant` 
DROP FOREIGN KEY `franchisee_updaety_fk`;
ALTER TABLE `foozup_restaurant`.`franchisee_updates_restaurant` 
ADD CONSTRAINT `franchisee_update_fk`
  FOREIGN KEY (`update_id`)
  REFERENCES `foozup_restaurant`.`franchisee_updates` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
  ALTER TABLE `foozup_restaurant`.`franchisee_updates_restaurant` 
ADD INDEX `restaurant_id_fk_idx` (`restaurant_id` ASC);
ALTER TABLE `foozup_restaurant`.`franchisee_updates_restaurant` 
ADD CONSTRAINT `restaurant_id_fk`
  FOREIGN KEY (`restaurant_id`)
  REFERENCES `foozup_restaurant`.`restaurants` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

  
  delete from franchisee_updates_days where update_id not in( select id from franchisee_updates);
  
  ALTER TABLE `foozup_restaurant`.`franchisee_updates_days` 
ADD INDEX `update_id_fk_idx` (`update_id` ASC);
ALTER TABLE `foozup_restaurant`.`franchisee_updates_days` 
ADD CONSTRAINT `update_id_fk`
  FOREIGN KEY (`update_id`)
  REFERENCES `foozup_restaurant`.`franchisee_updates` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
  delete from restaurant_updates where restaurant_id not in( select id from franchisee_updates);
  
  ALTER TABLE `foozup_restaurant`.`restaurant_updates` 
ADD INDEX `rest_id_fk_idx` (`restaurant_id` ASC);
ALTER TABLE `foozup_restaurant`.`restaurant_updates` 
ADD CONSTRAINT `rest_id_fk`
  FOREIGN KEY (`restaurant_id`)
  REFERENCES `foozup_restaurant`.`restaurants` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
  
  delete from restaurant_updates_days where update_id not in( select id from restaurant_updates);
  
  ALTER TABLE `foozup_restaurant`.`restaurant_updates_days` 
ADD CONSTRAINT `update_id_fk_rest`
  FOREIGN KEY (`update_id`)
  REFERENCES `foozup_restaurant`.`restaurant_updates` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
  
  