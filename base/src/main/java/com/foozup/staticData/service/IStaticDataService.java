package com.foozup.staticData.service;

import java.util.List;
import java.util.Map;

import com.foozup.staticData.model.Area;
import com.foozup.staticData.model.City;
import com.foozup.staticData.model.Location;
import com.foozup.staticData.model.Tag;

public interface IStaticDataService {

	public City getCityById(Integer cityId);
	public Area getAreaById(Integer cityId,Integer areaId);
	public Location getLocation(Integer cityId,Integer areaId,Integer locationId);
	public Area getAreaByLocId(Integer cityId,Integer locationId);
	public List<Area> getAreasbyCityId(Integer cityId);
	public List<Integer>  getAreabyLocIds(Integer cityId,List<Integer> locIds);
	public List<Location> getLocationsByAreaId(Integer areaId);
	public List<Location> getLocationsByAreaId(List<Integer> areaId);
	public Map<Integer,String> getTags(List<Tag> tags);
	public List<Tag> getTags();
	
}
