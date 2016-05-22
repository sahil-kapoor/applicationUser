package com.foozup.updates.service.helper;

import java.util.List;
import java.util.Map;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.update.dao.IUpdatesDao;
import com.foozup.updates.model.UpdateBase;

@Service("updateServiceHelper")
public class UpdateServiceHelperImpl implements IUpdateServiceHelper {

	
	@Autowired
	private IUpdatesDao updatesDaoImpl;

	@Override
	public void getUpdatesByRestaurant(Map<String, Map<Integer, List<UpdateBase>>> updateByRestCategory,
			List<RestaurantBase> restaurants) {
		
		// TODO Auto-generated method stub
		
	}

	@Override
	public List<UpdateBase> formatCollateUpdateData(Map<String, Map<Integer, List<UpdateBase>>> updateByRestCategory,
			Map<String, List<RestaurantBase>> formattedRestData) {
		// TODO Auto-generated method stub
		return null;
	}
}
