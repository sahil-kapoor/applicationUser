package com.foozup.dao.staticData;

import java.util.List;
import java.util.Map;

import com.foozup.model.staticData.Location;

public interface StaticDataDao {

	public List<Object> getAreaByCity(String cityId);
	
	public Map<String,List<Object>> getLocationDetailsByArea(String areaId);
	
	public Location getAllAreaLocation(String cityId);
}
