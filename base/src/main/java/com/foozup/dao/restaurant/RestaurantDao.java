package com.foozup.dao.restaurant;

import java.util.List;

import com.foozup.model.sql.RestaurantInfo;

public interface RestaurantDao {

	public List<RestaurantInfo>findRestaurantByName(String keyword);
}
