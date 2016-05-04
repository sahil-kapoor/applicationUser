package com.foozup.staticData.dao;

import java.util.List;
import java.util.Map;

import com.foozup.staticData.model.City;
import com.foozup.staticData.model.Location;

public interface IStaticDataDao {

	public List<Object> getAreaByCity(String cityId);
	
	public Map<String,List<Object>> getLocationDetailsByArea(String areaId);
	
	public City getAllAreaLocation(Integer cityId);
}
