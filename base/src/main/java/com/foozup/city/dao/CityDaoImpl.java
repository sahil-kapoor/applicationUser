package com.foozup.city.dao;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.foozup.dao.AbstractDao;

public class CityDaoImpl implements CityDao {

	
	private AbstractDao abstractDao; 
	private static final Logger logger = LoggerFactory.getLogger(CityDaoImpl.class);;
	 
	public CityDaoImpl(AbstractDao abstractDao) {
		this.abstractDao=abstractDao;
	}

	@Override
	public String getNameById(String Id) {
		String query="Select name from city where ID=?";
		logger.debug(query);
		String name= (String)abstractDao.getJdbcTemplate().queryForObject(
				query, new Object[] { Id }, String.class);
		
		return name;
	}

}
