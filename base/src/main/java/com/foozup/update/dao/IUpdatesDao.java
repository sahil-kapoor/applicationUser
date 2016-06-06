package com.foozup.update.dao;

import java.util.List;

import com.foozup.updates.model.dto.UpdateFranchiseDto;
import com.foozup.updates.model.dto.UpdateRestDto;

public interface IUpdatesDao {

	public List<UpdateFranchiseDto> getRestAsFrachiseUpdate(Integer restId);

	public List<UpdateRestDto> getRestUpdate(Integer restId);
}
