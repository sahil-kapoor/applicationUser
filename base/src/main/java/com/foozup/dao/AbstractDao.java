package com.foozup.dao;

import javax.sql.DataSource;

import org.springframework.jdbc.core.JdbcTemplate;



public class AbstractDao {
	
	private DataSource dataSource;
	 
	public AbstractDao(DataSource dataSource){
		this.dataSource=dataSource;
	}
	    
	    public JdbcTemplate getJdbcTemplate(){
	    	JdbcTemplate jdbcTemplate=new JdbcTemplate(this.dataSource);
	    	return jdbcTemplate;
	    }
}
