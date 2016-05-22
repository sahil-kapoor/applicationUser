package com.foozup.restaurant.dao;

import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.jdbc.core.namedparam.MapSqlParameterSource;
import org.springframework.jdbc.core.namedparam.NamedParameterJdbcTemplate;

import com.foozup.dao.AbstractDao;
import com.foozup.restaurant.dao.rowMapper.RestaurantMetaDataRowMapper;
import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.RestaurantInfo;

public class RestaurantDaoImpl implements IRestaurantDao {

	private AbstractDao abstractDao;
	private static final Logger logger = LoggerFactory.getLogger(RestaurantDaoImpl.class);;

	public RestaurantDaoImpl(AbstractDao abstractDao) {
		this.abstractDao = abstractDao;
	}

	@Override
	public List<RestaurantInfo> findRestaurantByName(String keyword) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<RestaurantBase> findRestrauantByLocation(List<Integer> locationIds) {

		List<RestaurantBase> restBaseList = new ArrayList<>();
		try {
			MapSqlParameterSource parameters = new MapSqlParameterSource();
			parameters.addValue("ids", locationIds);

			String restQuery = "SELECT r.id as restId, r.name as name, r.city_id as cityId,r.area_id as areaId,"
					+ "r.location_id as locationId,r.address as address,r.min_delivery_cost as minDeliveryCost,r.photo as photo, r.cost_for_2 as cost "
					+ ", r.is_franchisee as isFranchisee FROM foozup_restaurant.restaurants r where r.status=1 and r.location_id IN (:ids);";
			NamedParameterJdbcTemplate template = new NamedParameterJdbcTemplate(
					abstractDao.getJdbcTemplate().getDataSource());
			restBaseList = template.query(restQuery, parameters, new RestaurantMetaDataRowMapper());

		} catch (Exception ex) {
			logger.error("Error in  findRestrauantByLocation :" +ex.getMessage());
		}
		return restBaseList;
	}

	@Override
	public List<RestaurantBase> findRestrauantByArea(List<Integer> areaIds) {
		List<RestaurantBase> restBaseList = new ArrayList<>();
		try {
			MapSqlParameterSource parameters = new MapSqlParameterSource();
			parameters.addValue("ids", areaIds);

			String restQuery = "SELECT r.id as restId, r.name as name, r.city_id as cityId,r.area_id as areaId,"
					+ "r.location_id as locationId,r.address as address,r.min_delivery_cost as minDeliveryCost,r.photo as photo,  r.cost_for_2 as cost "
					+ " ,r.is_franchisee as isFranchisee FROM foozup_restaurant.restaurants r where r.status=1 and r.area_id IN (:ids);";
			NamedParameterJdbcTemplate template = new NamedParameterJdbcTemplate(
					abstractDao.getJdbcTemplate().getDataSource());
			restBaseList = template.query(restQuery, parameters, new RestaurantMetaDataRowMapper());

		} catch (Exception ex) {
			logger.error("Error in  findRestrauantByLocation :" +ex.getMessage());
		}
		return restBaseList;
	}

	
	@Override
	public List<RestaurantBase> getRestaurntByAreaServed(List<Integer> areaIdList) {
		List<RestaurantBase> restBaseList = new ArrayList<>();
		try {
			MapSqlParameterSource parameters = new MapSqlParameterSource();
			parameters.addValue("ids", areaIdList);

			String restQuery = "SELECT r.id as restId, r.name as name, r.city_id as cityId,r.area_id as areaId,"+
						"  r.location_id as locationId,r.address as address,r.min_delivery_cost as minDeliveryCost, r.photo as photo ,r.cost_for_2 as cost "+
					", r.is_franchisee as isFranchisee FROM foozup_restaurant.restaurants r,restaurant_areas_served rs where r.id=rs.restaurant_id and r.status=1 and "+
						" rs.area_id IN (:ids) and r.area_id not in (:ids)" ;
			NamedParameterJdbcTemplate template = new NamedParameterJdbcTemplate(
					abstractDao.getJdbcTemplate().getDataSource());
			restBaseList = template.query(restQuery, parameters, new RestaurantMetaDataRowMapper());

		} catch (Exception ex) {
			logger.error("Error in  findRestrauantByLocation :" +ex.getMessage());
		}
		return restBaseList;
	}

	@Override
	public List<RestaurantBase> getRestaurntByCity(Integer cityId) {
		List<RestaurantBase> restBaseList = new ArrayList<>();
		
		String restQuery = "SELECT r.id as restId, r.name as name, r.city_id as cityId,r.area_id as areaId,"
				+ "r.location_id as locationId,r.address as address,r.min_delivery_cost as minDeliveryCost,r.photo as photo,  r.cost_for_2 as cost "
				+ ", r.is_franchisee as isFranchisee FROM foozup_restaurant.restaurants r where r.city_id=? and r.status=1;";
		try{
			restBaseList=abstractDao.getJdbcTemplate().query(restQuery,new Object[] { cityId },new RestaurantMetaDataRowMapper());
				
		}catch(Exception ex){
			logger.error("Error in  getRestaurntByCity :" +ex.getMessage());
			
		}
		// TODO Auto-generated method stub
		return restBaseList;
	}

}
