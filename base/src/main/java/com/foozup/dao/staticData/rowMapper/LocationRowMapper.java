package com.foozup.dao.staticData.rowMapper;

import java.sql.ResultSet;
import java.sql.SQLException;

import org.springframework.jdbc.core.RowMapper;

import com.foozup.model.staticData.Location;

public class LocationRowMapper implements RowMapper
{

	@Override
	public Object mapRow(ResultSet rs, int rowNum) throws SQLException {
		Location loc=new Location();
		
		return null;
	}

	

}
