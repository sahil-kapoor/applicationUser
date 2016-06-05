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
		//for each restaurant 
		/*ForkJoinPool forkJoinPool = new ForkJoinPool(3);  
		forkJoinPool.submit(() -> {  
		    firstRange.parallelStream().forEach((number) -> {
		        try {
		            Thread.sleep(5);
		        } catch (InterruptedException e) { }
		    });
		});*/
		restaurants.forEach(rest->{
		
			//Get update if it is franchise
			
			//Get update from normal restaurant update
			
			// Collate data
			
			// Filter data - primary, today only, franchise
			
			//Enrich data
			
		});
			
		//Collect all restaurant data
		
		//Randomize
		
		//Put results back in updateByRestCatergory
		
	}

	@Override
	public List<UpdateBase> collateUpdateData(Map<String, Map<Integer, List<UpdateBase>>> updateByRestCategory,
			Map<String, List<RestaurantBase>> formattedRestData) {
		// TODO Auto-generated method stub
		return null;
	}
}
