package com.foozup.staticData.dao;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.cache.annotation.Cacheable;

import com.foozup.dao.AbstractDao;
import com.foozup.staticData.dao.rowMapper.CityRowMapper;
import com.foozup.staticData.model.Area;
import com.foozup.staticData.model.City;
import com.foozup.staticData.model.Location;


public class StaticDataDaoImpl implements IStaticDataDao {

	
	private AbstractDao abstractDao; 
	private static final Logger logger = LoggerFactory.getLogger(StaticDataDaoImpl.class);;
	 
	public StaticDataDaoImpl(AbstractDao abstractDao) {
		this.abstractDao=abstractDao;
	}
	
	
	@Override
	public List<Object> getAreaByCity(String cityId) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public Map<String, List<Object>> getLocationDetailsByArea(String areaId) {
		// TODO Auto-generated method stub
		return null;
	}

	@SuppressWarnings("unchecked")
	@Override
	@Cacheable(value="locationCache", key="#cityId")
	public City getAllAreaLocation(Integer cityId) {
	
		
		
		String cityQuery="SELECT c.id as cityId,c.name as cityName,c.lat cityLat,c.lon as cityLon FROM "+
		"foozup_restaurant.cities c where c.id=? ";
		City cityRow=(City)abstractDao.getJdbcTemplate().queryForObject(cityQuery, new Object[]{cityId},new CityRowMapper());
		cityRow.setAreas(new HashMap<>());
		
		Map<Integer,Area> areaMap=new HashMap<>();
		String areaQuery="select a.id as areaId ,a.name as areaName ,a.lat as areaLat,a.lon as areaLon "+
		"FROM foozup_restaurant.areas a where a.city_id=?";
		List<Map<String, Object>> areaRows=abstractDao.getJdbcTemplate().queryForList(areaQuery, cityId);
		
		
		String locationQuery="Select l.id as locationId,l.area_id as areaId, l.name as locationName, l.lat as locationLat, l.lon as locationLon "+
		"FROM foozup_restaurant.locations l where l.city_id=?";
		List<Map<String, Object>> locationRows=abstractDao.getJdbcTemplate().queryForList(locationQuery, cityId);
		
		
		for (Map row : areaRows) {
			Area area = new Area();
			area.setId(Integer.parseInt(String.valueOf(row.get("areaId"))));
			area.setName((String)row.get("areaName"));
			area.setLatitude((String)row.get("areaLat"));
			area.setLongitude((String)row.get("areaLon"));
			area.setCityId((cityId));
			area.setLocations(new HashMap<>());
			for (Map locRow : locationRows) {
			   	Location loc=new Location();
			   	if(Integer.parseInt(String.valueOf(locRow.get("areaId")))==area.getId()){
			   		loc.setAreaId(area.getId());
			   		loc.setCityId(cityId);
			   		loc.setId(Integer.parseInt(String.valueOf(locRow.get("locationId"))));
			   		loc.setLatitude((String)locRow.get("locationLat"));
			   		loc.setLongitude((String)locRow.get("locationLon"));
			   		loc.setName((String)locRow.get("locationName"));
			   		area.getLocations().put(loc.getId(), loc);
			    }
			 cityRow.getAreas().put(area.getId(), area);   	
		}
			            
     }

		
		return cityRow;
	}

	
}
