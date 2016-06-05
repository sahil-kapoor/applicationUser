package com.foozup.update.dao;

import java.util.List;

import com.foozup.updates.model.UpdateBase;

public interface IUpdatesDao {

	public List<UpdateBase> getRestAsFrachiseUpdate(Integer restId);
}
