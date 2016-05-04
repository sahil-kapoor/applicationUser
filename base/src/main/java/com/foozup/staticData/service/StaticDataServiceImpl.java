package com.foozup.staticData.service;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.staticData.dao.StaticDataDao;
import com.foozup.staticData.model.Area;
import com.foozup.staticData.model.City;
import com.foozup.staticData.model.Location;

@Service("staticDataService")
public class StaticDataServiceImpl implements StaticDataService {

	@Autowired
	private StaticDataDao staticDataDaoImpl;

	@Override
	public City getCityById(Integer cityId) {
		return staticDataDaoImpl.getAllAreaLocation(cityId);
		
	}

	@Override
	public Area getAreaById(Integer cityId, Integer areaId) {
		return staticDataDaoImpl.getAllAreaLocation(cityId).getAreas().get(areaId);
	}

	@Override
	public Location getLocation(Integer cityId, Integer areaId, Integer locationId) {
		return staticDataDaoImpl.getAllAreaLocation(cityId).getAreas().get(areaId).getLocations().get(locationId);
	}

	@Override
	public Area getAreaByLocId(Integer cityId, Integer locationId) {
	Area area=new Area();
		Map<Integer,Area> areaMap=staticDataDaoImpl.getAllAreaLocation(cityId).getAreas();
		area=areaMap.entrySet().stream().filter(p->p.getValue().getLocations().entrySet().stream().filter(x->x.getKey().intValue()==locationId.intValue()).findAny().isPresent()).findAny().get().getValue();
		return area;
	}

	@Override
	public List<Area> getAreasbyCityId(Integer cityId) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<Area> getAreabyLocIds(Integer cityId, List<Integer> locIds) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<Location> getLocationsByAreaId(Integer areaId) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<Location> getLocationsByAreaId(List<Integer> areaId) {
		// TODO Auto-generated method stub
		return null;
	}
	
	
	
}
