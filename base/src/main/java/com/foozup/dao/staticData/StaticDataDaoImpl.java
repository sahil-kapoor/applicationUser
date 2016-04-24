package com.foozup.dao.staticData;

import java.util.List;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.cache.annotation.Cacheable;
import org.springframework.stereotype.Repository;

import com.foozup.dao.AbstractDao;
import com.foozup.dao.city.CityDaoImpl;
import com.foozup.model.staticData.Location;

@Repository("staticDataDao")
public class StaticDataDaoImpl implements StaticDataDao {

	
	private AbstractDao abstractDao; 
	private static final Logger logger = LoggerFactory.getLogger(CityDaoImpl.class);;
	 
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

	@Override
	@Cacheable(value="locationCache", key="#cityId")
	public Location getAllAreaLocation(String cityId) {
	
		
		String query="SELECT c.id as cityId,c.name as cityName,c.lat cityLong,c.lon as cityLon, a.id as areaId ,"+ 
		"a.name as areaName ,a.lat as areaLat,a.lon as areaLon,"+ 
		"l.id as locationId, l.name as locationName, l.lat as locationLat, l.lon as locationLon"+
		"FROM foozup_restaurant.cities c left join foozup_restaurant.areas a on c.id=a.city_id "+
		"left join foozup_restaurant.locations l on a.id=l.area_id where c.id='"+cityId+"'";
		logger.debug(query);
		//List<Map> rows = abstractDao.getJdbcTemplate().queryFor(query);
		
		return null;
	}

	
}
