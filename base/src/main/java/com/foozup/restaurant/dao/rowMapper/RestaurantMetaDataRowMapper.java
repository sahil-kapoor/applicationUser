package com.foozup.restaurant.dao.rowMapper;

import java.sql.ResultSet;
import java.sql.SQLException;

import org.springframework.jdbc.core.RowMapper;

import com.foozup.restaurant.model.RestaurantBase;

@SuppressWarnings("rawtypes")
public class RestaurantMetaDataRowMapper implements RowMapper{
	public Object mapRow(ResultSet rs, int rowNum) throws SQLException {
		RestaurantBase restBase = new RestaurantBase();
		restBase.setId(rs.getInt("restId"));
		restBase.setName(rs.getString("name"));
		restBase.setAddress(rs.getString("address"));
		restBase.setAreaId(rs.getInt("areaId"));
		restBase.setCityId(rs.getInt("cityId"));
		restBase.setLocationId(rs.getInt("locationId"));
		restBase.setPhoto(rs.getString("photo"));
		restBase.setCost((int)(rs.getFloat("cost")));
		restBase.setFranchisee(rs.getInt("isFranchisee") == 0?false:true );
		restBase.setMinDeliveryCost((int)rs.getFloat("minDeliveryCost"));
		return restBase;
	}
}	
	