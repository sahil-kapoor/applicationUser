package com.foozup.staticData.service;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.staticData.dao.IStaticDataDao;
import com.foozup.staticData.model.Area;
import com.foozup.staticData.model.City;
import com.foozup.staticData.model.Location;
import com.foozup.staticData.model.Tag;

@Service("staticDataService")
public class StaticDataServiceImpl implements IStaticDataService {

	@Autowired
	private IStaticDataDao staticDataDaoImpl;

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
	public List<Integer> getAreabyLocIds(Integer cityId, List<Integer> locIds) {
		Set<Integer> areaIds=new HashSet<>();
		Map<Integer,Area> areaMap=staticDataDaoImpl.getAllAreaLocation(cityId).getAreas();
		for(Integer locationId:locIds){
		areaIds.add(areaMap.entrySet().stream().filter(p->p.getValue().getLocations().entrySet().stream().
				filter(x->x.getKey().intValue()==locationId.intValue()).
				findAny().isPresent()).findAny().get().getKey());
		}
		List<Integer> list = new ArrayList<Integer>(areaIds);
		return list;
		
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

	@Override
	public Map<Integer, String> getTags(List<Tag> tags) {
		Map<Integer, String> tagMap=new HashMap<>();
		staticDataDaoImpl.getAllTags().forEach(x->{
			tagMap.put(x.getTagId(),x.getTagName());
		});
		return tagMap;
	}

	@Override
	public List<Tag> getTags() {
		staticDataDaoImpl.getAllTags();
		return null;
	}
	
}
