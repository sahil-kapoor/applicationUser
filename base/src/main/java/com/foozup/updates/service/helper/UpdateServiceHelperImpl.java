package com.foozup.updates.service.helper;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.common.Utils;
import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.update.common.UpdateUtils;
import com.foozup.update.dao.IUpdatesDao;
import com.foozup.updates.model.UpdateBase;
import com.foozup.updates.model.dto.UpdateFranchiseDto;
import com.foozup.updates.model.dto.UpdateRestDto;

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
			List<UpdateBase> updateList=new ArrayList<>();
			//Get update if it is franchise
			if(rest.isFranchisee()){
				updateList.addAll(transformFranchiseDtoTOBase(updatesDaoImpl.getRestAsFrachiseUpdate(rest.getId()),rest));	
			}
			//Get update from normal restaurant update
			  updateList.addAll(transformRestDtoTOBase(updatesDaoImpl.getRestUpdate(rest.getId()),rest));
			// Collate data
			updateList.forEach(update->{
			
			});
			// Filter data - primary and today only, primary, today only, franchise
			
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
	
	public List<UpdateBase> transformFranchiseDtoTOBase(List<UpdateFranchiseDto> franchiseDto,RestaurantBase rest){
		List<UpdateBase> updateBaseList=new ArrayList<>();
		franchiseDto.forEach(franchiseUpdate->{
			UpdateBase update=new UpdateBase();
			update.setArea(rest.getArea());
			update.setAreaId(rest.getAreaId());
			update.setCity(rest.getCity());
			update.setCost(rest.getCost());
			update.setActiveDays(Utils.convertIntDaytoString(franchiseUpdate.getActiveDays()));
			update.setEndDate(franchiseUpdate.getEndDate());
			update.setEndTime(franchiseUpdate.getEndTime());
			update.setFranchisee(franchiseUpdate.isFranchisee());
			update.setFranchiseeId(franchiseUpdate.getFranchiseeId());
			update.setId(franchiseUpdate.getId());
			update.setLocation(rest.getLocation());
			update.setLocationId(rest.getLocationId());
			update.setMinDeliveryCost(rest.getMinDeliveryCost());
			update.setPhoto(rest.getPhoto());
			update.setRestaurntId(rest.getId());
			update.setPrimary(false);
			update.setRestaurntName(rest.getName());
			update.setStartDate(franchiseUpdate.getStartDate());
			update.setStartTime(franchiseUpdate.getStartTime());
			update.setTodayOnly(UpdateUtils.isTodayOnly(update.getActiveDays()));
			update.setTotalCount(0);
			update.setUpdateText(franchiseUpdate.getUpdateText());
			updateBaseList.add(update);
		});
		return updateBaseList;
	}
	
	public List<UpdateBase> transformRestDtoTOBase(List<UpdateRestDto> updateRestDto,RestaurantBase rest){
		List<UpdateBase> updateBaseList=new ArrayList<>();
		updateRestDto.forEach(restUpdate->{
			UpdateBase update=new UpdateBase();
			update.setArea(rest.getArea());
			update.setAreaId(rest.getAreaId());
			update.setCity(rest.getCity());
			update.setCost(rest.getCost());
			update.setActiveDays(Utils.convertIntDaytoString(restUpdate.getActiveDays()));
			update.setEndDate(restUpdate.getEndDate());
			update.setEndTime(restUpdate.getEndTime());
			update.setFranchisee(false);
			update.setFranchiseeId(0);
			update.setId(restUpdate.getId());
			update.setLocation(rest.getLocation());
			update.setLocationId(rest.getLocationId());
			update.setMinDeliveryCost(rest.getMinDeliveryCost());
			update.setPhoto(rest.getPhoto());
			update.setRestaurntId(rest.getId());
			update.setPrimary(restUpdate.isPrimary());
			update.setRestaurntName(rest.getName());
			update.setStartDate(restUpdate.getStartDate());
			update.setStartTime(restUpdate.getStartTime());
			update.setTodayOnly(UpdateUtils.isTodayOnly(update.getActiveDays()));
			update.setTotalCount(0);
			update.setUpdateText(restUpdate.getUpdateText());
			updateBaseList.add(update);
		});
		return updateBaseList;
	}
	
	
	
}
