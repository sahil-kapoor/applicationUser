package com.foozup.dao.staticData.rowMapper;

import java.sql.ResultSet;
import java.sql.SQLException;

import org.springframework.jdbc.core.RowMapper;

import com.foozup.model.staticData.City;

@SuppressWarnings("rawtypes")
public class CityRowMapper implements RowMapper
{

	@Override
	public Object mapRow(ResultSet rs, int rowNum) throws SQLException {
		City city=new City();
		city.setId(rs.getShort("cityId"));
		city.setName(rs.getString("cityName"));
		city.setLatitude(rs.getString("cityLat"));
		city.setLongitude(rs.getString("cityLon"));
		
		return city;
	}

	

}
