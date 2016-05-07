package com.foozup.updates.service.helper;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.update.dao.IUpdatesDao;

@Service("updateServiceHelper")
public class UpdateServiceHelperImpl implements IUpdateServiceHelper {

	
	@Autowired
	private IUpdatesDao updatesDaoImpl;
}
