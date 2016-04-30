package com.foozup.restaurant.dao;

import java.util.List;

import com.foozup.restaurant.model.sql.RestaurantInfo;

public interface RestaurantDao {

	public List<RestaurantInfo>findRestaurantByName(String keyword);
}
